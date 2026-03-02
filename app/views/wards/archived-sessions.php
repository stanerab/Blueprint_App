<?php $title = $title ?? 'Archived Sessions'; ?>

<style>
/* ===== PAGE LAYOUT ===== */

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #f0f2f5;
    color: #1e293b;
    padding: 30px;
    max-width: 1300px;
    margin: 0 auto;
}

/* ===== HEADER ===== */

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
    content: "🗂️";
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

/* ===== TABLE CONTAINER ===== */

.sessions-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    overflow-x: auto;
}

/* ===== TABLE ===== */

.sessions-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.sessions-table thead {
    background: #f8fafc;
}

.sessions-table th {
    text-align: left;
    padding: 14px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sessions-table td {
    padding: 16px 14px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
}

.sessions-table tbody tr:hover {
    background: #f8fafc;
}

/* ===== STATUS BADGES ===== */

.status-pill {
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.status-yes {
    background: #dcfce7;
    color: #166534;
}

.status-no {
    background: #fee2e2;
    color: #991b1b;
}

/* ===== ACTION BUTTONS ===== */

.actions {
    display: flex;
    gap: 10px;
}

.btn-restore,
.btn-delete {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    font-size: 16px;
    transition: all .25s ease;
}

.btn-restore {
    background: #10b981;
    color: white;
}

.btn-restore:hover {
    background: #059669;
    transform: scale(1.1);
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.inline-form {
    display: inline;
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
    body { padding: 20px; }
    .page-header { flex-direction: column; align-items: flex-start; }
}
</style>

<!-- HEADER -->
<div class="page-header">
    <div>
        <h1><?= e($ward) ?> Ward - Archived Sessions</h1>
        <p class="page-subtitle">Archived clinical session records</p>
    </div>

    <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-outline">
        ← Back to Ward
    </a>
</div>

<?php if(!empty($archivedSessions)): ?>

<div class="sessions-card">
    <table class="sessions-table">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Date & Time</th>
                <th>Room</th>
                <th>CareNotes</th>
                <th>Tracker</th>
                <th>Tasks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($archivedSessions as $s): ?>
                <tr>
                    <td><strong><?= e($s->initials) ?></strong></td>
                    <td><?= date('d M Y H:i', strtotime($s->datetime)) ?></td>
                    <td><?= e($s->room_number) ?></td>

                    <td>
                        <span class="status-pill <?= $s->carenotes_completed ? 'status-yes' : 'status-no' ?>">
                            <?= $s->carenotes_completed ? 'Completed' : 'Pending' ?>
                        </span>
                    </td>

                    <td>
                        <span class="status-pill <?= $s->tracker_completed ? 'status-yes' : 'status-no' ?>">
                            <?= $s->tracker_completed ? 'Completed' : 'Pending' ?>
                        </span>
                    </td>

                    <td>
                        <span class="status-pill <?= $s->tasks_completed ? 'status-yes' : 'status-no' ?>">
                            <?= $s->tasks_completed ? 'Completed' : 'Pending' ?>
                        </span>
                    </td>

                    <td class="actions">
                        <form method="POST" action="<?= url('sessions/restore') ?>" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s->id ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="ward" value="<?= $ward ?>">
                            <button type="submit" class="btn-restore" title="Restore">
                                ↩
                            </button>
                        </form>

                        <form method="POST" action="<?= url('sessions/delete') ?>" class="inline-form">
                            <input type="hidden" name="id" value="<?= $s->id ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn-delete" title="Delete"
                                onclick="return confirm('⚠️ Permanently delete this session?')">
                                🗑
                            </button>
                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php else: ?>

<div class="empty-state">
    <div class="empty-icon">📦</div>
    <h3>No Archived Sessions</h3>
    <p>There are no archived sessions in <?= e($ward) ?> ward.</p>
    <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-primary">
        Back to Ward
    </a>
</div>

<?php endif; ?>