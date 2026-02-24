<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Session;
use App\Models\Patient;
use App\Models\ActivityLog; // ADD THIS LINE
use App\Config\Database;

class SessionController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    /**
     * Display list of all sessions
     */
    public function index()
    {
        Auth::requireLogin();
        
        $sessions = Session::getAll();
        view('sessions.list', ['sessions' => $sessions]);
    }

    /**
     * Store a new session (called from ward pages)
     */
    public function store()
    {
        Auth::requireLogin();
        
        // Verify CSRF token
        verify_csrf($_POST['csrf_token'] ?? '');

        // Validate required fields
        $errors = [];
        
        if (empty($_POST['patient_id'])) {
            $errors[] = 'Patient ID is required';
        }
        
        if (empty($_POST['datetime'])) {
            $errors[] = 'Session date and time is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            
            // Get patient to redirect back to ward
            $patient = Patient::find($_POST['patient_id'] ?? 0);
            if ($patient) {
                redirect('/wards/' . strtolower($patient->ward));
            }
            redirect('/dashboard');
            return;
        }

        // Get patient info to add ward and room number
        $patient = Patient::find($_POST['patient_id']);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found';
            redirect('/dashboard');
            return;
        }

        // Add ward and room number to session data
        $_POST['ward'] = $patient->ward;
        $_POST['room_number'] = $patient->room_number;
        $_POST['initials'] = $patient->initials;

        // Debug: Log checkbox values before creating
        error_log("CareNotes checkbox present: " . (isset($_POST['carenotes']) ? 'YES' : 'NO'));
        error_log("Tracker checkbox present: " . (isset($_POST['tracker']) ? 'YES' : 'NO'));
        error_log("Tasks checkbox present: " . (isset($_POST['tasks']) ? 'YES' : 'NO'));

        // Create the session
        $result = Session::create($_POST);

        if ($result) {
            // Get the new session ID
            $db = Database::getInstance();
            $sessionId = $db->lastInsertId();
            
            // Log the activity
            ActivityLog::create([
                'action_type' => 'session_created',
                'description' => 'Created new session for patient ' . $patient->initials . ' in ' . $patient->ward . ' ward',
                'patient_id' => $patient->id,
                'session_id' => $sessionId,
                'ward' => $patient->ward
            ]);
            
            $_SESSION['success'] = 'Session recorded successfully for patient ' . $patient->initials;
            error_log("Session created successfully");
        } else {
            $_SESSION['error'] = 'Failed to record session';
            error_log("Failed to create session");
        }

        // Redirect back to the ward page
        redirect('/wards/' . strtolower($patient->ward));
    }

    /**
     * Update an existing session
     */
    public function update()
    {
        Auth::requireLogin();
        
        // Verify CSRF token
        verify_csrf($_POST['csrf_token'] ?? '');

        $sessionId = $_POST['session_id'] ?? 0;
        $patientId = $_POST['patient_id'] ?? 0;
        
        // Debug log
        error_log("========== UPDATE SESSION CALLED ==========");
        error_log("Session ID: " . $sessionId);
        error_log("Patient ID: " . $patientId);
        error_log("POST data: " . print_r($_POST, true));
        
        if (!$sessionId || !$patientId) {
            $_SESSION['error'] = 'Missing session or patient ID. Session: ' . $sessionId . ', Patient: ' . $patientId;
            redirect('/dashboard');
            return;
        }
        
        // Get patient info to redirect back
        $patient = Patient::find($patientId);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found with ID: ' . $patientId;
            redirect('/dashboard');
            return;
        }
        
        // Get the original session data before update (for logging)
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM sessions WHERE id = ?");
        $stmt->execute([$sessionId]);
        $oldSession = $stmt->fetch(\PDO::FETCH_OBJ);
        
        // Update the session
        $result = Session::update($sessionId, $_POST);

        if ($result) {
            // Log the activity
            $changes = [];
            if ($oldSession) {
                if ($oldSession->datetime != $_POST['datetime']) {
                    $changes[] = 'time changed';
                }
                if (($oldSession->carenotes_completed ? 1 : 0) != (isset($_POST['carenotes']) ? 1 : 0)) {
                    $changes[] = 'carenotes ' . (isset($_POST['carenotes']) ? 'completed' : 'uncompleted');
                }
                if (($oldSession->tracker_completed ? 1 : 0) != (isset($_POST['tracker']) ? 1 : 0)) {
                    $changes[] = 'tracker ' . (isset($_POST['tracker']) ? 'completed' : 'uncompleted');
                }
                if (($oldSession->tasks_completed ? 1 : 0) != (isset($_POST['tasks']) ? 1 : 0)) {
                    $changes[] = 'tasks ' . (isset($_POST['tasks']) ? 'completed' : 'uncompleted');
                }
                if ($oldSession->notes != $_POST['notes']) {
                    $changes[] = 'notes updated';
                }
            }
            
            $changeText = empty($changes) ? 'No changes' : implode(', ', $changes);
            
            ActivityLog::create([
                'action_type' => 'session_updated',
                'description' => 'Updated session for patient ' . $patient->initials . ' in ' . $patient->ward . ' ward (' . $changeText . ')',
                'patient_id' => $patient->id,
                'session_id' => $sessionId,
                'ward' => $patient->ward
            ]);
            
            $_SESSION['success'] = 'Session updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update session';
        }

        // Redirect back to the ward page
        redirect('/wards/' . strtolower($patient->ward));
    }

    /**
     * Legacy add method (for backward compatibility)
     */
    public function add()
    {
        Auth::requireLogin();
        
        verify_csrf($_POST['csrf_token'] ?? '');
        
        Session::create($_POST);
        redirect('/dashboard');
    }

    /**
     * Archive a session
     */
    public function archive()
    {
        Auth::requireLogin();
        
        $id = $_POST['id'] ?? 0;
        $ward = $_POST['ward'] ?? 'hope';
        
        // Get session info for logging
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT s.*, p.initials as patient_initials FROM sessions s LEFT JOIN patients p ON s.patient_id = p.id WHERE s.id = ?");
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);
        
        Session::archive($id);
        
        // Log the activity
        if ($session) {
            ActivityLog::create([
                'action_type' => 'session_archived',
                'description' => 'Archived session for patient ' . ($session->patient_initials ?? 'Unknown') . ' from ' . ucfirst($ward) . ' ward',
                'patient_id' => $session->patient_id ?? null,
                'session_id' => $id,
                'ward' => ucfirst($ward)
            ]);
        }
        
        $_SESSION['success'] = 'Session archived successfully';
        redirect('/wards/' . strtolower($ward));
    }

    /**
     * Delete a session
     */
    public function delete()
    {
        Auth::requireLogin();
        
        $id = $_POST['id'] ?? 0;
        $ward = $_POST['ward'] ?? 'hope';
        
        // Get session info for logging
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT s.*, p.initials as patient_initials FROM sessions s LEFT JOIN patients p ON s.patient_id = p.id WHERE s.id = ?");
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);
        
        // Log before deletion
        if ($session) {
            ActivityLog::create([
                'action_type' => 'session_deleted',
                'description' => 'Permanently deleted session for patient ' . ($session->patient_initials ?? 'Unknown') . ' from ' . ucfirst($ward) . ' ward',
                'patient_id' => $session->patient_id ?? null,
                'session_id' => $id,
                'ward' => ucfirst($ward)
            ]);
        }
        
        Session::delete($id);
        $_SESSION['success'] = 'Session permanently deleted';
        redirect('/wards/' . strtolower($ward));
    }

    /**
     * Show sessions by ward
     */
    public function byWard($ward)
    {
        Auth::requireLogin();
        
        $sessions = Session::getByWard($ward);
        view('sessions.ward', [
            'ward' => $ward,
            'sessions' => $sessions
        ]);
    }

    /**
     * Show today's sessions by ward
     */
    public function todayByWard($ward)
    {
        Auth::requireLogin();
        
        $sessions = Session::getTodaysByWard($ward);
        view('sessions.today', [
            'ward' => $ward,
            'sessions' => $sessions
        ]);
    }

    /**
     * Show sessions for a specific patient (HTML view)
     */
    public function byPatient($patientId)
    {
        Auth::requireLogin();
        
        $sessions = Session::getByPatient($patientId);
        $patient = Patient::find($patientId);
        
        view('sessions.patient', [
            'patientId' => $patientId,
            'patient' => $patient,
            'sessions' => $sessions
        ]);
    }
    
    /**
     * Restore an archived session
     */
    public function restore()
    {
        Auth::requireLogin();
        verify_csrf($_POST['csrf_token'] ?? '');
        
        $id = $_POST['id'] ?? 0;
        $ward = $_POST['ward'] ?? 'hope';
        
        $db = Database::getInstance();
        
        // Get session info for logging
        $stmt = $db->prepare("SELECT s.*, p.initials as patient_initials FROM sessions s LEFT JOIN patients p ON s.patient_id = p.id WHERE s.id = ?");
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);
        
        $updateStmt = $db->prepare("UPDATE sessions SET is_archived = 0 WHERE id = ?");
        
        if ($updateStmt->execute([$id])) {
            // Log the activity
            if ($session) {
                ActivityLog::create([
                    'action_type' => 'session_restored',
                    'description' => 'Restored archived session for patient ' . ($session->patient_initials ?? 'Unknown') . ' in ' . ucfirst($ward) . ' ward',
                    'patient_id' => $session->patient_id ?? null,
                    'session_id' => $id,
                    'ward' => ucfirst($ward)
                ]);
            }
            
            $_SESSION['success'] = 'Session restored successfully';
        } else {
            $_SESSION['error'] = 'Failed to restore session';
        }
        
        redirect('/wards/' . strtolower($ward) . '/archived-sessions');
    }

    /**
     * Get sessions by patient ID as JSON for AJAX calls
     */
    public function getByPatientJson()
    {
        // Turn off error reporting to prevent HTML output
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Clear any previous output
        if (ob_get_level()) ob_clean();
        
        header('Content-Type: application/json');
        
        try {
            error_log("========== getByPatientJson CALLED ==========");
            error_log("GET params: " . print_r($_GET, true));
            
            Auth::requireLogin();
            
            $patientId = $_GET['id'] ?? null;
            error_log("Patient ID: " . $patientId);
            
            if (!$patientId || !is_numeric($patientId)) {
                error_log("ERROR: Invalid patient ID");
                http_response_code(400);
                echo json_encode(['error' => 'Invalid patient ID']);
                exit;
            }
            
            $sessions = Session::getByPatient($patientId);
            error_log("Found " . count($sessions) . " sessions");
            
            $formattedSessions = [];
            foreach ($sessions as $session) {
                $formattedSessions[] = [
                    'id' => (int)$session->id,
                    'patient_id' => (int)$session->patient_id,
                    'datetime' => $session->datetime,
                    'carenotes_completed' => (bool)$session->carenotes_completed,
                    'tracker_completed' => (bool)$session->tracker_completed,
                    'tasks_completed' => (bool)$session->tasks_completed,
                    'notes' => $session->notes ?? ''
                ];
            }
            
            error_log("Sending " . count($formattedSessions) . " formatted sessions");
            echo json_encode($formattedSessions);
            
        } catch (\Exception $e) {
            error_log("EXCEPTION in getByPatientJson: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }
}