<?php $title = $title ?? 'All Activities'; ?>

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

/* ===== PAGE HEADER (same as dashboard header actions) ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 5px 0;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header h1::before {
    content: '📋';
    font-size: 32px;
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

/* ===== TIMELINE DATE HEADER ===== */
.timeline-date {
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-700);
    padding: 15px 0 8px 0;
    margin-top: 15px;
    border-bottom: 2px solid var(--gray-200);
    display: flex;
    align-items: center;
    gap: 10px;
}

.timeline-date::before {
    content: '📅';
    font-size: 18px;
    opacity: 0.7;
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
    font-size: 22px;
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
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-user::before {
    content: '👤';
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
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
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
@media (max-width: 1200px) {
    body { padding: 20px; }
}

@media (max-width: 1024px) {
    .activities-full { padding: 30px; }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-outline {
        width: 100%;
        justify-content: center;
    }
    
    .activities-full {
        padding: 20px;
    }
    
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
    
    .page-header h1::before {
        font-size: 28px;
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
        font-size: 48px;
    }
    
    .empty-state h3 {
        font-size: 20px;
    }
}
</style>

<!-- PAGE HEADER -->
<div class="page-header">
    <div>
        <h1><?= isset($ward) ? $ward . ' Ward Activities' : 'All Activities' ?></h1>
        <p class="page-subtitle">Complete activity log</p>
    </div>
    <a href="<?= url('dashboard') ?>" class="btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Dashboard
    </a>
</div>

<!-- ACTIVITIES TIMELINE -->
<div class="activities-full">
    <?php if(!empty($activities)): ?>
        <div class="activities-timeline">
            <?php 
            $currentDate = '';
            foreach($activities as $activity): 
                $activityDate = date('Y-m-d', strtotime($activity->created_at));
                if($activityDate != $currentDate):
                    $currentDate = $activityDate;
            ?>
                <div class="timeline-date">
                    <?= date('l, d F Y', strtotime($activityDate)) ?>
                </div>
            <?php endif; ?>
                <div class="activity-full-item">
                    <div class="activity-time"><?= date('H:i', strtotime($activity->created_at)) ?></div>
                    <div class="activity-icon-large">
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
                    <div class="activity-details">
                        <div class="activity-desc"><?= htmlspecialchars($activity->description) ?></div>
                        <div class="activity-meta-full">
                            <span class="activity-user"><?= htmlspecialchars($activity->user_name ?? 'System') ?></span>
                            <?php if(isset($activity->ward) && $activity->ward): ?>
                                <span class="activity-ward <?= strtolower($activity->ward) ?>">
                                    <?= htmlspecialchars($activity->ward) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3>No Activities Yet</h3>
            <p>Activities will appear here as you use the system</p>
            <a href="<?= url('dashboard') ?>" class="btn-primary">Back to Dashboard</a>
        </div>
    <?php endif; ?>
</div>