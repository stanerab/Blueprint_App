<?php $title = 'Login'; ?>

<div class="login-container">
    <div class="login-box">
        <div class="logo">
            <h1>Blueprint</h1>
            <p class="tagline">Task Management System</p>
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
        
        <form method="POST" action="<?php echo url('login'); ?>" class="login-form">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter your username or email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-primary btn-block">Login</button>
            </div>
            
            <div class="form-footer">
                <a href="<?php echo url('register'); ?>">Create new account</a>
                <a href="<?php echo url('forgot-password'); ?>">Forgot password?</a>
            </div>
        </form>
        

         <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submitted');
            // e.preventDefault(); // Uncomment this to test if form is being caught
        });
        </script>
        
        <div class="demo-credentials">
            <p><strong>Demo Credentials:</strong></p>
            <p>Username: admin<br>Password: admin123</p>
        </div>
    </div>
</div>