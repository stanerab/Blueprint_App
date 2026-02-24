<?php
require_once '../app/config/Database.php';
require_once '../app/models/User.php';

use App\Config\Database;
use App\Models\User;

echo "<h1>Blueprint Setup</h1>";

try {
    $db = Database::getInstance();
    echo "✅ Database connected<br>";
    
    // Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Users table not found. Running schema...<br>";
        
        // Read schema file
        $schema = file_get_contents('../database/schema.sql');
        
        // Split into individual queries
        $queries = array_filter(array_map('trim', explode(';', $schema)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                $db->exec($query);
            }
        }
        echo "✅ Schema executed<br>";
    } else {
        echo "✅ Users table exists<br>";
    }
    
    // Check for admin user
    $stmt = $db->query("SELECT * FROM users WHERE username = 'admin'");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        echo "❌ Admin user not found. Creating admin user...<br>";
        
        // Create admin user
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password_hash, full_name, role) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute(['admin', 'admin@blueprint.app', $hash, 'Administrator', 'admin']);
        
        if ($result) {
            echo "✅ Admin user created successfully!<br>";
            echo "Username: admin<br>";
            echo "Password: admin123<br>";
        } else {
            echo "❌ Failed to create admin user<br>";
        }
    } else {
        echo "✅ Admin user exists<br>";
        
        // Reset password just in case
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE username = 'admin'");
        $stmt->execute([$hash]);
        echo "✅ Admin password reset to 'admin123'<br>";
    }
    
    // Show all users
    echo "<h2>Current Users:</h2>";
    $users = $db->query("SELECT id, username, email, full_name, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['full_name'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No users found<br>";
    }
    
    echo "<h2>Setup Complete!</h2>";
    echo '<a href="' . url('login') . '">Go to Login Page</a>';
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Check your database configuration in app/config/Database.php";
}