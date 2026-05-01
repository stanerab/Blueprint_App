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
                INSERT INTO activity_logs 
                (user_id, user_name, action_type, description, patient_id, session_id, ward) 
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
            error_log("Failed to create activity log: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent activity logs (global)
     */
  public static function getRecent($limit = 50)
{
    try {
        $db = Database::getInstance();

        $stmt = $db->prepare("
            SELECT 
                al.*,
                u.full_name,
                u.username,
                u.role
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT ?
        ");

        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        error_log("Failed to get activity logs: " . $e->getMessage());
        return [];
    }
}

    /**
     * Get activity logs by ward
     */
    public static function getByWard($ward, $limit = 50)
{
    try {
        $db = Database::getInstance();

        $stmt = $db->prepare("
            SELECT 
                al.*,
                u.full_name,
                u.username,
                u.role
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            WHERE al.ward = ?
            ORDER BY al.created_at DESC
            LIMIT ?
        ");

        $stmt->bindValue(1, $ward, PDO::PARAM_STR);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        error_log("Failed to get activity logs by ward: " . $e->getMessage());
        return [];
    }
}
/**
 * Backwards compatibility method
 */
public static function getRecentByUser($userId, $limit = 50)
{
    return self::getByUser($userId, $limit);
}
    /**
     * Get activity logs by user
     */
    public static function getByUser($userId, $limit = 50)
    {
        try {
            $db = Database::getInstance();

            $stmt = $db->prepare("
                SELECT 
                    a.*,
                    p.initials AS patient_initials
                FROM activity_logs a
                LEFT JOIN patients p ON a.patient_id = p.id
                WHERE a.user_id = ?
                ORDER BY a.created_at DESC
                LIMIT ?
            ");

            $stmt->bindValue(1, $userId);
            $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            error_log("Failed to get activity logs by user: " . $e->getMessage());
            return [];
        }
    }
}