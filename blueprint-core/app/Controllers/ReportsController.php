<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Config\Database;

class ReportsController
{
    private $db;

    public function __construct()
    {
        Auth::requireLogin();
        $this->db = Database::getInstance();
    }

    // ==================== REPORTS PAGE ====================
    public function index()
    {
        view('reports/index', []);
    }

    // ==================== INDIVIDUAL SESSION SUMMARY ====================
    public function individualJson()
    {
        $this->setJsonHeaders();

        $startDate = $this->sanitiseDate($_GET['start'] ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']   ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']  ?? 'all');

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            exit;
        }

        $params = [$startDate, $endDate];
        $wardClause = '';
        if ($ward !== 'all') {
            $wardClause = 'AND s.ward = ?';
            $params[] = $ward;
        }

        $sql = "
            SELECT
                s.ward,
                COUNT(*)                                   AS total_offered,
                SUM(LOWER(TRIM(s.status)) = 'completed')   AS total_completed,
                SUM(LOWER(TRIM(s.status)) = 'declined')    AS total_declined,
                SUM(LOWER(TRIM(s.status)) = 'dna')         AS total_dna
            FROM sessions s
            WHERE DATE(s.datetime) BETWEEN ? AND ?
            AND s.is_archived = 0
            $wardClause
            GROUP BY s.ward
            ORDER BY FIELD(s.ward, 'Hope', 'Lakeside', 'Manor')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($rows);
        exit;
    }

    // ==================== INDIVIDUAL SESSION DRILL-DOWN ====================
    public function individualDrilldown()
    {
        $this->setJsonHeaders();

        $startDate = $this->sanitiseDate($_GET['start']  ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']    ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']   ?? 'all');
        $status    = $this->sanitiseStatus($_GET['status'] ?? 'all');

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            exit;
        }

        $params = [$startDate, $endDate];
        $wardClause   = '';
        $statusClause = '';

        if ($ward !== 'all') {
            $wardClause = 'AND s.ward = ?';
            $params[] = $ward;
        }

        if ($status !== 'all') {
            $statusClause = 'AND LOWER(TRIM(s.status)) = LOWER(?)';
            $params[] = $status;
        }

        $sql = "
            SELECT
                p.initials                          AS patient_name,
                s.ward                              AS ward,
                s.datetime                          AS session_date,
                COALESCE(u.full_name, u.username, 'Unknown') AS clinician,
                COALESCE(NULLIF(TRIM(s.status), ''), 'offered') AS status
            FROM sessions s
            LEFT JOIN patients p ON p.id = s.patient_id
            LEFT JOIN users u    ON u.id = s.created_by
            WHERE DATE(s.datetime) BETWEEN ? AND ?
            AND s.is_archived = 0
            $wardClause
            $statusClause
            ORDER BY s.datetime DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($rows);
        exit;
    }

    // ==================== GROUP SESSION SUMMARY ====================
    public function groupJson()
    {
        $this->setJsonHeaders();

        $startDate = $this->sanitiseDate($_GET['start'] ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']   ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']  ?? 'all');

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            exit;
        }

        $params = [$startDate, $endDate];
        $wardClause = '';
        if ($ward !== 'all') {
            $wardClause = 'AND p.ward = ?';
            $params[] = $ward;
        }

        $sql = "
            SELECT
                gs.group_type,
                p.ward                              AS ward_name,
                COUNT(gsa.id)                       AS offered,
                SUM(COALESCE(gsa.attended, 0) = 1)  AS accepted,
                SUM(COALESCE(gsa.declined, 0) = 1)  AS declined,
                SUM(COALESCE(gsa.dna, 0) = 1)       AS dna
            FROM group_sessions gs
            INNER JOIN group_session_attendance gsa ON gsa.group_session_id = gs.id
            INNER JOIN patients p ON p.id = gsa.patient_id
            WHERE gs.session_date BETWEEN ? AND ?
            AND gs.status = 'completed'
            $wardClause
            GROUP BY gs.group_type, p.ward
            ORDER BY gs.group_type ASC, FIELD(p.ward, 'Hope', 'Lakeside', 'Manor')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($rows);
        exit;
    }

    // ==================== GROUP SESSION DRILL-DOWN ====================
    public function groupDrilldown()
    {
        $this->setJsonHeaders();

        $startDate  = $this->sanitiseDate($_GET['start']       ?? date('Y-m-01'));
        $endDate    = $this->sanitiseDate($_GET['end']         ?? date('Y-m-t'));
        $ward       = $this->sanitiseWard($_GET['ward']        ?? 'all');
        $groupType  = trim($_GET['group_type']                 ?? 'all');
        $attStatus  = trim($_GET['att_status']                 ?? 'all');

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            exit;
        }

        $params = [$startDate, $endDate];
        $wardClause      = '';
        $groupTypeClause = '';
        $attClause       = '';

        if ($ward !== 'all') {
            $wardClause = 'AND p.ward = ?';
            $params[] = $ward;
        }

        if ($groupType !== 'all' && $groupType !== '') {
            $groupTypeClause = 'AND gs.group_type = ?';
            $params[] = $groupType;
        }

        // Attendance status filter
        if ($attStatus === 'attended') {
            $attClause = 'AND gsa.attended = 1';
        } elseif ($attStatus === 'declined') {
            $attClause = 'AND gsa.declined = 1';
        } elseif ($attStatus === 'dna') {
            $attClause = 'AND gsa.dna = 1';
        }
        // 'all' = no filter — show all attendance records

        $sql = "
            SELECT
                p.initials                                  AS patient_name,
                p.ward                                      AS ward,
                gs.session_date                             AS session_date,
                COALESCE(u.full_name, u.username, 'Unknown') AS clinician,
                gs.group_type,
                CASE
                    WHEN gsa.attended = 1 THEN 'Attended'
                    WHEN gsa.declined = 1 THEN 'Declined'
                    WHEN gsa.dna = 1      THEN 'DNA'
                    ELSE 'Unknown'
                END                                         AS attendance_status
            FROM group_sessions gs
            INNER JOIN group_session_attendance gsa ON gsa.group_session_id = gs.id
            INNER JOIN patients p ON p.id = gsa.patient_id
            LEFT JOIN users u ON u.id = gs.created_by
            WHERE gs.session_date BETWEEN ? AND ?
            AND gs.status = 'completed'
            $wardClause
            $groupTypeClause
            $attClause
            ORDER BY gs.session_date DESC, p.ward ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode($rows);
        exit;
    }

    // ==================== CSV EXPORT ====================
  public function exportCsv()
{
    $type      = trim($_GET['type']   ?? 'individual');
    $startDate = $this->sanitiseDate($_GET['start'] ?? date('Y-m-01'));
    $endDate   = $this->sanitiseDate($_GET['end']   ?? date('Y-m-t'));
    $ward      = $this->sanitiseWard($_GET['ward']  ?? 'all');

    if (!$startDate || !$endDate) {
        http_response_code(400);
        echo 'Invalid date range';
        exit;
    }

    // Build rows directly without calling drilldown methods
    if ($type === 'individual') {
        $status = $this->sanitiseStatus($_GET['status'] ?? 'all');
        $params = [$startDate, $endDate];
        $wardClause   = '';
        $statusClause = '';
        if ($ward !== 'all') { $wardClause = 'AND s.ward = ?'; $params[] = $ward; }
        if ($status !== 'all') { $statusClause = 'AND LOWER(TRIM(s.status)) = LOWER(?)'; $params[] = $status; }

        $sql = "
            SELECT
                p.initials                                   AS patient_name,
                s.ward                                       AS ward,
                s.datetime                                   AS session_date,
                COALESCE(u.full_name, u.username, 'Unknown') AS clinician,
                COALESCE(NULLIF(TRIM(s.status), ''), 'offered') AS status
            FROM sessions s
            LEFT JOIN patients p ON p.id = s.patient_id
            LEFT JOIN users u    ON u.id = s.created_by
            WHERE DATE(s.datetime) BETWEEN ? AND ?
            AND s.is_archived = 0
            $wardClause
            $statusClause
            ORDER BY s.datetime DESC
        ";
        $headers = ['Patient', 'Ward', 'Session Date', 'Clinician', 'Status'];

    } else {
        $groupType  = trim($_GET['group_type'] ?? 'all');
        $attStatus  = trim($_GET['att_status'] ?? 'all');
        $params = [$startDate, $endDate];
        $wardClause      = '';
        $groupTypeClause = '';
        $attClause       = '';
        if ($ward !== 'all') { $wardClause = 'AND p.ward = ?'; $params[] = $ward; }
        if ($groupType !== 'all' && $groupType !== '') { $groupTypeClause = 'AND gs.group_type = ?'; $params[] = $groupType; }
        if ($attStatus === 'attended') $attClause = 'AND gsa.attended = 1';
        elseif ($attStatus === 'declined') $attClause = 'AND gsa.declined = 1';
        elseif ($attStatus === 'dna') $attClause = 'AND gsa.dna = 1';

        $sql = "
            SELECT
                p.initials                                   AS patient_name,
                p.ward                                       AS ward,
                gs.session_date                              AS session_date,
                COALESCE(u.full_name, u.username, 'Unknown') AS clinician,
                gs.group_type,
                CASE
                    WHEN gsa.attended = 1 THEN 'Attended'
                    WHEN gsa.declined = 1 THEN 'Declined'
                    WHEN gsa.dna = 1      THEN 'DNA'
                    ELSE 'Unknown'
                END AS attendance_status
            FROM group_sessions gs
            INNER JOIN group_session_attendance gsa ON gsa.group_session_id = gs.id
            INNER JOIN patients p ON p.id = gsa.patient_id
            LEFT JOIN users u ON u.id = gs.created_by
            WHERE gs.session_date BETWEEN ? AND ?
            AND gs.status = 'completed'
            $wardClause
            $groupTypeClause
            $attClause
            ORDER BY gs.session_date DESC
        ";
        $headers = ['Patient', 'Ward', 'Session Date', 'Clinician', 'Group Type', 'Attendance Status'];
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $filename = 'blueprint-report-' . $type . '-' . $startDate . '-to-' . $endDate . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel

    fputcsv($out, $headers);

    foreach ($rows as $row) {
        fputcsv($out, array_values($row));
    }

    fclose($out);
    exit;
}

    // ==================== PRIVATE HELPERS ====================

    private function setJsonHeaders()
    {
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache');
    }

    private function sanitiseDate($date)
    {
        if (!$date) return null;
        $d = \DateTime::createFromFormat('Y-m-d', trim($date));
        return ($d && $d->format('Y-m-d') === trim($date)) ? trim($date) : null;
    }

    private function sanitiseWard($ward)
    {
        $allowed = ['all', 'Hope', 'Lakeside', 'Manor'];
        return in_array($ward, $allowed, true) ? $ward : 'all';
    }

    private function sanitiseStatus($status)
    {
        $allowed = ['all', 'offered', 'completed', 'declined', 'dna'];
        return in_array(strtolower(trim($status)), $allowed, true)
            ? strtolower(trim($status))
            : 'all';
    }
}