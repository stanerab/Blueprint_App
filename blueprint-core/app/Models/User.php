<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class User
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password_hash = null;
    public ?string $full_name = null;
    public ?string $role = null;
    public int $is_admin = 0;
    public int $is_active = 1;
    public ?string $created_at = null;
    public ?string $last_login = null;
    public ?string $reset_token = null;
    public ?string $reset_expires = null;

    public static function findByUsername($username)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$username, $username]);
            return $stmt->fetchObject(self::class);
        } catch (PDOException $e) {
            error_log("User::findByUsername error: " . $e->getMessage());
            return null;
        }
    }

    public static function findByEmail($email)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetchObject(self::class);
        } catch (PDOException $e) {
            error_log("User::findByEmail error: " . $e->getMessage());
            return null;
        }
    }

    public static function find($id)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetchObject(self::class);
        } catch (PDOException $e) {
            error_log("User::find error: " . $e->getMessage());
            return null;
        }
    }

    public static function create($data)
    {
        try {
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
                !empty($data['role']) ? trim($data['role']) : 'Psychologist'
            ]);
        } catch (PDOException $e) {
            error_log("User::create error: " . $e->getMessage());
            return false;
        }
    }

    public function save()
    {
        if (!$this->id) {
            return false;
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

    public function updateLastLogin()
    {
        if (!$this->id) return false;
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    public static function isAdmin($userId)
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user && $user->is_admin;
        } catch (PDOException $e) {
            return false;
        }
    }
}