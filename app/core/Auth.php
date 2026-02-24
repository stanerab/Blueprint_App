<?php
namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt($username, $password)
    {
        // Find user by username or email
        $user = User::findByUsername($username);
        
        if ($user) {
            // Verify password
            if (password_verify($password, $user->password_hash)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['full_name'] = $user->full_name;
                $_SESSION['role'] = $user->role;
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                // Update last login
                $user->updateLastLogin();
                
                return true;
            }
        }
        
        return false;
    }
    
    public static function check()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
            return false;
        }
        
        // Check session timeout (30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            self::logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    // ✅ ADD THIS METHOD
    public static function requireLogin()
    {
        if (!self::check()) {
            redirect('/login');
            exit();
        }
    }
    
    public static function user()
    {
        if (self::check()) {
            return User::find($_SESSION['user_id']);
        }
        return null;
    }
    
    public static function logout()
    {
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
    }
}