    <?php $title = 'Login'; ?>

    <style>
        /* Hide footer on login page only */
        .app-footer, 
        .site-footer,
        footer:not(.login-footer) {
            display: none !important;
        }
    </style>

    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <h1>Blueprint</h1>
                <p class="tagline">Clinical Management System</p>
            </div>
            
            <h2>Login to Your Account</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">Registration successful! Please login.</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['timeout'])): ?>
                <div class="alert alert-warning">Session expired. Please login again.</div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo url('login'); ?>" class="login-form" novalidate>
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" 
                        placeholder="Enter your username or email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                        placeholder="Enter your password">
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-primary btn-block">Login</button>
                </div>
                
                <div class="form-footer">
                   <?php if (!empty($_SESSION['is_admin'])): ?>
                <a href="<?php echo url('register'); ?>"><i class="bi bi-person-plus"></i> Create account</a>
                <?php endif; ?>
                <a href="<?php echo url('forgot-password'); ?>">Forgot password?</a> 
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
    </script>