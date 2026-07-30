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
        // If logged in as admin, show register form
        if (Auth::check()) {
            if (empty($_SESSION['is_admin'])) {
                header('Location: ' . url('dashboard'));
                exit;
            }
            view('auth.register');
            return;
        }
        // Not logged in — redirect to login
        header('Location: ' . url('login'));
        exit;
    }
    
public function register()
    {
        Auth::requireLogin();
        if (empty($_SESSION['is_admin'])) {
            redirect('dashboard');
            return;
        }

        $errors  = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email    = trim($_POST['email']     ?? '');

            if (empty($fullName)) $errors[] = 'Full name is required';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email address is required';
            }

            if (empty($errors)) {
                // Check email not already registered
                $existing = User::findByEmail($email);
                if ($existing) {
                    $errors[] = 'An account with this email already exists';
                } else {
                    // Generate secure invite token
                    $token       = bin2hex(random_bytes(32));
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    $expiresAt   = date('Y-m-d H:i:s', strtotime('+48 hours'));

                    $db = \App\Config\Database::getInstance();
                    $db->prepare("
                        INSERT INTO user_invites (email, full_name, token, expires_at, created_by)
                        VALUES (?, ?, ?, ?, ?)
                    ")->execute([$email, $fullName, $hashedToken, $expiresAt, $_SESSION['user_id']]);

                    $inviteLink = url('accept-invite?token=' . urlencode($token) . '&email=' . urlencode($email));

                    $htmlBody = "
                    <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:2rem;'>
                        <div style='background:#1e3a8a;padding:1.5rem;border-radius:0.75rem 0.75rem 0 0;text-align:center;'>
                            <h1 style='color:white;margin:0;font-size:1.4rem;'>Blueprint</h1>
                            <p style='color:rgba(255,255,255,0.8);margin:0.25rem 0 0;font-size:0.85rem;'>Clinical Management System</p>
                        </div>
                        <div style='background:white;border:1px solid #e2e8f0;border-top:none;padding:2rem;border-radius:0 0 0.75rem 0.75rem;'>
                            <h2 style='color:#1e293b;font-size:1.1rem;margin:0 0 1rem;'>You've been invited to Blueprint</h2>
                            <p style='color:#475569;font-size:0.9rem;line-height:1.6;'>Hi {$fullName},</p>
                            <p style='color:#475569;font-size:0.9rem;line-height:1.6;'>You have been invited to join <strong>Blueprint Clinical Management System</strong>. Click the button below to set up your account.</p>
                            <div style='text-align:center;margin:1.5rem 0;'>
                                <a href='{$inviteLink}' style='background:#1e3a8a;color:white;padding:0.75rem 2rem;border-radius:0.5rem;text-decoration:none;font-weight:600;font-size:0.9rem;display:inline-block;'>Accept Invitation</a>
                            </div>
                            <p style='color:#94a3b8;font-size:0.78rem;line-height:1.6;'>This invitation expires in <strong>48 hours</strong>. If you did not expect this invitation, you can safely ignore this email.</p>
                            <hr style='border:none;border-top:1px solid #f1f5f9;margin:1.5rem 0;'>
                            <p style='color:#94a3b8;font-size:0.72rem;text-align:center;margin:0;'>Blueprint Clinical System &nbsp;·&nbsp; blueprintcaretech.com</p>
                        </div>
                    </div>";

                    $sent = \App\Config\Mail::send($email, $fullName, 'You\'ve been invited to Blueprint', $htmlBody);

                    if ($sent) {
                        \App\Models\ActivityLog::create([
                            'action_type' => 'user_invited',
                            'description' => ($_SESSION['full_name'] ?? 'Admin') . " sent an invitation to {$fullName} ({$email})",
                            'patient_id'  => null,
                            'ward'        => null
                        ]);
                     header('Location: ' . url('admin/users/list?created=1'));
                        exit;;
                    } else {
                        $errors[] = 'Failed to send invitation email. Please try again.';
                    }
                }
            }
        }

        view('auth.register', ['errors' => $errors, 'success' => $success]);
    }

