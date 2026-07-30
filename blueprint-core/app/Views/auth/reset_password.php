<?php $title = 'Reset Password'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .app-footer,
    .site-footer,
    footer:not(.login-footer) {
        display: none !important;
    }

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background: #f8fafc;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
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

    .logo {
        text-align: center;
        margin-bottom: 1.75rem;
    }

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
        margin: 0 0 0.25rem;
        text-align: center;
    }

    .login-subtitle {
        text-align: center;
        font-size: 0.82rem;
        color: #64748b;
        margin: 0 0 1.5rem;
    }

    .login-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0 0 1.5rem;
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
.input-wrap i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .input-wrap .toggle-pwd {
        left: auto;
        right: 0.85rem;
        pointer-events: all;
        cursor: pointer;
        font-size: 0.9rem;
        z-index: 2;
    }

    .input-wrap .toggle-pwd:hover { color: #1e3a8a; }

  .input-wrap input {
        width: 100%;
        padding: 0.65rem 2.4rem 0.65rem 2.4rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.6rem;
        font-size: 0.88rem;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
        outline: none;
    }

    .input-wrap input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
        background: white;
    }

    .input-wrap input::placeholder { color: #cbd5e1; }

    .btn-submit {
        width: 100%;
        padding: 0.75rem;
        background: linear-gradient(135deg, #1e3a8a, #1e40af);
        color: white;
        border: none;
        border-radius: 0.6rem;
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(30,58,138,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .btn-submit:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(30,58,138,0.3);
    }

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

    .pwd-req {
        color: #dc2626;
        transition: color 0.2s;
        margin-bottom: 0.2rem;
        font-size: 0.78rem;
    }

    .pwd-req.met { color: #065f46; }

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

    .login-footer-note {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f1f5f9;
        font-size: 0.72rem;
        color: #94a3b8;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .login-footer-note span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    @media (max-width: 480px) {
        .login-box { padding: 2rem 1.25rem 1.5rem; border-radius: 1rem; }
    }
</style>

<div class="login-container">
    <div class="login-box">

        <div class="logo">
            <h1>Blueprint</h1>
            <p class="tagline">Clinical Management System</p>
        </div>

        <h2>Set New Password</h2>
        <p class="login-subtitle">Choose a strong password for your account.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="login-divider"></div>

        <form method="POST" action="<?= url('reset-password') ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

           <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" id="password" name="password"
                        placeholder="Min. 8 characters, one number, one special character">
                    <i class="bi bi-eye toggle-pwd" id="togglePassword"></i>
                </div>
                <div style="margin-top:0.5rem;">
                    <div id="req-length"  class="pwd-req">✗ At least 8 characters</div>
                    <div id="req-number"  class="pwd-req">✗ At least one number</div>
                    <div id="req-special" class="pwd-req">✗ At least one special character (!, @, #, $...)</div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" id="confirm_password" name="confirm_password"
                        placeholder="Confirm your password">
                    <i class="bi bi-eye toggle-pwd" id="toggleConfirm"></i>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Reset Password
                </button>
            </div>
        </form>

        <a href="<?= url('login') ?>" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Login
        </a>

        <div class="login-footer-note">
            <span><i class="bi bi-shield-lock"></i> Secure</span>
            <span><i class="bi bi-file-earmark-lock"></i> GDPR compliant</span>
            <span><i class="bi bi-eye-slash"></i> Data protected</span>
        </div>

    </div>
</div>

<script>
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const lenOk     = val.length >= 8;
    const numberOk  = /[0-9]/.test(val);
    const specialOk = /[^a-zA-Z0-9]/.test(val);

    const reqLength  = document.getElementById('req-length');
    const reqNumber  = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    reqLength.className  = 'pwd-req' + (lenOk     ? ' met' : '');
    reqNumber.className  = 'pwd-req' + (numberOk  ? ' met' : '');
    reqSpecial.className = 'pwd-req' + (specialOk ? ' met' : '');

    reqLength.textContent  = (lenOk     ? '✓' : '✗') + ' At least 8 characters';
    reqNumber.textContent  = (numberOk  ? '✓' : '✗') + ' At least one number';
    reqSpecial.textContent = (specialOk ? '✓' : '✗') + ' At least one special character (!, @, #, $...)';
});
</script>