<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\User;

class AuthController
{
    public function showLogin()
    {
        if (Auth::check()) {
            header('Location: ' . url('dashboard'));
            exit;
        }
        view('auth.login');
    }
    
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (Auth::attempt($username, $password)) {
            header('Location: ' . url('dashboard'));
            exit;
        }
        
        view('auth.login', ['error' => 'Invalid username or password']);
    }
    
    public function logout()
    {
        Auth::logout();
        header('Location: ' . url('login?loggedout=1'));
        exit;
    }

    public function showRegister()
    {
        if (Auth::check()) {
            header('Location: ' . url('dashboard'));
            exit;
        }
        view('auth.register');
    }
    
    public function register()
    {
        $errors = [];
        
        if (empty($_POST['username'])) {
            $errors[] = 'Username is required';
        }
        
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        
        if ($_POST['password'] !== ($_POST['confirm_password'] ?? '')) {
            $errors[] = 'Passwords do not match';
        }
        
        if (empty($errors)) {
            if (User::create($_POST)) {
                header('Location: ' . url('login?registered=1'));
                exit;
            } else {
                $errors[] = 'Registration failed. Username or email may already exist.';
            }
        }
        
        view('auth.register', ['errors' => $errors]);
    }

    // ========== PASSWORD RESET METHODS ==========

    public function showForgotForm()
    {
        view('auth.forgot_password');
    }

    public function sendResetLink()
    {
        $email = $_POST['email'] ?? '';
        
        // Use findByEmail instead of where()
        $user = User::findByEmail($email);
        if (!$user) {
            $_SESSION['error'] = 'No account found with that email address.';
            header('Location: ' . url('forgot-password'));
            exit;
        }
        
        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        
        $user->reset_token = $hashedToken;
        $user->reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $user->save();
        
        $resetLink = url("reset-password?token=" . urlencode($token) . "&email=" . urlencode($email));
        
        $subject = "Reset Your Password";
        $message = "Click the link below to reset your password:\n\n" . $resetLink . "\n\nThis link expires in 1 hour.";
        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        
        // Using mail() – replace with PHPMailer if needed
        $mailSent = mail($email, $subject, $message, $headers);
        
        if ($mailSent) {
            $_SESSION['success'] = 'A password reset link has been sent to your email.';
        } else {
            $_SESSION['error'] = 'Failed to send email. Please try again later.';
        }
        
        header('Location: ' . url('forgot-password'));
        exit;
    }
    
    public function showResetForm()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';
        
        if (empty($token) || empty($email)) {
            $_SESSION['error'] = 'Invalid reset link.';
            header('Location: ' . url('login'));
            exit;
        }
        
        $user = User::findByEmail($email);
        if (!$user || !$user->reset_token || !$user->reset_expires || strtotime($user->reset_expires) < time()) {
            $_SESSION['error'] = 'Invalid or expired reset link.';
            header('Location: ' . url('login'));
            exit;
        }
        
        if (!password_verify($token, $user->reset_token)) {
            $_SESSION['error'] = 'Invalid reset link.';
            header('Location: ' . url('login'));
            exit;
        }
        
        view('auth.reset_password', ['email' => $email, 'token' => $token]);
    }
    
    public function updatePassword()
    {
        $email = $_POST['email'] ?? '';
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        if (empty($password) || $password !== $confirm) {
            $_SESSION['error'] = 'Passwords do not match.';
            header("Location: " . url("reset-password?token=$token&email=$email"));
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters.';
            header("Location: " . url("reset-password?token=$token&email=$email"));
            exit;
        }
        
        $user = User::findByEmail($email);
        if (!$user || !$user->reset_token || !$user->reset_expires || strtotime($user->reset_expires) < time() || !password_verify($token, $user->reset_token)) {
            $_SESSION['error'] = 'Invalid or expired reset link.';
            header('Location: ' . url('login'));
            exit;
        }
        
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->reset_token = null;
        $user->reset_expires = null;
        $user->save();
        
        $_SESSION['success'] = 'Your password has been reset. Please log in.';
        header('Location: ' . url('login'));
        exit;
    }
}