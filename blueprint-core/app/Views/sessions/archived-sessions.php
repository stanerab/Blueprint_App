<?php $title = 'Archived Sessions'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ===== MODERN CLINICAL STYLES (matches home page) ===== */
:root {
    --ward-hope: #eab308;
    --ward-lakeside: #22c55e;
    --ward-manor: #3b82f6;
}

.archived-page { padding: 1.5rem; }

.archived-header {
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}
.archived-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e3a8a;
    margin: 0 0 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.archived-header p {
    color: #64748b;
    font-size: 0.9rem;
    margin: 0;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.btn-back:hover {
    background: #f8fafc;
    border-color: #1e3a8a;
    color: #1e3a8a;
    transform: translateY(-1px);
    text-decoration: none;
}
.text-muted {
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 0.25rem;
}
.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    border: 1px solid #cbd5e1;
    color: #475569;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.btn-outline:hover {
    background: #f8fafc;
    border-color: #3b82f6;
    color: #2563eb;
    transform: translateY(-1px);
}
.top-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.search-box {
    position: relative;
    flex: 1;
    max-width: 280px;
}
.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}
#searchInput {
    width: 100%;
    padding: 0.6rem 0.6rem 0.6rem 2.2rem;
    border: 1px solid #e2e8f0;
    border-radius: 2rem;
    font-size: 0.85rem;
    background: white;
    transition: 0.2s;
}
#searchInput:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.tabs {
    display: flex;
    gap: 0.25rem;
    background: #f1f5f9;
    padding: 0.25rem;
    border-radius: 2rem;
}
.ward-tab {
    padding: 0.5rem 1.2rem;
    border: none;
    background: transparent;
    border-radius: 1.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
    color: #475569;
}
.ward-tab.active {
    background: white;
    color: #1e293b;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.ward-section {
    margin-bottom: 2rem;
}
.ward-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid;
    display: inline-block;
}
/* UPDATED WARD COLOURS - Hope = Yellow, Lakeside = Green, Manor = Blue */
.ward-section[data-ward="Hope"] .ward-title { color: var(--ward-hope); border-bottom-color: var(--ward-hope); }
.ward-section[data-ward="Lakeside"] .ward-title { color: var(--ward-lakeside); border-bottom-color: var(--ward-lakeside); }
.ward-section[data-ward="Manor"] .ward-title { color: var(--ward-manor); border-bottom-color: var(--ward-manor); }
.sessions-card {
    background: white;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
    overflow-x: auto;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.sessions-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.sessions-table th, .sessions-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}
.sessions-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
}
.sessions-table tbody tr:hover {
    background: #f8fafc;
}
.status-pill {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    border-radius: 2rem;
    font-size: 0.7rem;
    font-weight: 600;
}
.status-yes {
    background: #d1fae5;
    color: #065f46;
}
.status-no {
    background: #fee2e2;
    color: #991b1b;
}
.actions {
    display: flex;
    gap: 0.5rem;
    white-space: nowrap;
}
.btn-restore, .btn-delete {
    width: 2rem;
    height: 2rem;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-restore {
    background: #ecfdf5;
    color: #10b981;
}
.btn-restore:hover {
    background: #10b981;
    color: white;
    transform: translateY(-2px);
}
.btn-delete {
    background: #fef2f2;
    color: #ef4444;
}
.btn-delete:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}
.empty-state {
    text-align: center;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 1rem;
    color: #64748b;
    border: 1px dashed #cbd5e1;
}
@media (max-width: 768px) {
    .top-controls {
        flex-direction: column;
        align-items: stretch;
    }
    .search-box {
        max-width: none;
    }
    .tabs {
        justify-content: center;
    }
    .sessions-table {
        min-width: 700px;
    }
    .sessions-table th, .sessions-table td {
        padding: 0.75rem;
    }
}
</style>

<div class="archived-page">

<div class="archived-header">
    <div>
        <h1><i class="bi bi-archive"></i> Archived Sessions</h1>
        <p>All wards overview</p>
    </div>
    <a href="<?= url('dashboard') ?>" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- TOP CONTROLS (search + tabs) -->
