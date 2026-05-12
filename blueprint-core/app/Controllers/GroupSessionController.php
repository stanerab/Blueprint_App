<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Config\Database;

class GroupSessionController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    // ── store() ─────────────────────────────────────────────
    public function store()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            exit;
        }

        try {
            $groupType    = trim($_POST['group_type']  ?? '');
            $rawDatetime  = trim($_POST['datetime']    ?? '');
            $notes        = trim($_POST['notes']       ?? '');
            $attendance   = json_decode($_POST['attendance'] ?? '[]', true) ?: [];
            $wardSnapshot = trim($_POST['ward_snapshot'] ?? '');

            if (!$groupType || !$rawDatetime) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                exit;
            }

         $dt          = new \DateTime($rawDatetime);
         $sessionDate = $dt->format('Y-m-d');
         $sessionTime = $dt->format('H:i:s');
         $today       = (new \DateTime())->format('Y-m-d');
         $status      = ($sessionDate > $today) ? 'scheduled' : 'completed';

            $ward = null;
            if ($wardSnapshot !== '') {
                $wardParts = array_values(array_filter(array_map('trim', explode(',', $wardSnapshot))));
                $ward      = count($wardParts) === 1 ? $wardParts[0] : null;
            }

            $db   = Database::getInstance();
           $stmt = $db->prepare(
    'INSERT INTO group_sessions
        (group_type, session_date, session_time, ward, notes, status, ward_snapshot, created_by)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);
            $stmt->execute([$groupType, $sessionDate, $sessionTime, $ward, $notes, $status, $wardSnapshot, $_SESSION['user_id'] ?? 0]);
            $groupSessionId = $db->lastInsertId();

            if (!empty($attendance)) {
                $attStmt = $db->prepare(
                    'INSERT INTO group_session_attendance
                        (group_session_id, patient_id, attended, declined, dna, notes)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                foreach ($attendance as $att) {
                    $patientId = (int)($att['patient_id'] ?? 0);
                    if (!$patientId) continue;
                    $attStatus = $att['status'] ?? 'not_set';
                    $attStmt->execute([
                        $groupSessionId, $patientId,
                        ($attStatus === 'attended') ? 1 : 0,
                        ($attStatus === 'declined') ? 1 : 0,
                        ($attStatus === 'dna')      ? 1 : 0,
                        trim($att['notes'] ?? '')
                    ]);
                }
            }

           // ── Activity log ──────────────────────────────────────────────────
$userId      = $_SESSION['user_id'] ?? 0;
$userName    = $_SESSION['username'] ?? 'Unknown';
$description = $status === 'scheduled'
    ? "Scheduled group session '{$groupType}' for {$sessionDate}"
    : "Created group session '{$groupType}'";
$logWard     = $ward ?? $wardSnapshot ?? null;

$db->prepare(
    'INSERT INTO activity_logs (user_id, user_name, action_type, description, ward)
     VALUES (?, ?, ?, ?, ?)'
)->execute([$userId, $userName, $status === 'scheduled' ? 'group_session_scheduled' : 'group_session_created', $description, $logWard]);

echo json_encode(['success' => true, 'id' => $groupSessionId]);
exit;

} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
    }

    // ── todayJson() ──────────────────────────────────────────
    public function todayJson()
    {
        header('Content-Type: application/json');
        ini_set('display_errors', 0);

        try {
            $db   = Database::getInstance();
            $stmt = $db->prepare(
                'SELECT id, group_type, session_date, session_time, ward, ward_snapshot, notes, status,
                        (SELECT COUNT(*) FROM group_session_attendance
                         WHERE group_session_id = group_sessions.id) AS patient_count
                 FROM group_sessions
                 WHERE session_date = CURDATE()
                 ORDER BY session_time ASC'
            );
            $stmt->execute();
            echo json_encode($stmt->fetchAll(\PDO::FETCH_OBJ));
            exit;
        } catch (\Exception $e) {
            echo json_encode([]);
            exit;
        }
    }

    // ── listJson() ───────────────────────────────────────────
    public function listJson()
    {
        try {
            $db   = Database::getInstance();
            $stmt = $db->query(
                'SELECT gs.*, gs.ward_snapshot,
                        COUNT(ga.id) AS patient_count
                 FROM group_sessions gs
                 LEFT JOIN group_session_attendance ga ON gs.id = ga.group_session_id
                 GROUP BY gs.id
                 ORDER BY gs.session_date DESC, gs.session_time DESC'
            );
            $this->jsonResponse($stmt->fetchAll(\PDO::FETCH_OBJ));
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Server error'], null, 500);
        }
    }
