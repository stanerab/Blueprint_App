<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;
use App\Models\ActivityLog;
use App\Config\Database;

class DashboardController
{
    public function index()
    {
        Auth::requireLogin();
        $userId = $_SESSION['user_id'];

        // ==================== GLOBAL DATA ====================
        $allPatients = Patient::getAll();
        $sessions = Session::getAll();

        // ==================== SEPARATE ACTIVE & DISCHARGED ====================
        $activePatients = array_filter($allPatients, fn($p) => !$p->discharge_date);
        $dischargedPatients = array_filter($allPatients, fn($p) => $p->discharge_date);

        // ==================== USER ACTIVITY ====================
        try {
            $recentActivities = ActivityLog::getRecentByUser($userId, 10);
        } catch (\Exception $e) {
            $recentActivities = [];
            error_log("Error fetching activities: " . $e->getMessage());
        }

        // ==================== COUNTS (ONLY ACTIVE) ====================
        $totalPatients = count($activePatients);
        $totalDischarged = count($dischargedPatients);
        $totalSessions = count($sessions);


       // ==================== TODAY'S SESSIONS (FINAL FIX) ====================
$db = Database::getInstance();

$todayStart = date('Y-m-d 00:00:00');
$todayEnd   = date('Y-m-d 23:59:59');

$stmt = $db->prepare("
    SELECT s.*,
           p.initials as patient_initials,
           p.ward,
           p.room_number
    FROM sessions s
    INNER JOIN patients p ON s.patient_id = p.id
    WHERE s.is_archived = 0
    AND p.discharge_date IS NULL
    AND s.datetime BETWEEN ? AND ?
    ORDER BY s.datetime ASC
");

$stmt->execute([$todayStart, $todayEnd]);
$todaySessions = $stmt->fetchAll(\PDO::FETCH_OBJ);


        // ==================== CORE-10 ====================
        $core10AdmissionCompleted = count(array_filter($activePatients, fn($p) => $p->core10_admission));
        $core10DischargeCompleted = count(array_filter($dischargedPatients, fn($p) => $p->core10_discharge));

        // ==================== WARD GROUPING (ACTIVE ONLY) ====================
        $wardPatients = ['Hope' => [], 'Manor' => [], 'Lakeside' => []];

        foreach ($activePatients as $p) {
            if (isset($wardPatients[$p->ward])) {
                $wardPatients[$p->ward][] = $p;
            }
        }

        // ==================== WARD SESSION COUNTS (MATCH TODAY SESSIONS) ====================
        $wardSessions = ['Hope' => 0, 'Manor' => 0, 'Lakeside' => 0];

        foreach ($todaySessions as $s) {
            if (isset($wardSessions[$s->ward])) {
                $wardSessions[$s->ward]++;
            }
        }

        // ==================== WARD CONFIG ====================
        $wardBeds = ['Hope' => 12, 'Manor' => 10, 'Lakeside' => 10];

        // ==================== WARD CORE-10 ====================
        $wardCoreAdmission = [];
        $wardCoreDischarge = [];

        foreach (['Hope', 'Manor', 'Lakeside'] as $ward) {
            $wardActive = array_filter($activePatients, fn($p) => $p->ward === $ward);
            $wardDischarged = array_filter($dischargedPatients, fn($p) => $p->ward === $ward);

            $wardCoreAdmission[$ward] = count(array_filter($wardActive, fn($p) => $p->core10_admission));
            $wardCoreDischarge[$ward] = count(array_filter($wardDischarged, fn($p) => $p->core10_discharge));
        }

        // ==================== RENDER ====================
        view('dashboard.home', [
            'patients' => $activePatients,              // active only
            'dischargedPatients' => $dischargedPatients,
            'sessions' => $sessions,
            'recentActivities' => $recentActivities,
            'totalPatients' => $totalPatients,
            'totalSessions' => $totalSessions,
            'todaySessions' => $todaySessions,          // now matches calendar again
            'totalDischarged' => $totalDischarged,
            'core10AdmissionCompleted' => $core10AdmissionCompleted,
            'core10DischargeCompleted' => $core10DischargeCompleted,
            'wardPatients' => $wardPatients,
            'wardSessions' => $wardSessions,
            'wardBeds' => $wardBeds,
            'wardCoreAdmission' => $wardCoreAdmission,
            'wardCoreDischarge' => $wardCoreDischarge
        ]);
    }
}