<div class="top-controls">
    <div class="search-box">
        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.3-4.3"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Search by initials..." />
    </div>
    <div class="tabs">
        <button class="ward-tab active" data-ward="All">All</button>
        <button class="ward-tab" data-ward="Hope">Hope</button>
        <button class="ward-tab" data-ward="Lakeside">Lakeside</button>
        <button class="ward-tab" data-ward="Manor">Manor</button>
    </div>
</div>

<?php
$grouped = $grouped ?? [
    'Hope' => [],
    'Lakeside' => [],
    'Manor' => []
];
?>

<div class="sessions-container">
    <?php foreach ($grouped as $wardName => $sessionsList): ?>
        <div class="ward-section" data-ward="<?= $wardName ?>">
            <h2 class="ward-title"><?= $wardName ?> Ward</h2>

            <?php if (!empty($sessionsList)): ?>
                <div class="sessions-card">
                    <table class="sessions-table">
                        <thead>
                            <tr>
                            <th>Patient</th>
                            <th> Session Date & Time</th>
                            <th>Room</th>
                            <th>Status</th>
                            <th>CareNotes</th>
                            <th>Tracker</th>
                            <th>Tasks</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessionsList as $s): ?>
                                <tr class="session-row" data-session-id="<?= $s->id ?>" data-ward="<?= strtolower($s->ward) ?>"
                                    data-search="<?= strtolower($s->initials) ?>">
                                    <td><strong><?= e($s->initials) ?></strong></td>
<td><?= date('d M Y H:i', strtotime($s->datetime)) ?></td>
<td><?= e($s->room_number) ?></td>
<td><?php
    $status = strtolower(trim($s->status ?? 'offered'));
    $statusColours = [
        'offered'   => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
        'completed' => ['bg' => '#d1fae5', 'color' => '#065f46'],
        'declined'  => ['bg' => '#fed7aa', 'color' => '#92400e'],
        'dna'       => ['bg' => '#fee2e2', 'color' => '#991b1b'],
    ];
    $sc = $statusColours[$status] ?? $statusColours['offered'];
    $label = strtoupper($status);
?><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>;"><?= $label ?></span></td>
<td><span class="status-pill <?= $s->carenotes_completed ? 'status-yes' : 'status-no' ?>"><?= $s->carenotes_completed ? 'Completed' : 'Pending' ?></span></td>
                                    
                                    <td><span class="status-pill <?= $s->tracker_completed ? 'status-yes' : 'status-no' ?>"><?= $s->tracker_completed ? 'Completed' : 'Pending' ?></span></td>
                                    <td><span class="status-pill <?= $s->tasks_completed ? 'status-yes' : 'status-no' ?>"><?= $s->tasks_completed ? 'Completed' : 'Pending' ?></span></td>
                                    <td class="actions">
                                        <button onclick="restoreSession(<?= $s->id ?>, '<?= $s->ward ?>')" class="btn-restore" title="Restore session">↩</button>
                                        <button onclick="deleteSession(<?= $s->id ?>, '<?= $s->ward ?>')" class="btn-delete" title="Permanently delete session">🗑</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>No archived sessions in <?= $wardName ?> ward</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
</div>

<script>
// ========== TOAST NOTIFICATION ==========
function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${isError ? '#b91c1c' : '#1e3a8a'};
        color: white;
        padding: 0.7rem 1.2rem;
        border-radius: 0.5rem;
        z-index: 1100;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-family: inherit;
        font-size: 0.9rem;
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ========== RESTORE SESSION (AJAX) ==========
function restoreSession(sessionId, ward) {
    if (!confirm('Restore this archived session? It will reappear in the active sessions list.')) return;

    const formData = new FormData();
    formData.append('id', sessionId);
    formData.append('ward', ward);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    fetch('<?= url('sessions/restore') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (!response.ok) throw new Error(data.error || `HTTP ${response.status}`);
            return data;
        } catch (e) {
            throw new Error(`Server response: ${text.substring(0, 150)}`);
        }
    })
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`.session-row[data-session-id="${sessionId}"]`);
            if (row) {
                const section = row.closest('.ward-section');
                row.remove();

                const tbody = section.querySelector('tbody');
                if (tbody && tbody.querySelectorAll('.session-row').length === 0) {
                    const card = section.querySelector('.sessions-card');
                    if (card) card.remove();
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = '<p>No archived sessions in ' + section.dataset.ward + ' ward</p>';
                    section.appendChild(emptyDiv);
                }
            }
            showToast('Session restored successfully');
            // Re-run search so counts and visibility stay accurate
            document.getElementById('searchInput').dispatchEvent(new Event('input'));
        } else {
            showToast(data.error || 'Failed to restore session', true);
        }
    })
    .catch(error => {
        console.error('Restore error:', error);
        showToast(error.message, true);
    });
}

