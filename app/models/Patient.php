<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Patient
{
    public static function getAll($archived = false)
    {
        $db = Database::getInstance();
        $archivedFlag = $archived ? 1 : 0;
        
        $stmt = $db->prepare("
            SELECT p.*, u.username as created_by_username 
            FROM patients p 
            LEFT JOIN users u ON p.created_by = u.id 
            WHERE p.is_archived = ?
            ORDER BY p.id DESC
        ");
        $stmt->execute([$archivedFlag]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO patients (ward, room_number, initials, admission_date, discharge_date, 
                                 core10_admission, core10_discharge, notes, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['ward'],
            $data['room_number'],
            strtoupper($data['initials']),
            $data['admission_date'] ?: null,
            $data['discharge_date'] ?: null,
            $data['core10_admission'] ?? 0,
            $data['core10_discharge'] ?? 0,
            $data['notes'],
            $_SESSION['user_id']
        ]);
    }
    
    public static function archive($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE patients SET is_archived = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM patients WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get active patients by ward (not discharged, not archived)
     * @param string $ward
     * @return array
     */
    public static function getActiveByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE ward = ? 
            AND discharge_date IS NULL 
            AND is_archived = 0 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get discharged patients by ward
     * @param string $ward
     * @return array
     */
    public static function getDischargedByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE ward = ? 
            AND discharge_date IS NOT NULL 
            AND is_archived = 0 
            ORDER BY discharge_date DESC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get patients by ward (backward compatibility)
     * @param string $ward
     * @param bool $discharged
     * @return array
     */
    public static function getByWard($ward, $discharged = false)
    {
        if ($discharged) {
            return self::getDischargedByWard($ward);
        }
        return self::getActiveByWard($ward);
    }
    
    /**
     * Get archived patients by ward
     * @param string $ward
     * @return array
     */
    public static function getArchivedByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE ward = ? 
            AND is_archived = 1 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get ward statistics
     * @param string $ward - Hope, Manor, or Lakeside
     * @return object with stats
     */
    public static function getWardStats($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT 
                COUNT(CASE WHEN discharge_date IS NULL THEN 1 END) as active_patients,
                COUNT(CASE WHEN discharge_date IS NOT NULL THEN 1 END) as discharged,
                COUNT(CASE WHEN core10_admission = 1 THEN 1 END) as core10_admission_completed,
                COUNT(CASE WHEN core10_discharge = 1 THEN 1 END) as core10_discharge_completed
            FROM patients 
            WHERE ward = ? AND is_archived = 0
        ");
        $stmt->execute([$ward]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Find patient by ID
     * @param int $id
     * @return object|null
     */
    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM patients WHERE id = ? AND is_archived = 0");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Update patient discharge
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function discharge($id, $data)
    {
        $db = Database::getInstance();
        
        // Debug log
        error_log("========== DISCHARGE METHOD CALLED ==========");
        error_log("Patient ID: " . $id);
        error_log("core10_discharge value: " . ($data['core10_discharge'] ?? 0));
        error_log("POST data: " . print_r($data, true));
        
        // Format discharge notes nicely
        $dischargeDate = date('Y-m-d H:i:s');
        $coreValue = isset($data['core10_discharge']) ? 1 : 0;
        $dischargeNote = "\n\n=== DISCHARGE NOTES [" . $dischargeDate . "] ===\n";
        $dischargeNote .= "CORE-10 completed at discharge: " . ($coreValue ? 'Yes' : 'No') . "\n";
        $dischargeNote .= "Notes: " . ($data['notes'] ?? 'No additional notes') . "\n";
        $dischargeNote .= "=====================================\n";
        
        $stmt = $db->prepare("
            UPDATE patients 
            SET discharge_date = CURDATE(), 
                core10_discharge = ?,
                notes = CONCAT(notes, ?)
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $coreValue,
            $dischargeNote,
            $id
        ]);
        
        if ($result) {
            error_log("Discharge successful for patient ID: " . $id . ", core10_discharge set to: " . $coreValue);
        } else {
            error_log("Discharge FAILED for patient ID: " . $id);
        }
        
        return $result;
    }
    
    /**
     * Get available rooms in a ward
     * @param string $ward
     * @return array of available room numbers
     */
    public static function getAvailableRooms($ward)
    {
        $db = Database::getInstance();
        
        // Get occupied rooms
        $stmt = $db->prepare("
            SELECT room_number FROM patients 
            WHERE ward = ? AND discharge_date IS NULL AND is_archived = 0
        ");
        $stmt->execute([$ward]);
        $occupied = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Define all rooms based on ward
        $bedCount = $ward === 'Hope' ? 12 : 10;
        $allRooms = range(1, $bedCount);
        
        // Return available rooms
        return array_values(array_diff($allRooms, $occupied));
    }
    
    /**
     * Update patient room number
     * @param int $id
     * @param int $newRoom
     * @param string $reason
     * @return bool
     */
    public static function updateRoom($id, $newRoom, $reason = '')
    {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            UPDATE patients 
            SET room_number = ?,
                notes = CONCAT(notes, '\n\n[', NOW(), '] Room changed to ', ?, ' - ', ?)
            WHERE id = ?
        ");
        
        return $stmt->execute([$newRoom, $newRoom, $reason, $id]);
    }
    
    /**
     * Alternative version with formatted date using PHP
     */
    public static function updateRoomWithPhpDate($id, $newRoom, $reason = '')
    {
        $db = Database::getInstance();
        
        $date = date('Y-m-d H:i:s');
        $reasonText = !empty($reason) ? " - $reason" : '';
        $note = "\n\n[$date] Room changed to $newRoom$reasonText";
        
        $stmt = $db->prepare("
            UPDATE patients 
            SET room_number = ?,
                notes = CONCAT(notes, ?)
            WHERE id = ?
        ");
        
        return $stmt->execute([$newRoom, $note, $id]);
    }
    
    /**
     * Get active patients by ward ordered by room number
     * @param string $ward
     * @return array
     */
    public static function getActiveByWardOrderedByRoom($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE ward = ? 
            AND discharge_date IS NULL 
            AND is_archived = 0 
            ORDER BY CAST(room_number AS UNSIGNED) ASC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Check if room is available in ward
     * @param string $ward
     * @param int $room_number
     * @return bool
     */
    public static function isRoomAvailable($ward, $room_number)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM patients 
            WHERE ward = ? AND room_number = ? 
            AND discharge_date IS NULL AND is_archived = 0
        ");
        $stmt->execute([$ward, $room_number]);
        return $stmt->fetchColumn() == 0;
    }
    
    /**
     * Restore an archived patient
     * @param int $id
     * @return bool
     */
    public static function restore($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE patients SET is_archived = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}