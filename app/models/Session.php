<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Session
{
    /**
     * Get all sessions
     */
    public static function getAll($archived = false)
    {
        $db = Database::getInstance();
        $archivedFlag = $archived ? 1 : 0;
        
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.is_archived = ?
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$archivedFlag]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Create a new session
     */
    public static function create($data)
    {
        $db = Database::getInstance();
        
        // Explicitly check for checkbox values
        $carenotes = isset($data['carenotes']) ? 1 : 0;
        $tracker = isset($data['tracker']) ? 1 : 0;
        $tasks = isset($data['tasks']) ? 1 : 0;
        
        // Debug log
        error_log("Session::create - CareNotes: $carenotes, Tracker: $tracker, Tasks: $tasks");
        
        $stmt = $db->prepare("
            INSERT INTO sessions (ward, room_number, initials, datetime, carenotes_completed, 
                                 tracker_completed, notes, tasks_completed, created_by, patient_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['ward'],
            $data['room_number'],
            strtoupper($data['initials']),
            $data['datetime'],
            $carenotes,
            $tracker,
            $data['notes'] ?? '',
            $tasks,
            $_SESSION['user_id'],
            $data['patient_id'] ?? null
        ]);
        
        if ($result) {
            error_log("Session created successfully with ID: " . $db->lastInsertId());
        } else {
            error_log("Failed to create session");
        }
        
        return $result;
    }
    
    /**
     * Archive a session
     */
    public static function archive($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE sessions SET is_archived = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Permanently delete a session
     */
    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM sessions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========== USER-SPECIFIC METHODS ==========
    
    /**
     * Get sessions created by a specific user
     * @param int $userId
     * @param bool $archived
     * @return array
     */
    public static function getByUser($userId, $archived = false)
    {
        $db = Database::getInstance();
        $archivedFlag = $archived ? 1 : 0;
        
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.created_by = ? AND s.is_archived = ?
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$userId, $archivedFlag]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get sessions by ward created by specific user
     * @param string $ward
     * @param int $userId
     * @return array
     */
    public static function getByWardAndUser($ward, $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? AND s.is_archived = 0 AND s.created_by = ?
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$ward, $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get today's sessions by ward created by specific user
     * @param string $ward
     * @param int $userId
     * @return array
     */
    public static function getTodaysByWardAndUser($ward, $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? 
            AND DATE(s.datetime) = CURDATE() 
            AND s.is_archived = 0
            AND s.created_by = ?
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$ward, $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get archived sessions by ward created by specific user
     * @param string $ward
     * @param int $userId
     * @return array
     */
    public static function getArchivedByWardAndUser($ward, $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? 
            AND s.is_archived = 1 
            AND s.created_by = ?
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$ward, $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get sessions by patient ID (for specific user)
     * @param int $patientId
     * @param int $userId
     * @param int|null $limit
     * @return array
     */
    public static function getByPatientAndUser($patientId, $userId, $limit = null)
    {
        $db = Database::getInstance();
        $query = "
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.patient_id = ? AND s.is_archived = 0 AND s.created_by = ?
            ORDER BY s.datetime DESC
        ";
        
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute([$patientId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // ========== EXISTING METHODS ==========
    
    /**
     * Get sessions by ward
     * @param string $ward Hope, Manor, or Lakeside
     * @param int|null $limit Optional limit
     * @return array
     */
    public static function getByWard($ward, $limit = null)
    {
        $db = Database::getInstance();
        $query = "
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? AND s.is_archived = 0 
            ORDER BY s.datetime DESC
        ";
        
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get today's sessions by ward
     * @param string $ward Hope, Manor, or Lakeside
     * @return array
     */
    public static function getTodaysByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? 
            AND DATE(s.datetime) = CURDATE() 
            AND s.is_archived = 0
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get sessions by patient ID
     * @param int $patientId
     * @param int|null $limit Optional limit
     * @return array
     */
    public static function getByPatient($patientId, $limit = null)
    {
        $db = Database::getInstance();
        $query = "
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.patient_id = ? AND s.is_archived = 0 
            ORDER BY s.datetime DESC
        ";
        
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get recent sessions across all wards
     * @param int $limit Number of sessions to return
     * @return array
     */
    public static function getRecent($limit = 10)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.is_archived = 0 
            ORDER BY s.datetime DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get archived sessions by ward
     * @param string $ward
     * @return array
     */
    public static function getArchivedByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT s.*, u.username as created_by_username 
            FROM sessions s 
            LEFT JOIN users u ON s.created_by = u.id 
            WHERE s.ward = ? 
            AND s.is_archived = 1 
            ORDER BY s.datetime DESC
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Count sessions by ward
     * @param string $ward
     * @return int
     */
    public static function countByWard($ward)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM sessions 
            WHERE ward = ? AND is_archived = 0
        ");
        $stmt->execute([$ward]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Update an existing session
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $db = Database::getInstance();
        
        // Explicitly check for checkbox values
        $carenotes = isset($data['carenotes']) ? 1 : 0;
        $tracker = isset($data['tracker']) ? 1 : 0;
        $tasks = isset($data['tasks']) ? 1 : 0;
        
        error_log("Updating session ID: $id");
        error_log("CareNotes: $carenotes, Tracker: $tracker, Tasks: $tasks");
        error_log("Datetime: " . ($data['datetime'] ?? 'not set'));
        
        $stmt = $db->prepare("
            UPDATE sessions 
            SET datetime = ?,
                carenotes_completed = ?,
                tracker_completed = ?,
                tasks_completed = ?,
                notes = ?
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $data['datetime'],
            $carenotes,
            $tracker,
            $tasks,
            $data['notes'] ?? '',
            $id
        ]);
        
        if ($result) {
            error_log("Session updated successfully");
        } else {
            error_log("Failed to update session");
        }
        
        return $result;
    }
    
    /**
     * Get session statistics
     * @param string|null $ward Optional ward filter
     * @return object
     */
    public static function getStats($ward = null)
    {
        $db = Database::getInstance();
        
        $query = "
            SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN carenotes_completed = 1 THEN 1 END) as carenotes_completed,
                COUNT(CASE WHEN tracker_completed = 1 THEN 1 END) as tracker_completed,
                COUNT(CASE WHEN tasks_completed = 1 THEN 1 END) as tasks_completed,
                COUNT(DISTINCT DATE(datetime)) as active_days
            FROM sessions 
            WHERE is_archived = 0
        ";
        
        $params = [];
        
        if ($ward) {
            $query .= " AND ward = ?";
            $params[] = $ward;
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /**
     * Get session statistics for a specific user
     * @param int $userId
     * @param string|null $ward Optional ward filter
     * @return object
     */
    public static function getStatsByUser($userId, $ward = null)
    {
        $db = Database::getInstance();
        
        $query = "
            SELECT 
                COUNT(*) as total_sessions,
                COUNT(CASE WHEN carenotes_completed = 1 THEN 1 END) as carenotes_completed,
                COUNT(CASE WHEN tracker_completed = 1 THEN 1 END) as tracker_completed,
                COUNT(CASE WHEN tasks_completed = 1 THEN 1 END) as tasks_completed,
                COUNT(DISTINCT DATE(datetime)) as active_days
            FROM sessions 
            WHERE is_archived = 0 AND created_by = ?
        ";
        
        $params = [$userId];
        
        if ($ward) {
            $query .= " AND ward = ?";
            $params[] = $ward;
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}