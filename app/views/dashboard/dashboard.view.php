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

/* Recent discharges */
$recentDischarges = array_slice($dischargedPatients, 0, 5);

/* Stats */
$totalPatients = count($activePatients);
$totalDischarged = count($dischargedPatients);
$totalSessions = count($sessions);
$todaySessions = count(array_filter($sessions, fn($s)=> str_starts_with($s->datetime, $today)));
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

/* ===== PATIENTS SECTION ===== */
.patients-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.patients-header h2 {
    font-size: 22px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.patients-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-select, .search-input {
    padding: 12px 18px;
    border: 1px solid var(--gray-200);
    border-radius: 40px;
    font-size: 14px;
    background: white;
    transition: all 0.3s;
    min-width: 200px;
}

.filter-select:focus, .search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

/* ===== PATIENTS GRID ===== */
.patients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.patient-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    border: 1px solid var(--gray-100);
}

.patient-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.patient-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.patient-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
    color: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 20px;
    box-shadow: var(--shadow);
}

.ward-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    color: white;
}

.ward-badge.hope { background: #059669; }
.ward-badge.manor { background: #d97706; }
.ward-badge.lakeside { background: #2563eb; }

.patient-info {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
}

.info-row {
    display: flex;
    margin-bottom: 10px;
    font-size: 14px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    width: 85px;
    color: var(--gray-500);
    font-weight: 500;
}

.info-value {
    flex: 1;
    font-weight: 500;
    color: var(--gray-800);
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.badge.success {
    background: #d1fae5;
    color: #065f46;
}

.badge.warning {
    background: #fed7aa;
    color: #92400e;
}

.patient-actions {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.btn-icon {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.3s;
    background: var(--gray-100);
    color: var(--gray-700);
}

.btn-icon:hover {
    background: var(--gray-200);
    transform: translateY(-2px);
}

.btn-icon.primary {
    background: var(--primary);
    color: white;
}

.btn-icon.primary:hover {
    background: var(--primary-dark);
}

.btn-icon.danger {
    background: var(--danger);
    color: white;
}

.btn-icon.danger:hover {
    background: #dc2626;
}

/* ===== SESSION PREVIEW ===== */
.session-preview {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 16px;
}

.session-preview h4 {
    font-size: 12px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 12px 0;
}

.session-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px solid var(--gray-200);
}

.session-item:last-child {
    border-bottom: none;
}

.session-date {
    color: var(--gray-600);
}

.session-icons {
    display: flex;
    gap: 6px;
    color: var(--gray-500);
}

/* ===== DISCHARGES SECTION ===== */
.discharges-section {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
}

.discharges-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.discharges-header h2 {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.discharges-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.discharge-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 16px;
    transition: all 0.3s;
}

.discharge-item:hover {
    background: var(--gray-100);
}

.discharge-info {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.patient-initial {
    font-weight: 700;
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-700);
    box-shadow: var(--shadow-sm);
}

.discharge-meta {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.discharge-ward {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    color: white;
}

.discharge-ward.hope { background: #059669; }
.discharge-ward.manor { background: #d97706; }
.discharge-ward.lakeside { background: #2563eb; }

.discharge-date {
    color: var(--gray-500);
    font-size: 13px;
}

.discharge-status {
    font-size: 12px;
    font-weight: 600;
}

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
    
    .patients-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .patients-filters {
        flex-direction: column;
    }
    
    .filter-select, .search-input {
        width: 100%;
    }
    
    .patients-grid {
        grid-template-columns: 1fr;
    }
    
    .patient-actions {
        flex-direction: column;
    }
    
    .discharge-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .discharge-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
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
    
    .patient-card {
        padding: 20px;
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
        <span class="trend">This month: <?= $stats['discharged_this_month'] ?? 0 ?></span>
    </div>
    <div class="stat-card">
        <span class="label">CORE-10 Completed</span>
        <div class="value"><?= count(array_filter($activePatients, fn($p)=> $p->core10_admission)) ?></div>
        <span class="trend">Admission assessments</span>
    </div>
</div>

<!-- WARD OVERVIEW -->
<div class="section-title">Ward Overview</div>
<div class="ward-overview">
    <?php
    $wardBeds = ['Hope'=>12, 'Manor'=>10, 'Lakeside'=>10];
    $wardColors = ['Hope'=>'#059669', 'Manor'=>'#d97706', 'Lakeside'=>'#2563eb'];
    
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

<!-- ACTIVE PATIENTS -->
<div class="patients-header">
    <h2>Active Patients</h2>
    <div class="patients-filters">
        <select id="wardFilter" class="filter-select">
            <option value="">All Wards</option>
            <option value="Hope">Hope Ward</option>
            <option value="Manor">Manor Ward</option>
            <option value="Lakeside">Lakeside Ward</option>
        </select>
        <input type="text" id="patientSearch" placeholder="Search initials..." class="search-input">
    </div>
</div>

<div class="patients-grid">
    <?php if($activePatients): ?>
        <?php foreach($activePatients as $patient): 
            $recentSessions = array_slice($sessionIndex[$patient->id] ?? [], 0, 2);
        ?>
            <div class="patient-card" 
                 data-ward="<?= htmlspecialchars($patient->ward) ?>"
                 data-initials="<?= strtoupper(htmlspecialchars($patient->initials)) ?>">
                
                <div class="patient-header">
                    <div class="patient-avatar"><?= htmlspecialchars($patient->initials) ?></div>
                    <span class="ward-badge <?= strtolower($patient->ward) ?>">
                        <?= htmlspecialchars($patient->ward) ?>
                    </span>
                </div>

                <div class="patient-info">
                    <div class="info-row">
                        <span class="info-label">Room</span>
                        <span class="info-value"><?= htmlspecialchars($patient->room_number) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Admitted</span>
                        <span class="info-value"><?= date('d M Y', strtotime($patient->admission_date)) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">CORE-10</span>
                        <span class="info-value">
                            <?php if($patient->core10_admission): ?>
                                <span class="badge success">Completed</span>
                            <?php else: ?>
                                <span class="badge warning">Pending</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="patient-actions">
                    <a href="<?= url('sessions/create/'.$patient->id) ?>" class="btn-icon primary">📝 Session</a>
                    <a href="<?= url('patients/view/'.$patient->id) ?>" class="btn-icon">👁️ View</a>
                    <button onclick="showDischargeModal(<?= $patient->id ?>)" class="btn-icon danger">🏥 Discharge</button>
                </div>

                <div class="session-preview">
                    <h4>Recent Sessions</h4>
                    <?php if($recentSessions): ?>
                        <?php foreach($recentSessions as $s): ?>
                            <div class="session-item">
                                <span class="session-date"><?= date('d M H:i', strtotime($s->datetime)) ?></span>
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
    <?php else: ?>
        <div class="empty-state">
            <p>No active patients</p>
            <a href="<?= url('patients/admit') ?>" class="btn-primary">Admit First Patient</a>
        </div>
    <?php endif; ?>
</div>

<!-- RECENT DISCHARGES -->
<div class="discharges-section">
    <div class="discharges-header">
        <h2>Recent Discharges</h2>
        <a href="<?= url('patients/discharged') ?>" class="ward-link">View All →</a>
    </div>

    <div class="discharges-list">
        <?php if($recentDischarges): ?>
            <?php foreach($recentDischarges as $p): ?>
                <div class="discharge-item">
                    <div class="discharge-info">
                        <span class="patient-initial"><?= htmlspecialchars($p->initials) ?></span>
                        <div class="discharge-meta">
                            <span class="discharge-ward <?= strtolower($p->ward) ?>">
                                <?= htmlspecialchars($p->ward) ?>
                            </span>
                            <span class="discharge-date">
                                <?= date('d M Y', strtotime($p->discharge_date)) ?>
                            </span>
                        </div>
                    </div>
                    <div class="discharge-status">
                        <?= $p->core10_discharge 
                            ? '<span class="badge success">CORE-10 Complete</span>' 
                            : '<span class="badge warning">CORE-10 Pending</span>' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">No recent discharges</div>
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
// Filter functionality
const wardFilter = document.getElementById('wardFilter');
const searchInput = document.getElementById('patientSearch');

function filterPatients() {
    const ward = wardFilter?.value.toLowerCase() || '';
    const search = searchInput?.value.toUpperCase() || '';

    document.querySelectorAll('.patient-card').forEach(card => {
        const cardWard = card.dataset.ward.toLowerCase();
        const cardInitials = card.dataset.initials;
        
        const wardMatch = !ward || cardWard === ward;
        const searchMatch = !search || cardInitials.includes(search);
        
        card.style.display = wardMatch && searchMatch ? 'block' : 'none';
    });
}

wardFilter?.addEventListener('change', filterPatients);
searchInput?.addEventListener('input', filterPatients);

// Discharge modal
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