<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;
use App\Models\ActivityLog;
use App\Config\Database;

class PatientController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    /**
     * Store a new patient (called from ward pages)
     */
    public function store()
    {
        $isAjax = $this->isAjax();

        // Start output buffering to catch any accidental output
        ob_start();

        // CSRF check
        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            ob_clean();
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
                exit;
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        // Validate required fields
        $errors = [];
        if (empty($_POST['ward'])) {
            $errors[] = 'Ward is required';
        }
        if (empty($_POST['room_number'])) {
            $errors[] = 'Room number is required';
        }
        if (empty($_POST['initials'])) {
            $errors[] = 'Patient initials are required';
        }

        // Check room availability
        if (!empty($_POST['ward']) && !empty($_POST['room_number'])) {
            if (!Patient::isRoomAvailable($_POST['ward'], $_POST['room_number'])) {
                $errors[] = 'Room ' . $_POST['room_number'] . ' is already occupied in ' . $_POST['ward'] . ' ward';
            }
        }

        if (!empty($errors)) {
            ob_clean();
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => implode(', ', $errors)]);
                exit;
            }
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/wards/' . strtolower($_POST['ward']));
            return;
        }

        // Prepare data
        $data = [
            'ward'          => $_POST['ward'],
            'room_number'   => $_POST['room_number'],
            'initials'      => strtoupper($_POST['initials']),
            'admission_date'=> $_POST['admission_date'] ?? date('Y-m-d'),
            'core10_admission' => isset($_POST['core10_admission']) ? 1 : 0,
            'notes'         => $_POST['notes'] ?? null
        ];

        try {
            $result = Patient::create($data);
        } catch (\Exception $e) {
            ob_clean();
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
            $_SESSION['error'] = 'Failed to admit patient. Please try again.';
            redirect('/wards/' . strtolower($_POST['ward']));
            return;
        }

        if ($result) {
            $db = \App\Config\Database::getInstance();
            $patientId = $db->lastInsertId();

            ActivityLog::create([
                'action_type' => 'patient_admitted',
                'description' => 'Admitted patient ' . strtoupper($data['initials']) . ' to Room ' . $data['room_number'] . ' in ' . $data['ward'] . ' ward',
                'patient_id' => $patientId,
                'ward' => $data['ward']
            ]);

            ob_clean();
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            $_SESSION['success'] = 'Patient ' . strtoupper($data['initials']) . ' admitted successfully to Room ' . $data['room_number'];
        } else {
            ob_clean();
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }
            $_SESSION['error'] = 'Failed to admit patient. Please try again.';
        }

        // Redirect for non‑AJAX requests
        redirect('/wards/' . strtolower($_POST['ward']));
    }

    /**
     * Helper to detect AJAX requests
     */
protected function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}
    /**
     * View single patient
     */
    public function view($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found';
            redirect('/dashboard');
        }
        $sessions = Session::getByPatient($id);
        view('patients.view', [
            'patient' => $patient,
            'sessions' => $sessions
        ]);
    }

    public function index()
    {
        $patients = Patient::getAll();
        view('patients.index', ['patients' => $patients]);
    }

    /**
     * Update patient room number (AJAX‑aware)
     */
    public function updateRoom()
    {
        $isAjax = $this->isAjax();

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
                exit;
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        $id = (int)($_POST['patient_id'] ?? 0);
        $newRoom = (int)($_POST['room_number'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');

        $patient = Patient::find($id);
        if (!$patient) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Patient not found']);
                exit;
            }
            $_SESSION['error'] = 'Patient not found';
            redirect('/dashboard');
            return;
        }

        // Check room availability (skip if same room)
        if ($newRoom != $patient->room_number && !Patient::isRoomAvailable($patient->ward, $newRoom)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Room ' . $newRoom . ' is already occupied']);
                exit;
            }
            $_SESSION['error'] = 'Room ' . $newRoom . ' is already occupied';
            redirect('/wards/' . strtolower($patient->ward));
            return;
        }

        $result = Patient::updateRoom($id, $newRoom, $reason);

        if ($result) {
            ActivityLog::create([
                'action_type' => 'room_changed',
                'description' => 'Changed room for patient ' . $patient->initials . ' from Room ' . $patient->room_number . ' to Room ' . $newRoom . ' in ' . $patient->ward . ' ward' . ($reason ? ' - Reason: ' . $reason : ''),
                'patient_id' => $patient->id,
                'ward' => $patient->ward
            ]);

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            $_SESSION['success'] = "Patient room changed to Room $newRoom";
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }
            $_SESSION['error'] = "Failed to change room";
        }

        redirect('/wards/' . strtolower($patient->ward));
    }

    // ward transfer and transfer history

    public function transferWard()
{
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);

    if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
        $this->jsonResponse(false, 'CSRF token validation failed', 403);
        return;
    }

 $patientId  = (int)($_POST['patient_id'] ?? 0);
