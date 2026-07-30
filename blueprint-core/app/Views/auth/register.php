<?php $title = 'Invite User'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .app-footer, .site-footer, footer:not(.login-footer) { display: none !important; }

    html, body { height: 100%; margin: 0; padding: 0; background: #f0f4f8; }

   .invite-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background: #f8fafc;
    }

    .invite-box {
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04), 0 20px 60px rgba(30,58,138,0.1);
        padding: 2.5rem;
        width: 100%;
        max-width: 440px;
        border: 1px solid rgba(30,58,138,0.08);
    }

    .invite-logo {
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .invite-logo-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #1e3a8a, #0369a1);
        border-radius: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: white;
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 12px rgba(30,58,138,0.3);
    }

    .invite-logo h1 {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e3a8a;
        margin: 0 0 0.2rem;
    }

    .invite-logo p {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .invite-box h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.25rem;
    }

    .invite-subtitle {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0 0 1.5rem;
    }

    .divider { height: 1px; background: #f1f5f9; margin: 1.25rem 0; }

    .form-group { margin-bottom: 1rem; }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .input-wrap { position: relative; }

    .input-wrap i {
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
        padding: 0.65rem 0.9rem 0.65rem 2.4rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.6rem;
        font-size: 0.88rem;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
        outline: none;
    }

    .input-wrap input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
        background: white;
    }

    .btn-invite {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, #1e3a8a, #1e40af);
        color: white;
        border: none;
        border-radius: 0.6rem;
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
        box-shadow: 0 2px 8px rgba(30,58,138,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .btn-invite:hover { opacity: 0.92; transform: translateY(-1px); }

    .alert {
        padding: 0.65rem 0.9rem;
        border-radius: 0.6rem;
        font-size: 0.82rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

    .back-link {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #1e3a8a;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        margin-top: 1.25rem;
        justify-content: center;
    }

    .back-link:hover { text-decoration: underline; }

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
</style>

<div class="invite-container">
    <div class="invite-box">
       <div class="invite-logo">
            <h1>Blueprint</h1>
            <p>Clinical Management System</p>
        </div>

        <h2>Invite New User</h2>

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
            The user will receive an email with a secure link to create their own username, password and role. The link expires in 48 hours.
        </div>

        <form method="POST" action="<?= url('register') ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <div class="input-wrap">
                    <i class="bi bi-person"></i>
                    <input type="text" id="full_name" name="full_name"
                        placeholder="Enter their full name"
                        value="<?= htmlspecialchars(old('full_name') ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope"></i>
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
            <i class="bi bi-arrow-left"></i> Back to User Management
        </a>
    </div>
</div>