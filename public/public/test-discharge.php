<?php
session_start();

// Set a test user ID
$_SESSION['user_id'] = 1;
$_SESSION['logged_in'] = true;

require_once '../app/config/Database.php';
require_once '../app/models/Patient.php';

use App\Models\Patient;

echo "<h2>Test Discharge</h2>";

// Get a patient to test with
$db = App\Config\Database::getInstance();
$stmt = $db->query("SELECT id, initials FROM patients WHERE discharge_date IS NULL LIMIT 1");
$patient = $stmt->fetch(PDO::FETCH_OBJ);

if (!$patient) {
    die("No active patient found to test with");
}

echo "<p>Testing with patient: {$patient->initials} (ID: {$patient->id})</p>";

// Test data
$testData = [
    'core10_discharge' => 1,
    'notes' => 'Test discharge notes'
];

// Call discharge method
$result = Patient::discharge($patient->id, $testData);

if ($result) {
    echo "<p style='color:green'>✅ Discharge successful!</p>";
    
    // Verify the update
    $updated = Patient::find($patient->id);
    echo "<p>core10_discharge is now: " . ($updated->core10_discharge ? '1 (Completed)' : '0 (Pending)') . "</p>";
    echo "<p>discharge_date is now: " . ($updated->discharge_date ?? 'Not set') . "</p>";
    
    // Show notes preview
    echo "<h3>Updated Notes:</h3>";
    echo "<pre>" . htmlspecialchars(substr($updated->notes, -500)) . "</pre>";
    
} else {
    echo "<p style='color:red'>❌ Discharge failed</p>";
}