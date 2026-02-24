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
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    --radius: 12px;
    --radius-sm: 8px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f1f5f9;
    color: var(--gray-800);
    line-height: 1.5;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* ===== HEADER ===== */
.dashboard-header {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    border-radius: 24px;
    padding: 30px 35px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    color: white;
    box-shadow: var(--shadow-lg);
}

.dashboard-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    letter-spacing: -0.5px;
}

.welcome-text {
    font-size: 16px;
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

.header-actions .btn-primary {
    background: white;
    color: var(--primary);
    padding: 14px 28px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 16px;
    box-shadow: var(--shadow);
}

.header-actions .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    background: var(--gray-50);
}

/* ===== STATS BAR ===== */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    border: 1px solid var(--gray-100);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.stat-card .label {
    font-size: 14px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
}

.stat-card .value {
    font-size: 36px;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-card .trend {
    font-size: 13px;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== WARD OVERVIEW ===== */
.section-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--gray-200), transparent);
}

.ward-overview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}

.ward-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
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
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.ward-card.hope::before { background: linear-gradient(90deg, #059669, #10b981); }
.ward-card.manor::before { background: linear-gradient(90deg, #d97706, #f59e0b); }
.ward-card.lakeside::before { background: linear-gradient(90deg, #2563eb, #3b82f6); }

.ward-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.ward-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.bed-count {
    background: var(--gray-100);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-600);
}

.ward-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.ward-stat .stat-label {
    font-size: 12px;
    color: var(--gray-500);
    display: block;
    margin-bottom: 5px;
}

.ward-stat .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-800);
}

.ward-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.3s;
}

.ward-link:hover {
    gap: 10px;
    color: var(--primary-dark);
}

/* ===== ACTIVITIES SECTION ===== */
.activities-section {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
    margin-top: 30px;
}

.activities-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.activities-header h2 {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.activities-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    background: var(--gray-50);
    border-radius: 16px;
    transition: all 0.3s;
    border-left: 3px solid transparent;
}

.activity-item:hover {
    background: var(--gray-100);
    transform: translateX(2px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: var(--shadow-sm);
}

.activity-content {
    flex: 1;
}

.activity-description {
    font-size: 14px;
    color: var(--gray-800);
    margin-bottom: 5px;
    font-weight: 500;
}

.activity-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 12px;
    flex-wrap: wrap;
}

.activity-user {
    color: var(--gray-600);
    font-weight: 500;
}

.activity-time {
    color: var(--gray-500);
}

.activity-ward {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
}

.activity-ward.hope { background: #059669; }
.activity-ward.manor { background: #d97706; }
.activity-ward.lakeside { background: #2563eb; }

/* ===== EMPTY STATES ===== */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    color: var(--gray-400);
    font-size: 16px;
    border: 2px dashed var(--gray-200);
}

.empty-state .btn-primary {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 28px;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 40px;
    font-weight: 500;
    transition: all 0.3s;
}

.empty-state .btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
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
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 32px;
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

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.modal-actions button {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .ward-overview {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stats-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
        padding: 25px;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn-primary {
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
        gap: 5px;
    }
    
    .modal-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .dashboard-header h1 {
        font-size: 24px;
    }
    
    .stat-card .value {
        font-size: 28px;
    }
}
</style>

<!-- HEADER -->
<div class="dashboard-header">
    <div>
        <h1>Clinical Dashboard</h1>
        <p class="welcome-text">Dr. <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></p>
    </div>
    <div class="header-actions">
        <a href="<?= url('patients/admit') ?>" class="btn-primary">
            <span>➕</span> Admit New Patient
        </a>
    </div>
</div>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stat-card">
        <span class="label">Active Patients</span>
        <div class="value"><?= $totalPatients ?></div>
        <span class="trend">Across all wards</span>
    </div>
    <div class="stat-card">
        <span class="label">Today's Sessions</span>
        <div class="value"><?= $todaySessions ?></div>
        <span class="trend"><?= $totalSessions ?> total</span>
    </div>
    <div class="stat-card">
        <span class="label">Discharged</span>
        <div class="value"><?= $totalDischarged ?></div>
        <span class="trend">This month</span>
    </div>
    <div class="stat-card">
        <span class="label">CORE-10 Completed</span>
        <div class="value"><?= $core10Completed ?></div>
        <span class="trend">Admission assessments</span>
    </div>
</div>

<!-- WARD OVERVIEW -->
<div class="section-title">Ward Overview</div>
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
                <span class="bed-count"><?= $bedCount ?>/<?= $totalBeds ?></span>
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
                View Ward →
            </a>
        </div>
    <?php endforeach; ?>
</div>
<!-- RECENT ACTIVITIES -->
<div class="activities-section">
    <div class="activities-header">
        <h2>Recent Activities</h2>
        <a href="<?= url('activities') ?>" class="ward-link">View All →</a>
    </div>

    <div class="activities-list">
        <?php if(isset($recentActivities) && !empty($recentActivities)): ?>
            <?php 
            // Show only the first 3 activities
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
                            default: $icon = '📋';
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
            
            <!-- Show a message if there are more activities -->
            <?php if(count($recentActivities) > 3): ?>
                <div class="activities-footer">
                    <a href="<?= url('activities') ?>" class="view-all-link">
                        View all <?= count($recentActivities) ?> activities →
                    </a>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>No recent activities</p>
                <small>Activities will appear here as you use the system</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- DISCHARGE MODAL -->
<div id="dischargeModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Patient Discharge</h3>
        <p>Please confirm discharge details</p>

        <form method="POST" action="<?= url('patients/discharge') ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="patient_id" id="dischargePatientId">

            <label class="checkbox-label">
                <input type="checkbox" name="core10_discharge" checked>
                <span>CORE-10 completed at discharge</span>
            </label>

            <textarea name="notes" rows="4" placeholder="Enter discharge notes..."></textarea>

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