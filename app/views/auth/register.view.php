<?php $title = 'Register'; ?>

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
        
        <form method="POST" action="<?php echo url('register'); ?>" class="login-form">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required 
                       placeholder="Enter your full name"
                       value="<?php echo htmlspecialchars(old('full_name')); ?>">
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Choose a username"
                       value="<?php echo htmlspecialchars(old('username')); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your email"
                       value="<?php echo htmlspecialchars(old('email')); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Choose a password (min. 6 characters)">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
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