public function showAcceptInvite()
    {
        $token = $_GET['token'] ?? '';
        $email = urldecode($_GET['email'] ?? '');

        if (!$token || !$email) {
            view('auth.accept-invite', ['expired' => true]);
            return;
        }

        $db   = \App\Config\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM user_invites WHERE email = ? AND used = 0 AND expires_at > NOW() LIMIT 1");
        $stmt->execute([$email]);
        $invite = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$invite || !password_verify($token, $invite->token)) {
            view('auth.accept-invite', ['expired' => true]);
            return;
        }

        view('auth.accept-invite', [
            'token'    => $token,
            'email'    => $email,
            'fullName' => $invite->full_name,
            'expired'  => false,
            'error'    => null
        ]);
    }

    public function acceptInvite()
    {
        $token    = $_POST['token']            ?? '';
        $email    = $_POST['email']            ?? '';
        $username = trim($_POST['username']    ?? '');
        $role     = trim($_POST['role']        ?? '');
        $password = $_POST['password']         ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($username)) $errors[] = 'Username is required';
        if (empty($role))     $errors[] = 'Role is required';

        if (empty($password) || strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }

        if ($password !== $confirm) $errors[] = 'Passwords do not match';

        $db     = \App\Config\Database::getInstance();
        $stmt   = $db->prepare("SELECT * FROM user_invites WHERE email = ? AND used = 0 AND expires_at > NOW() LIMIT 1");
        $stmt->execute([$email]);
        $invite = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$invite || !password_verify($token, $invite->token)) {
            view('auth.accept-invite', ['expired' => true]);
            return;
        }

        if (!empty($errors)) {
            view('auth.accept-invite', [
                'token'    => $token,
                'email'    => $email,
                'fullName' => $invite->full_name,
                'expired'  => false,
                'error'    => implode(' ', $errors)
            ]);
            return;
        }

        // Check username not taken
        $checkStmt = $db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $checkStmt->execute([$username]);
        if ($checkStmt->fetch()) {
            view('auth.accept-invite', [
                'token'    => $token,
                'email'    => $email,
                'fullName' => $invite->full_name,
                'expired'  => false,
                'error'    => 'That username is already taken. Please choose another.'
            ]);
            return;
        }

        // Create the user
        $db->prepare("
            INSERT INTO users (username, email, password_hash, full_name, role, is_active)
            VALUES (?, ?, ?, ?, ?, 1)
        ")->execute([
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $invite->full_name,
            $role
        ]);

        // Mark invite as used
        $db->prepare("UPDATE user_invites SET used = 1 WHERE id = ?")->execute([$invite->id]);

      // Get the newly created user ID
        $db3 = \App\Config\Database::getInstance();
        $newUser = $db3->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $newUser->execute([$username]);
        $newUserId = $newUser->fetchColumn();

        \App\Models\ActivityLog::create([
            'action_type' => 'user_registered',
            'description' => "{$fullName} accepted their invitation and created a Blueprint account (username: {$username})",
            'user_id'     => $newUserId ?: null,
            'user_name'   => $fullName,
            'patient_id'  => null,
            'ward'        => null
        ]);

        // Notify admin by email
        $db2  = \App\Config\Database::getInstance();
        $admins = $db2->query("SELECT email, full_name FROM users WHERE is_admin = 1 AND is_active = 1")->fetchAll(\PDO::FETCH_OBJ);
        foreach ($admins as $admin) {
            $htmlBody = "
            <p>Hi {$admin->full_name},</p>
            <p><strong>{$fullName}</strong> has accepted their Blueprint invitation and created an account.</p>
            <p>Username: <strong>{$username}</strong><br>Email: <strong>{$email}</strong><br>Role: <strong>{$role}</strong></p>
            <p>You can view and manage this account in the <a href='" . url('admin/users/list') . "'>User Management</a> page.</p>
            <p>Blueprint Clinical System<br>blueprintcaretech.com</p>";

            \App\Config\Mail::send($admin->email, $admin->full_name, "{$fullName} has joined Blueprint", $htmlBody);
        }

      $_SESSION['success'] = 'Your account has been created successfully. Please log in.';
        header('Location: ' . url('login?registered=1'));
        exit;
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

        $htmlBody = "
        <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto;padding:2rem;'>
            <div style='background:#1e3a8a;padding:1.5rem;border-radius:0.75rem 0.75rem 0 0;text-align:center;'>
                <h1 style='color:white;margin:0;font-size:1.4rem;'>Blueprint</h1>
                <p style='color:rgba(255,255,255,0.8);margin:0.25rem 0 0;font-size:0.85rem;'>Clinical Management System</p>
            </div>
            <div style='background:white;border:1px solid #e2e8f0;border-top:none;padding:2rem;border-radius:0 0 0.75rem 0.75rem;'>
                <h2 style='color:#1e293b;font-size:1.1rem;margin:0 0 1rem;'>Password Reset Request</h2>
                <p style='color:#475569;font-size:0.9rem;line-height:1.6;'>We received a request to reset your Blueprint password. Click the button below to set a new password. This link expires in <strong>1 hour</strong>.</p>
                <div style='text-align:center;margin:1.5rem 0;'>
                    <a href='{$resetLink}' style='background:#1e3a8a;color:white;padding:0.75rem 2rem;border-radius:0.5rem;text-decoration:none;font-weight:600;font-size:0.9rem;display:inline-block;'>Reset My Password</a>
                </div>
                <p style='color:#94a3b8;font-size:0.78rem;line-height:1.6;'>If you did not request a password reset, you can safely ignore this email. Your password will not change.</p>
                <hr style='border:none;border-top:1px solid #f1f5f9;margin:1.5rem 0;'>
                <p style='color:#94a3b8;font-size:0.72rem;text-align:center;margin:0;'>Blueprint Clinical System &nbsp;·&nbsp; blueprintcaretech.com</p>
            </div>
        </div>";

       $mailSent = \App\Config\Mail::send($email, $email, 'Reset Your Blueprint Password', $htmlBody);

        if ($mailSent) {
            $_SESSION['success'] = 'A password reset link has been sent to ' . htmlspecialchars($email) . '. Please check your inbox.';
        } else {
            $_SESSION['error'] = 'Failed to send email. Please check your email address or contact your administrator.';
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
        
       if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters.';
            header("Location: " . url("reset-password?token=$token&email=$email"));
            exit;
        }

        if (!preg_match('/[0-9]/', $password)) {
            $_SESSION['error'] = 'Password must contain at least one number.';
            header("Location: " . url("reset-password?token=$token&email=$email"));
            exit;
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $_SESSION['error'] = 'Password must contain at least one special character.';
            header("Location: " . url("reset-password?token=$token&email=$email"));
            exit;
        }
        
        $user = User::findByEmail($email);
        if (!$user || !$user->reset_token || !$user->reset_expires || strtotime($user->reset_expires) < time() || !password_verify($token, $user->reset_token)) {
            $_SESSION['error'] = 'Invalid or expired reset link.';
            header('Location: ' . url('login'));
            exit;
        }
        
       $user->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->reset_token = null;
        $user->reset_expires = null;
        $user->save();

      $_SESSION['success'] = 'Your password has been reset successfully. Please log in with your new password.';
        header('Location: ' . url('login?reset=1'));
        exit;
    }
}
