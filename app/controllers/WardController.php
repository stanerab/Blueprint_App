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
        $ward = 'Hope';
        $wardBeds = 12;
        
        // Get active patients (not discharged, not archived)
        $patients = Patient::getActiveByWard($ward);
        
        // Sort patients by room number (ascending)
        usort($patients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Get all sessions for this ward
        $sessions = Session::getByWard($ward);
        
        // Get archived patients
        $archivedPatients = Patient::getArchivedByWard($ward);
        
        // Sort archived patients by room number
        usort($archivedPatients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Get archived sessions
        $archivedSessions = Session::getArchivedByWard($ward);
        
        // Get discharged patients
        $discharged = Patient::getDischargedByWard($ward);
        
        // Sort discharged patients by room number
        usort($discharged, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        // Calculate stats
        $stats = [
            'total_beds' => $wardBeds,
            'occupied_beds' => count($patients),
            'available_beds' => $wardBeds - count($patients),
            'core10_completed' => count(array_filter($patients, function($p) { 
                return $p->core10_admission; 
            })),
            'total_sessions' => count($sessions),
            'sessions_today' => count(array_filter($sessions, function($s) {
                return strpos($s->datetime, date('Y-m-d')) === 0;
            })),
            'discharged_this_month' => count(array_filter($discharged, function($p) {
                return strpos($p->discharge_date ?? '', date('Y-m')) === 0;
            }))
        ];
        
        view('wards.hope', [
            'ward' => $ward,
            'patients' => $patients,
            'sessions' => $sessions,
            'archivedPatients' => $archivedPatients,
            'archivedSessions' => $archivedSessions,
            'discharged' => array_slice($discharged, 0, 5),
            'stats' => $stats
        ]);
    }

    public function manor()
    {
        $ward = 'Manor';
        $wardBeds = 10;
        
        $patients = Patient::getActiveByWard($ward);
        
        // Sort patients by room number (ascending)
        usort($patients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $sessions = Session::getByWard($ward);
        $archivedPatients = Patient::getArchivedByWard($ward);
        
        // Sort archived patients by room number
        usort($archivedPatients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $archivedSessions = Session::getArchivedByWard($ward);
        $discharged = Patient::getDischargedByWard($ward);
        
        // Sort discharged patients by room number
        usort($discharged, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $stats = [
            'total_beds' => $wardBeds,
            'occupied_beds' => count($patients),
            'available_beds' => $wardBeds - count($patients),
            'core10_completed' => count(array_filter($patients, function($p) { 
                return $p->core10_admission; 
            })),
            'total_sessions' => count($sessions),
            'sessions_today' => count(array_filter($sessions, function($s) {
                return strpos($s->datetime, date('Y-m-d')) === 0;
            })),
            'discharged_this_month' => count(array_filter($discharged, function($p) {
                return strpos($p->discharge_date ?? '', date('Y-m')) === 0;
            }))
        ];
        
        view('wards.manor', [
            'ward' => $ward,
            'patients' => $patients,
            'sessions' => $sessions,
            'archivedPatients' => $archivedPatients,
            'archivedSessions' => $archivedSessions,
            'discharged' => array_slice($discharged, 0, 5),
            'stats' => $stats
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
    
    public function lakeside()
    {
        $ward = 'Lakeside';
        $wardBeds = 10;
        
        $patients = Patient::getActiveByWard($ward);
        
        // Sort patients by room number (ascending)
        usort($patients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $sessions = Session::getByWard($ward);
        $archivedPatients = Patient::getArchivedByWard($ward);
        
        // Sort archived patients by room number
        usort($archivedPatients, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $archivedSessions = Session::getArchivedByWard($ward);
        $discharged = Patient::getDischargedByWard($ward);
        
        // Sort discharged patients by room number
        usort($discharged, function($a, $b) {
            return (int)$a->room_number - (int)$b->room_number;
        });
        
        $stats = [
            'total_beds' => $wardBeds,
            'occupied_beds' => count($patients),
            'available_beds' => $wardBeds - count($patients),
            'core10_completed' => count(array_filter($patients, function($p) { 
                return $p->core10_admission; 
            })),
            'total_sessions' => count($sessions),
            'sessions_today' => count(array_filter($sessions, function($s) {
                return strpos($s->datetime, date('Y-m-d')) === 0;
            })),
            'discharged_this_month' => count(array_filter($discharged, function($p) {
                return strpos($p->discharge_date ?? '', date('Y-m')) === 0;
            }))
        ];
        
        view('wards.lakeside', [
            'ward' => $ward,
            'patients' => $patients,
            'sessions' => $sessions,
            'archivedPatients' => $archivedPatients,
            'archivedSessions' => $archivedSessions,
            'discharged' => array_slice($discharged, 0, 5),
            'stats' => $stats
        ]);
    }
}