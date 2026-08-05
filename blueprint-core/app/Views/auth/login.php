<?php $title = 'Login'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Blueprint</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 16px;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            background: #f8fafc;
        }

        .login-box {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04), 0 20px 60px rgba(30,58,138,0.1);
            padding: 2.5rem 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(30,58,138,0.08);
        }

        .logo { text-align: center; margin-bottom: 1.75rem; }

        .logo h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0 0 0.2rem;
            letter-spacing: -0.5px;
        }

        .logo .tagline {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 500;
        }

        .login-box h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 1.5rem;
            text-align: center;
        }

        .login-divider { height: 1px; background: #f1f5f9; margin: 0 0 1.5rem; }

        .form-group { margin-bottom: 1rem; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .input-wrap { position: relative; }

        .input-wrap i.input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 0.7rem 2.4rem 0.7rem 2.4rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.6rem;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
            font-family: inherit;
        }

        .input-wrap input:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
            background: white;
        }

        .input-wrap input::placeholder { color: #cbd5e1; }

        .toggle-pwd {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: all;
        }

        .toggle-pwd:hover { color: #1e3a8a; }

        .btn-primary.btn-block {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
            color: white;
            border: none;
            border-radius: 0.6rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(30,58,138,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-family: inherit;
        }

        .btn-primary.btn-block:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(30,58,138,0.3);
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            font-size: 0.82rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .form-footer a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover { text-decoration: underline; }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            line-height: 1.5;
        }

        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.72rem;
            color: #94a3b8;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .footer-note span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 2rem 1.25rem 1.5rem;
                border-radius: 1rem;
            }
            .login-box h2 { font-size: 1rem; }
            .logo h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-box">

       <div class="logo">
            <img src="https://www.blueprintcaretech.com/assets/images/favicon.png" alt="Blueprint" style="width:52px;height:52px;border-radius:12px;box-shadow:0 4px 12px rgba(30,58,138,0.3);margin-bottom:0.75rem;">
            <h1>Blueprint</h1>
            <p class="tagline">Clinical Management System</p>
        </div>

        <h2>Welcome back!</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> Account created successfully. Please log in.</div>
        <?php endif; ?>

        <?php if (isset($_GET['timeout'])): ?>
            <div class="alert alert-warning"><i class="bi bi-clock"></i> Session expired. Please login again.</div>
        <?php endif; ?>

        <?php if (isset($_GET['reset'])): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> Password reset successfully. Please log in with your new password.</div>
        <?php endif; ?>

        <div class="login-divider"></div>

        <form method="POST" action="<?php echo url('login'); ?>" class="login-form" novalidate>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <div class="input-wrap">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" id="username" name="username"
                        placeholder="Enter your username or email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="password" name="password"
                        placeholder="Enter your password">
                    <i class="bi bi-eye toggle-pwd" id="togglePassword"></i>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary btn-block">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </div>

            <div class="form-footer">
                <?php if (!empty($_SESSION['is_admin'])): ?>
                    <a href="<?php echo url('register'); ?>"><i class="bi bi-person-plus"></i> Invite User</a>
                <?php endif; ?>
                <a href="<?php echo url('forgot-password'); ?>"><i class="bi bi-key"></i> Forgot password?</a>
            </div>
        </form>

    </div>
</div>

<script>
document.querySelector('.login-form').addEventListener('submit', function(e) {
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    let valid = true;

    if (!username.value.trim()) {
        username.style.borderColor = '#dc2626';
        username.addEventListener('input', function clearError() {
            username.style.borderColor = '';
            username.removeEventListener('input', clearError);
        });
        valid = false;
    }

    if (!password.value.trim()) {
        password.style.borderColor = '#dc2626';
        password.addEventListener('input', function clearError() {
            password.style.borderColor = '';
            password.removeEventListener('input', clearError);
        });
        valid = false;
    }

    if (!valid) e.preventDefault();
});

document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    this.classList.toggle('bi-eye', !isPassword);
    this.classList.toggle('bi-eye-slash', isPassword);
});
</script>
</body>
</html>