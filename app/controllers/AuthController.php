<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\User;

class AuthController
{
    public function showLogin()
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        view('auth.login');
    }
    
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (Auth::attempt($username, $password)) {
            redirect('/dashboard');
        }
        
        view('auth.login', ['error' => 'Invalid username or password']);
    }
    
    // ✅ KEEP THIS ONE (using Auth::logout)
    public function logout()
    {
        Auth::logout();
        redirect('/login?loggedout=1');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        view('auth.register');
    }
    
    public function register()
    {
        $errors = [];
        
        // Validation
        if (empty($_POST['username'])) {
            $errors[] = 'Username is required';
        }
        
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $errors[] = 'Passwords do not match';
        }
        
        if (empty($errors)) {
            if (User::create($_POST)) {
                redirect('/login?registered=1');
            } else {
                $errors[] = 'Registration failed. Username or email may already exist.';
            }
        }
        
        view('auth.register', ['errors' => $errors]);
    }
}