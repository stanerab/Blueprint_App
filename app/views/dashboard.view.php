<?php $title = 'Clinical Dashboard'; ?>

<?php
$today = date('Y-m-d');

/* -------------------------
   PREPROCESS DATA (OPTIMIZED)
--------------------------*/

$patients = $patients ?? [];
$sessions = $sessions ?? [];

/* Active / discharged */
$activePatients = array_filter($patients, fn($p)=> !$p->discharge_date);
$dischargedPatients = array_filter($patients, fn($p)=> $p->discharge_date);

/* Ward patient grouping */
$wardPatients = [
    'Hope'=>[],
    'Manor'=>[],
    'Lakeside'=>[]
];

foreach($activePatients as $p){
    $wardPatients[$p->ward][]=$p;
}

/* Ward sessions today */
$wardSessions=['Hope'=>0,'Manor'=>0,'Lakeside'=>0];

foreach($sessions as $s){
    if(str_starts_with($s->datetime,$today)){
        $wardSessions[$s->ward]++;
    }
}

/* Index sessions by patient id */
$sessionIndex=[];
foreach($sessions as $s){
    $sessionIndex[$s->patient_id][]=$s;
}

/* Sort sessions newest first */
foreach($sessionIndex as &$list){
    usort($list, fn($a,$b)=>strtotime($b->datetime)-strtotime($a->datetime));
}
unset($list);

/* Stats */
$totalPatients = count($activePatients);
$totalDischarged = count($dischargedPatients);
$totalSessions = count($sessions);
$todaySessions = count(array_filter($sessions, fn($s)=> str_starts_with($s->datetime, $today)));

/* CORE-10 completed count */
$core10Completed = count(array_filter($activePatients, fn($p)=> $p->core10_admission));
?>

<style>
/* ===== MODERN DASHBOARD STYLES ===== */
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-light: #3b82f6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    --radius: 16px;
    --radius-sm: 8px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f0f2f5;
    color: var(--gray-800);
    line-height: 1.5;
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== HEADER ===== */
.dashboard-header {
    background: linear-gradient(135deg, #0b2b5c, #1e4b8a);
    border-radius: var(--radius);
    padding: 35px 40px;
    margin-bottom: 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 25px;
    color: white;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

.dashboard-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.header-left h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.welcome-text {
    font-size: 18px;
    opacity: 0.9;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.welcome-text::before {
    content: '👋';
    font-size: 20px;
}

.header-right {
    display: flex;
    gap: 15px;
}

.btn-primary {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px 28px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    font-size: 16px;
}

.btn-primary:hover {
    background: white;
    color: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: transparent;
}

.btn-outline {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 12px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: white;
    transform: translateY(-2px);
}

/* ===== STATS BAR ===== */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, var(--primary), var(--primary-light));
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 14px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}

.stat-value {
    font-size: 42px;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-trend {
    font-size: 13px;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 5px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--gray-50);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary);
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* ===== SECTION TITLE ===== */
.section-title {
    font-size: 22px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--gray-200), transparent);
}

/* ===== WARD OVERVIEW ===== */
.ward-overview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 45px;
}

.ward-card {
    background: white;
    border-radius: var(--radius);
    padding: 28px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
    position: relative;
    overflow: hidden;
}

