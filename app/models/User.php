<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    public $id;
    public $username;
    public $email;
    public $full_name;
    public $role;
    public $created_at;
    public $last_login;
    
    /**
     * Find user by ID
     * @param int $id
     * @return object|null
     */
    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }
    
    /**
     * Find user by username or email
     * @param string $username
     * @return object|null
     */
    public static function findByUsername($username)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        return $stmt->fetchObject(self::class);
    }
    
    /**
     * Create a new user
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password_hash, full_name, role) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['full_name'],
            $data['role'] ?? 'user' // Default role is 'user'
        ]);
    }
    
    /**
     * Update user's last login timestamp
     * @return bool
     */
    public function updateLastLogin()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
    
    /**
     * Get all users (for admin purposes)
     * @return array
     */
    public static function getAll()
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id, username, email, full_name, role, created_at, last_login FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Check if user has admin role
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Get display name (full name or username)
     * @return string
     */
    public function getDisplayName()
    {
        return $this->full_name ?: $this->username;
    }
    
    /**
     * Update user profile
     * @param array $data
     * @return bool
     */
    public function update($data)
    {
        $db = Database::getInstance();
        
        $query = "UPDATE users SET ";
        $params = [];
        $updates = [];
        
        if (isset($data['full_name'])) {
            $updates[] = "full_name = ?";
            $params[] = $data['full_name'];
        }
        
        if (isset($data['email'])) {
            $updates[] = "email = ?";
            $params[] = $data['email'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $updates[] = "password_hash = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($updates)) {
            return true; // Nothing to update
        }
        
        $query .= implode(', ', $updates);
        $query .= " WHERE id = ?";
        $params[] = $this->id;
        
        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    }
    
    /**
     * Get count of patients created by this user
     * @return int
     */
    public function getPatientCount()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM patients 
            WHERE created_by = ? AND is_archived = 0
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get count of sessions created by this user
     * @return int
     */
    public function getSessionCount()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM sessions 
            WHERE created_by = ? AND is_archived = 0
        ");
        $stmt->execute([$this->id]);
        return $stmt->fetchColumn();
    }
}