<?php $title = 'Reset Password'; ?>
<style>
    /* Ensure the page takes full height */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    /* Make the main container use flexbox to push footer down */
    .main-content {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* The content wrapper grows to fill space */
    .content-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    
    /* Responsive card adjustments */
    .reset-card {
        max-width: 450px;
        width: 100%;
        margin: 0 auto;
    }
    
    /* Mobile adjustments */
    @media (max-width: 576px) {
        .reset-card {
            margin: 1rem;
        }
        .content-wrapper {
            padding: 1rem;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        h2 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="main-content">
    <div class="content-wrapper">
        <div class="reset-card">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="color: var(--clinical-blue);">Set New Password</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= url('reset-password') ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= url('login') ?>">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>