$reason     = trim($_POST['reason'] ?? '');
$roomNumber = (int)($_POST['room_number'] ?? 0);

    $patient = Patient::find($patientId);
    if (!$patient) {
        $this->jsonResponse(false, 'Patient not found', 404);
        return;
    }

    $fromWard = $patient->ward;

    // Enforce transfer rules — Hope is never transferable
    if ($fromWard === 'Hope') {
        $this->jsonResponse(false, 'Hope ward patients cannot be transferred', 403);
        return;
    }

    $toWard = $fromWard === 'Manor' ? 'Lakeside' : 'Manor';

    $db = \App\Config\Database::getInstance();

    // Update patient ward
   if (!$roomNumber) {
    $this->jsonResponse(false, 'Please select a room in the destination ward', 400);
    return;
}

// Check room availability in destination ward
$checkStmt = $db->prepare("SELECT id FROM patients WHERE ward = ? AND room_number = ? AND is_discharged = 0");
$checkStmt->execute([$toWard, $roomNumber]);
if ($checkStmt->fetch()) {
    $this->jsonResponse(false, 'Room ' . $roomNumber . ' is already occupied in ' . $toWard . ' ward', 400);
    return;
}

// Update patient ward and room
$stmt = $db->prepare("UPDATE patients SET ward = ?, room_number = ? WHERE id = ?");
$result = $stmt->execute([$toWard, $roomNumber, $patientId]);
    if (!$result) {
        $this->jsonResponse(false, 'Failed to transfer patient', 500);
        return;
    }

    // Record transfer history
    $stmt = $db->prepare("
        INSERT INTO patient_ward_history (patient_id, from_ward, to_ward, changed_by, transfer_reason, transferred_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$patientId, $fromWard, $toWard, $_SESSION['user_id'], $reason ?: null]);

    // Audit log
    ActivityLog::create([
        'action_type' => 'ward_transfer',
        'description' => 'Transferred patient ' . $patient->initials . ' from ' . $fromWard . ' to ' . $toWard . ($reason ? ' — ' . $reason : ''),
        'patient_id'  => $patientId,
        'session_id'  => null,
        'ward'        => $toWard
    ]);

    $this->jsonResponse(true, 'Patient transferred to ' . $toWard . ' successfully');
}

