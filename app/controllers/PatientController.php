<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\Patient;
use App\Models\Session;
use App\Models\ActivityLog; // ADD THIS LINE
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
        // Verify CSRF token
        verify_csrf($_POST['csrf_token'] ?? '');

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

        // Check if room is available in the ward
        if (!empty($_POST['ward']) && !empty($_POST['room_number'])) {
            if (!Patient::isRoomAvailable($_POST['ward'], $_POST['room_number'])) {
                $errors[] = 'Room ' . $_POST['room_number'] . ' is already occupied in ' . $_POST['ward'] . ' ward';
            }
        }

        // If there are errors, redirect back with error messages
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/wards/' . strtolower($_POST['ward']));
            return;
        }

        // Create the patient
        $result = Patient::create($_POST);

        if ($result) {
            // Get the new patient ID
            $db = Database::getInstance();
            $patientId = $db->lastInsertId();
            
            // Log the activity
            ActivityLog::create([
                'action_type' => 'patient_admitted',
                'description' => 'Admitted patient ' . strtoupper($_POST['initials']) . ' to Room ' . $_POST['room_number'] . ' in ' . $_POST['ward'] . ' ward',
                'patient_id' => $patientId,
                'ward' => $_POST['ward']
            ]);
            
            $_SESSION['success'] = 'Patient ' . strtoupper($_POST['initials']) . ' admitted successfully to Room ' . $_POST['room_number'];
        } else {
            $_SESSION['error'] = 'Failed to admit patient. Please try again.';
        }

        // Redirect back to the ward page
        redirect('/wards/' . strtolower($_POST['ward']));
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
        
        // Get patient sessions
        $sessions = Session::getByPatient($id);
        
        view('patients.view', [
            'patient' => $patient,
            'sessions' => $sessions
        ]);
    }

    /**
     * Discharge a patient
     */
    public function discharge()
    {
        verify_csrf($_POST['csrf_token'] ?? '');
        
        $id = $_POST['patient_id'] ?? 0;
        $patient = Patient::find($id);
        
        if ($patient) {
            Patient::discharge($id, $_POST);
            
            // Log the activity
            ActivityLog::create([
                'action_type' => 'patient_discharged',
                'description' => 'Discharged patient ' . $patient->initials . ' from Room ' . $patient->room_number . ' in ' . $patient->ward . ' ward',
                'patient_id' => $patient->id,
                'ward' => $patient->ward
            ]);
            
            $_SESSION['success'] = 'Patient ' . $patient->initials . ' discharged successfully';
            redirect('/wards/' . strtolower($patient->ward));
        }
        
        $_SESSION['error'] = 'Patient not found';
        redirect('/dashboard');
    }

    /**
     * Show all patients (optional)
     */
    public function index()
    {
        $patients = Patient::getAll();
        view('patients.index', ['patients' => $patients]);
    }

    /**
     * Update patient room number
     */
    public function updateRoom()
    {
        verify_csrf($_POST['csrf_token'] ?? '');
        
        $id = $_POST['patient_id'] ?? 0;
        $newRoom = $_POST['room_number'] ?? 0;
        $reason = $_POST['reason'] ?? '';
        
        $patient = Patient::find($id);
        
        if ($patient && Patient::updateRoom($id, $newRoom, $reason)) {
            // Log the activity
            ActivityLog::create([
                'action_type' => 'room_changed',
                'description' => 'Changed room for patient ' . $patient->initials . ' from Room ' . $patient->room_number . ' to Room ' . $newRoom . ' in ' . $patient->ward . ' ward' . ($reason ? ' - Reason: ' . $reason : ''),
                'patient_id' => $patient->id,
                'ward' => $patient->ward
            ]);
            
            $_SESSION['success'] = "Patient room changed to Room $newRoom";
        } else {
            $_SESSION['error'] = "Failed to change room";
        }
        
        redirect('/wards/' . strtolower($patient->ward));
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
            // Log the activity
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
        verify_csrf($_POST['csrf_token'] ?? '');
        
        $id = $_POST['id'] ?? 0;
        $patient = Patient::find($id);
        $ward = $patient ? $patient->ward : 'hope';
        
        // Log the activity before deletion
        if ($patient) {
            ActivityLog::create([
                'action_type' => 'patient_deleted',
                'description' => 'Permanently deleted patient ' . $patient->initials . ' from ' . $patient->ward . ' ward',
                'patient_id' => $id,
                'ward' => $patient->ward
            ]);
        }
        
        Patient::delete($id);
        $_SESSION['success'] = 'Patient permanently deleted';
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
            // Get patient info for logging
            $patient = Patient::find($id);
            
            // Log the activity
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

    // ========== AJAX METHODS (WITH QUERY PARAMETERS) ==========

   /**
 * Get patient summary as JSON for AJAX calls
 */
public function getSummaryJson()
{
    // Turn off error reporting to prevent HTML output
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // Clear any previous output
    if (ob_get_level()) ob_clean();
    
    header('Content-Type: application/json');
    
    try {
        error_log("========== getSummaryJson CALLED ==========");
        
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
        
        $response = [
            'id' => $patient->id,
            'initials' => $patient->initials,
            'ward' => $patient->ward,
            'room_number' => $patient->room_number,
            'admission_date' => $patient->admission_date ? date('d/m/Y', strtotime($patient->admission_date)) : null,
            'discharge_date' => $patient->discharge_date ? date('d/m/Y', strtotime($patient->discharge_date)) : null,
            'core10_admission' => (bool)$patient->core10_admission,
            'core10_discharge' => (bool)$patient->core10_discharge,
            'is_discharged' => $patient->discharge_date ? true : false
        ];
        
        echo json_encode($response);
        
    } catch (\Exception $e) {
        error_log("EXCEPTION in getSummaryJson: " . $e->getMessage());
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
        // Turn off error reporting
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Clear any previous output
        if (ob_get_level()) ob_clean();
        
        header('Content-Type: application/json');
        
        try {
            error_log("========== getNotesJson CALLED ==========");
            
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
            
            // Extract ONLY admission notes (exclude discharge notes)
            $admissionNotes = $patient->notes ?? '';
            
            // Remove discharge notes section if present
            if (strpos($admissionNotes, '=== DISCHARGE NOTES') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, '=== DISCHARGE NOTES'));
            } elseif (strpos($admissionNotes, 'DISCHARGE NOTES') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, 'DISCHARGE NOTES'));
            } elseif (strpos($admissionNotes, 'Discharge Notes:') !== false) {
                $admissionNotes = substr($admissionNotes, 0, strpos($admissionNotes, 'Discharge Notes:'));
            }
            
            // Clean up any trailing whitespace or separators
            $admissionNotes = trim($admissionNotes);
            
            echo json_encode(['notes' => $admissionNotes]);
            
        } catch (\Exception $e) {
            error_log("EXCEPTION in getNotesJson: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }

    /**
     * Get patient discharge notes as JSON for AJAX calls
     */
    public function getDischargeNotesJson()
    {
        // Turn off error reporting
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // Clear any previous output
        if (ob_get_level()) ob_clean();
        
        header('Content-Type: application/json');
        
        try {
            error_log("========== getDischargeNotesJson CALLED ==========");
            
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
            
            // Extract discharge notes from the notes field
            $dischargeNotes = '';
            if ($patient->notes) {
                // Look for discharge notes section with the format from our discharge method
                if (preg_match('/={50,}\nDISCHARGE NOTES \[(.*?)\]\n={50,}\n(.*?)\n={50,}/s', $patient->notes, $matches)) {
                    // New format with === markers
                    $dischargeNotes = trim($matches[2]);
                } 
                // Try the older format
                elseif (strpos($patient->notes, 'Discharge Notes:') !== false) {
                    $parts = explode('Discharge Notes:', $patient->notes);
                    $dischargeNotes = trim(end($parts));
                }
                // If we have the header but no content captured, get everything after "DISCHARGE NOTES"
                elseif (strpos($patient->notes, 'DISCHARGE NOTES') !== false) {
                    $parts = explode('DISCHARGE NOTES', $patient->notes);
                    if (isset($parts[1])) {
                        // Remove the date part and get the content
                        $content = substr($parts[1], strpos($parts[1], ']\n') + 2);
                        // Remove the trailing ===
                        $content = preg_replace('/=+$/', '', $content);
                        $dischargeNotes = trim($content);
                    }
                }
            }
            
            echo json_encode(['notes' => $dischargeNotes]);
            
        } catch (\Exception $e) {
            error_log("EXCEPTION in getDischargeNotesJson: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
        exit;
    }
}