<?php $title = 'Forgot Password'; ?>
<style>
    /* Full height layout to push footer down */
    html, body {
    height: 100%;
    margin: 0;
}

.main-content {
    min-height: 100vh; 
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
}

.forgot-card {
    width: 100%;
    max-width: 420px;
}

/* Better mobile handling */
@media (max-width: 576px) {
    .content-wrapper {
        align-items: flex-start; 
        padding-top: 3rem;
    }

    .card-body {
        padding: 1.5rem !important;
    }

    h2 {
        font-size: 1.4rem;
    }
}
</style>

<div class="main-content">
    <div class="content-wrapper">
        <div class="forgot-card mx auto">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="color: var(--clinical-blue);">Reset Password</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= url('forgot-password') ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= url('login') ?>">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>