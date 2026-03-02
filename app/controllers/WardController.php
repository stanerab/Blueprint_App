<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;

class WardController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function hope()
    {
        return $this->showUserWard('Hope', 12);
    }

    public function manor()
    {
        return $this->showUserWard('Manor', 10);
    }
    
    public function lakeside()
    {
        return $this->showUserWard('Lakeside', 10);
    }
    
    /**
     * Show ward data for the logged-in user only
     */
    private function showUserWard($ward, $totalBeds)
    {
        $userId = $_SESSION['user_id'];
        
        // Get user's active patients in this ward
        $patients = Patient::getActiveByWardAndUser($ward, $userId);
        
        // Sort patients by room number (ascending)
        usort($patients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Get user's sessions for this ward
        $sessions = Session::getByWardAndUser($ward, $userId);
        
        // Get user's archived patients in this ward
        $archivedPatients = Patient::getArchivedByWardAndUser($ward, $userId);
        
        // Sort archived patients by room number
        usort($archivedPatients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Get user's archived sessions in this ward
        $archivedSessions = Session::getArchivedByWardAndUser($ward, $userId);
        
        // Get user's discharged patients from this ward
        $discharged = Patient::getDischargedByWardAndUser($ward, $userId);
        
        // Sort discharged patients by room number
        usort($discharged, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Calculate CORE-10 stats
        $core10AdmissionCompleted = count(array_filter($patients, fn($p) => $p->core10_admission));
        $core10DischargeCompleted = count(array_filter($discharged, fn($p) => $p->core10_discharge));
        
        // Calculate stats
        $occupiedBeds = count($patients);
        $stats = [
            'total_beds' => $totalBeds,
            'occupied_beds' => $occupiedBeds,
            'available_beds' => $totalBeds - $occupiedBeds,
            'core10_completed' => $core10AdmissionCompleted, // For backward compatibility
            'core10_admission_completed' => $core10AdmissionCompleted,
            'core10_discharge_completed' => $core10DischargeCompleted,
            'total_sessions' => count($sessions),
            'sessions_today' => count(array_filter($sessions, function($s) {
                return strpos($s->datetime, date('Y-m-d')) === 0;
            })),
            'discharged_this_month' => count(array_filter($discharged, function($p) {
                return strpos($p->discharge_date ?? '', date('Y-m')) === 0;
            }))
        ];
        
        view('wards.' . strtolower($ward), [
            'ward' => $ward,
            'patients' => $patients,
            'sessions' => $sessions,
            'archivedPatients' => $archivedPatients,
            'archivedSessions' => $archivedSessions,
            'discharged' => array_slice($discharged, 0, 5),
            'stats' => $stats,
            'totalDischarged' => count($discharged),
            'core10AdmissionCompleted' => $core10AdmissionCompleted,
            'core10DischargeCompleted' => $core10DischargeCompleted
        ]);
    }

    /**
     * Show all archived patients
     */
    public function archivedPatients($ward)
    {
        Auth::requireLogin();
        
        $ward = ucfirst(strtolower($ward));
        $archivedPatients = Patient::getArchivedByWard($ward);
        
        // Sort archived patients by room number (ascending)
        usort($archivedPatients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Load the view directly without using the view() helper
        $viewPath = APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'wards' . DIRECTORY_SEPARATOR . 'archived-patients.php';
        
        if (!file_exists($viewPath)) {
            die("View file not found: " . $viewPath);
        }
        
        // Extract variables for the view
        extract([
            'ward' => $ward,
            'archivedPatients' => $archivedPatients,
            'title' => $ward . ' Ward - Archived Patients'
        ]);
        
        // Include the header
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';
        
        // Include the view
        require $viewPath;
        
        // Include the footer
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
        
        exit;
    }
    
    /**
     * Show all archived sessions
     */
    public function archivedSessions($ward)
    {
        Auth::requireLogin();
        
        $ward = ucfirst(strtolower($ward));
        $archivedSessions = Session::getArchivedByWard($ward);
        
        // Direct file path approach (same as archived patients)
        $viewPath = APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'wards' . DIRECTORY_SEPARATOR . 'archived-sessions.php';
        
        if (!file_exists($viewPath)) {
            die("View file not found at: " . $viewPath);
        }
        
        // Extract variables for the view
        extract([
            'ward' => $ward,
            'archivedSessions' => $archivedSessions,
            'title' => $ward . ' Ward - Archived Sessions'
        ]);
        
        // Include the header
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';
        
        // Include the view
        require $viewPath;
        
        // Include the footer
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
        
        exit;
    }

    /**
     * Show all discharged patients
     */
    public function dischargedPatients($ward)
    {
        Auth::requireLogin();
        
        $ward = ucfirst(strtolower($ward));
        $discharged = Patient::getDischargedByWard($ward);
        
        // Sort discharged patients by room number (ascending)
        usort($discharged, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $viewPath = APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'wards' . DIRECTORY_SEPARATOR . 'discharged-patients.php';
        
        if (!file_exists($viewPath)) {
            die("View file not found at: " . $viewPath);
        }
        
        extract([
            'ward' => $ward,
            'discharged' => $discharged,
            'title' => $ward . ' Ward - Discharged Patients'
        ]);
        
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';
        require $viewPath;
        require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
        
        exit;
    }
}