<?php $title = $title ?? 'Discharged Patients'; ?>

<!-- Include the patient details modal functions -->
<script>
// These functions need to be available for viewPatientDetails to work
<?php if (!function_exists('url')): ?>
// If this file is loaded directly, we need the url helper
function url($path) {
    return '/blueprint/public/' . ltrim($path, '/');
}
<?php endif; ?>
</script>

<div class="page-header">
    <div>
        <h1><?= e($ward) ?> Ward - Discharged Patients</h1>
        <p class="text-muted">View discharged patient records</p>
    </div>
    <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Ward
    </a>
</div>

<div class="records-container">
    <?php if(!empty($discharged)): ?>
        <div class="records-grid">
            <?php foreach($discharged as $p): ?>
                <div class="record-card">
                    <div class="record-header">
                        <div class="record-avatar"><?= e($p->initials) ?></div>
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
                        <!-- TWO CORE-10 BADGES: ADMISSION & DISCHARGE -->
                        <div class="detail-item core10-row">
                            <span class="detail-label">CORE-10</span>
                            <div class="core10-badges">
                                <span class="core10-badge admission-badge <?= ($p->core10_admission ?? false) ? 'completed' : 'pending' ?>">
                                    Admission: <?= ($p->core10_admission ?? false) ? '✓' : '○' ?>
                                </span>
                                <span class="core10-badge discharge-badge <?= ($p->core10_discharge ?? false) ? 'completed' : 'pending' ?>">
                                    Discharge: <?= ($p->core10_discharge ?? false) ? '✓' : '○' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="record-actions">
                        <button onclick="viewPatientDetails(<?= $p->id ?>, '<?= e($p->initials) ?>')" class="btn-view">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7z"/>
                            </svg>
                            View Details
                        </button>
                        
                        <form method="POST" action="<?= url('patients/delete') ?>" class="inline-form" onsubmit="return confirm('⚠️ PERMANENTLY DELETE this discharged patient? This action cannot be undone!')">
                            <input type="hidden" name="id" value="<?= $p->id ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn-delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">🚪</div>
            <h3>No Discharged Patients</h3>
            <p>There are no discharged patients in <?= e($ward) ?> ward.</p>
            <a href="<?= url('wards/' . strtolower($ward)) ?>" class="btn-primary">Back to Ward</a>
        </div>
    <?php endif; ?>
</div>

<!-- Patient Details Modal (updated with two CORE-10 fields) -->
<div id="patientDetailsModal" class="modal" style="display:none;">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2>Patient: <span id="viewPatientName"></span></h2>
            <button type="button" class="modal-close" onclick="closePatientDetailsModal()">✕</button>
        </div>


<!-- Patient Summary Card -->
<div class="patient-summary-card">
    <div class="summary-row">
        <div class="summary-item">
            <span class="summary-label">Ward:</span>
            <span class="summary-value" id="viewPatientWard"></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Room:</span>
            <span class="summary-value" id="viewPatientRoom"></span>
        </div>
    </div>
    <div class="summary-row">
        <div class="summary-item">
            <span class="summary-label">Admitted:</span>
            <span class="summary-value" id="viewPatientAdmissionDateTime"></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Discharged:</span>
            <span class="summary-value" id="viewPatientDischargeDateTime"></span>
        </div>
    </div>
    <div class="summary-row">
        <div class="summary-item">
            <span class="summary-label">Admission CORE-10:</span>
            <span class="summary-value" id="viewPatientAdmissionCore"></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Discharge CORE-10:</span>
            <span class="summary-value" id="viewPatientDischargeCore"></span>
        </div>
    </div>
