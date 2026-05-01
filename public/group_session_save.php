<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'blueprint-core';
$appPath  = $basePath . DIRECTORY_SEPARATOR . 'app';

if (!defined('BASE_PATH')) define('BASE_PATH', $basePath);
if (!defined('APP_PATH'))  define('APP_PATH',  $appPath);

spl_autoload_register(function ($class) use ($appPath) {
    $prefix = 'App\\';
    $len    = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative = substr($class, $len);
    $file = $appPath . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
    if (file_exists($file)) require $file;
});

require_once $appPath . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Helpers.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}
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
    $status      = 'completed';

    $ward = null;
    if ($wardSnapshot !== '') {
        $wardParts = array_values(array_filter(array_map('trim', explode(',', $wardSnapshot))));
        $ward      = count($wardParts) === 1 ? $wardParts[0] : null;
    }

    $db = \App\Config\Database::getInstance();

    $stmt = $db->prepare(
        'INSERT INTO group_sessions
            (group_type, session_date, session_time, ward, notes, status, ward_snapshot)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$groupType, $sessionDate, $sessionTime, $ward, $notes, $status, $wardSnapshot]);
    $groupSessionId = $db->lastInsertId();

    $attendedCount = 0;
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
            if ($attStatus === 'attended') $attendedCount++;
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
    $description = "Created group session '{$groupType}'";
    // Use ward for single-ward sessions, ward_snapshot for multi-ward
    $logWard     = $ward ?? $wardSnapshot ?? null;

    $logStmt = $db->prepare(
        'INSERT INTO activity_logs
            (user_id, user_name, action_type, description, ward)
         VALUES (?, ?, ?, ?, ?)'
    );
    $logStmt->execute([$userId, $userName, 'group_session_created', $description, $logWard]);

    echo json_encode([
        'success' => true,
        'id'      => $groupSessionId,
        'message' => 'Group session saved successfully'
    ]);
    exit;

} catch (\Exception $e) {
    error_log('group_session_save error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}