<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;
use App\Models\ActivityLog;

class DashboardController
{
    public function index()
    {
        Auth::requireLogin();
        $userId = $_SESSION['user_id'];
        
        // Get user's patients and sessions
        $patients = Patient::getByUser($userId);
        $sessions = Session::getByUser($userId);
        
        // Get user's recent activities
        try {
            $recentActivities = ActivityLog::getRecentByUser($userId, 10);
        } catch (\Exception $e) {
            $recentActivities = [];
            error_log("Error fetching activities: " . $e->getMessage());
        }
        
        // Get stats for user's data
        $activePatients = array_filter($patients, fn($p) => !$p->discharge_date);
        $dischargedPatients = array_filter($patients, fn($p) => $p->discharge_date);
        
        $totalPatients = count($activePatients);
        $totalDischarged = count($dischargedPatients);
        $totalSessions = count($sessions);
        $todaySessions = count(array_filter($sessions, function($s) {
            return strpos($s->datetime, date('Y-m-d')) === 0;
        }));
        
        // Calculate CORE-10 stats separately
        $core10AdmissionCompleted = count(array_filter($activePatients, fn($p) => $p->core10_admission));
        $core10DischargeCompleted = count(array_filter($dischargedPatients, fn($p) => $p->core10_discharge));
        
        // Group user's patients by ward
        $wardPatients = [
            'Hope' => [],
            'Manor' => [],
            'Lakeside' => []
        ];
        
        foreach ($activePatients as $p) {
            if (isset($wardPatients[$p->ward])) {
                $wardPatients[$p->ward][] = $p;
            }
        }
        
        // Calculate user's ward stats
        $wardBeds = ['Hope' => 12, 'Manor' => 10, 'Lakeside' => 10];
        $wardSessions = ['Hope' => 0, 'Manor' => 0, 'Lakeside' => 0];
        
        foreach ($sessions as $s) {
            if (strpos($s->datetime, date('Y-m-d')) === 0 && isset($wardSessions[$s->ward])) {
                $wardSessions[$s->ward]++;
            }
        }
        
        // Calculate ward-specific CORE-10 stats
        $wardCoreAdmission = [];
        $wardCoreDischarge = [];
        foreach (['Hope', 'Manor', 'Lakeside'] as $ward) {
            $wardActive = array_filter($activePatients, fn($p) => $p->ward === $ward);
            $wardDischarged = array_filter($dischargedPatients, fn($p) => $p->ward === $ward);
            
            $wardCoreAdmission[$ward] = count(array_filter($wardActive, fn($p) => $p->core10_admission));
            $wardCoreDischarge[$ward] = count(array_filter($wardDischarged, fn($p) => $p->core10_discharge));
        }
        
        view('dashboard', [
            'patients' => $patients,
            'sessions' => $sessions,
            'recentActivities' => $recentActivities,
            'totalPatients' => $totalPatients,
            'totalSessions' => $totalSessions,
            'todaySessions' => $todaySessions,
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