</div>

        <!-- Tabs for switching between views -->
        <div class="details-tabs">
            <button class="tab-btn active" onclick="switchTab('sessions')" id="sessionsTabBtn">📋 All Sessions</button>
            <button class="tab-btn" onclick="switchTab('admission')" id="admissionTabBtn">📝 Admission Notes</button>
            <button class="tab-btn" onclick="switchTab('discharge')" id="dischargeTabBtn">🚪 Discharge Notes</button>
        </div>

        <!-- All Sessions Tab -->
        <div id="sessionsTab" class="tab-content active">
            <div class="sessions-list" id="sessionsList">
                <div class="loading">Loading sessions...</div>
            </div>
        </div>

        <!-- Admission Notes Tab -->
        <div id="admissionTab" class="tab-content">
            <div class="notes-card" id="admissionNotes">
                <div class="loading">Loading admission notes...</div>
            </div>
        </div>

        <!-- Discharge Notes Tab -->
        <div id="dischargeTab" class="tab-content">
            <div class="notes-card" id="dischargeNotes">
                <div class="loading">Loading discharge notes...</div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" onclick="closePatientDetailsModal()" class="btn-secondary">Close</button>
        </div>
    </div>
</div>

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
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header h1::before {
    content: "🚪";
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
    grid-template-columns: repeat(auto-fit, minmax(300px,1fr));
    gap: 25px;
}

/* ===== CARD ===== */

.record-card {
    background: white;
    border-radius: 22px;
    padding: 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 25px rgba(0,0,0,0.04);
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
    width: 54px;
    height: 54px;
    border-radius: 16px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
}

.record-status.discharged {
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 30px;
    background: #dcfce7;
    color: #166534;
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

.detail-label {
    color: #64748b;
}

.detail-value {
    font-weight: 600;
}

/* CORE 10 BADGES */

.core10-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.core10-badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 30px;
    font-weight: 600;
}

.core10-badge.completed {
    background: #dcfce7;
    color: #166534;
}

.core10-badge.pending {
    background: #fee2e2;
    color: #991b1b;
}

/* ACTIONS */

.record-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
}

.btn-view,
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

.btn-view {
    background: #2563eb;
    color: white;
}

.btn-view:hover {
    background: #1d4ed8;
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

/* NOTES PREVIEW */

.notes-preview {
    background: #f8fafc;
    border-radius: 14px;
    padding: 14px;
    border-left: 4px solid #94a3b8;
}

.notes-preview-header {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #64748b;
}

.notes-preview-content {
    font-size: 13px;
    color: #475569;
}

/* ===== MODAL MODERN ===== */

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.6);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 24px;
    padding: 35px;
    max-width: 950px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 30px 60px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.modal-header h2 {
    font-size: 22px;
}

.modal-close {
    border: none;
    background: none;
    font-size: 22px;
    cursor: pointer;
}

/* TABS */

.details-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.tab-btn {
    padding: 10px 18px;
    border-radius: 30px;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
    font-weight: 500;
}

.tab-btn.active {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>

<script>
// Patient details functions
let currentViewPatientId = null;

function viewPatientDetails(patientId, patientName) {
    currentViewPatientId = patientId;
    document.getElementById('viewPatientName').innerText = patientName;

    // Show modal with loading
    document.getElementById('patientDetailsModal').style.display = 'flex';

    // Load patient data
    loadPatientSummary(patientId);
    loadAllSessions(patientId);
    loadAdmissionNotes(patientId);
    loadDischargeNotes(patientId);

    // Reset to sessions tab
    switchTab('sessions');
}

function loadPatientSummary(patientId) {
    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            console.log('Patient summary data:', data);
            document.getElementById('viewPatientWard').innerText = data.ward || 'N/A';
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';
            
            // Extract only date part (remove time) if time is 00:00
            const admissionDate = data.admission_datetime ? data.admission_datetime.split(' ')[0] : 'N/A';
            const dischargeDate = data.discharge_datetime ? data.discharge_datetime.split(' ')[0] : 'N/A';
            document.getElementById('viewPatientAdmissionDateTime').innerText = admissionDate;
            document.getElementById('viewPatientDischargeDateTime').innerText = dischargeDate;

            // Admission CORE-10 badge
            document.getElementById('viewPatientAdmissionCore').innerHTML = data.core10_admission
                ? '<span class="badge-success">Completed</span>'
                : '<span class="badge-warning">Pending</span>';

            // Discharge CORE-10 badge
            document.getElementById('viewPatientDischargeCore').innerHTML = data.core10_discharge
                ? '<span class="badge-success">Completed</span>'
                : '<span class="badge-warning">Pending</span>';
        })
        .catch(error => {
            console.error('Error loading patient summary:', error);
        });
}
function loadAllSessions(patientId) {
    const sessionsList = document.getElementById('sessionsList');
    sessionsList.innerHTML = '<div class="loading">Loading sessions...</div>';

    fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            displayAllSessions(data);
        })
        .catch(error => {
            console.error('Error loading sessions:', error);
            sessionsList.innerHTML = '<div class="error">Error loading sessions</div>';
        });
}