public function roomHistoryJson()
    {
        header('Content-Type: application/json');
        $patientId = (int)($_GET['id'] ?? 0);
        if (!$patientId) { echo json_encode([]); exit; }

        $db = Database::getInstance();
       $stmt = $db->prepare("
            SELECT 
                prh.from_room,
                prh.to_room,
                prh.changed_at,
                prh.reason,
                COALESCE(u.full_name, u.username, 'Unknown') AS changed_by
            FROM patient_room_history prh
            LEFT JOIN users u ON u.id = prh.changed_by
            WHERE prh.patient_id = ?
            ORDER BY prh.changed_at DESC
        ");
        $stmt->execute([$patientId]);
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
        exit;
    }

public function wardHistoryJson()
{
    $patientId = (int)($_GET['id'] ?? 0);
    if (!$patientId) {
        $this->jsonResponse([], 'Invalid patient ID', 400);
        return;
    }

    $db = \App\Config\Database::getInstance();
    $stmt = $db->prepare("
        SELECT 
            pwh.id,
            pwh.from_ward,
            pwh.to_ward,
            pwh.transfer_reason,
            pwh.transferred_at,
            COALESCE(NULLIF(u.full_name, ''), NULLIF(u.username, ''), 'Unknown') AS changed_by
        FROM patient_ward_history pwh
        LEFT JOIN users u ON u.id = pwh.changed_by AND pwh.changed_by > 0
        WHERE pwh.patient_id = ?
        ORDER BY pwh.transferred_at DESC
    ");
    $stmt->execute([$patientId]);
    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $this->jsonResponse($rows);
}

    /**
     * Archive a patient (soft delete)
     */
    public function archive()
    {
        verify_csrf($_POST['csrf_token'] ?? '');
        $id = $_POST['id'] ?? 0;
        $ward = $_POST['ward'] ?? 'hope';
        $patient = Patient::find($id);
        if (Patient::archive($id)) {
            ActivityLog::create([
                'action_type' => 'patient_archived',
                'description' => 'Archived patient ' . ($patient ? $patient->initials : 'Unknown') . ' from ' . ucfirst($ward) . ' ward',
                'patient_id' => $id,
                'ward' => ucfirst($ward)
            ]);
            $_SESSION['success'] = 'Patient archived successfully';
        } else {
            $_SESSION['error'] = 'Failed to archive patient';
        }
        redirect('/wards/' . strtolower($ward));
    }

   
/**
 * Permanently delete a patient
 */
public function delete()
{
    $isAjax = $this->isAjax();

    //  Disable error output (prevents breaking JSON)
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    // 🧹 Clear ALL output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    // CSRF check
    if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'CSRF token validation failed'
            ]);
            exit;
        }

        $_SESSION['error'] = 'CSRF token validation failed';
        redirect('/dashboard');
        return;
    }

    $id = (int)($_POST['id'] ?? 0);

    if (!$id) {
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => 'Invalid patient ID'
            ]);
            exit;
        }
        redirect('/dashboard');
        return;
    }

    $patient = Patient::find($id);
    $ward = $patient ? $patient->ward : 'hope';

    //  Log before delete
    if ($patient) {
        ActivityLog::create([
            'action_type' => 'patient_deleted',
            'description' => 'Discharged Patients: Permanently deleted patient ' .
$patient->initials .
' from ' .
$patient->ward .
' ward',
            'patient_id' => $id,
            'ward' => $patient->ward
        ]);
    }

    $result = Patient::delete($id);

    //  Ensure nothing leaked after DB call
    while (ob_get_level()) {
        ob_end_clean();
    }

    //  AJAX response
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => (bool)$result,
            'error' => $result ? null : 'Failed to delete patient'
        ]);

        exit;
    }

    //  Fallback (non-AJAX)
    if ($result) {
        $_SESSION['success'] = 'Patient permanently deleted';
    } else {
        $_SESSION['error'] = 'Failed to delete patient';
    }

    redirect('/wards/' . strtolower($ward));
}
    /**
     * Restore an archived patient
     */
    public function restore()
    {
        verify_csrf($_POST['csrf_token'] ?? '');
        $id = $_POST['id'] ?? 0;
        $ward = $_POST['ward'] ?? 'hope';
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE patients SET is_archived = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $patient = Patient::find($id);
            ActivityLog::create([
                'action_type' => 'patient_restored',
                'description' => 'Restored archived patient ' . ($patient ? $patient->initials : 'Unknown') . ' in ' . ucfirst($ward) . ' ward',
                'patient_id' => $id,
                'ward' => ucfirst($ward)
            ]);
            $_SESSION['success'] = 'Patient restored successfully';
        } else {
            $_SESSION['error'] = 'Failed to restore patient';
        }
        redirect('/wards/' . strtolower($ward) . '/archived-patients');
    }

    // ==================== NEW METHODS ====================

    /**
     * Change patient room (AJAX) – used by Change Room modal
     */
    public function changeRoom()
    {
        $isAjax = $this->isAjax();

        if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
                exit;
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        $patientId = (int)($_POST['patient_id'] ?? 0);
        $newRoom = (int)($_POST['room_number'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');

        $patient = Patient::find($patientId);
        if (!$patient) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Patient not found']);
                exit;
            }
            $_SESSION['error'] = 'Patient not found';
            redirect('/dashboard');
            return;
        }

        // Check room availability (skip if same room)
        if ($newRoom != $patient->room_number && !Patient::isRoomAvailable($patient->ward, $newRoom)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Room ' . $newRoom . ' is already occupied']);
                exit;
            }
            $_SESSION['error'] = 'Room ' . $newRoom . ' is already occupied';
            redirect('/wards/' . strtolower($patient->ward));
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE patients SET room_number = ? WHERE id = ?");
        $result = $stmt->execute([$newRoom, $patientId]);

       if ($result) {
            // Log to room history table
           $db->prepare("
                INSERT INTO patient_room_history (patient_id, from_room, to_room, changed_by, reason)
                VALUES (?, ?, ?, ?, ?)
            ")->execute([
                $patientId,
                $patient->room_number,
                $newRoom,
                $_SESSION['user_id'] ?? null,
                $reason ?: null
            ]);

            ActivityLog::create([
                'action_type' => 'room_changed',
                'description' => 'Changed room for patient ' . $patient->initials . ' from Room ' . $patient->room_number . ' to Room ' . $newRoom . ' in ' . $patient->ward . ' ward' . ($reason ? ' - Reason: ' . $reason : ''),
                'patient_id' => $patientId,
                'ward' => $patient->ward
            ]);

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            $_SESSION['success'] = "Patient room changed to Room $newRoom";
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }
            $_SESSION['error'] = "Failed to change room";
        }

        redirect('/wards/' . strtolower($patient->ward));
    }

