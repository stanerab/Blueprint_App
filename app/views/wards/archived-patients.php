<?php $title = $title ?? 'Archived Patients'; ?>

<style>
/* ===== PAGE LAYOUT (MATCHING ACTIVITIES STYLE) ===== */

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #f0f2f5;
    color: #1e293b;
    padding: 30px;
    max-width: 1300px;
    margin: 0 auto;
}

/* ===== PAGE HEADER ===== */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header h1::before {
    content: "📦";
    font-size: 30px;
}

.page-subtitle {
    font-size: 15px;
    color: #64748b;
    margin-top: 6px;
}

.btn-outline {
    border: 1px solid #cbd5e1;
    background: white;
    padding: 12px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #334155;
    transition: all .25s ease;
}

.btn-outline:hover {
    border-color: #2563eb;
    color: #2563eb;
    transform: translateY(-2px);
}

/* ===== GRID ===== */

.records-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
    gap: 25px;
}

/* ===== CARD ===== */

.record-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 20px rgba(0,0,0,0.04);
    transition: all .25s ease;
}

.record-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}

/* HEADER */

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.record-avatar {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}

.record-status {
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 30px;
    background: #f1f5f9;
    color: #475569;
}

/* DETAILS */

.record-details {
    background: #f8fafc;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    color: #64748b;
}

.detail-value {
    font-weight: 600;
}

/* ACTIONS */

.record-actions {
    display: flex;
    gap: 12px;
}

.btn-restore,
.btn-delete {
    flex: 1;
    padding: 12px;
    border-radius: 40px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all .25s ease;
    font-size: 14px;
}

.btn-restore {
    background: #10b981;
    color: white;
}

.btn-restore:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.inline-form {
    flex: 1;
}

/* ===== EMPTY STATE ===== */

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    border: 2px dashed #e2e8f0;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.btn-primary {
    display: inline-block;
    margin-top: 20px;
    padding: 14px 32px;
    border-radius: 40px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    font-weight: 600;
}

.btn-primary:hover {
    background: #1d4ed8;
}

/* ===== RESPONSIVE ===== */

@media (max-width: 768px) {
    body {
        padding: 20px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .record-actions {
        flex-direction: column;
    }
}
</style>

<!-- HEADER -->
<div class="page-header">
    <div>
        <h1><?= htmlspecialchars($ward ?? '') ?> Ward - Archived Patients</h1>
        <p class="page-subtitle">Archived clinical records</p>
    </div>
    <a href="<?= url('wards/' . strtolower($ward ?? 'hope')) ?>" class="btn-outline">
        ← Back to Ward
    </a>
</div>

<!-- GRID -->
<?php if(!empty($archivedPatients)): ?>

<div class="records-grid">
    <?php foreach($archivedPatients as $p): ?>
        <div class="record-card">

            <div class="record-header">
                <div class="record-avatar"><?= htmlspecialchars($p->initials ?? '') ?></div>
                <div class="record-status">Archived</div>
            </div>

            <div class="record-details">
                <div class="detail-item">
                    <span class="detail-label">Room</span>
                    <span class="detail-value"><?= htmlspecialchars($p->room_number ?? '') ?></span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Admitted</span>
                    <span class="detail-value">
                        <?= !empty($p->admission_date) ? date('d M Y', strtotime($p->admission_date)) : '' ?>
                    </span>
                </div>

                <?php if(!empty($p->discharge_date)): ?>
                <div class="detail-item">
                    <span class="detail-label">Discharged</span>
                    <span class="detail-value">
                        <?= date('d M Y', strtotime($p->discharge_date)) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <div class="record-actions">
                <form method="POST" action="<?= url('patients/restore') ?>" class="inline-form">
                    <input type="hidden" name="id" value="<?= $p->id ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="btn-restore">
                        ↩ Restore
                    </button>
                </form>

                <form method="POST" action="<?= url('patients/delete') ?>" class="inline-form">
                    <input type="hidden" name="id" value="<?= $p->id ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="btn-delete">
                        🗑 Delete
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
    <a href="<?= url('wards/' . strtolower($ward ?? 'hope')) ?>" class="btn-primary">
        Back to Ward
    </a>
</div>

<?php endif; ?>