// Completed group sesion
public function complete()
{
    header('Content-Type: application/json');

    if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
        exit;
    }

    $id         = (int)($_POST['id'] ?? 0);
    $attendance = json_decode($_POST['attendance'] ?? '[]', true) ?: [];

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'Invalid session ID']);
        exit;
    }

    try {
        $db = Database::getInstance();

        // Update session status to completed
        $db->prepare('UPDATE group_sessions SET status = ? WHERE id = ?')
           ->execute(['completed', $id]);

        // Update or insert attendance records
        foreach ($attendance as $att) {
            $patientId = (int)($att['patient_id'] ?? 0);
            if (!$patientId) continue;
            $attStatus = $att['status'] ?? 'not_set';
            $attNotes  = trim($att['notes'] ?? '');

            // Check if attendance record exists
            $stmt = $db->prepare('SELECT id FROM group_session_attendance WHERE group_session_id = ? AND patient_id = ?');
            $stmt->execute([$id, $patientId]);
            $existing = $stmt->fetch(\PDO::FETCH_OBJ);

            if ($existing) {
                $db->prepare('UPDATE group_session_attendance SET attended=?, declined=?, dna=?, notes=? WHERE id=?')
                   ->execute([
                       ($attStatus === 'attended') ? 1 : 0,
                       ($attStatus === 'declined') ? 1 : 0,
                       ($attStatus === 'dna')      ? 1 : 0,
                       $attNotes,
                       $existing->id
                   ]);
            } else {
                $db->prepare('INSERT INTO group_session_attendance (group_session_id, patient_id, attended, declined, dna, notes) VALUES (?,?,?,?,?,?)')
                   ->execute([
                       $id, $patientId,
                       ($attStatus === 'attended') ? 1 : 0,
                       ($attStatus === 'declined') ? 1 : 0,
                       ($attStatus === 'dna')      ? 1 : 0,
                       $attNotes
                   ]);
            }
        }

        // Log activity
        $stmt = $db->prepare('SELECT group_type, ward, ward_snapshot FROM group_sessions WHERE id = ?');
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($session) {
            $userId      = $_SESSION['user_id'] ?? 0;
            $userName    = $_SESSION['username'] ?? 'Unknown';
            $logWard     = $session->ward ?? $session->ward_snapshot ?? null;
            $description = "Completed group session '{$session->group_type}'";

            $db->prepare('INSERT INTO activity_logs (user_id, user_name, action_type, description, ward) VALUES (?,?,?,?,?)')
               ->execute([$userId, $userName, 'group_session_created', $description, $logWard]);
        }

        echo json_encode(['success' => true]);
        exit;

    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

    // ── getJson() ────────────────────────────────────────────
    public function getJson()
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { $this->jsonError('Invalid ID', 400); return; }

        $db   = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM group_sessions WHERE id = ?');
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);
        if (!$session) { $this->jsonError('Not found', 404); return; }

        $stmt = $db->prepare(
            'SELECT ga.*, p.initials AS patient_initials, p.room_number, p.ward
             FROM group_session_attendance ga
             JOIN patients p ON ga.patient_id = p.id
             WHERE ga.group_session_id = ?
             ORDER BY FIELD(p.ward,"Hope","Lakeside","Manor"),
                      CAST(p.room_number AS UNSIGNED) ASC'
        );
        $stmt->execute([$id]);
        $session->attendance = $stmt->fetchAll(\PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode($session);
    }

    // ── getByDateJson() ──────────────────────────────────────
    public function getByDateJson()
    {
        $date = $_GET['date'] ?? '';
        if (!$date) { $this->jsonError('Missing date', 400); return; }

        $db   = Database::getInstance();
        $stmt = $db->prepare(
          'SELECT gs.id, gs.group_type AS title, gs.session_time AS time,
        gs.ward, gs.ward_snapshot, gs.status, COUNT(ga.id) AS patient_count
             FROM group_sessions gs
             LEFT JOIN group_session_attendance ga ON gs.id = ga.group_session_id
             WHERE gs.session_date = ?
             GROUP BY gs.id
             ORDER BY gs.session_time ASC'
        );
        $stmt->execute([$date]);
        $this->jsonResponse($stmt->fetchAll(\PDO::FETCH_OBJ));
    }

    // ── delete() ─────────────────────────────────────────────
    public function delete()
    {
        header('Content-Type: application/json');

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }

        try {
            $db = Database::getInstance();

            // Get session details before deleting for the log
            $stmt = $db->prepare('SELECT group_type, ward, ward_snapshot FROM group_sessions WHERE id = ?');
            $stmt->execute([$id]);
            $session = $stmt->fetch(\PDO::FETCH_OBJ);

            // Delete attendance records first
            $db->prepare('DELETE FROM group_session_attendance WHERE group_session_id = ?')
               ->execute([$id]);

            // Delete the session
            $db->prepare('DELETE FROM group_sessions WHERE id = ?')
               ->execute([$id]);

            // Log activity
            if ($session) {
                $userId      = $_SESSION['user_id'] ?? 0;
                $userName    = $_SESSION['username'] ?? 'Unknown';
                $logWard     = $session->ward ?? $session->ward_snapshot ?? null;
                $description = "Deleted group session '{$session->group_type}'";

                $db->prepare(
                    'INSERT INTO activity_logs (user_id, user_name, action_type, description, ward)
                     VALUES (?, ?, ?, ?, ?)'
                )->execute([$userId, $userName, 'group_session_deleted', $description, $logWard]);
            }

            echo json_encode(['success' => true]);
            exit;

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    // ── Helpers ──────────────────────────────────────────────
    protected function jsonResponse($data, $errorMessage = null, $statusCode = 200)
    {
        while (ob_get_level()) ob_end_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    private function jsonError($msg, $code = 400)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $msg]);
        exit;
    }
}