/**
 * Discharge patient (AJAX) – with backdate support
 */
public function discharge()
{
    $isAjax = $this->isAjax();

    // CSRF check
    if (!isset($_POST['csrf_token']) || !verify_csrf($_POST['csrf_token'])) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
            exit;
        }
        $_SESSION['error'] = 'CSRF token validation failed';
        redirect('/dashboard');
        return;
    }

    $patientId = (int)($_POST['patient_id'] ?? 0);
    $patient = Patient::find($patientId);

    if (!$patient) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Patient not found']);
            exit;
        }
        $_SESSION['error'] = 'Patient not found';
        redirect('/dashboard');
        return;
    }

    // Prevent double discharge
    if ($patient->discharge_date) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Patient already discharged']);
            exit;
        }
        $_SESSION['error'] = 'Patient already discharged';
        redirect('/wards/' . strtolower($patient->ward));
        return;
    }

    // Handle inputs
    $core10_discharge = isset($_POST['core10_discharge']) ? 1 : 0;
    $dischargeNotes = trim($_POST['notes'] ?? '');
    $submittedDate = trim($_POST['discharge_date'] ?? '');

    // Validate and set discharge date (allow backdating)
    $dischargeDate = date('Y-m-d'); // default today
    if (!empty($submittedDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $submittedDate)) {
        $dischargeDate = $submittedDate;
    }

    // Append discharge notes (include the selected discharge date)
    $currentNotes = $patient->notes ?? '';
    $dischargeSection = "\n\n=== DISCHARGE NOTES [" . date('Y-m-d H:i:s') . "] ===\n";
    $dischargeSection .= "Discharge Date: " . $dischargeDate . "\n";
    $dischargeSection .= "CORE-10 at discharge: " . ($core10_discharge ? 'Yes' : 'No') . "\n";
    $dischargeSection .= "Notes: " . $dischargeNotes . "\n";
    $dischargeSection .= str_repeat('=', 50) . "\n";

    $newNotes = $currentNotes . $dischargeSection;

    // Update patient with custom discharge date
    $db = Database::getInstance();
    $stmt = $db->prepare("
        UPDATE patients 
        SET discharge_date = ?, 
            core10_discharge = ?,
            notes = ?,
            is_discharged = 1
        WHERE id = ?
    ");

    $result = $stmt->execute([$dischargeDate, $core10_discharge, $newNotes, $patientId]);

    if ($result) {

        // Log activity
        ActivityLog::create([
            'action_type' => 'patient_discharged',
            'description' => 'Discharged patient ' . $patient->initials . ' from Room ' . $patient->room_number . ' in ' . $patient->ward . ' ward (discharge date: ' . $dischargeDate . ')',
            'patient_id' => $patientId,
            'ward' => $patient->ward
        ]);

        // AJAX response
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        // Normal request success message
        $_SESSION['success'] = 'Patient discharged successfully';

    } else {

        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
            exit;
        }

        $_SESSION['error'] = 'Failed to discharge patient';
    }

    redirect('/wards/' . strtolower($patient->ward));
}

    /**
     * Update CORE-10 admission status (AJAX)
     */
    public function updateCore10()
    {
        $isAjax = $this->isAjax();

        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['csrf_token']) || !verify_csrf($input['csrf_token'])) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
                exit;
            }
            $_SESSION['error'] = 'CSRF token validation failed';
            redirect('/dashboard');
            return;
        }

        $patientId = (int)($input['patient_id'] ?? 0);
        $core10Admission = (int)($input['core10_admission'] ?? 0);

        $patient = Patient::find($patientId);
        if (!$patient) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Patient not found']);
                exit;
            }
            $_SESSION['error'] = 'Patient not found';
            redirect('/dashboard');
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE patients SET core10_admission = ? WHERE id = ?");
        $result = $stmt->execute([$core10Admission, $patientId]);

    if ($result) {
            $source = trim($input['source'] ?? '');
            $prefix = $source === 'discharged' ? 'Discharged Patients: ' : '';
            ActivityLog::create([
                'action_type' => 'core10_updated',
                'description' => $prefix . 'Updated CORE-10 admission status for patient '
                    . $patient->initials
                    . ' to '
                    . ($core10Admission ? 'Completed' : 'Pending'),
                'patient_id'  => $patient->id,
                'ward'        => $patient->ward
            ]);

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }
        }

        redirect('/wards/' . strtolower($patient->ward));
    }
    
    /**
 * Update CORE-10 discharge status (AJAX)
 */
