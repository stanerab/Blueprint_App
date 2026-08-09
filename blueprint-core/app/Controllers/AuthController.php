<?php
namespace App\Controllers;

    use App\Core\Auth;
    use App\Models\User;

    class AuthController
    {
        // ==================== SHOW LOGIN ====================
        public function showLogin()
        {
            if (Auth::check()) {
                redirect('dashboard');
            }
            view('auth.login');
        }

        // ==================== LOGIN ====================
        public function login()
        {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                view('auth.login', ['error' => 'Please enter your username and password']);
                return;
            }

            $result = Auth::attempt($username, $password);

            if ($result === true) {
                redirect('dashboard');
            } elseif ($result === 'disabled') {
                view('auth.login', ['error' => 'Your account has been deactivated. Please contact your administrator.']);
            } else {
                view('auth.login', ['error' => 'Invalid username or password']);
            }
        }

        // ==================== LOGOUT ====================
        public function logout()
        {
            Auth::logout();
            redirect('login?loggedout=1');
        }

        // ==================== SHOW REGISTER (INVITE FORM) ====================
        public function showRegister()
        {
            if (Auth::check()) {
                if (empty($_SESSION['is_admin'])) {
                    redirect('dashboard');
                    return;
                }
                view('auth.register');
                return;
            }
            redirect('login');
        }

        // ==================== REGISTER (SEND INVITE) ====================
        public function register()
        {
            Auth::requireLogin();
            if (empty($_SESSION['is_admin'])) {
                redirect('dashboard');
                return;
            }

            $errors = [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fullName = trim($_POST['full_name'] ?? '');
                $email    = trim($_POST['email']     ?? '');

                if (empty($fullName)) $errors[] = 'Full name is required';
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'A valid email address is required';
                }

                if (empty($errors)) {
                    $existing = User::findByEmail($email);
                    if ($existing) {
                        $errors[] = 'An account with this email already exists';
                    } else {
                        $db = \App\Config\Database::getInstance();

                        // Mark any existing unused invites for this email as used
                        $db->prepare("UPDATE user_invites SET used = 1 WHERE email = ? AND used = 0")->execute([$email]);

                        // Generate secure invite token
                        $token       = bin2hex(random_bytes(32));
                        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                        $expiresAt   = date('Y-m-d H:i:s', strtotime('+48 hours'));

                        $db->prepare("
                            INSERT INTO user_invites (email, full_name, token, expires_at, created_by)
                            VALUES (?, ?, ?, ?, ?)
                        ")->execute([$email, $fullName, $hashedToken, $expiresAt, $_SESSION['user_id']]);

                        $inviteLink = url('accept-invite?token=' . urlencode($token) . '&email=' . urlencode($email));

                  $htmlBody = "<!DOCTYPE html>
<html><head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f8fafc;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f8fafc;padding:2rem 1rem;'>
  <tr><td align='center'>
    <table width='560' cellpadding='0' cellspacing='0' style='background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>
      <tr>
        <td style='background:#1e3a8a;padding:1.5rem 2rem;text-align:center;'>
<img src='cid:blueprint-logo' alt='Blueprint' width='40' height='40' style='display:inline-block;vertical-align:middle;margin-right:0.5rem;border-radius:8px;'>          <span style='color:white;font-size:1.3rem;font-weight:700;vertical-align:middle;'>Blueprint</span>
          <p style='color:rgba(255,255,255,0.75);font-size:0.75rem;margin:0.25rem 0 0;letter-spacing:0.08em;text-transform:uppercase;'>Clinical Management System</p>
        </td>
      </tr>
      <tr>
        <td style='padding:2rem;color:#1e293b;font-size:0.9rem;line-height:1.7;'>
          <p style='margin:0 0 1rem;'>Hi <strong>{$fullName}</strong>,</p>
          <p style='margin:0 0 1rem;'>You have been invited to join <strong>Blueprint Clinical Management System</strong>. Click the button below to set up your account.</p>
          <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
              <td align='center' style='padding:1.5rem 0;'>
                <a href='{$inviteLink}' style='background:#1e3a8a;color:white;padding:0.75rem 2rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.9rem;display:inline-block;'>Accept Invitation</a>
              </td>
            </tr>
          </table>
          <p style='margin:0 0 0.5rem;color:#64748b;font-size:0.8rem;'>Or copy this link into your browser:</p>
          <p style='margin:0 0 1.5rem;word-break:break-all;'><a href='{$inviteLink}' style='color:#1e3a8a;font-size:0.8rem;'>{$inviteLink}</a></p>
          <p style='margin:0;color:#64748b;font-size:0.82rem;'>This invitation expires in <strong>48 hours</strong>. If you did not expect this invitation, you can safely ignore this email.</p>
        </td>
      </tr>
      <tr>
        <td style='background:#f8fafc;padding:1rem 2rem;text-align:center;border-top:1px solid #e2e8f0;'>
          <p style='margin:0;color:#94a3b8;font-size:0.72rem;'>Blueprint Clinical System &nbsp;&middot;&nbsp; blueprintcaretech.com</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body></html>";

                        $sent = \App\Config\Mail::send($email, $fullName, 'You\'ve been invited to Blueprint', $htmlBody);

                        if ($sent) {
                            \App\Models\ActivityLog::create([
                                'action_type' => 'user_invited',
                                'description' => ($_SESSION['full_name'] ?? 'Admin') . " sent an invitation to {$fullName} ({$email})",
                                'patient_id'  => null,
                                'ward'        => null
                            ]);
                            header('Location: ' . url('admin/users/list?created=1'));
                            exit;
                        } else {
                            $errors[] = 'Failed to send invitation email. Please try again.';
                        }
                    }
                }
            }

            view('auth.register', ['errors' => $errors]);
        }

        // ==================== SHOW ACCEPT INVITE ====================
        public function showAcceptInvite()
        {
            $token = $_GET['token'] ?? '';
            $email = urldecode($_GET['email'] ?? '');

            if (!$token || !$email) {
                view('auth.accept-invite', ['expired' => true]);
                return;
            }

            $db   = \App\Config\Database::getInstance();
            $stmt = $db->prepare("
                SELECT * FROM user_invites 
                WHERE email = ? AND used = 0 AND expires_at > NOW() 
                ORDER BY created_at DESC LIMIT 1
            ");
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

        // ==================== ACCEPT INVITE (POST) ====================
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

            $db   = \App\Config\Database::getInstance();
            $stmt = $db->prepare("
                SELECT * FROM user_invites 
                WHERE email = ? AND used = 0 AND expires_at > NOW() 
                ORDER BY created_at DESC LIMIT 1
            ");
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

            // Get new user ID
            $newUserId = $db->lastInsertId();

            // Mark all invites for this email as used
            $db->prepare("UPDATE user_invites SET used = 1 WHERE email = ?")->execute([$email]);

            \App\Models\ActivityLog::create([
                'action_type' => 'user_registered',
                'description' => "{$invite->full_name} accepted their invitation and created a Blueprint account (username: {$username})",
                'user_id'     => $newUserId ?: null,
                'user_name'   => $invite->full_name,
                'patient_id'  => null,
                'ward'        => null
            ]);

            // Notify all admins
            $admins = $db->query("SELECT email, full_name FROM users WHERE is_admin = 1 AND is_active = 1")->fetchAll(\PDO::FETCH_OBJ);
            foreach ($admins as $admin) {
                $adminUrl = url('admin/users/list');
              $htmlBody = "<!DOCTYPE html>
<html><head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f8fafc;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f8fafc;padding:2rem 1rem;'>
  <tr><td align='center'>
    <table width='560' cellpadding='0' cellspacing='0' style='background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>
      <tr>
        <td style='background:#1e3a8a;padding:1.5rem 2rem;text-align:center;'>
<img src='cid:blueprint-logo' alt='Blueprint' width='40' height='40' style='display:inline-block;vertical-align:middle;margin-right:0.5rem;border-radius:8px;'>          <span style='color:white;font-size:1.3rem;font-weight:700;vertical-align:middle;'>Blueprint</span>
          <p style='color:rgba(255,255,255,0.75);font-size:0.75rem;margin:0.25rem 0 0;letter-spacing:0.08em;text-transform:uppercase;'>Clinical Management System</p>
        </td>
      </tr>
      <tr>
        <td style='padding:2rem;color:#1e293b;font-size:0.9rem;line-height:1.7;'>
          <p style='margin:0 0 1rem;'>Hi <strong>{$admin->full_name}</strong>,</p>
          <p style='margin:0 0 1rem;'><strong>{$invite->full_name}</strong> has accepted their Blueprint invitation and created an account.</p>
          <table width='100%' cellpadding='0' cellspacing='0' style='background:#f8fafc;border-radius:8px;padding:1rem;margin:0 0 1.5rem;'>
            <tr><td style='padding:0.3rem 1rem;'><span style='color:#64748b;font-size:0.8rem;'>Username</span><br><strong>{$username}</strong></td></tr>
            <tr><td style='padding:0.3rem 1rem;'><span style='color:#64748b;font-size:0.8rem;'>Email</span><br><strong>{$email}</strong></td></tr>
            <tr><td style='padding:0.3rem 1rem;'><span style='color:#64748b;font-size:0.8rem;'>Role</span><br><strong>{$role}</strong></td></tr>
          </table>
          <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
              <td align='center' style='padding:0.5rem 0 1.5rem;'>
                <a href='{$adminUrl}' style='background:#1e3a8a;color:white;padding:0.75rem 2rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.9rem;display:inline-block;'>View User Management</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style='background:#f8fafc;padding:1rem 2rem;text-align:center;border-top:1px solid #e2e8f0;'>
          <p style='margin:0;color:#94a3b8;font-size:0.72rem;'>Blueprint Clinical System &nbsp;&middot;&nbsp; blueprintcaretech.com</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body></html>";
                \App\Config\Mail::send($admin->email, $admin->full_name, "{$invite->full_name} has joined Blueprint", $htmlBody);
            }

            $_SESSION['success'] = 'Your account has been created successfully. Please log in with your new password.';
            header('Location: ' . url('login?registered=1'));
            exit;
        }

        // ==================== FORGOT PASSWORD ====================
       public function showForgotPassword()
    {
        view('auth.forgot_password');
    }

        public function sendResetLink()
        {
            $email = trim($_POST['email'] ?? '');

            $user = User::findByEmail($email);
            if (!$user) {
                $_SESSION['error'] = 'No account found with that email address.';
                header('Location: ' . url('forgot-password'));
                exit;
            }

            $token       = bin2hex(random_bytes(32));
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);

            $user->reset_token   = $hashedToken;
            $user->reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $user->save();

            $resetLink = url("reset-password?token=" . urlencode($token) . "&email=" . urlencode($email));

            $htmlBody = "<!DOCTYPE html>
<html><head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f8fafc;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f8fafc;padding:2rem 1rem;'>
  <tr><td align='center'>
    <table width='560' cellpadding='0' cellspacing='0' style='background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>
      <!-- Header -->
      <tr>
        <td style='background:#1e3a8a;padding:1.5rem 2rem;text-align:center;'>
<img src='cid:blueprint-logo' alt='Blueprint' width='40' height='40' style='display:inline-block;vertical-align:middle;margin-right:0.5rem;border-radius:8px;'>          <span style='color:white;font-size:1.3rem;font-weight:700;vertical-align:middle;'>Blueprint</span>
          <p style='color:rgba(255,255,255,0.75);font-size:0.75rem;margin:0.25rem 0 0;letter-spacing:0.08em;text-transform:uppercase;'>Clinical Management System</p>
        </td>
      </tr>
      <!-- Body -->
      <tr>
        <td style='padding:2rem;color:#1e293b;font-size:0.9rem;line-height:1.7;'>
         <p style='margin:0 0 1rem;'>Hi <strong>{$user->full_name}</strong>,</p>
          <p style='margin:0 0 1rem;'>We received a request to reset your <strong>Blueprint</strong> password. Click the button below to set a new password.</p>
          <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
              <td align='center' style='padding:1.5rem 0;'>
                <a href='{$resetLink}' style='background:#1e3a8a;color:white;padding:0.75rem 2rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.9rem;display:inline-block;'>Reset My Password</a>
              </td>
            </tr>
          </table>
          <p style='margin:0 0 0.5rem;color:#64748b;font-size:0.8rem;'>Or copy this link into your browser:</p>
          <p style='margin:0 0 1.5rem;word-break:break-all;'><a href='{$resetLink}' style='color:#1e3a8a;font-size:0.8rem;'>{$resetLink}</a></p>
          <p style='margin:0;color:#64748b;font-size:0.82rem;'>This link expires in <strong>1 hour</strong>. If you did not request a password reset, you can safely ignore this email.</p>
        </td>
      </tr>
      <!-- Footer -->
      <tr>
        <td style='background:#f8fafc;padding:1rem 2rem;text-align:center;border-top:1px solid #e2e8f0;'>
          <p style='margin:0;color:#94a3b8;font-size:0.72rem;'>Blueprint Clinical System &nbsp;&middot;&nbsp; blueprintcaretech.com</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body></html>";

            $mailSent = \App\Config\Mail::send($email, $email, 'Reset Your Blueprint Password', $htmlBody);

            if ($mailSent) {
                $_SESSION['success'] = 'A password reset link has been sent to ' . htmlspecialchars($email) . '. Please check your inbox.';
            } else {
                $_SESSION['error'] = 'Failed to send email. Please check your email address or contact your administrator.';
            }

            header('Location: ' . url('forgot-password'));
            exit;
        }

        // ==================== RESET PASSWORD ====================
        public function showResetPassword()
        {
            $token = $_GET['token'] ?? '';
            $email = urldecode($_GET['email'] ?? '');

            if (!$token || !$email) {
                redirect('login');
                return;
            }

           view('auth.reset_password', ['token' => $token, 'email' => $email]);
        }

        public function updatePassword()
        {
            $email    = $_POST['email']            ?? '';
            $token    = $_POST['token']            ?? '';
            $password = $_POST['password']         ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

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
            $user->reset_token   = null;
            $user->reset_expires = null;
            $user->save();

            $_SESSION['success'] = 'Your password has been reset successfully. Please log in with your new password.';
            header('Location: ' . url('login?reset=1'));
            exit;
        }
    }