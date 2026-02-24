<?php $title = $title ?? 'Archived Sessions'; ?>

<div class="page-header">
    <div>
        <h1><?= e($ward) ?> Ward - Archived Sessions</h1>
        <p class="text-muted">View and manage archived session records</p>
    </div>
    <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Ward
    </a>
</div>

<div class="records-container">
    <?php if(!empty($archivedSessions)): ?>
        <div class="sessions-table-wrapper">
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
                            <td><?= date('d/m/Y H:i', strtotime($s->datetime)) ?></td>
                            <td><?= e($s->room_number) ?></td>
                            <td><?= $s->carenotes_completed ? '✅' : '❌' ?></td>
                            <td><?= $s->tracker_completed ? '✅' : '❌' ?></td>
                            <td><?= $s->tasks_completed ? '✅' : '❌' ?></td>
                            <td class="actions">
                                <form method="POST" action="<?= url('sessions/restore') ?>" class="inline-form">
                                    <input type="hidden" name="id" value="<?= $s->id ?>">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="ward" value="<?= $ward ?>">
                                    <button type="submit" class="btn-restore-small" title="Restore">↩️</button>
                                </form>
                                <form method="POST" action="<?= url('sessions/delete') ?>" class="inline-form">
                                    <input type="hidden" name="id" value="<?= $s->id ?>">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <button type="submit" class="btn-delete-small" title="Delete" onclick="return confirm('⚠️ Permanently delete?')">🗑️</button>
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
            <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-primary">Back to Ward</a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Add styles similar to archived-patients but adapted for table */
.sessions-table-wrapper {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sessions-table {
    width: 100%;
    border-collapse: collapse;
}

.sessions-table th {
    text-align: left;
    padding: 12px;
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 13px;
}

.sessions-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
}

.sessions-table tr:hover {
    background: #f8fafc;
}

.btn-restore-small, .btn-delete-small {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.2s;
}

.btn-restore-small:hover {
    background: #27ae60;
    color: white;
    transform: scale(1.1);
}

.btn-delete-small:hover {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

.actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.inline-form {
    display: inline;
}
</style>