public function updateDischargeCore10()
{
    $isAjax = $this->isAjax();

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['csrf_token']) || !verify_csrf($input['csrf_token'])) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'CSRF token validation failed']);
            exit;
        }
        $_SESSION['error'] = 'CSRF token validation failed';
        redirect('/dashboard');
        return;
    }

    $patientId = (int)($input['patient_id'] ?? 0);
    $core10Discharge = (int)($input['core10_discharge'] ?? 0);

    $patient = Patient::find($patientId);
    if (!$patient) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Patient not found']);
            exit;
        }
        $_SESSION['error'] = 'Patient not found';
        redirect('/dashboard');
        return;
    }

    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE patients SET core10_discharge = ? WHERE id = ?");
    $result = $stmt->execute([$core10Discharge, $patientId]);

  if ($result) {
        $source = trim($input['source'] ?? '');
        $prefix = $source === 'discharged' ? 'Discharged Patients: ' : '';
        ActivityLog::create([
            'action_type' => 'core10_updated',
            'description' => $prefix . 'Updated CORE-10 discharge status for patient '
                . $patient->initials
                . ' to '
                . ($core10Discharge ? 'Completed' : 'Pending'),
            'patient_id'  => $patientId,
            'ward'        => $patient->ward
        ]);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    } else {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
            exit;
        }
    }

    redirect('/wards/' . strtolower($patient->ward));
}