function displayAllSessions(sessions) {
    const container = document.getElementById('sessionsList');

    if (!sessions || sessions.length === 0) {
        container.innerHTML = '<div class="no-notes">No sessions recorded for this patient</div>';
        return;
    }

    let html = '<table class="sessions-table">';
    html += '<thead><tr><th>Date & Time</th><th>CareNotes</th><th>Tracker</th><th>Tasks</th><th>Notes</th></tr></thead>';
    html += '<tbody>';

    sessions.forEach(s => {
        const date = new Date(s.datetime);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        html += `<tr>
            <td>${formattedDate}</td>
            <td class="status-icon">${s.carenotes_completed ? '✅' : '❌'}</td>
            <td class="status-icon">${s.tracker_completed ? '✅' : '❌'}</td>
            <td class="status-icon">${s.tasks_completed ? '✅' : '❌'}</td>
            <td class="notes-cell">${s.notes ? s.notes.substring(0, 50) + (s.notes.length > 50 ? '...' : '') : '-'}</td>
        </tr>`;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

function loadAdmissionNotes(patientId) {
    const container = document.getElementById('admissionNotes');
    container.innerHTML = '<div class="loading">Loading admission notes...</div>';

    fetch('<?= url('patients/get-notes') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.trim() !== '') {
                let formattedNotes = data.notes
                    .replace(/\n/g, '<br>')
                    .replace(/CORE-10 completed on admission/g, '<strong>CORE-10 on admission</strong>')
                    .replace(/Admission Date:/g, '<strong>Admission Date:</strong>');
                container.innerHTML = `<div class="notes-content admission-notes">${formattedNotes}</div>`;
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
            if (data.notes && data.notes.trim() !== '') {
                // Extract only the actual notes content after "Notes:" 
                let notesText = data.notes;
                // Remove any leading timestamp and markers up to "Notes:"
                const notesMatch = notesText.match(/Notes:\s*(.*?)(?:\n|$)/is);
                if (notesMatch && notesMatch[1]) {
                    notesText = notesMatch[1].trim();
                } else {
                    // Fallback: remove everything before the first occurrence of "Notes:" if present
                    const notesIndex = notesText.indexOf('Notes:');
                    if (notesIndex !== -1) {
                        notesText = notesText.substring(notesIndex + 6).trim();
                    }
                }
                // Remove any trailing separators or markers
                notesText = notesText.replace(/={3,}/g, '').trim();
                
                // Replace newlines with <br> for HTML display
                let formattedNotes = notesText.replace(/\n/g, '<br>');
                container.innerHTML = `<div class="notes-content">${formattedNotes}</div>`;
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
    const sessionsTab = document.getElementById('sessionsTab');
    const admissionTab = document.getElementById('admissionTab');
    const dischargeTab = document.getElementById('dischargeTab');
    const sessionsBtn = document.getElementById('sessionsTabBtn');
    const admissionBtn = document.getElementById('admissionTabBtn');
    const dischargeBtn = document.getElementById('dischargeTabBtn');

    sessionsTab.classList.remove('active');
    admissionTab.classList.remove('active');
    dischargeTab.classList.remove('active');
    sessionsBtn.classList.remove('active');
    admissionBtn.classList.remove('active');
    dischargeBtn.classList.remove('active');

    if (tab === 'sessions') {
        sessionsTab.classList.add('active');
        sessionsBtn.classList.add('active');
    } else if (tab === 'admission') {
        admissionTab.classList.add('active');
        admissionBtn.classList.add('active');
    } else if (tab === 'discharge') {
        dischargeTab.classList.add('active');
        dischargeBtn.classList.add('active');
    }
}

function closePatientDetailsModal() {
    document.getElementById('patientDetailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('patientDetailsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>