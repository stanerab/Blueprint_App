<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Config\Database;
use App\Models\ActivityLog;

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
                COUNT(*)                                    AS total_offered,
                SUM(LOWER(TRIM(s.status)) = 'completed')    AS total_completed,
                SUM(LOWER(TRIM(s.status)) = 'declined')     AS total_declined,
                SUM(LOWER(TRIM(s.status)) = 'dna')          AS total_dna
            FROM sessions s
            WHERE DATE(s.datetime) BETWEEN ? AND ?
            AND s.is_archived = 0
            AND DATE(s.datetime) <= CURDATE()
            $wardClause
            GROUP BY s.ward
            ORDER BY FIELD(s.ward, 'Hope', 'Lakeside', 'Manor')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $userName  = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
        $wardLabel = $ward === 'all' ? 'All Wards' : $ward . ' Ward';
        ActivityLog::create([
            'action_type' => 'report_generated',
            'description' => "{$userName} generated Individual Session Report for {$wardLabel} (" . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)) . ")",
            'patient_id'  => null,
            'ward'        => $ward === 'all' ? null : $ward
        ]);

        echo json_encode($rows);
        exit;
    }

    // ==================== INDIVIDUAL SESSION DRILL-DOWN ====================
    public function individualDrilldown()
    {
        $this->setJsonHeaders();

        $startDate = $this->sanitiseDate($_GET['start']    ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']      ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']     ?? 'all');
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
                p.initials                                              AS patient_name,
                p.initials                                              AS patient_name,
p.discharge_date                                        AS discharge_date,
                s.ward                                                  AS ward,
                DATE_FORMAT(s.datetime, '%d/%m/%Y %H:%i')              AS session_date,
                COALESCE(NULLIF(u.full_name, ''), NULLIF(u.username, ''), NULLIF(s.clinician_name, ''), 'Not recorded') AS clinician,
                COALESCE(NULLIF(TRIM(s.status), ''), 'offered')        AS status
            FROM sessions s
            LEFT JOIN patients p ON p.id = s.patient_id
            LEFT JOIN users u    ON u.id = s.created_by AND s.created_by > 0
            WHERE DATE(s.datetime) BETWEEN ? AND ?
            AND s.is_archived = 0
            AND DATE(s.datetime) <= CURDATE()
            $wardClause
            $statusClause
            ORDER BY s.datetime DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $userName    = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
        $wardLabel   = $ward === 'all' ? 'All Wards' : $ward . ' Ward';
        $statusLabel = $status === 'all' ? 'All Sessions' : ($status === 'dna' ? 'DNA' : ucfirst($status));
        ActivityLog::create([
            'action_type' => 'report_drilldown_viewed',
            'description' => "{$userName} viewed {$statusLabel} drill-down for {$wardLabel}",
            'patient_id'  => null,
            'ward'        => $ward === 'all' ? null : $ward
        ]);

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
            AND gs.session_date <= CURDATE()
            AND (gsa.attended = 1 OR gsa.declined = 1 OR gsa.dna = 1)
            $wardClause
            GROUP BY gs.group_type, p.ward
            ORDER BY gs.group_type ASC, FIELD(p.ward, 'Hope', 'Lakeside', 'Manor')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $userName  = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
        $wardLabel = $ward === 'all' ? 'All Wards' : $ward . ' Ward';
        ActivityLog::create([
            'action_type' => 'report_generated',
            'description' => "{$userName} generated Group Session Report for {$wardLabel} (" . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)) . ")",
            'patient_id'  => null,
            'ward'        => $ward === 'all' ? null : $ward
        ]);

        echo json_encode($rows);
        exit;
    }

    // ==================== GROUP SESSION DRILL-DOWN ====================
    public function groupDrilldown()
    {
        $this->setJsonHeaders();

        $startDate = $this->sanitiseDate($_GET['start']      ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']        ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']       ?? 'all');
        $groupType = trim($_GET['group_type']                ?? 'all');
        $attStatus = trim($_GET['att_status']                ?? 'all');

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

        if ($attStatus === 'attended') {
            $attClause = 'AND gsa.attended = 1';
        } elseif ($attStatus === 'declined') {
            $attClause = 'AND gsa.declined = 1';
        } elseif ($attStatus === 'dna') {
            $attClause = 'AND gsa.dna = 1';
        } else {
            // all — only show records that have a marked status
            $attClause = 'AND (gsa.attended = 1 OR gsa.declined = 1 OR gsa.dna = 1)';
        }

        $sql = "
            SELECT
                p.initials                                                          AS patient_name,
                p.initials                                                          AS patient_name,
