<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Session;
use App\Models\Patient;
use App\Models\ActivityLog;
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
     * Store a new session (AJAX‑aware)
     */
    public function store()
    {
        $isAjax = $this->isAjax();

        // CSRF check
        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            if ($isAjax) {
                $this->jsonResponse(false, 'CSRF token validation failed', 403);
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        // Get patient
        $patientId = (int)($_POST['patient_id'] ?? 0);
        $patient = Patient::find($patientId);
        if (!$patient) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Invalid patient', 400);
            }
            $_SESSION['error'] = 'Invalid patient';
            redirect('/dashboard');
            return;
        }

        // Validate datetime
        if (empty($_POST['datetime'])) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Date and time are required', 400);
            }
            $_SESSION['error'] = 'Date and time are required';
            redirect('/wards/' . strtolower($patient->ward));
            return;
        }

        // CORRECT CHECKBOX HANDLING – convert '0'/'1' to int
        $carenotes = isset($_POST['carenotes']) ? (int)$_POST['carenotes'] : 0;
        $tracker   = isset($_POST['tracker'])   ? (int)$_POST['tracker'] : 0;
        $tasks     = isset($_POST['tasks'])     ? (int)$_POST['tasks'] : 0;

      $data = [
    'patient_id'          => $patientId,
    'ward'                => $patient->ward,
    'room_number'         => $patient->room_number,
    'initials'            => $patient->initials,
    'datetime'            => $_POST['datetime'],
    'carenotes_completed' => $carenotes,
    'tracker_completed'   => $tracker,
    'tasks_completed'     => $tasks,
    'notes'               => trim($_POST['notes'] ?? ''),
    'status'              => $_POST['status'] ?? 'offered',
];

        $sessionId = Session::create($data);

        if ($sessionId) {
            //  LOG: Session created (clean description - no ward, no datetime)
            ActivityLog::create([
                'action_type' => 'session_created',
                'description' => 'Created session for patient ' . $patient->initials,
                'patient_id' => $patientId,
                'session_id' => $sessionId,
                'ward' => $patient->ward
            ]);

            if ($isAjax) {
                $this->jsonResponse(true, 'Session added successfully');
            }
            $_SESSION['success'] = 'Session added successfully';
        } else {
            if ($isAjax) {
                $this->jsonResponse(false, 'Could not save session', 500);
            }
            $_SESSION['error'] = 'Failed to add session';
        }

        // Non‑AJAX redirect
        redirect('/wards/' . strtolower($patient->ward));
    }

    /**
     * Update an existing session (AJAX‑aware)
     */
    public function update()
    {
        $isAjax = $this->isAjax();

        // Clean output buffers
        if (ob_get_level()) ob_clean();

        // CSRF check
        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            if ($isAjax) {
                $this->jsonResponse(false, 'CSRF token validation failed', 403);
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        $id = (int)($_POST['session_id'] ?? 0);
        $session = Session::find($id);
        if (!$session) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Session not found', 404);
            }
            $_SESSION['error'] = 'Session not found';
            redirect('/dashboard');
            return;
        }

        if (empty($_POST['datetime'])) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Date and time are required', 400);
            }
            $_SESSION['error'] = 'Date and time are required';
            redirect('/wards/' . strtolower($session->ward));
            return;
        }

        // CORRECT CHECKBOX HANDLING – convert '0'/'1' to int
        $carenotes = isset($_POST['carenotes']) ? (int)$_POST['carenotes'] : 0;
        $tracker   = isset($_POST['tracker'])   ? (int)$_POST['tracker'] : 0;
        $tasks     = isset($_POST['tasks'])     ? (int)$_POST['tasks'] : 0;

      $data = [
    'datetime'            => $_POST['datetime'],
    'carenotes_completed' => $carenotes,
    'tracker_completed'   => $tracker,
    'tasks_completed'     => $tasks,
    'notes'               => trim($_POST['notes'] ?? ''),
    'status'              => $_POST['status'] ?? 'offered',
];
     $oldStatus = $session->status ?? 'offered';
        $result = Session::update($id, $data);

        if ($result) {
            // LOG: Session updated (clean description - no ward, no datetime)
            $statusNote = '';
            if (strtolower($oldStatus) !== strtolower($data['status'])) {
                $statusNote = ' — status changed to ' . ucfirst($data['status']);
            }

            ActivityLog::create([
                'action_type' => 'session_updated',
                'description' => 'Updated session for patient ' . ($session->initials ?? 'Unknown') . $statusNote,
                'patient_id' => $session->patient_id ?? null,
                'session_id' => $id,
                'ward' => $session->ward ?? 'Unknown'
            ]);

            if ($isAjax) {
                $this->jsonResponse(true, 'Session updated successfully');
            }
            $_SESSION['success'] = 'Session updated successfully';
        } else {
            if ($isAjax) {
                $this->jsonResponse(false, 'Could not update session', 500);
            }
            $_SESSION['error'] = 'Failed to update session';
        }

        redirect('/wards/' . strtolower($session->ward));
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
     * Archive a session (AJAX‑aware) – with logging
     */
    public function archive()
    {
        $isAjax = $this->isAjax();

        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Invalid session ID', 400);
            }
            $_SESSION['error'] = 'Invalid session ID';
            redirect('/dashboard');
            return;
        }

        // Fetch session with patient details BEFORE archiving (for logging)
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT 
                s.*, 
                p.initials AS patient_initials, 
                p.ward AS patient_ward,
                p.id AS patient_id
            FROM sessions s
            LEFT JOIN patients p ON s.patient_id = p.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$session) {
            if ($isAjax) {
                $this->jsonResponse(false, 'Session not found', 404);
            }
            $_SESSION['error'] = 'Session not found';
            redirect('/dashboard');
            return;
        }

        $result = Session::archive($id);

        if ($result) {
            //  LOG: Session archived (clean description - no ward, no datetime)
            $patientInitials = $session->patient_initials ?? $session->initials ?? 'Unknown';

            ActivityLog::create([
                'action_type' => 'session_archived',
                'description' => 'Archived session for patient ' . $patientInitials,
                'patient_id' => $session->patient_id ?? null,
                'session_id' => $id,
                'ward' => $session->patient_ward ?? $session->ward ?? 'Unknown'
            ]);

            if ($isAjax) {
                $this->jsonResponse(true, 'Session archived successfully');
            }
            $_SESSION['success'] = 'Session archived successfully';
        } else {
            if ($isAjax) {
                $this->jsonResponse(false, 'Database error - could not archive session', 500);
            }
            $_SESSION['error'] = 'Failed to archive session';
        }

        // Safe fallback redirect
        $redirectWard = isset($session->patient_ward) ? strtolower($session->patient_ward) : 'dashboard';
        redirect('/wards/' . $redirectWard);
    }

    /**
     * Delete a session (AJAX‑aware) – WITH LOGGING (fetches data before deletion)
     */
    public function delete()
    {
        $isAjax = $this->isAjax();

        // Clean output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $this->jsonResponse(false, 'Invalid session ID', 400);
        }

        // CRITICAL: Fetch session data BEFORE deletion (for activity log)
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT 
                s.*, 
                p.initials AS patient_initials, 
                p.ward AS patient_ward,
                p.id AS patient_id
            FROM sessions s
            LEFT JOIN patients p ON s.patient_id = p.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $session = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$session) {
            $this->jsonResponse(false, 'Session not found', 404);
        }

        // Store log data before deletion
        $patientInitials = $session->patient_initials ?? $session->initials ?? 'Unknown';

        // Perform deletion
        $result = Session::delete($id);

        if ($result) {
            // ✅ LOG: Session deleted (clean description - no ward, no datetime)
            ActivityLog::create([
                'action_type' => 'session_deleted',
                'description' => 'Deleted session for patient ' . $patientInitials,
                'patient_id' => $session->patient_id ?? null,
                'session_id' => $id,
                'ward' => $session->patient_ward ?? $session->ward ?? 'Unknown'
            ]);

            $this->jsonResponse(true, 'Session deleted successfully');
        } else {
            $this->jsonResponse(false, 'Failed to delete session', 500);
        }
    }

    /**
     * Show sessions by ward
     */
    public function byWard($ward)
    {
        Auth::requireLogin();
        $sessions = Session::getByWard($ward);
        view('sessions.ward', ['ward' => $ward, 'sessions' => $sessions]);
    }

    /**
     * Show today's sessions by ward
     */
    public function todayByWard($ward)
    {
        Auth::requireLogin();
        $sessions = Session::getTodaysByWard($ward);
        view('sessions.today', ['ward' => $ward, 'sessions' => $sessions]);
    }

    /**
     * Show sessions for a specific patient (HTML view)
     */
    public function byPatient($patientId)
    {
        Auth::requireLogin();
        $sessions = Session::getByPatient($patientId);
        $patient = Patient::find($patientId);
        view('sessions.patient', ['patientId' => $patientId, 'patient' => $patient, 'sessions' => $sessions]);
    }

    /**
     * Restore an archived session – with logging
     */
    public function restore()
    {
        // CSRF check
        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            $this->jsonResponse(false, 'CSRF token validation failed', 403);
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->jsonResponse(false, 'Invalid session ID', 400);
        }

        try {
            // Fetch session details BEFORE restore for activity log
            $db = Database::getInstance();
            $stmt = $db->prepare("
                SELECT s.*, p.initials AS patient_initials, p.ward AS patient_ward, p.id AS patient_id
                FROM sessions s
                LEFT JOIN patients p ON s.patient_id = p.id
                WHERE s.id = ? AND s.is_archived = 1
            ");
            $stmt->execute([$id]);
            $session = $stmt->fetch(\PDO::FETCH_OBJ);

            $result = Session::restore($id);

            if ($result) {
                //  LOG: Session restored (clean description - no ward, no datetime)
                if ($session) {
                    ActivityLog::create([
                        'action_type' => 'session_restored',
                        'description' => 'Restored session for patient ' . ($session->patient_initials ?? 'Unknown'),
                        'patient_id' => $session->patient_id ?? null,
                        'session_id' => $id,
                        'ward' => $session->patient_ward ?? 'Unknown'
                    ]);
                }
                $this->jsonResponse(true, 'Session restored successfully');
            } else {
                $this->jsonResponse(false, 'Failed to restore session');
            }
        } catch (\Exception $e) {
            error_log('Restore error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $this->jsonResponse(false, 'Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show archived sessions page
     */
    public function archived()
    {
        Auth::requireLogin();
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, p.initials, p.ward, p.room_number
            FROM sessions s
            LEFT JOIN patients p ON s.patient_id = p.id
            WHERE s.is_archived = 1
            ORDER BY s.datetime DESC
        ");
        $stmt->execute();
        $sessions = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $grouped = ['Hope' => [], 'Lakeside' => [], 'Manor' => []];
        foreach ($sessions as $s) {
            $ward = $s->ward ?? 'Hope';
            if (isset($grouped[$ward])) {
                $grouped[$ward][] = $s;
            }
        }
        view('sessions.archived-sessions', ['grouped' => $grouped]);
    }

    /**
     * Get all sessions as JSON for calendar view
     */
    public function getAllJson()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        
        try {
            Auth::requireLogin();

            $db = Database::getInstance();
            $stmt = $db->prepare("
                SELECT s.id, s.patient_id, s.datetime, 
                       s.carenotes_completed, s.tracker_completed, s.tasks_completed,
                       p.initials, p.ward, p.room_number
                FROM sessions s
                LEFT JOIN patients p ON s.patient_id = p.id
                WHERE s.is_archived = 0
                AND (p.discharge_date IS NULL OR p.discharge_date = '0000-00-00')
                ORDER BY s.datetime ASC
            ");
            $stmt->execute();
            $sessions = $stmt->fetchAll(\PDO::FETCH_OBJ);

            $formatted = [];
            foreach ($sessions as $s) {
             $formatted[] = [
    'id'                  => (int)$s->id,
    'patient_id'          => (int)$s->patient_id,
    'datetime'            => $s->datetime,
    'initials'            => $s->initials ?? '?',
    'ward'                => $s->ward ?? 'Hope',
    'room_number'         => $s->room_number,
    'carenotes_completed' => (bool)$s->carenotes_completed,
    'tracker_completed'   => (bool)$s->tracker_completed,
    'tasks_completed'     => (bool)$s->tasks_completed,
    'status'              => $s->status ?? 'offered',
];
            }

            $this->jsonResponse($formatted);

        } catch (\Exception $e) {
            error_log('getAllJson error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Server error'], 500);
        }
    }

    /**
     * Get sessions by patient ID as JSON for AJAX calls
     */
    public function getByPatientJson()
    {
        try {
            Auth::requireLogin();
            $patientId = $_GET['id'] ?? null;
            
            if (!$patientId || !is_numeric($patientId)) {
                $this->jsonResponse(['error' => 'Invalid patient ID'], 400);
            }

            $sessions = Session::getByPatient((int)$patientId);
            $formattedSessions = [];
            
          foreach ($sessions as $session) {
   $formattedSessions[] = [
    'id'                  => (int)$session->id,
    'patient_id'          => (int)$session->patient_id,
    'datetime'            => $session->datetime,
    'carenotes_completed' => (bool)$session->carenotes_completed,
    'tracker_completed'   => (bool)$session->tracker_completed,
    'tasks_completed'     => (bool)$session->tasks_completed,
    'notes'               => $session->notes ?? '',
    'ward'                => $session->ward ?? '',
    'room_number'         => $session->room_number ?? '?',
    'status'              => $session->status ?? 'offered',
];
}
            
            $this->jsonResponse($formattedSessions);

        } catch (\Exception $e) {
            error_log('getByPatientJson error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Server error'], 500);
        }
    }

    /**
     * Helper to detect AJAX requests
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

// ==================== BY WARD AND MONTH (Calendar) ====================
    public function byWardMonth()
    {
        header('Content-Type: application/json');
        while (ob_get_level()) ob_end_clean();

        $ward  = $_GET['ward']  ?? null;
        $year  = (int)($_GET['year']  ?? date('Y'));
        $month = (int)($_GET['month'] ?? date('m'));

        if (!$ward) {
            echo json_encode([]);
            exit;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.id, s.patient_id, s.datetime, s.status,
                   s.notes, s.carenotes_completed,
                   s.tracker_completed, s.tasks_completed,
                   p.initials, p.ward, p.room_number AS patient_room
            FROM sessions s
            JOIN patients p ON s.patient_id = p.id
            WHERE s.ward = ?
            AND YEAR(s.datetime) = ?
            AND MONTH(s.datetime) = ?
            AND s.is_archived = 0
            ORDER BY s.datetime ASC
        ");
        $stmt->execute([$ward, $year, $month]);
        $sessions = $stmt->fetchAll(\PDO::FETCH_OBJ);
     echo json_encode($sessions);
        exit;
    }

    /**
     * Helper to send clean JSON responses (no redirects, no extra output)
     */
    protected function jsonResponse($data, $errorMessage = null, $statusCode = 200)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        if (is_array($data) && isset($data['success']) === false && $errorMessage === null) {
            echo json_encode($data);
        } elseif (is_bool($data)) {
            echo json_encode([
                'success' => $data,
                'error' => $errorMessage
            ]);
        } else {
            echo json_encode($data);
        }

        exit;
    }
}