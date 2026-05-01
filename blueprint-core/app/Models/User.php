<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $full_name = null;
    public ?string $role = null;
    public ?string $created_at = null;
    public ?string $last_login = null;
    public ?string $password_hash = null;
    public ?string $reset_token = null;
    public ?string $reset_expires = null;

    /**
     * Find user by ID
     * @param int $id
     * @return self|null
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
     * @return self|null
     */
    public static function findByUsername($username)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        return $stmt->fetchObject(self::class);
    }
    
    /**
     * Find user by email address
     * @param string $email
     * @return self|null
     */
    public static function findByEmail($email)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
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
            $data['full_name'] ?? null,
            $data['role'] ?? 'Psycologist'
        ]);
    }
    
    /**
     * Save current user instance to database
     * @return bool
     */
    public function save()
    {
        if (!$this->id) {
            return false; // Cannot save without ID, use create() for new users
        }
        
        $db = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE users 
            SET username = ?, email = ?, full_name = ?, role = ?, 
                password_hash = ?, reset_token = ?, reset_expires = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $this->username,
            $this->email,
            $this->full_name,
            $this->role,
            $this->password_hash,
            $this->reset_token,
            $this->reset_expires,
            $this->id
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
     * UPDATED: Shared patient count (ALL users)
     */
    public function getPatientCount()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM patients 
            WHERE is_archived = 0
        ");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     *  UPDATED: Shared session count (ALL users)
     */
    public function getSessionCount()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM sessions 
            WHERE is_archived = 0
        ");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}