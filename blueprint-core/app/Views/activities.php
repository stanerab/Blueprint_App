<?php $title = $title ?? 'All Activities'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
/* ===== SHARED DASHBOARD STYLES ===== */
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
    /* Ward colours (clinical standards) */
    --ward-hope: #eab308;
    --ward-lakeside: #22c55e;
    --ward-manor: #3b82f6;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ===== MOBILE HEADER FIX – FULL WIDTH ===== */
@media (max-width: 768px) {
    body {
        padding: 15px 0 0 0 !important; /* remove side padding on body */
    }
    .container, .activities-full, .page-header {
        padding-left: 15px !important;
        padding-right: 15px !important;
        width: 100% !important;
        box-sizing: border-box;
    }
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }
    .page-header h1 {
        font-size: 24px;
        margin: 0;
    }
    .btn-outline {
        width: auto;
        justify-content: center;
        align-self: flex-start;
    }
}
@media (max-width: 480px) {
    .page-header {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
    .btn-outline {
        width: 100%;
        text-align: center;
    }
}
/* ===== PAGE HEADER (same as dashboard header actions) ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1e3a8a;
    margin: 0 0 5px 0;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-subtitle {
    font-size: 16px;
    color: var(--gray-500);
    margin: 0;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--gray-300);
    color: var(--gray-700);
    padding: 12px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 15px;
}

.btn-outline:hover {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.btn-outline svg {
    stroke: currentColor;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border: 1px solid var(--gray-200);
    color: var(--gray-600);
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.btn-back:hover {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-1px);
    text-decoration: none;
}

/* ===== ACTIVITIES CARD (same as dashboard sections) ===== */
.activities-full {
    background: white;
    border-radius: var(--radius);
    padding: 35px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
}

.activities-timeline {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* ===== TIMELINE DATE HEADER (no emoji) ===== */
.timeline-date {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-700);
    padding: 15px 0 8px 0;
    margin-top: 15px;
    border-bottom: 2px solid var(--gray-200);
}

.timeline-date:first-child {
    margin-top: 0;
}

/* ===== ACTIVITY ITEM (consistent with dashboard activity items) ===== */
.activity-full-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 18px 20px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-100);
    margin-left: 25px;
    position: relative;
}

.activity-full-item:hover {
    background: white;
    border-color: var(--gray-200);
    transform: translateX(5px);
    box-shadow: var(--shadow-sm);
}

.activity-full-item::before {
    content: '';
    position: absolute;
    left: -10px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 40px;
    background: var(--primary-light);
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.2s;
}

.activity-full-item:hover::before {
    opacity: 0.3;
}
/* Remove white space above nav on mobile */
@media (max-width: 768px) {
    body {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    .page-header {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    .activities-full {
        margin-top: 0 !important;
    }
}

.activity-time {
    min-width: 70px;
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-600);
    background: white;
    padding: 4px 10px;
    border-radius: 30px;
    text-align: center;
    border: 1px solid var(--gray-200);
}

.activity-icon-large {
    width: 44px;
    height: 44px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 500;
    color: var(--gray-600);
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
    border: 1px solid var(--gray-200);
    transition: all 0.2s;
}

.activity-full-item:hover .activity-icon-large {
    transform: scale(1.1);
    border-color: var(--primary);
}

.activity-details {
    flex: 1;
}

.activity-desc {
    font-size: 15px;
    color: var(--gray-800);
    margin-bottom: 6px;
    font-weight: 500;
    line-height: 1.5;
}

.activity-meta-full {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 13px;
    flex-wrap: wrap;
}

.activity-user {
    color: var(--gray-600);
    font-weight: 500;
}

/* ===== ACTIVITY WARD (USES GLOBAL WARD SYSTEM) ===== */
.activity-ward {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: white;
    letter-spacing: 0.3px;
}

/* Global ward colour classes - Clinical Standards */
/* Hope = Yellow (#eab308) */
.ward-hope {
    background: var(--ward-hope);
}

/* Lakeside = Green (#22c55e) */
.ward-lakeside {
    background: var(--ward-lakeside);
}

/* Manor = Blue (#3b82f6) */
.ward-manor {
    background: var(--ward-manor);
}

/* ===== EMPTY STATE (same as dashboard empty state) ===== */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--gray-50);
    border-radius: var(--radius);
    color: var(--gray-400);
    border: 2px dashed var(--gray-200);
}

