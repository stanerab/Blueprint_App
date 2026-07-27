<?php $title = 'Discharged Patients'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="discharged-page">

<!-- ===== HEADER ===== -->
<div class="page-header">
    <div class="page-title-block">
        <h1><i class="bi bi-box-arrow-right"></i> Discharged Patients</h1>
        <p class="text-muted">Overview across all wards</p>
    </div>

    <div class="page-actions">
        <a href="<?= url('dashboard') ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- ===== CONTROLS ===== -->
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

<!-- ===== RECORDS ===== -->
<div class="records-container">
<?php foreach ($grouped as $wardName => $patientsList): ?>
    <div class="ward-section" data-ward="<?= $wardName ?>">
        <h2 class="ward-title"><?= $wardName ?> Ward</h2>

        <?php if (!empty($patientsList)): ?>
            <div class="records-grid">
                <?php foreach ($patientsList as $p): ?>
                    <div class="record-card" data-patient-id="<?= $p->id ?>">
                        
                        <div class="record-header">
                            <div class="record-avatar">
                                <?= e($p->initials) ?>
                            </div>
                            <div class="record-status discharged">Discharged</div>
                        </div>

                        <div class="record-details">
                            <div class="detail-item">
                                <span class="detail-label">Room</span>
                                <span class="detail-value"><?= e($p->room_number) ?></span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Admitted</span>
                                <span class="detail-value"><?= date('d/m/Y', strtotime($p->admission_date)) ?></span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Discharged</span>
                                <span class="detail-value"><?= date('d/m/Y', strtotime($p->discharge_date)) ?></span>
                            </div>

                            <div class="detail-item core10-row">
                                <span class="detail-label">CORE-10</span>
                                <div class="core10-badges">
                                <span class="core10-badge <?= $p->core10_admission ? 'completed' : 'pending' ?>">
    Admission <?= $p->core10_admission ? '✓' : '✗' ?>
</span>
<span class="core10-badge <?= $p->core10_discharge ? 'completed' : 'pending' ?>">
    Discharge <?= $p->core10_discharge ? '✓' : '✗' ?>
</span>
                                </div>
                            </div>
                        </div>

                        <div class="record-actions">
                            <button onclick="viewPatientDetails(<?= $p->id ?>,'<?= e($p->initials) ?>')" class="btn-view">
                                View Details
                            </button>
                            <button onclick="deletePatient(<?= $p->id ?>)" class="btn-delete">
                                Delete
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No discharged patients in <?= $wardName ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>

<!-- ===== PATIENT DETAILS MODAL ===== -->
<div id="patientDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Patient: <span id="viewPatientName"></span></h3>
            <span class="modal-close" onclick="closePatientDetailsModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="summary-grid">
                <div><strong>Ward:</strong> <span id="viewPatientWard"></span></div>
                <div><strong>Room:</strong> <span id="viewPatientRoom"></span></div>
                <div><strong>Admitted:</strong> <span id="viewPatientAdmissionDateTime"></span></div>
                <div><strong>Discharged:</strong> <span id="viewPatientDischargeDateTime"></span></div>
                <div><strong>CORE-10 Admission:</strong> <span id="viewPatientAdmissionCore"></span></div>
                <div><strong>CORE-10 Discharge:</strong> <span id="viewPatientDischargeCore"></span></div>
            </div>
            <div class="modal-tabs">
                <button id="sessionsTabBtn" class="tab-btn active" onclick="switchTab('sessions')">Sessions</button>
                <button id="admissionTabBtn" class="tab-btn" onclick="switchTab('admission')">Admission Notes</button>
                <button id="dischargeTabBtn" class="tab-btn" onclick="switchTab('discharge')">Discharge Notes</button>
              <button id="transferTabBtn" class="tab-btn" onclick="switchTab('transfer')" style="display:none;">Ward History</button>
                <button id="roomHistoryTabBtn" class="tab-btn" onclick="switchTab('roomHistory')" style="display:none;">Room History</button>
            </div>
            <div id="sessionsTab" class="tab-content active">
                <div id="sessionsList"><div class="loading">Loading sessions...</div></div>
            </div>
            <div id="admissionTab" class="tab-content">
                <div id="admissionNotes"><div class="loading">Loading admission notes...</div></div>
            </div>
            <div id="dischargeTab" class="tab-content">
                <div id="dischargeNotes"><div class="loading">Loading discharge notes...</div></div>
            </div>
         <div id="transferTab" class="tab-content">
    <div id="transferHistory" style="overflow-x:auto;">
        <div class="loading">Loading transfer history...</div>
    </div>
