<?php $title = $title ?? 'All Activities'; ?>

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

<style>
.activities-full {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--shadow);
}

.activities-timeline {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.timeline-date {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-600);
    padding: 10px 0 5px 0;
    margin-top: 10px;
    border-bottom: 1px solid var(--gray-200);
}

.timeline-date:first-child {
    margin-top: 0;
}

.activity-full-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 12px 15px;
    background: var(--gray-50);
    border-radius: 12px;
    margin-left: 20px;
}

.activity-full-item:hover {
    background: var(--gray-100);
}

.activity-time {
    min-width: 60px;
    font-size: 13px;
    font-weight: 600;
    color: var(--gray-600);
}

.activity-icon-large {
    width: 36px;
    height: 36px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: var(--shadow-sm);
    flex-shrink: 0;
}

.activity-details {
    flex: 1;
}

.activity-desc {
    font-size: 14px;
    color: var(--gray-800);
    margin-bottom: 5px;
}

.activity-meta-full {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
}

@media (max-width: 768px) {
    .activity-full-item {
        flex-direction: column;
        align-items: flex-start;
        margin-left: 0;
    }
    
    .activity-time {
        align-self: flex-start;
    }
}
</style>