// ========== DELETE SESSION (AJAX) ==========
function deleteSession(sessionId, ward) {
    if (!confirm('⚠️ Permanently delete this archived session? This action cannot be undone!')) return;

    const formData = new FormData();
    formData.append('id', sessionId);
    formData.append('ward', ward);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    fetch('<?= url('sessions/delete') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (!response.ok) throw new Error(data.error || `HTTP ${response.status}`);
            return data;
        } catch (e) {
            throw new Error(`Server response: ${text.substring(0, 150)}`);
        }
    })
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`.session-row[data-session-id="${sessionId}"]`);
            if (row) {
                const section = row.closest('.ward-section');
                row.remove();

                const tbody = section.querySelector('tbody');
                if (tbody && tbody.querySelectorAll('.session-row').length === 0) {
                    const card = section.querySelector('.sessions-card');
                    if (card) card.remove();
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = '<p>No archived sessions in ' + section.dataset.ward + ' ward</p>';
                    section.appendChild(emptyDiv);
                }
            }
            showToast('Session permanently deleted');
            // Re-run search so counts and visibility stay accurate
            document.getElementById('searchInput').dispatchEvent(new Event('input'));
        } else {
            showToast(data.error || 'Failed to delete session', true);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showToast(error.message, true);
    });
}

// ========== TABS & SEARCH ==========
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.ward-tab');
    const sections = document.querySelectorAll('.ward-section');
    const searchInput = document.getElementById('searchInput');

    let activeWard = 'All';

    function applySearch() {
        const term = searchInput.value.toLowerCase().trim();
        let totalVisible = 0;

        sections.forEach(section => {
            const ward = section.dataset.ward;
            const wardVisible = activeWard === 'All' || ward === activeWard;

            if (!wardVisible) {
                section.style.display = 'none';
                return;
            }

            let visibleRows = 0;
            const rows = section.querySelectorAll('.session-row');

         rows.forEach(row => {
                const initials = row.getAttribute('data-search') || '';
                const match = term === '' || initials === term || initials.startsWith(term);
                row.style.display = match ? '' : 'none';
                if (match) visibleRows++;
            });

            section.style.display = (term === '' || visibleRows > 0) ? '' : 'none';
            totalVisible += visibleRows;
        });

        let noResults = document.getElementById('globalNoResults');
        if (!noResults) {
            noResults = document.createElement('div');
            noResults.id = 'globalNoResults';
            noResults.style.cssText = 'text-align:center;padding:3rem;color:#64748b;background:#f8fafc;border-radius:1rem;border:1px dashed #cbd5e1;margin-top:1rem;';
            document.querySelector('.sessions-container').appendChild(noResults);
        }
        if (term !== '' && totalVisible === 0) {
            noResults.innerHTML = '<p style="margin:0;font-size:0.9rem;">No sessions found matching <strong>"' + term + '"</strong></p>';
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }

    function filterByWard() {
        sections.forEach(section => {
            const ward = section.dataset.ward;
            section.style.display = (activeWard === 'All' || ward === activeWard) ? '' : 'none';
        });
        applySearch();
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            activeWard = tab.dataset.ward;
            filterByWard();
        });
    });

    searchInput.addEventListener('input', applySearch);
    filterByWard();
});
</script>