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
        
        // Get all patients and sessions
        $patients = Patient::getAll();
        $sessions = Session::getAll();
        
        // Get recent activities (with error handling)
        try {
            $recentActivities = ActivityLog::getRecent(10);
        } catch (\Exception $e) {
            $recentActivities = [];
            error_log("Error fetching activities: " . $e->getMessage());
        }
        
        // Get stats
        $totalPatients = count(array_filter($patients, fn($p) => !$p->discharge_date));
        $totalDischarged = count(array_filter($patients, fn($p) => $p->discharge_date));
        $totalSessions = count($sessions);
        $todaySessions = count(array_filter($sessions, function($s) {
            return strpos($s->datetime, date('Y-m-d')) === 0;
        }));
        $core10Completed = count(array_filter($patients, function($p) {
            return !$p->discharge_date && $p->core10_admission;
        }));
        
        // Group by ward
        $wardPatients = [
            'Hope' => [],
            'Manor' => [],
            'Lakeside' => []
        ];
        
        foreach ($patients as $p) {
            if (!$p->discharge_date && isset($wardPatients[$p->ward])) {
                $wardPatients[$p->ward][] = $p;
            }
        }
        
        // Calculate ward stats
        $wardBeds = ['Hope' => 12, 'Manor' => 10, 'Lakeside' => 10];
        $wardSessions = ['Hope' => 0, 'Manor' => 0, 'Lakeside' => 0];
        
        foreach ($sessions as $s) {
            if (strpos($s->datetime, date('Y-m-d')) === 0 && isset($wardSessions[$s->ward])) {
                $wardSessions[$s->ward]++;
            }
        }
        
        view('dashboard', [
            'patients' => $patients,
            'sessions' => $sessions,
            'recentActivities' => $recentActivities,
            'totalPatients' => $totalPatients,
            'totalSessions' => $totalSessions,
            'todaySessions' => $todaySessions,
            'totalDischarged' => $totalDischarged,
            'core10Completed' => $core10Completed,
            'wardPatients' => $wardPatients,
            'wardSessions' => $wardSessions,
            'wardBeds' => $wardBeds
        ]);
    }
}