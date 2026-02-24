<?php
require_once '../app/config/Database.php';
require_once '../app/models/Patient.php';

use App\Config\Database;
use App\Models\Patient;

echo "<h2>CORE-10 Database Debug</h2>";

$db = Database::getInstance();

// Check if core10_discharge column exists
$columns = $db->query("SHOW COLUMNS FROM patients LIKE 'core10_discharge'");
if ($columns->rowCount() > 0) {
    echo "<p style='color:green'>✅ core10_discharge column exists</p>";
} else {
    echo "<p style='color:red'>❌ core10_discharge column MISSING!</p>";
}

// Get all discharged patients
$stmt = $db->query("SELECT id, initials, discharge_date, core10_discharge FROM patients WHERE discharge_date IS NOT NULL ORDER BY discharge_date DESC LIMIT 10");
$discharged = $stmt->fetchAll(PDO::FETCH_OBJ);

echo "<h3>Discharged Patients:</h3>";
if (count($discharged) > 0) {
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Initials</th><th>Discharge Date</th><th>core10_discharge</th><th>Status</th></tr>";
    foreach ($discharged as $p) {
        $status = $p->core10_discharge ? '✅ Completed' : '❌ Pending';
        echo "<tr>";
        echo "<td>{$p->id}</td>";
        echo "<td>{$p->initials}</td>";
        echo "<td>{$p->discharge_date}</td>";
        echo "<td>{$p->core10_discharge}</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No discharged patients found</p>";
}

// Check the discharge method by looking at notes
echo "<h3>Sample Patient Notes (to verify discharge format):</h3>";
$stmt = $db->query("SELECT id, initials, notes FROM patients WHERE notes LIKE '%DISCHARGE%' LIMIT 3");
$samples = $stmt->fetchAll(PDO::FETCH_OBJ);

if (count($samples) > 0) {
    foreach ($samples as $s) {
        echo "<h4>Patient {$s->initials} (ID: {$s->id})</h4>";
        echo "<pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc; max-height:200px; overflow:auto;'>" . htmlspecialchars($s->notes) . "</pre>";
    }
} else {
    echo "<p>No discharge notes found</p>";
}