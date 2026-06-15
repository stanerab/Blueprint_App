<?php $title = 'Register'; ?>
<style>
    .app-footer, 
    .site-footer,
    footer:not(.login-footer) {
        display: none !important;
    }

    .login-box {
        padding: 1.5rem;
    }

    .login-box .logo {
        margin-bottom: 0.5rem;
    }

    .login-box h2 {
        margin-bottom: 0.75rem;
        font-size: 1.2rem;
    }

    .login-box .form-group {
        margin-bottom: 0.6rem;
    }

    .login-box .form-footer {
        margin-top: 0.75rem;
    }
</style>

<div class="login-container">
    <div class="login-box">
        <div class="logo">
            <h1>Blueprint</h1>
            <p class="tagline">Create New Account</p>
        </div>
        
        <h2>Register</h2>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo url('register'); ?>" class="login-form" novalidate>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" 
                       placeholder="Enter your full name"
                       value="<?php echo htmlspecialchars(old('full_name')); ?>">
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       placeholder="Choose a username"
                       value="<?php echo htmlspecialchars(old('username')); ?>">
            </div>

             <div class="form-group">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" maxlength="50"
                       placeholder="e.g. Psychologist, Art Therapist, Assistant Psychologist"
                       value="<?php echo htmlspecialchars(old('role')); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       placeholder="Enter your email"
                       value="<?php echo htmlspecialchars(old('email')); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Choose a password (min. 6 characters)">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Confirm your password">
            </div>

            
            <div class="form-group">
                <button type="submit" class="btn-primary btn-block">Register</button>
            </div>
            
            <div class="form-footer">
                <a href="<?php echo url('login'); ?>">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('.login-form').addEventListener('submit', function(e) {
    const fields = [
        { el: document.getElementById('full_name'), name: 'full name' },
        { el: document.getElementById('username'), name: 'username' },
        { el: document.getElementById('email'), name: 'email' },
        { el: document.getElementById('password'), name: 'password' },
        { el: document.getElementById('confirm_password'), name: 'confirm password' },
        { el: document.getElementById('role'), name: 'role' }
    ];

    let valid = true;

    fields.forEach(field => {
        if (!field.el.value.trim()) {
            field.el.style.borderColor = '#dc2626';
            field.el.addEventListener('input', function clearError() {
                field.el.style.borderColor = '';
                field.el.removeEventListener('input', clearError);
            });
            valid = false;
        }
    });

    if (!valid) e.preventDefault();
});
</script> 