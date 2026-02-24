<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class ActivityLog
{
    /**
     * Create a new activity log entry
     */
    public static function create($data)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                INSERT INTO activity_logs (user_id, user_name, action_type, description, patient_id, session_id, ward) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $data['user_id'] ?? $_SESSION['user_id'],
                $data['user_name'] ?? ($_SESSION['full_name'] ?? $_SESSION['username']),
                $data['action_type'],
                $data['description'],
                $data['patient_id'] ?? null,
                $data['session_id'] ?? null,
                $data['ward'] ?? null
            ]);
        } catch (PDOException $e) {
            // Log error but don't break the application
            error_log("Failed to create activity log: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent activity logs
     */
    public static function getRecent($limit = 10)
    {
        try {
            $db = Database::getInstance();
            
            // First check if table exists
            $checkTable = $db->query("SHOW TABLES LIKE 'activity_logs'");
            if ($checkTable->rowCount() == 0) {
                return []; // Return empty array if table doesn't exist
            }
            
            $stmt = $db->prepare("
                SELECT * FROM activity_logs 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Failed to get activity logs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get activity logs by ward
     */
    public static function getByWard($ward, $limit = 10)
    {
        try {
            $db = Database::getInstance();
            
            // Check if table exists
            $checkTable = $db->query("SHOW TABLES LIKE 'activity_logs'");
            if ($checkTable->rowCount() == 0) {
                return [];
            }
            
            $stmt = $db->prepare("
                SELECT * FROM activity_logs 
                WHERE ward = ? 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$ward, $limit]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Failed to get activity logs by ward: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get activity logs by user
     */
    public static function getByUser($userId, $limit = 10)
    {
        try {
            $db = Database::getInstance();
            
            $stmt = $db->prepare("
                SELECT * FROM activity_logs 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Failed to get activity logs by user: " . $e->getMessage());
            return [];
        }
    }
}