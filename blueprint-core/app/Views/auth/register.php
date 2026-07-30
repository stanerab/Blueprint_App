<?php $title = 'Invite User'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite User — Blueprint</title>
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

        .invite-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
        }

        .invite-box {
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

        .invite-box h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.25rem;
            text-align: center;
        }

        .invite-subtitle {
            font-size: 0.82rem;
            color: #64748b;
            margin: 0 0 1.5rem;
            text-align: center;
        }

        .divider { height: 1px; background: #f1f5f9; margin: 0 0 1.5rem; }

        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 0.6rem;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            color: #0369a1;
            margin-bottom: 1.25rem;
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
            line-height: 1.5;
        }

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
            padding: 0.7rem 0.9rem 0.7rem 2.4rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.6rem;
            font-size: 0.95rem;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
            width: 100%;
        }

        .input-wrap input:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
            background: white;
        }

        .input-wrap input::placeholder { color: #cbd5e1; }

        .btn-invite {
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
        }

        .btn-invite:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(30,58,138,0.3);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.6rem;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            line-height: 1.5;
        }

        .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            color: #1e3a8a;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            margin-top: 1.25rem;
        }

        .back-link:hover { text-decoration: underline; }

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
            .invite-box {
                padding: 2rem 1.25rem 1.5rem;
                border-radius: 1rem;
            }

            .invite-box h2 { font-size: 1rem; }
            .logo h1 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
<div class="invite-container">
    <div class="invite-box">

        <div class="logo">
            <h1>Blueprint</h1>
            <p class="tagline">Clinical Management System</p>
        </div>

        <h2>Invite New User</h2>
        <p class="invite-subtitle">A secure invitation will be sent to the user's email.</p>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <div><?php foreach($errors as $e): ?><?= htmlspecialchars($e) ?><br><?php endforeach; ?></div>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="info-box">
            <i class="bi bi-info-circle" style="flex-shrink:0;margin-top:0.1rem;"></i>
            The user will receive a secure link to create their own username, password and role. The link expires in 48 hours.
        </div>

        <form method="POST" action="<?= url('register') ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <div class="input-wrap">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" id="full_name" name="full_name"
                        placeholder="Enter their full name"
                        value="<?= htmlspecialchars(old('full_name') ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" id="email" name="email"
                        placeholder="Enter their email address"
                        value="<?= htmlspecialchars(old('email') ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-invite">
                    <i class="bi bi-send"></i> Send Invitation
                </button>
            </div>
        </form>

        <a href="<?= url('admin/users') ?>" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Admin
        </a>

    </div>
</div>
</body>
</html>