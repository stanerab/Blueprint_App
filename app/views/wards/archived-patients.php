<?php $title = $title ?? 'Archived Patients'; ?>

<!-- Debug info -->
<div style="background: #e3f2fd; padding: 15px; margin-bottom: 20px; border-left: 4px solid #2196f3; border-radius: 4px;">
    <h3 style="margin-top: 0;">🔍 Debug Info</h3>
    <p><strong>Ward:</strong> <?= htmlspecialchars($ward ?? 'not set') ?></p>
    <p><strong>Archived Patients Count:</strong> <?= count($archivedPatients ?? []) ?></p>
    <?php if(!empty($archivedPatients)): ?>
        <p><strong>First Patient:</strong> <?= htmlspecialchars($archivedPatients[0]->initials ?? 'N/A') ?></p>
    <?php endif; ?>
</div>

<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($ward ?? '') ?> Ward - Archived Patients</h1>
        <p class="text-muted">View and manage archived patient records</p>
    </div>
    <a href="<?= url('wards/' . strtolower($ward ?? 'hope')) ?>" class="btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Ward
    </a>
</div>

<div class="records-container">
    <?php if(!empty($archivedPatients)): ?>
        <div class="records-grid">
            <?php foreach($archivedPatients as $p): ?>
                <div class="record-card">
                    <div class="record-header">
                        <div class="record-avatar"><?= htmlspecialchars($p->initials ?? '') ?></div>
                        <div class="record-status archived">Archived</div>
                    </div>
                    
                    <div class="record-details">
                        <div class="detail-item">
                            <span class="detail-label">Room</span>
                            <span class="detail-value"><?= htmlspecialchars($p->room_number ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Admitted</span>
                            <span class="detail-value"><?= !empty($p->admission_date) ? date('d/m/Y', strtotime($p->admission_date)) : '' ?></span>
                        </div>
                        <?php if(!empty($p->discharge_date)): ?>
                        <div class="detail-item">
                            <span class="detail-label">Discharged</span>
                            <span class="detail-value"><?= date('d/m/Y', strtotime($p->discharge_date)) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="record-actions">
                        <form method="POST" action="<?= url('patients/restore') ?>" class="inline-form">
                            <input type="hidden" name="id" value="<?= $p->id ?? '' ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="ward" value="<?= htmlspecialchars($ward ?? 'hope') ?>">
                            <button type="submit" class="btn-restore" onclick="return confirm('Restore this patient?')">
                                ↩️ Restore
                            </button>
                        </form>
                        <form method="POST" action="<?= url('patients/delete') ?>" class="inline-form">
                            <input type="hidden" name="id" value="<?= $p->id ?? '' ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn-delete" onclick="return confirm('⚠️ Permanently delete this archived patient?')">
                                🗑️ Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>No Archived Patients</h3>
            <p>There are no archived patients in <?= htmlspecialchars($ward ?? '') ?> ward.</p>
            <a href="<?= url('wards/' . strtolower($ward ?? 'hope')) ?>" class="btn-primary">Back to Ward</a>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h1 {
    font-size: 28px;
    color: #1e293b;
    margin: 0 0 5px 0;
}

.text-muted {
    color: #64748b;
    margin: 0;
}

.records-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.record-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border: 1px solid #eef2f6;
    transition: all 0.2s ease;
}

.record-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.record-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}

.record-status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.record-status.archived {
    background: #f1f5f9;
    color: #475569;
}

.record-details {
    background: #f8fafc;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 15px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    color: #64748b;
}

.detail-value {
    color: #1e293b;
    font-weight: 500;
}

.record-actions {
    display: flex;
    gap: 10px;
}

.btn-restore, .btn-delete {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-restore {
    background: #27ae60;
    color: white;
}

.btn-restore:hover {
    background: #229954;
    transform: translateY(-2px);
}

.btn-delete {
    background: #e74c3c;
    color: white;
}

.btn-delete:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    color: #475569;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.btn-primary {
    display: inline-block;
    background: #1e3a8a;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    margin-top: 15px;
}

.inline-form {
    display: inline;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .records-grid {
        grid-template-columns: 1fr;
    }
    
    .record-actions {
        flex-direction: column;
    }
}
</style>