p.discharge_date                                                    AS discharge_date,
                p.ward                                                              AS ward,
                CONCAT(DATE_FORMAT(gs.session_date,'%d/%m/%Y'), ' ', DATE_FORMAT(gs.session_time,'%H:%i')) AS session_date,
                COALESCE(NULLIF(u.full_name, ''), NULLIF(u.username, ''), NULLIF(gs.clinician_name, ''), 'Not recorded') AS clinician,
                gs.group_type,
                CASE
                    WHEN gsa.attended = 1 THEN 'Attended'
                    WHEN gsa.declined = 1 THEN 'Declined'
                    WHEN gsa.dna = 1      THEN 'DNA'
                END AS attendance_status
            FROM group_sessions gs
            INNER JOIN group_session_attendance gsa ON gsa.group_session_id = gs.id
            INNER JOIN patients p ON p.id = gsa.patient_id
            LEFT JOIN users u ON u.id = gs.created_by AND gs.created_by > 0
            WHERE gs.session_date BETWEEN ? AND ?
            AND gs.status = 'completed'
            AND gs.session_date <= CURDATE()
            $wardClause
            $groupTypeClause
            $attClause
            ORDER BY gs.session_date DESC, p.ward ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $userName    = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
        $wardLabel   = $ward === 'all' ? 'All Wards' : $ward . ' Ward';
        $statusLabel = $attStatus === 'all' ? 'All Sessions' : ($attStatus === 'dna' ? 'DNA' : ucfirst($attStatus));
        ActivityLog::create([
            'action_type' => 'report_drilldown_viewed',
            'description' => "{$userName} viewed {$statusLabel} drill-down for {$wardLabel}" . ($groupType !== 'all' && $groupType !== '' ? " ({$groupType})" : ''),
            'patient_id'  => null,
            'ward'        => $ward === 'all' ? null : $ward
        ]);

        echo json_encode($rows);
        exit;
    }

    // ==================== CSV EXPORT ====================
    public function exportCsv()
    {
        $type      = trim($_GET['type']  ?? 'individual');
        $startDate = $this->sanitiseDate($_GET['start'] ?? date('Y-m-01'));
        $endDate   = $this->sanitiseDate($_GET['end']   ?? date('Y-m-t'));
        $ward      = $this->sanitiseWard($_GET['ward']  ?? 'all');

        if (!$startDate || !$endDate) {
            http_response_code(400);
            echo 'Invalid date range';
            exit;
        }

        if ($type === 'individual') {
            $status = $this->sanitiseStatus($_GET['status'] ?? 'all');
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
                    p.initials                                              AS patient_name,
                    p.initials                                              AS patient_name,
p.discharge_date                                        AS discharge_date,
                    s.ward                                                  AS ward,
                    DATE_FORMAT(s.datetime, '%d/%m/%Y %H:%i')              AS session_date,
                    COALESCE(NULLIF(u.full_name, ''), NULLIF(u.username, ''), NULLIF(s.clinician_name, ''), 'Not recorded') AS clinician,
                    COALESCE(NULLIF(TRIM(s.status), ''), 'offered')        AS status
                FROM sessions s
                LEFT JOIN patients p ON p.id = s.patient_id
                LEFT JOIN users u    ON u.id = s.created_by AND s.created_by > 0
                WHERE DATE(s.datetime) BETWEEN ? AND ?
                AND s.is_archived = 0
                AND DATE(s.datetime) <= CURDATE()
                $wardClause
                $statusClause
                ORDER BY s.datetime DESC
            ";
            $headers = ['Patient', 'Ward', 'Session Date', 'Clinician', 'Status'];

        } else {
            $groupType = trim($_GET['group_type'] ?? 'all');
            $attStatus = trim($_GET['att_status'] ?? 'all');
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
            if ($attStatus === 'attended') {
                $attClause = 'AND gsa.attended = 1';
            } elseif ($attStatus === 'declined') {
                $attClause = 'AND gsa.declined = 1';
            } elseif ($attStatus === 'dna') {
                $attClause = 'AND gsa.dna = 1';
            } else {
                $attClause = 'AND (gsa.attended = 1 OR gsa.declined = 1 OR gsa.dna = 1)';
            }

            $sql = "
                SELECT
                    p.initials                                              AS patient_name,
                    p.initials                                              AS patient_name,
p.discharge_date                                        AS discharge_date,
                    p.ward                                                  AS ward,
                    CONCAT(DATE_FORMAT(gs.session_date,'%d/%m/%Y'), ' ', DATE_FORMAT(gs.session_time,'%H:%i')) AS session_date,
                    COALESCE(NULLIF(u.full_name, ''), NULLIF(u.username, ''), NULLIF(gs.clinician_name, ''), 'Not recorded') AS clinician,
                    gs.group_type,
                    CASE
                        WHEN gsa.attended = 1 THEN 'Attended'
                        WHEN gsa.declined = 1 THEN 'Declined'
                        WHEN gsa.dna = 1      THEN 'DNA'
                    END AS attendance_status
                FROM group_sessions gs
                INNER JOIN group_session_attendance gsa ON gsa.group_session_id = gs.id
                INNER JOIN patients p ON p.id = gsa.patient_id
                LEFT JOIN users u ON u.id = gs.created_by AND gs.created_by > 0
                WHERE gs.session_date BETWEEN ? AND ?
                AND gs.status = 'completed'
                AND gs.session_date <= CURDATE()
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

        $userName    = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown';
        $isDrilldown = isset($_GET['status']) || isset($_GET['att_status']);
        $recordCount = count($rows);

        if ($isDrilldown) {
            $statusParam = $_GET['status'] ?? $_GET['att_status'] ?? 'all';
            $statusLabel = $statusParam === 'all' ? 'All Sessions' : ($statusParam === 'dna' ? 'DNA' : ucfirst($statusParam));
            $description = "{$userName} exported {$statusLabel} drill-down CSV ({$recordCount} records)";
        } else {
            $reportLabel = $type === 'individual' ? 'Individual Session Report' : 'Group Session Report';
            $description = "{$userName} exported {$reportLabel} CSV";
        }

        ActivityLog::create([
            'action_type' => 'report_csv_exported',
            'description' => $description,
            'patient_id'  => null,
            'ward'        => $ward === 'all' ? null : $ward
        ]);

        $filename = 'blueprint-report-' . $type . '-' . $startDate . '-to-' . $endDate . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, $headers);

       foreach ($rows as $row) {
    if (!empty($row['discharge_date']) && $row['discharge_date'] !== '0000-00-00') {
        $row['patient_name'] .= ' (Discharged)';
    }
    unset($row['discharge_date']);
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