.empty-icon {
    font-size: 48px;
    font-weight: 300;
    margin-bottom: 20px;
    opacity: 0.5;
    color: var(--gray-500);
}

.empty-state h3 {
    font-size: 22px;
    color: var(--gray-600);
    margin-bottom: 10px;
    font-weight: 600;
}

.empty-state p {
    font-size: 16px;
    color: var(--gray-500);
    margin-bottom: 25px;
}

.btn-primary {
    background: var(--primary);
    color: white;
    padding: 14px 32px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 15px;
    box-shadow: var(--shadow);
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

/* ===== RESPONSIVE (aligned with dashboard breakpoints) ===== */
@media (max-width: 768px) {
    .btn-outline {
        width: 100%;
        justify-content: center;
    }

    .btn-back {
        width: 100%;
        justify-content: center;
    }
    
    .activities-full {
    
    .activity-full-item {
        flex-direction: column;
        align-items: flex-start;
        margin-left: 0;
        padding: 18px;
    }
    
    .activity-full-item::before {
        left: 0;
        top: 0;
        width: 100%;
        height: 4px;
        transform: none;
    }
    
    .activity-time {
        align-self: flex-start;
    }
    
    .activity-meta-full {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    body { padding: 15px; }
    
    .page-header h1 {
        font-size: 24px;
    }
    
    .activities-full {
        padding: 15px;
    }
    
    .timeline-date {
        font-size: 14px;
    }
    
    .activity-desc {
        font-size: 14px;
    }
    
    .empty-state {
        padding: 60px 15px;
    }
    
    .empty-icon {
        font-size: 36px;
    }
    
    .empty-state h3 {
        font-size: 20px;
    }
}
</style>

<!-- PAGE HEADER -->
<div class="page-header">
    <div>
        <h1><i class="bi bi-activity"></i> <?= isset($ward) ? $ward . ' Ward Activities' : 'All Activities' ?></h1>
        <p class="page-subtitle">Complete activity log</p>
    </div>
    <a href="<?= url('dashboard') ?>" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- ACTIVITIES TIMELINE -->
<div class="activities-full">

    <?php if (!empty($_SESSION['is_admin'])): ?>
    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;flex-wrap:wrap;">
        <?php foreach(['all' => 'All Actions', 'clinical' => 'Clinical', 'admin' => 'Admin'] as $key => $label): ?>
            <button
                onclick="filterByCategory('<?= $key ?>')"
                id="cat-btn-<?= $key ?>"
                style="padding:0.35rem 1rem;border-radius:2rem;font-size:0.78rem;font-weight:600;cursor:pointer;border:1px solid #e2e8f0;background:white;color:#475569;transition:all 0.2s;">
                <?= $label ?>
            </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if(!empty($activities)): ?>
       <div class="activities-timeline">
            <?php 
            $currentDate = '';
            foreach($activities as $activity): 
                $createdAtLondon = new \DateTime($activity->created_at, new \DateTimeZone('UTC'));
                $createdAtLondon->setTimezone(new \DateTimeZone('Europe/London'));
                $activityDate = $createdAtLondon->format('Y-m-d');
                if($activityDate != $currentDate):
                    $currentDate = $activityDate;
            ?>
                <div class="timeline-date">
                    <?= $createdAtLondon->format('l, d F Y') ?>
                </div>
            <?php endif; ?>
                <div class="activity-full-item" data-action-type="<?= htmlspecialchars($activity->action_type ?? '') ?>">
                    <div class="activity-time"><?= $createdAtLondon->format('H:i') ?></div>
              <div class="activity-icon-large">
    <?php
    $icon = '•';
    $isBootstrap = false;
    switch($activity->action_type) {
        case 'patient_admitted':        $icon = '+';  break;
        case 'patient_discharged':      $icon = 'bi-box-arrow-right'; $isBootstrap = true; break;
        case 'patient_archived':        $icon = '↓';  break;
        case 'patient_restored':        $icon = '↺';  break;
        case 'patient_deleted':         $icon = '×';  break;
        case 'session_created':         $icon = '+';  break;
        case 'session_updated':         $icon = '✎';  break;
        case 'session_archived':        $icon = '↓';  break;
        case 'session_deleted':         $icon = '×';  break;
        case 'session_restored':        $icon = '↺';  break;
        case 'room_changed':            $icon = '⇄';  break;
        case 'ward_transfer':           $icon = 'bi-arrow-left-right'; $isBootstrap = true; break;
        case 'core10_updated':          $icon = 'bi-clipboard2-check'; $isBootstrap = true; break;        case 'group_session_created':   $icon = 'bi-people-fill';    $isBootstrap = true; break;
        case 'group_session_scheduled': $icon = 'bi-calendar-event'; $isBootstrap = true; break;
       case 'group_session_deleted':   $icon = '×'; break;
        case 'group_session_updated':   $icon = 'bi-pencil'; $isBootstrap = true; break;
        case 'report_generated':        $icon = 'bi-bar-chart-line'; $isBootstrap = true; break;
        case 'report_drilldown_viewed': $icon = 'bi-bar-chart-line'; $isBootstrap = true; break;
       case 'report_csv_exported':     $icon = 'bi-bar-chart-line'; $isBootstrap = true; break;
        case 'user_invited':            $icon = 'bi-envelope';        $isBootstrap = true; break;
        case 'user_registered':         $icon = 'bi-person-check';    $isBootstrap = true; break;
        case 'user_updated':            $icon = 'bi-person-gear';     $isBootstrap = true; break;
        case 'user_deleted':            $icon = 'bi-person-x';        $isBootstrap = true; break;
    }
    if ($isBootstrap) {
        echo '<i class="bi ' . $icon . '"></i>';
    } else {
        echo $icon;
    }
    ?>
</div>
                    <div class="activity-details">
                        <div class="activity-desc"><?= htmlspecialchars($activity->description) ?></div>
                        <div class="activity-meta-full">
                            <span class="activity-user">
                                <?= htmlspecialchars($activity->full_name ?? $activity->username ?? 'System') ?>
                            </span>
                            
                        <?php if(isset($activity->ward) && $activity->ward): ?>
    <?php
    $wards = array_map('trim', explode(',', $activity->ward));
    $wardColours = [
        'Hope'     => '#eab308',
        'Lakeside' => '#22c55e',
        'Manor'    => '#3b82f6',
    ];
    foreach ($wards as $w):
        $colour = $wardColours[$w] ?? '#8b5cf6';
    ?>
        <span class="activity-ward" style="background:<?= $colour ?>; color:white;">
            <?= htmlspecialchars($w) ?>
        </span>
    <?php endforeach; ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">·</div>
            <h3>No Activities Yet</h3>
            <p>Activities will appear here as you use the system</p>
            <a href="<?= url('dashboard') ?>" class="btn-primary">Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // ==================== CATEGORY FILTER ====================
    const adminActions = ['user_invited', 'user_registered', 'user_updated', 'user_deleted'];

    function filterByCategory(category) {
        ['all', 'clinical', 'admin'].forEach(k => {
            const btn = document.getElementById('cat-btn-' + k);
            if (btn) {
                btn.style.background  = k === category ? '#1e3a8a' : 'white';
                btn.style.color       = k === category ? 'white'   : '#475569';
                btn.style.borderColor = k === category ? '#1e3a8a' : '#e2e8f0';
            }
        });

        document.querySelectorAll('.activity-full-item').forEach(item => {
            const actionType = item.getAttribute('data-action-type') || '';
            const isAdmin    = adminActions.includes(actionType);
            if (category === 'all')      item.style.display = '';
            else if (category === 'admin')    item.style.display = isAdmin  ? '' : 'none';
            else                              item.style.display = isAdmin  ? 'none' : '';
        });

        // Hide empty date headers
        document.querySelectorAll('.timeline-date').forEach(header => {
            let next = header.nextElementSibling;
            let hasVisible = false;
            while (next && !next.classList.contains('timeline-date')) {
                if (next.classList.contains('activity-full-item') && next.style.display !== 'none') {
                    hasVisible = true;
                    break;
                }
                next = next.nextElementSibling;
            }
            header.style.display = hasVisible ? '' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        <?php if (!empty($_SESSION['is_admin'])): ?>
            filterByCategory('all');
        <?php else: ?>
            filterByCategory('clinical');
        <?php endif; ?>
    });
</script>