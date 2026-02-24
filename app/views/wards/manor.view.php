<?php $title = $ward . ' Ward - Blueprint'; ?>

<div class="ward-header">
    <div>
        <h1><?= htmlspecialchars($ward) ?> Ward</h1>
        <p class="ward-subtitle">Patient Management & Clinical Oversight</p>
    </div>
    <a href="<?= url('dashboard') ?>" class="btn-back">← Back to Dashboard</a>
</div>

<!-- Ward Stats -->
<div class="ward-stats-grid">
    <div class="stat-card">
        <span class="stat-label">Bed Occupancy</span>
        <span class="stat-value"><?= $stats['occupied_beds'] ?>/<?= $stats['total_beds'] ?></span>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= ($stats['occupied_beds']/$stats['total_beds'])*100 ?>%"></div>
        </div>
        <span class="stat-note"><?= $stats['available_beds'] ?> beds available</span>
    </div>
    
    <div class="stat-card">
        <span class="stat-label">CORE-10 Completed</span>
        <span class="stat-value"><?= $stats['core10_completed'] ?>/<?= $stats['occupied_beds'] ?></span>
        <span class="stat-note">Admission assessments</span>
    </div>
    
    <div class="stat-card">
        <span class="stat-label">Total Sessions</span>
        <span class="stat-value"><?= $stats['total_sessions'] ?></span>
        <span class="stat-note"><?= $stats['sessions_today'] ?> today</span>
    </div>
    
    <div class="stat-card">
        <span class="stat-label">Discharged</span>
        <span class="stat-value"><?= $stats['discharged_this_month'] ?></span>
        <span class="stat-note">This month</span>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="<?= url('patients/admit?ward=Hope') ?>" class="action-btn">
        <span class="action-icon">➕</span>
        Admit Patient
    </a>
    <a href="<?= url('sessions/create?ward=Hope') ?>" class="action-btn">
        <span class="action-icon">📝</span>
        New Session
    </a>
    <a href="<?= url('reports/ward/Hope') ?>" class="action-btn">
        <span class="action-icon">📊</span>
        Generate Report
    </a>
</div>

<!-- Active Patients -->
<div class="patients-section">
    <h2>Active Patients (<?= count($patients) ?>)</h2>
    
    <div class="patient-grid">
        <?php foreach($patients as $patient): ?>
        <div class="patient-card">
            <div class="patient-header">
                <div class="patient-avatar"><?= htmlspecialchars($patient->initials) ?></div>
                <span class="room-badge">Room <?= $patient->room_number ?></span>
            </div>
            
            <div class="patient-info">
                <div class="info-row">
                    <span class="info-label">Admitted:</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($patient->admission_date)) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">CORE-10:</span>
                    <span class="info-value">
                        <?= $patient->core10_admission ? '✅ Done' : '⏳ Pending' ?>
                    </span>
                </div>
            </div>
            
            <div class="patient-actions">
                <a href="<?= url('sessions/create/' . $patient->id) ?>" class="btn-small btn-session">New Session</a>
                <a href="<?= url('patients/view/' . $patient->id) ?>" class="btn-small btn-view">View</a>
                <button onclick="showDischargeModal(<?= $patient->id ?>)" class="btn-small btn-discharge">Discharge</button>
            </div>
            
            <!-- Recent Sessions -->
            <div class="recent-sessions">
                <h4>Recent Sessions</h4>
                <?php 
                $patientSessions = array_filter($sessions, fn($s) => $s->initials === $patient->initials);
                $recent = array_slice($patientSessions, 0, 2);
                ?>
                <?php if($recent): ?>
                    <?php foreach($recent as $s): ?>
                    <div class="session-item">
                        <span class="session-time"><?= date('d/m H:i', strtotime($s->datetime)) ?></span>
                        <span class="session-icons">
                            <?= $s->carenotes_completed ? '📋' : '' ?>
                            <?= $s->tracker_completed ? '📊' : '' ?>
                            <?= $s->tasks_completed ? '✅' : '' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-sessions">No sessions yet</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($patients)): ?>
        <div class="empty-state">
            <p>No active patients in <?= $ward ?> ward</p>
            <a href="<?= url('patients/admit?ward=manor') ?>" class="btn-primary">Admit First Patient</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Today's Sessions -->
<div class="sessions-section">
    <h2>Today's Sessions (<?= $stats['sessions_today'] ?>)</h2>
    
    <div class="sessions-list">
        <?php 
        $todaySessions = array_filter($sessions, function($s) {
            return strpos($s->datetime, date('Y-m-d')) === 0;
        });
        ?>
        
        <?php foreach($todaySessions as $session): ?>
        <div class="session-card">
            <div class="session-time-large"><?= date('H:i', strtotime($session->datetime)) ?></div>
            <div class="session-details">
                <div class="session-patient">Patient: <?= $session->initials ?></div>
                <div class="session-meta">
                    <span>Room <?= $session->room_number ?></span>
                    <span class="session-status">
                        <?= $session->carenotes_completed ? '📋' : '⬜' ?>
                        <?= $session->tracker_completed ? '📊' : '⬜' ?>
                        <?= $session->tasks_completed ? '✅' : '⬜' ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($todaySessions)): ?>
        <div class="empty-state">No sessions recorded today</div>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Discharges -->
<div class="discharges-section">
    <h2>Recent Discharges</h2>
    
    <div class="discharges-list">
        <?php foreach($discharged as $patient): ?>
        <div class="discharge-item">
            <span class="patient-init"><?= $patient->initials ?></span>
            <span class="discharge-date">Discharged: <?= date('d/m/Y', strtotime($patient->discharge_date)) ?></span>
            <span class="discharge-core">
                <?= $patient->core10_discharge ? '✅ CORE-10 Done' : '⏳ CORE-10 Pending' ?>
            </span>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($discharged)): ?>
        <div class="empty-state">No recent discharges</div>
        <?php endif; ?>
    </div>
</div>

<!-- Include the discharge modal from dashboard -->
<?php include APP_PATH . '/views/partials/discharge_modal.php'; ?>