public function getByWard()
{
    header('Content-Type: application/json');
    $ward = $_GET['ward'] ?? '';
    if (!$ward) {
        echo json_encode([]);
        return;
    }
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT id, initials, room_number, ward 
        FROM patients 
        WHERE ward = ? AND is_discharged = 0 
        ORDER BY room_number
    ");
    $stmt->execute([$ward]);
    $patients = $stmt->fetchAll(\PDO::FETCH_OBJ);
    echo json_encode($patients);
}

    /**
     * Get patient summary as JSON for AJAX calls
     */
    public function getSummaryJson()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');

        try {
            Auth::requireLogin();
            $patientId = $_GET['id'] ?? null;
            if (!$patientId || !is_numeric($patientId)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid patient ID']);
                exit;
            }

            $db = \App\Config\Database::getInstance();
            $stmt = $db->prepare("
                SELECT id, initials, ward, room_number, 
                       admission_date, discharge_date,
                       core10_admission, core10_discharge 
                FROM patients WHERE id = ?
            ");
            $stmt->execute([$patientId]);
            $patient = $stmt->fetch(\PDO::FETCH_OBJ);

            if (!$patient) {
                http_response_code(404);
                echo json_encode(['error' => 'Patient not found']);
                exit;
            }

            $admissionDateTime = $patient->admission_date
                ? date('d/m/Y', strtotime($patient->admission_date))
                : null;
            $dischargeDateTime = $patient->discharge_date
                ? date('d/m/Y', strtotime($patient->discharge_date))
                : null;

            $response = [
                'id'                   => $patient->id,
                'initials'             => $patient->initials,
                'ward'                 => $patient->ward,
                'room_number'          => $patient->room_number,
                'admission_date'   => $admissionDateTime,
                'discharge_datetime'   => $dischargeDateTime,
                'core10_admission'     => (bool)$patient->core10_admission,
                'core10_discharge'     => (bool)$patient->core10_discharge,
                'is_discharged'        => $patient->discharge_date ? true : false
            ];
            echo json_encode($response);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }

    /**
     * Get patient notes as JSON for AJAX calls (ADMISSION NOTES ONLY)
     */
    public function getNotesJson()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');

        try {
            Auth::requireLogin();
            $patientId = $_GET['id'] ?? null;
            if (!$patientId || !is_numeric($patientId)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid patient ID']);
                exit;
            }

            $patient = Patient::find($patientId);
            if (!$patient) {
                http_response_code(404);
                echo json_encode(['error' => 'Patient not found']);
                exit;
            }

            $admissionNotes = $patient->notes ?? '';
            if (strpos($admissionNotes, '=== DISCHARGE NOTES') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, '=== DISCHARGE NOTES'));
            } elseif (strpos($admissionNotes, 'DISCHARGE NOTES') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, 'DISCHARGE NOTES'));
            } elseif (strpos($admissionNotes, 'Discharge Notes:') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, 'Discharge Notes:'));
            }
            $admissionNotes = trim($admissionNotes);

            echo json_encode(['notes' => $admissionNotes]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }

    /**
     * Show all discharged patients (grouped by ward)
     */
    public function discharged()
    {
        $db = \App\Config\Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE discharge_date IS NOT NULL 
            ORDER BY discharge_date DESC
        ");
        $stmt->execute();
        $patients = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $grouped = ['Hope' => [], 'Lakeside' => [], 'Manor' => []];
        foreach ($patients as $p) {
            if (isset($grouped[$p->ward])) {
                $grouped[$p->ward][] = $p;
            } else {
                $grouped['Hope'][] = $p;
            }
        }
        view('patients.discharged-patients', ['grouped' => $grouped]);
    }

protected function jsonResponse($data, $errorMessage = null, $statusCode = 200)
{
    while (ob_get_level()) {
        ob_end_clean();
    }

    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');

    if (is_array($data) && $errorMessage === null) {
        echo json_encode($data);
    } elseif (is_bool($data)) {
        echo json_encode([
            'success' => $data,
            'message' => $errorMessage,
            'error'   => $data ? null : $errorMessage
        ]);
    } else {
        echo json_encode($data);
    }

    exit;
}

    /**
     * Get patient discharge notes as JSON for AJAX calls
     */
    public function getDischargeNotesJson()
    {
        error_reporting(0);
        ini_set('display_errors', 0);
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');

        try {
            Auth::requireLogin();
            $patientId = $_GET['id'] ?? null;
            if (!$patientId || !is_numeric($patientId)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid patient ID']);
                exit;
            }

            $patient = Patient::find($patientId);
            if (!$patient) {
                http_response_code(404);
                echo json_encode(['error' => 'Patient not found']);
                exit;
            }

            $dischargeNotes = '';
            if ($patient->notes) {
                if (preg_match('/={50,}\nDISCHARGE NOTES \[(.*?)\]\n={50,}\n(.*?)\n={50,}/s', $patient->notes, $matches)) {
                    $dischargeNotes = trim($matches[2]);
                } elseif (strpos($patient->notes, 'Discharge Notes:') !== false) {
                    $parts = explode('Discharge Notes:', $patient->notes);
                    $dischargeNotes = trim(end($parts));
                } elseif (strpos($patient->notes, 'DISCHARGE NOTES') !== false) {
                    $parts = explode('DISCHARGE NOTES', $patient->notes);
                    if (isset($parts[1])) {
                        $content = substr($parts[1], strpos($parts[1], ']\n') + 2);
                        $content = preg_replace('/=+$/', '', $content);
                        $dischargeNotes = trim($content);
                    }
                }
            }
            echo json_encode(['notes' => $dischargeNotes]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }
}