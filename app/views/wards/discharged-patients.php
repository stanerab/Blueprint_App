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
                        <div class="detail-item">
                            <span class="detail-label">CORE-10</span>
                            <span class="detail-value">
                                <?php if($p->core10_discharge): ?>
                                    <span class="badge-success">Completed</span>
                                <?php else: ?>
                                    <span class="badge-warning">Pending</span>
                                <?php endif; ?>
                            </span>
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

                    <!-- Admission Notes Preview -->
                    <?php if(!empty($p->notes)): ?>
                        <div class="notes-preview">
                            <div class="notes-preview-header">
                                <span>📝 Admission Notes</span>
                            </div>
                            <div class="notes-preview-content">
                                <?= e(substr($p->notes, 0, 100)) ?>...
                            </div>
                        </div>
                    <?php endif; ?>
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

<!-- Patient Details Modal (reused from main ward view) -->
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
                <div class="summary-item">
                    <span class="summary-label">Admitted:</span>
                    <span class="summary-value" id="viewPatientAdmission"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">CORE-10:</span>
                    <span class="summary-value" id="viewPatientCore"></span>
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
.record-status.discharged {
    background: #e6f7ed;
    color: #166534;
}

.badge-success {
    background: #d4edda;
    color: #155724;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
}

.btn-view, .btn-delete {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-view {
    background: #1e3a8a;
    color: white;
}

.btn-view:hover {
    background: #3b82f6;
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

.record-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.notes-preview {
    background: #f8fafc;
    border-radius: 8px;
    padding: 12px;
    border-left: 3px solid #94a3b8;
}

.notes-preview-header {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.notes-preview-content {
    font-size: 13px;
    color: #475569;
    line-height: 1.5;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 16px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.large-modal {
    max-width: 900px;
    width: 95%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #64748b;
    padding: 5px;
}

.modal-close:hover {
    color: #1e293b;
}

.patient-summary-card {
    background: #f8fafc;
    border-radius: 10px;
    padding: 15px;
    margin: 15px 0;
    border: 1px solid #e2e8f0;
}

.summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.summary-item {
    flex: 1;
    min-width: 120px;
}

.summary-label {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-value {
    font-size: 16px;
    font-weight: 500;
    color: #1e293b;
}

.details-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
}

.tab-btn {
    padding: 8px 16px;
    background: none;
    border: none;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
}

.tab-btn:hover {
    background: #f1f5f9;
    color: #334155;
}

.tab-btn.active {
    background: #1e3a8a;
    color: white;
}

.tab-content {
    display: none;
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}

.tab-content.active {
    display: block;
}

.sessions-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.sessions-table th {
    background: #f8fafc;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    border-bottom: 2px solid #e2e8f0;
}

.sessions-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
}

/* Notes Cards Styling */
.notes-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    min-height: 200px;
    border: 1px solid #e2e8f0;
}

.notes-content {
    line-height: 1.6;
    color: #334155;
    white-space: pre-wrap;
    font-family: 'Courier New', monospace;
    padding: 15px;
    background: #ffffff;
    border-radius: 8px;
    border-left: 4px solid;
}

#admissionNotes .notes-content {
    border-left-color: #3498db;
}

#dischargeNotes .notes-content {
    border-left-color: #e74c3c;
}

.notes-content strong {
    color: #1e3a8a;
    font-weight: 600;
}

.notes-separator {
    border: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #94a3b8, transparent);
    margin: 15px 0;
}

.no-notes {
    color: #94a3b8;
    font-style: italic;
    text-align: center;
    padding: 40px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #64748b;
    font-style: italic;
}

.error {
    text-align: center;
    padding: 40px;
    color: #dc2626;
}

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.modal-actions button {
    flex: 1;
    padding: 12px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    font-size: 14px;
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

@media (max-width: 768px) {
    .record-actions {
        flex-direction: column;
    }
    
    .summary-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .details-tabs {
        flex-direction: column;
    }
    
    .tab-btn {
        width: 100%;
        text-align: center;
    }
}
/* Admission notes specific styling */
.admission-notes {
    border-left-color: #3498db !important;
    background: linear-gradient(to right, #f8fafc, #ffffff);
}

/* Discharge notes specific styling */
#dischargeNotes .notes-content {
    border-left-color: #e74c3c !important;
    background: linear-gradient(to right, #fff5f5, #ffffff);
}

/* Notes content improvements */
.notes-content {
    line-height: 1.6;
    color: #334155;
    white-space: pre-wrap;
    font-family: 'Courier New', monospace;
    padding: 20px;
    background: #ffffff;
    border-radius: 8px;
    border-left: 4px solid;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
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
            document.getElementById('viewPatientWard').innerText = data.ward || 'N/A';
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';
            document.getElementById('viewPatientAdmission').innerText = data.admission_date || 'N/A';
            document.getElementById('viewPatientCore').innerHTML = data.core10_admission ? 
                '<span class="badge-success">Done</span>' : 
                '<span class="badge-warning">Pending</span>';
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
                // Format admission notes
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
                // Format discharge notes with proper styling
                let formattedNotes = data.notes
                    .replace(/\n/g, '<br>')
                    .replace(/CORE-10 completed at discharge: /g, '<strong>CORE-10 at discharge:</strong> ')
                    .replace(/Discharge Notes: /g, '<strong>Notes:</strong> ')
                    .replace(/={50,}/g, '<hr class="notes-separator">')
                    .replace(/DISCHARGE NOTES \[(.*?)\]/g, '<strong>Discharge Date:</strong> $1');
                
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
    
    // Hide all tabs
    sessionsTab.classList.remove('active');
    admissionTab.classList.remove('active');
    dischargeTab.classList.remove('active');
    sessionsBtn.classList.remove('active');
    admissionBtn.classList.remove('active');
    dischargeBtn.classList.remove('active');
    
    // Show selected tab
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