.ward-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.ward-card.hope::before { background: linear-gradient(90deg, #059669, #10b981); }
.ward-card.manor::before { background: linear-gradient(90deg, #d97706, #f59e0b); }
.ward-card.lakeside::before { background: linear-gradient(90deg, #2563eb, #3b82f6); }

.ward-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.ward-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.ward-header h3 {
    font-size: 22px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.bed-count {
    background: var(--gray-100);
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
}

.ward-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.ward-stat {
    background: var(--gray-50);
    padding: 15px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-100);
    transition: background 0.2s;
}

.ward-card:hover .ward-stat {
    background: white;
    border-color: var(--gray-200);
}

.ward-stat .stat-label {
    font-size: 12px;
    color: var(--gray-500);
    display: block;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.ward-stat .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1;
}

.ward-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.ward-link:hover {
    gap: 12px;
    color: var(--primary-dark);
}

/* ===== ACTIVITIES SECTION ===== */
.activities-section {
    background: white;
    border-radius: var(--radius);
    padding: 28px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
    margin-top: 30px;
}

.activities-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.activities-header h2 {
    font-size: 22px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.activities-header h2::before {
    content: '⏱️';
    font-size: 24px;
}

.activities-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 18px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    border: 1px solid var(--gray-100);
}

.activity-item:hover {
    background: white;
    border-color: var(--gray-200);
    transform: translateX(4px);
    box-shadow: var(--shadow-sm);
}

.activity-icon {
    width: 44px;
    height: 44px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
    border: 1px solid var(--gray-200);
}

.activity-content {
    flex: 1;
}

.activity-description {
    font-size: 15px;
    color: var(--gray-800);
    margin-bottom: 6px;
    font-weight: 500;
}

.activity-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    font-size: 13px;
    flex-wrap: wrap;
}

.activity-user {
    color: var(--gray-600);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-user::before {
    content: '👤';
    font-size: 12px;
    opacity: 0.7;
}

.activity-time {
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-time::before {
    content: '🕒';
    font-size: 12px;
    opacity: 0.7;
}

.activity-ward {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
    letter-spacing: 0.3px;
}

.activity-ward.hope { background: #059669; }
.activity-ward.manor { background: #d97706; }
.activity-ward.lakeside { background: #2563eb; }

.activities-footer {
    margin-top: 20px;
    text-align: center;
    padding-top: 15px;
    border-top: 1px solid var(--gray-200);
}

.view-all-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.view-all-link:hover {
    gap: 10px;
    color: var(--primary-dark);
}

/* ===== MODAL ===== */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(5px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    padding: 35px;
    border-radius: 24px;
    max-width: 450px;
    width: 90%;
    box-shadow: var(--shadow-lg);
    animation: modalSlide 0.3s ease;
}

@keyframes modalSlide {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-content h3 {
    margin: 0 0 8px 0;
    font-size: 24px;
    color: var(--gray-800);
    font-weight: 600;
}

.modal-content p {
    margin: 0 0 25px 0;
    color: var(--gray-500);
    font-size: 15px;
}

.modal-content textarea {
    width: 100%;
    padding: 14px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    margin: 20px 0;
    font-family: inherit;
    resize: vertical;
    transition: border-color 0.2s;
    font-size: 14px;
}

.modal-content textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.modal-actions {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.modal-actions button {
    flex: 1;
    padding: 14px;
    border-radius: 40px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    font-size: 15px;
    transition: all 0.2s;
}

.btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.btn-secondary:hover {
    background: var(--gray-200);
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0;
    color: var(--gray-700);
    font-weight: 500;
    cursor: pointer;
    font-size: 15px;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--primary);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 70px 20px;
    background: var(--gray-50);
    border-radius: var(--radius);
    color: var(--gray-400);
    font-size: 16px;
    border: 2px dashed var(--gray-200);
}

.empty-icon {
    font-size: 56px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h4 {
    font-size: 18px;
    color: var(--gray-600);
    margin-bottom: 8px;
}

.empty-state small {
    display: block;
    margin-top: 10px;
    font-size: 14px;
    color: var(--gray-500);
}

/* ===== RESPONSIVE BREAKPOINTS ===== */
@media (max-width: 1200px) {
    body { padding: 20px; }
    .stats-bar { gap: 20px; }
    .stat-value { font-size: 36px; }
}

@media (max-width: 1024px) {
    .ward-overview {
        grid-template-columns: repeat(2, 1fr);
    }
    .stats-bar {
        grid-template-columns: repeat(2, 1fr);
    }
    .dashboard-header {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
        padding: 25px;
    }
    .header-right {
        width: 100%;
        flex-direction: column;
    }
    .btn-primary, .btn-outline {
        width: 100%;
        justify-content: center;
    }
    .stats-bar {
        grid-template-columns: 1fr;
    }
    .ward-overview {
        grid-template-columns: 1fr;
    }
    .activity-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .activity-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .modal-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    body { padding: 15px; }
    .dashboard-header h1 { font-size: 26px; }
    .welcome-text { font-size: 16px; }
    .stat-value { font-size: 32px; }
    .ward-header h3 { font-size: 20px; }
    .ward-stat .stat-value { font-size: 24px; }
    .modal-content { padding: 25px; }
}
</style>

<!-- HEADER -->
<div class="dashboard-header">
    <div class="header-left">
        <h1>Clinical Dashboard</h1>
        <p class="welcome-text">Dr. <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></p>
    </div>
    <div class="header-right">
        <a href="<?= url('wards/hope') ?>" class="btn-outline">
            <span>🏥</span> Hope Ward
        </a>
        <a href="<?= url('wards/manor') ?>" class="btn-outline">
            <span>🏥</span> Manor Ward
        </a>
        <a href="<?= url('wards/lakeside') ?>" class="btn-outline">
            <span>🏥</span> Lakeside Ward
        </a>
    </div>
</div>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Active Patients</span>
            <div class="stat-value"><?= $totalPatients ?></div>
            <span class="stat-trend">All wards combined</span>
        </div>
        <div class="stat-icon">👥</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Today's Sessions</span>
            <div class="stat-value"><?= $todaySessions ?></div>
            <span class="stat-trend"><?= $totalSessions ?> total sessions</span>
        </div>
        <div class="stat-icon">📊</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">Discharged</span>
            <div class="stat-value"><?= $totalDischarged ?></div>
            <span class="stat-trend">This month</span>
        </div>
        <div class="stat-icon">🚪</div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-label">CORE-10 Completed</span>
            <div class="stat-value"><?= $core10Completed ?></div>
            <span class="stat-trend">Admission assessments</span>
        </div>
        <div class="stat-icon">📋</div>
    </div>
</div>

<!-- WARD OVERVIEW -->
<div class="section-title">🏥 Ward Overview</div>
<div class="ward-overview">
    <?php
    $wardBeds = ['Hope'=>12, 'Manor'=>10, 'Lakeside'=>10];
    
    foreach($wardPatients as $ward=>$list):
        $coreCount = count(array_filter($list, fn($p)=>$p->core10_admission));
        $bedCount = count($list);
        $totalBeds = $wardBeds[$ward];
    ?>
        <div class="ward-card <?= strtolower($ward) ?>">
            <div class="ward-header">
                <h3><?= $ward ?> Ward</h3>
                <span class="bed-count"><?= $bedCount ?>/<?= $totalBeds ?> beds</span>
            </div>
            <div class="ward-stats">
                <div class="ward-stat">
                    <span class="stat-label">CORE-10 Completed</span>
                    <span class="stat-value"><?= $coreCount ?></span>
                </div>
                <div class="ward-stat">
                    <span class="stat-label">Today's Sessions</span>
                    <span class="stat-value"><?= $wardSessions[$ward] ?></span>
                </div>
            </div>
            <a href="<?= url('wards/'.strtolower($ward)) ?>" class="ward-link">
                View Ward Details →
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- RECENT ACTIVITIES -->
<div class="activities-section">
    <div class="activities-header">
        <h2>Recent Activities</h2>
        <a href="<?= url('activities') ?>" class="ward-link">View All Activities →</a>
    </div>

    <div class="activities-list">
        <?php if(isset($recentActivities) && !empty($recentActivities)): ?>
            <?php 
            $displayActivities = array_slice($recentActivities, 0, 3);
            foreach($displayActivities as $activity): 
            ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <?php
                        $icon = '📋';
                        switch($activity->action_type) {
                            case 'patient_admitted': $icon = '➕'; break;
                            case 'patient_discharged': $icon = '🚪'; break;
                            case 'patient_archived': $icon = '📦'; break;
                            case 'patient_restored': $icon = '↩️'; break;
                            case 'patient_deleted': $icon = '🗑️'; break;
                            case 'session_created': $icon = '📝'; break;
                            case 'session_updated': $icon = '✏️'; break;
                            case 'session_archived': $icon = '📦'; break;
                            case 'session_deleted': $icon = '🗑️'; break;
                            case 'session_restored': $icon = '↩️'; break;
                            case 'room_changed': $icon = '🔄'; break;
                        }
                        echo $icon;
                        ?>
                    </div>
                    <div class="activity-content">
                        <div class="activity-description">
                            <?= htmlspecialchars($activity->description) ?>
                        </div>
                        <div class="activity-meta">
                            <span class="activity-user"><?= htmlspecialchars($activity->user_name ?? 'System') ?></span>
                            <span class="activity-time"><?= date('d M H:i', strtotime($activity->created_at)) ?></span>
                            <?php if(isset($activity->ward) && $activity->ward): ?>
                                <span class="activity-ward <?= strtolower($activity->ward) ?>">
                                    <?= htmlspecialchars($activity->ward) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
          
            
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h4>No recent activities</h4>
                <p>Activities will appear here as you use the system</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- DISCHARGE MODAL -->
<div id="dischargeModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Patient Discharge</h3>
        <p>Please confirm discharge details for the selected patient</p>

        <form method="POST" action="<?= url('patients/discharge') ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="patient_id" id="dischargePatientId">

            <label class="checkbox-label">
                <input type="checkbox" name="core10_discharge" checked>
                <span>✅ CORE-10 completed at discharge</span>
            </label>

            <textarea name="notes" rows="4" placeholder="Enter discharge notes (summary, follow-up plans, etc.)..."></textarea>

            <div class="modal-actions">
                <button type="button" onclick="closeDischargeModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Confirm Discharge</button>
            </div>
        </form>
    </div>
</div>

<script>
// Discharge modal functions
function showDischargeModal(id) {
    document.getElementById('dischargePatientId').value = id;
    document.getElementById('dischargeModal').style.display = 'flex';
}

function closeDischargeModal() {
    document.getElementById('dischargeModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('dischargeModal')?.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeDischargeModal();
    }
});
</script>