</div>

<div id="roomHistoryTab" class="tab-content">
    <div id="roomHistoryContent" style="overflow-x:auto;">
        <div class="loading">Loading room history...</div>
    </div>
</div>
</div>
        </div>
    </div>
</div>

<style>
/* ===== HEADER ===== */
.discharged-page { padding: 1.5rem; }

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title-block h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e3a8a;
    margin: 0 0 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.text-muted {
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

/* ===== STATS ===== */
.stats-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.stat-pill {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    background: #f1f5f9;
}

/* UPDATED WARD COLOURS - Hope = Yellow, Lakeside = Green, Manor = Blue */
.stat-pill.hope { color: #eab308; }
.stat-pill.lakeside { color: #22c55e; }
.stat-pill.manor { color: #3b82f6; }

/* ===== CONTROLS ===== */
.top-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
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

.search-box input {
    width: 100%;
    padding: 0.6rem 0.6rem 0.6rem 2.2rem;
    border: 1px solid #e2e8f0;
    border-radius: 2rem;
    font-size: 0.85rem;
    background: white;
}

.tabs {
    display: flex;
    gap: 0.25rem;
    background: #f1f5f9;
    padding: 0.25rem;
    border-radius: 2rem;
}

/* core10 edit */
.btn-core10-edit {
    font-size: 0.7rem;
    padding: 0.15rem 0.5rem;
    border-radius: 0.3rem;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    cursor: pointer;
    margin-left: 6px;
    transition: all 0.15s;
}
.btn-core10-edit:hover { background: #e2e8f0; }


.ward-tab {
    padding: 0.5rem 1.2rem;
    border: none;
    background: transparent;
    border-radius: 1.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    color: #475569;
}

.ward-tab.active {
    background: white;
    color: #1e293b;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

/* ===== WARD COLOURS - UPDATED ===== */
/* Hope = Yellow (#eab308) */
.ward-section[data-ward="Hope"] .ward-title { color: #eab308; border-bottom: 2px solid #eab308; }
/* Lakeside = Green (#22c55e) */
.ward-section[data-ward="Lakeside"] .ward-title { color: #22c55e; border-bottom: 2px solid #22c55e; }
/* Manor = Blue (#3b82f6) */
.ward-section[data-ward="Manor"] .ward-title { color: #3b82f6; border-bottom: 2px solid #3b82f6; }

/* ===== CARDS ===== */
.records-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}

.record-card {
    background: white;
    border-radius: 16px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}

.record-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.06);
}

.record-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.record-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

/* UPDATED AVATAR COLOURS - Hope = Yellow, Lakeside = Green, Manor = Blue */
.ward-section[data-ward="Hope"] .record-avatar { background: #eab308; }
.ward-section[data-ward="Lakeside"] .record-avatar { background: #22c55e; }
.ward-section[data-ward="Manor"] .record-avatar { background: #3b82f6; }

.record-status.discharged {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: 2rem;
    background: #fee2e2;
    color: #b91c1c;
}

.record-details {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 0.85rem;
}

.detail-label {
    color: #64748b;
    font-weight: 500;
}

.detail-value {
    color: #1e293b;
    font-weight: 500;
}

.core10-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.3rem;
}

.core10-badges {
    display: flex;
    gap: 0.5rem;
}

.core10-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 2rem;
    background: #f1f5f9;
    color: #475569;
    white-space: nowrap;
    min-width: 105px;
    text-align: center;
    display: inline-block;
}

.core10-badge.completed {
    background: #d1fae5;
    color: #065f46;
}

.core10-badge.pending {
    background: #fee2e2;
    color: #991b1b;
}

.record-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.btn-view, .btn-delete {
    flex: 1;
    padding: 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    border: none;
    transition: 0.2s;
}

.btn-view {
    background: #eff6ff;
    color: #2563eb;
}

.btn-view:hover {
    background: #dbeafe;
}

.btn-delete {
    background: #fef2f2;
    color: #dc2626;
}

.btn-delete:hover {
    background: #fee2e2;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 1rem;
    color: #64748b;
    border: 1px dashed #cbd5e1;
}

/* ===== MODAL ===== */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    max-width: 800px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
}

.modal-content::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
}

.modal-close {
    font-size: 1.5rem;
    cursor: pointer;
    color: #94a3b8;
}

.modal-body {
    padding: 1.5rem;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px,1fr));
    gap: 0.75rem;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
}

/* MODAL TABS - RESPONSIVE FIX */
.modal-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 0.5rem 1rem;
    background: none;
    border: none;
    font-size: 0.85rem;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    border-radius: 0.5rem;
}

.tab-btn:hover {
    background: #f1f5f9;
    color: #2563eb;
}

.tab-btn.active {
    color: #2563eb;
    border-bottom: 2px solid #2563eb;
    margin-bottom: -1px;
    background: none;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.sessions-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8rem;
}

.sessions-table th, .sessions-table td {
    padding: 0.6rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.sessions-table th {
    background: #f1f5f9;
    font-weight: 600;
}

.notes-content {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0.5rem;
    white-space: pre-wrap;
    font-size: 0.85rem;
    line-height: 1.5;
}

.loading, .no-notes, .error {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

.component-badge {
    display: inline-block;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.68rem;
    font-weight: 500;
    min-width: unset;
    text-align: center;
    white-space: nowrap;
}

.component-badge.completed { background: #d1fae5; color: #065f46; }
.component-badge.pending { background: #fee2e2; color: #991b1b; }


/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .records-grid {
        grid-template-columns: 1fr;
    }
    
    .top-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .tabs {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .modal-tabs {
        justify-content: center;
    }
    
    .tab-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    .modal-body {
        padding: 1rem;
    }
    
    .modal-header h3 {
        font-size: 1rem;
    }
    
    .sessions-table th, 
    .sessions-table td {
        padding: 0.4rem;
        font-size: 0.7rem;
    }
}
</style>

<!-- NOTE MODAL -->
<div id="noteModal" class="modal">
    <div class="modal-content" style="max-width:560px;">
        <div class="modal-header">
            <h3 style="margin:0;font-size:1.1rem;">Session Note</h3>
            <span class="modal-close" onclick="closeNoteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="noteModalContent" class="notes-content" style="min-height:80px;max-height:400px;overflow-y:auto;white-space:pre-wrap;line-height:1.6;"></div>
            <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-top:1rem;">
                <button onclick="copyNoteFromModal()" style="padding:0.5rem 1.2rem;border-radius:2rem;border:1px solid #e2e8f0;background:#f1f5f9;cursor:pointer;font-size:0.85rem;">Copy</button>
                <button onclick="closeNoteModal()" style="padding:0.5rem 1.2rem;border-radius:2rem;border:none;background:#1e3a8a;color:white;cursor:pointer;font-size:0.85rem;">Close</button>
            </div>
        </div>
    </div>
</div>

</div>


<script>
// Store current patient ID
let currentViewPatientId = null;

// ========== MODAL FUNCTIONS ==========
function viewPatientDetails(patientId, patientName) {
    currentViewPatientId = patientId;
    document.getElementById('viewPatientName').innerText = patientName;
    document.getElementById('patientDetailsModal').style.display = 'flex';

    loadPatientSummary(patientId);
    loadAllSessions(patientId);
    loadAdmissionNotes(patientId);
    loadDischargeNotes(patientId);
  loadWardTransferHistory(patientId);
    loadRoomHistory(patientId);

    switchTab('sessions');
}

function closePatientDetailsModal() {
    document.getElementById('patientDetailsModal').style.display = 'none';
}

function loadRoomHistory(patientId) {
    const container = document.getElementById('roomHistoryContent');
    container.innerHTML = '<div class="loading">Loading room history...</div>';

    fetch(`<?= url('patients/room-history') ?>?id=${patientId}`)
        .then(r => r.json())
        .then(data => {
            const btn = document.getElementById('roomHistoryTabBtn');
            if (!data.length) {
                btn.style.display = 'none';
                container.innerHTML = '<div class="no-notes">No room change history for this patient</div>';
                return;
            }
            btn.style.display = '';
            btn.textContent = `Room History (${data.length})`;

            let html = '<table class="sessions-table" style="min-width:600px;"><thead><tr><th>Date</th><th>From</th><th>To</th><th>Changed By</th><th>Reason</th></tr></thead><tbody>';
            data.forEach(row => {
                const changedAt = new Date(row.changed_at.replace(' ', 'T') + 'Z');
                const dateStr = changedAt.toLocaleDateString('en-GB') + ' ' +
                                changedAt.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                html += `<tr>
                    <td style="white-space:nowrap;">${dateStr}</td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:#e2e8f0;color:#475569;">Room ${row.from_room ?? '—'}</span></td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:#1e3a8a;color:white;">Room ${row.to_room}</span></td>
                    <td>${row.changed_by}</td>
                    <td style="color:#64748b;font-style:${row.reason ? 'normal' : 'italic'};">${row.reason ? row.reason : 'No reason given'}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(err => {
            console.error('Room history error:', err);
            container.innerHTML = '<div class="error">Error loading room history</div>';
        });
}


function loadWardTransferHistory(patientId) {
    const container = document.getElementById('transferHistory');
    const tabBtn    = document.getElementById('transferTabBtn');

    container.innerHTML = '<div class="loading">Loading transfer history...</div>';
    tabBtn.style.display = 'none';

    fetch('<?= url('patients/ward-history') ?>?id=' + patientId)
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                container.innerHTML = '<div class="no-notes">No transfer history for this patient</div>';
                return;
            }

            tabBtn.style.display = '';
            tabBtn.textContent = `Ward History (${data.length})`;

            const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };

            let html = '<table class="sessions-table" style="min-width:600px;"><thead><tr><th>Date</th><th>From</th><th>To</th><th>Changed By</th><th>Reason</th></tr></thead><tbody>';

         data.forEach(row => {
                const transferDate = new Date(row.transferred_at.replace(' ', 'T') + 'Z');
                const date = transferDate.toLocaleDateString('en-GB') + ' ' +
                             transferDate.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                const fromColour = wardColours[row.from_ward] || '#94a3b8';
                const toColour   = wardColours[row.to_ward]   || '#94a3b8';

                html += `<tr>
                    <td style="white-space:nowrap;">${date}</td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${fromColour};color:white;">${row.from_ward}</span></td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${toColour};color:white;">${row.to_ward}</span></td>
                    <td>${row.changed_by}</td>
                    <td style="color:#64748b;font-style:${row.transfer_reason ? 'normal' : 'italic'};">${row.transfer_reason || 'No reason given'}</td>
                </tr>`;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(() => {
            container.innerHTML = '<div class="error">Error loading transfer history</div>';
        });
}

// ========== DATA LOADING ==========
function loadPatientSummary(patientId) {
    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('viewPatientWard').innerText = data.ward || 'N/A';
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';

            // Always update header with initials + room for consistency
            const currentName = document.getElementById('viewPatientName').innerText;
            const initials = currentName.split(',')[0].trim();
            if (data.room_number) {
                document.getElementById('viewPatientName').innerText = `${initials}, Room ${data.room_number}`;
            }

            const admissionRaw = data.admission_datetime || data.admission_date || data.admitted || null;
            const dischargeRaw = data.discharge_datetime || data.discharge_date || null;
            const admissionDate = admissionRaw ? admissionRaw.split(' ')[0] : 'N/A';
            const dischargeDate = dischargeRaw ? dischargeRaw.split(' ')[0] : 'N/A';
            document.getElementById('viewPatientAdmissionDateTime').innerText = admissionDate;
            document.getElementById('viewPatientDischargeDateTime').innerText = dischargeDate;

            document.getElementById('viewPatientAdmissionCore').innerHTML = data.core10_admission
                ? '<span class="core10-badge completed">✓ Completed </span>'
                : '<span class="core10-badge pending">✗ Not Completed </span>';
            document.getElementById('viewPatientAdmissionCore').innerHTML +=
                ` <button class="btn-core10-edit" onclick="editDischargedCore10('admission', ${patientId}, ${data.core10_admission ? 1 : 0})">✎ Edit</button>`;

            document.getElementById('viewPatientDischargeCore').innerHTML = data.core10_discharge
                ? '<span class="core10-badge completed">✓ Completed </span>'
                : '<span class="core10-badge pending">✗ Not Completed </span>';
            document.getElementById('viewPatientDischargeCore').innerHTML +=
                ` <button class="btn-core10-edit" onclick="editDischargedCore10('discharge', ${patientId}, ${data.core10_discharge ? 1 : 0})">✎ Edit</button>`;
        })
        .catch(error => console.error('Error loading patient summary:', error));
}

function editDischargedCore10(type, patientId, currentValue) {
    const spanId = type === 'admission' ? 'viewPatientAdmissionCore' : 'viewPatientDischargeCore';
    const span = document.getElementById(spanId);
    const isCompleted = currentValue == 1;

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = isCompleted;
    checkbox.id = 'dischargedCore10_' + type;

    const saveBtn = document.createElement('button');
    saveBtn.textContent = 'Save';
    saveBtn.className = 'btn-core10-edit';
    saveBtn.style.marginLeft = '6px';
   saveBtn.onclick = async () => {
    const completed = checkbox.checked ? 1 : 0;
    const endpoint = type === 'admission'
        ? '<?= url('patients/update-core10') ?>'
        : '<?= url('patients/update-discharge-core10') ?>';
 const body = type === 'admission'
        ? { patient_id: patientId, core10_admission: completed, csrf_token: '<?= csrf_token() ?>', source: 'discharged' }
        : { patient_id: patientId, core10_discharge: completed, csrf_token: '<?= csrf_token() ?>', source: 'discharged' };

    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(body)
        });
        const data = await response.json();
    if (data.success) {
    showToast('CORE-10 updated successfully (Discharged Patients)');

    // Update modal badge immediately
    const spanId = type === 'admission' ? 'viewPatientAdmissionCore' : 'viewPatientDischargeCore';
    const span = document.getElementById(spanId);
    const badgeClass = completed ? 'completed' : 'pending';
    const badgeText = type === 'admission'
        ? (completed ? '✓ Completed ' : '✗ Not Completed ')
        : (completed ? '✓ Completed ' : '✗ Not Completed ');
    span.innerHTML = `<span class="core10-badge ${badgeClass}">${badgeText}</span>
        <button class="btn-core10-edit" onclick="editDischargedCore10('${type}', ${patientId}, ${completed})">✎ Edit</button>`;

    // Also update the patient card on the page so it reflects immediately without refresh
    const card = document.querySelector(`.record-card[data-patient-id="${patientId}"]`);
    if (card) {
        const cardBadgeClass = completed ? 'completed' : 'pending';
        const cardBadgeSymbol = completed ? '✓' : '✗';
        const cardBadgeLabel = type === 'admission' ? 'Admission' : 'Discharge';
        const existingBadge = Array.from(card.querySelectorAll('.core10-badge'))
            .find(b => b.textContent.includes(cardBadgeLabel));
        if (existingBadge) {
            existingBadge.className = `core10-badge ${cardBadgeClass}`;
            existingBadge.textContent = `${cardBadgeLabel} ${cardBadgeSymbol}`;
        }
    }
}
        
        else {
            showToast(data.error || 'Failed to update', true);
            loadPatientSummary(patientId);
        }
    } catch (err) {
        showToast('Network error', true);
        loadPatientSummary(patientId);
    }
};

    const cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Cancel';
    cancelBtn.className = 'btn-core10-edit';
    cancelBtn.style.marginLeft = '4px';
    cancelBtn.onclick = () => loadPatientSummary(patientId);

    span.innerHTML = '';
    span.appendChild(checkbox);
    span.appendChild(saveBtn);
    span.appendChild(cancelBtn);
}

function loadAllSessions(patientId) {
    const container = document.getElementById('sessionsList');
    container.innerHTML = '<div class="loading">Loading sessions...</div>';

    fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="no-notes">No sessions recorded for this patient</div>';
                return;
            }

            window._sessionNotes = {};
            let html = '<table class="sessions-table" style="min-width:700px;"><thead><tr><th>Date & Time</th><th>Session Status</th><th>CareNotes</th><th>Tracker</th><th>Tasks</th><th>Notes</th></tr></thead><tbody>';

            data.forEach(s => {
                window._sessionNotes[s.id] = s.notes || '';
                const date = new Date(s.datetime);
                const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});

                const sessionStatus = (s.status || 'offered').toLowerCase();
                const statusColours = {
                    offered:   { bg: '#e0f2fe', color: '#0369a1' },
                    completed: { bg: '#d1fae5', color: '#065f46' },
                    declined:  { bg: '#fed7aa', color: '#92400e' },
                    dna:       { bg: '#fee2e2', color: '#991b1b' }
                };
                const sc = statusColours[sessionStatus] || statusColours['offered'];
                const statusBadge = `<span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${sc.bg};color:${sc.color};">${sessionStatus.toUpperCase()}</span>`;

                html += `<tr>
                    <td style="white-space:nowrap;">${formattedDate}</td>
                    <td>${statusBadge}</td>
                    <td class="status-icon">${s.carenotes_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">✗ Not Completed</span>'}</td>
                    <td class="status-icon">${s.tracker_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">✗ Not Completed</span>'}</td>
                    <td class="status-icon">${s.tasks_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">✗ Not Completed</span>'}</td>
                    <td>${s.notes ? `<button onclick="openNoteModal(${s.id})" style="font-size:0.7rem;padding:2px 8px;border-radius:4px;border:1px solid #e2e8f0;background:#f8fafc;color:#2563eb;cursor:pointer;white-space:nowrap;">View</button>` : '<span style="font-size:0.75rem;color:#94a3b8;font-style:italic;">No notes recorded</span>'}</td>
                </tr>`;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading sessions:', error);
            container.innerHTML = '<div class="error">Error loading sessions</div>';
        });
}

function copyNoteText(el, fullText) {
    if (!fullText || fullText === '') return;
    navigator.clipboard.writeText(fullText).then(() => {
        const original = el.style.background;
        el.style.background = '#d1fae5';
        el.style.color = '#065f46';
        const prev = el.innerHTML;
        el.innerHTML = '✓ Note copied';
        setTimeout(() => {
            el.style.background = original;
            el.style.color = '';
            el.innerHTML = prev;
        }, 1500);
    }).catch(() => {
        showToast('Could not copy — please copy manually', true);
    });
}

function loadAdmissionNotes(patientId) {
    const container = document.getElementById('admissionNotes');
    container.innerHTML = '<div class="loading">Loading admission notes...</div>';

    fetch('<?= url('patients/get-notes') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.trim()) {
                const formatted = data.notes.replace(/\n/g, '<br>');
                container.innerHTML = `<div class="notes-content">${formatted}</div>`;
            } else {
                container.innerHTML = '<div class="no-notes">No admission notes available</div>';
            }
        })
        .catch(error => {
            console.error('Error loading admission notes:', error);
            container.innerHTML = '<div class="error">Failed to load admission notes</div>';
        });
}

function loadDischargeNotes(patientId) {
    const container = document.getElementById('dischargeNotes');
    container.innerHTML = '<div class="loading">Loading discharge notes...</div>';

    fetch('<?= url('patients/get-discharge-notes') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.trim()) {
                let notesText = data.notes;
                const notesMatch = notesText.match(/Notes:\s*(.*?)(?:\n|$)/is);
                if (notesMatch && notesMatch[1]) notesText = notesMatch[1].trim();
                else {
                    const notesIndex = notesText.indexOf('Notes:');
                    if (notesIndex !== -1) notesText = notesText.substring(notesIndex + 6).trim();
                }
                notesText = notesText.replace(/={3,}/g, '').trim();
                container.innerHTML = `<div class="notes-content">${notesText.replace(/\n/g, '<br>')}</div>`;
            } else {
                container.innerHTML = '<div class="no-notes">No discharge notes available</div>';
            }
        })
        .catch(error => {
            console.error('Error loading discharge notes:', error);
            container.innerHTML = '<div class="error">Failed to load discharge notes</div>';
        });
}

function switchTab(tab) {
    const sessionsTab    = document.getElementById('sessionsTab');
    const admissionTab   = document.getElementById('admissionTab');
    const dischargeTab   = document.getElementById('dischargeTab');
    const transferTab    = document.getElementById('transferTab');
    const roomHistoryTab = document.getElementById('roomHistoryTab');
    const sessionsBtn    = document.getElementById('sessionsTabBtn');
    const admissionBtn   = document.getElementById('admissionTabBtn');
    const dischargeBtn   = document.getElementById('dischargeTabBtn');
    const transferBtn    = document.getElementById('transferTabBtn');
    const roomHistoryBtn = document.getElementById('roomHistoryTabBtn');

    [sessionsTab, admissionTab, dischargeTab, transferTab, roomHistoryTab].forEach(t => { if(t) t.classList.remove('active'); });
    [sessionsBtn, admissionBtn, dischargeBtn, transferBtn, roomHistoryBtn].forEach(b => { if(b) b.classList.remove('active'); });

    if (tab === 'sessions')         { sessionsTab.classList.add('active');    sessionsBtn.classList.add('active'); }
    else if (tab === 'admission')   { admissionTab.classList.add('active');   admissionBtn.classList.add('active'); }
    else if (tab === 'discharge')   { dischargeTab.classList.add('active');   dischargeBtn.classList.add('active'); }
    else if (tab === 'transfer')    { transferTab.classList.add('active');    transferBtn.classList.add('active'); }
    else if (tab === 'roomHistory') { roomHistoryTab.classList.add('active'); roomHistoryBtn.classList.add('active'); }
}

// ========== DELETE PATIENT ==========
function deletePatient(patientId) {
    if (!confirm('⚠️ Permanently delete this discharged patient? This action cannot be undone!')) return;

    const formData = new FormData();
    formData.append('id', patientId);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    fetch('<?= url('patients/delete') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const card = document.querySelector(`.record-card[data-patient-id="${patientId}"]`);
            if (card) {
                const section = card.closest('.ward-section');
                card.remove();
                const grid = section.querySelector('.records-grid');
                if (grid && grid.children.length === 0) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = '<p>No discharged patients in ' + section.dataset.ward + '</p>';
                    grid.remove();
                    section.appendChild(emptyDiv);
                }
            }
            showToast('Patient deleted successfully', false);
        } else {
            showToast(data.error || 'Failed to delete patient', true);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showToast('Server error (invalid JSON)', true);
    });
}

let _currentNoteText = '';

function openNoteModal(sessionId) {
    const note = (window._sessionNotes && window._sessionNotes[sessionId]) || '';
    _currentNoteText = note;
    const container = document.getElementById('noteModalContent');
    if (note.trim()) {
        container.innerText = note;
        container.style.color = '';
    } else {
        container.innerText = 'No notes recorded for this session.';
        container.style.color = '#94a3b8';
    }
    document.getElementById('noteModal').style.display = 'flex';
}

function closeNoteModal() {
    document.getElementById('noteModal').style.display = 'none';
}

function copyNoteFromModal() {
    navigator.clipboard.writeText(_currentNoteText).then(() => {
        const btn = document.querySelector('#noteModal button:first-of-type');
        const prev = btn.textContent;
        btn.textContent = '✓ Copied';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        setTimeout(() => {
            btn.textContent = prev;
            btn.style.background = '';
            btn.style.color = '';
        }, 1500);
    }).catch(() => showToast('Could not copy — please copy manually', true));
}

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

            const cards = section.querySelectorAll('.record-card');
            let sectionVisible = 0;

            cards.forEach(card => {
                // Only match against the initials, not all card text
                const initials = card.querySelector('.record-avatar')?.innerText.toLowerCase() || '';
                const match = term === '' || initials.includes(term);
                card.style.display = match ? '' : 'none';
                if (match) sectionVisible++;
            });

           section.style.display = (term === '' || sectionVisible > 0) ? 'block' : 'none';
            totalVisible += sectionVisible;
        });

        let noResults = document.getElementById('globalNoResults');
        if (!noResults) {
            noResults = document.createElement('div');
            noResults.id = 'globalNoResults';
            noResults.style.cssText = 'text-align:center;padding:3rem;color:#64748b;background:#f8fafc;border-radius:1rem;border:1px dashed #cbd5e1;margin-top:1rem;';
            document.querySelector('.records-container').appendChild(noResults);
        }
        if (term !== '' && totalVisible === 0) {
            noResults.innerHTML = '<p style="margin:0;font-size:0.9rem;">No patients found matching <strong>"' + term + '"</strong></p>';
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }

    function filterByWard() {
        sections.forEach(section => {
            const ward = section.dataset.ward;
            section.style.display = (activeWard === 'All' || ward === activeWard) ? 'block' : 'none';
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

   window.onclick = function(event) {
    const modal = document.getElementById('patientDetailsModal');
    if (event.target == modal) closePatientDetailsModal();
    const noteModal = document.getElementById('noteModal');
    if (event.target == noteModal) closeNoteModal();
};
});
</script>