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
    
    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }
    
    public static function findByUsername($username)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        return $stmt->fetchObject(self::class);
    }
    
    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password_hash, full_name) 
            VALUES (?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['full_name']
        ]);
    }
    
    public function updateLastLogin()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}