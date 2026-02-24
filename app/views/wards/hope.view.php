<?php $title = $ward . ' Ward - Clinical Console'; ?>

<!-- Success/Error Messages - Modern styling -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success animate-slide">
        <div class="alert-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                <path d="M8 12l3 3 6-6" stroke="currentColor" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="alert-content"><?= $_SESSION['success'] ?></div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error animate-slide">
        <div class="alert-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                <path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-linecap="round"/>
            </svg>
        </div>
        <div class="alert-content"><?= $_SESSION['error'] ?></div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">✕</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Header with Title and Actions - Modern design -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= e($ward) ?> Ward</h1>
        <p class="page-subtitle">Clinical management console</p>
    </div>
    <div class="header-actions">
        <a href="<?= url('dashboard') ?>" class="btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Dashboard
        </a>
        <button onclick="openAdmitModal()" class="btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Admit Patient
        </button>
    </div>
</div>

<!-- ===============================
WARD OVERVIEW - Modern Stats Cards
=================================-->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #3b82f6);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= count($patients) ?></span>
            <span class="stat-label">Active Patients</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= $stats['available_beds'] ?></span>
            <span class="stat-label">Beds Available</span>
        </div>
        <div class="stat-footer">of <?= ($ward === 'Hope') ? 12 : 10 ?> total</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= $stats['sessions_today'] ?></span>
            <span class="stat-label">Sessions Today</span>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M18 4v16M8 4v16M4 8h16M4 16h16"/>
            </svg>
        </div>
        <div class="stat-content">
            <span class="stat-value"><?= $stats['discharged_this_month'] ?></span>
            <span class="stat-label">Discharged</span>
        </div>
        <div class="stat-footer">this month</div>
    </div>
</div>

<!-- ===============================
PATIENT LIST - Modern Card Design
=================================-->
<div class="patients-header">
    <h2 class="section-title">Active Patients</h2>
    <div class="search-filters">
        <div class="search-box">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.3-4.3"/>
            </svg>
            <input type="text" id="patientSearch" placeholder="Search by initials..." class="search-input">
        </div>
        <select id="roomFilter" class="filter-select">
            <option value="">All Rooms</option>
            <?php for($i = 1; $i <= (($ward === 'Hope') ? 12 : 10); $i++): ?>
                <option value="<?= $i ?>">Room <?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>
</div>

<?php if($patients): ?>
    <div class="patients-grid">
        <?php foreach($patients as $index => $p): ?>
            <div class="patient-card" 
                 data-patient-id="<?= $p->id ?>"
                 data-initials="<?= strtoupper(e($p->initials)) ?>" 
                 data-room="<?= $p->room_number ?>">
                
                <div class="patient-card-header">
                    <div class="patient-avatar">
                        <?= e($p->initials) ?>
                    </div>
                    <div class="patient-status">
                        <span class="room-badge">Room <?= e($p->room_number) ?></span>
                        <button onclick="openEditRoomModal(<?= $p->id ?>, <?= $p->room_number ?>)" class="icon-btn" title="Change Room">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="patient-info">
                    <div class="info-row">
                        <span class="info-label">Admitted</span>
                        <span class="info-value"><?= date('d M Y', strtotime($p->admission_date)) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">CORE-10</span>
                        <span class="info-value">
                            <?php if($p->core10_admission): ?>
                                <span class="badge badge-success">Completed</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="patient-actions">
                    <button onclick="selectPatient(<?= $p->id ?>, '<?= e($p->initials) ?>')" class="btn-icon btn-primary" title="New Session">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                        <span>Session</span>
                    </button>
                    
                    <button onclick="viewPatientDetails(<?= $p->id ?>, '<?= e($p->initials) ?>')" class="btn-icon" title="View Details">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M22 12c-2.667 4.667-6 7-10 7s-7.333-2.333-10-7c2.667-4.667 6-7 10-7s7.333 2.333 10 7z"/>
                        </svg>
                        <span>View</span>
                    </button>
                    
                    <div class="action-dropdown">
                        <button class="btn-icon btn-more">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="1"/>
                                <circle cx="12" cy="5" r="1"/>
                                <circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            <button onclick="openDischargeModal(<?= $p->id ?>, '<?= e($p->initials) ?>', <?= $p->room_number ?>, '<?= date('d/m/Y', strtotime($p->admission_date)) ?>', <?= $p->core10_admission ? 'true' : 'false' ?>)" class="dropdown-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 4v16M8 4v16M4 8h16M4 16h16"/>
                                </svg>
                                Discharge
                            </button>
                            <form method="POST" action="<?= url('patients/archive') ?>" class="inline-form">
                                <input type="hidden" name="id" value="<?= $p->id ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="ward" value="<?= $ward ?>">
                                <button type="submit" class="dropdown-item" onclick="return confirm('Archive this patient?')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="6" width="20" height="16" rx="2"/>
                                        <path d="M8 10h8M8 14h4"/>
                                    </svg>
                                    Archive
                                </button>
                            </form>
                            <form method="POST" action="<?= url('patients/delete') ?>" class="inline-form">
                                <input type="hidden" name="id" value="<?= $p->id ?>">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('⚠️ Permanently delete?')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                $patientSessions = array_filter($sessions ?? [], fn($s) => $s->patient_id == $p->id);
                if($patientSessions):
                    $recentSessions = array_slice($patientSessions, 0, 2);
                ?>
                    <div class="session-preview">
                        <div class="preview-header">
                            <span>Recent Sessions</span>
                            <button onclick="viewPatientDetails(<?= $p->id ?>, '<?= e($p->initials) ?>')" class="view-all-link">View all →</button>
                        </div>
                        <?php foreach($recentSessions as $session): ?>
                            <div class="preview-item">
                                <span class="session-time"><?= date('d M H:i', strtotime($session->datetime)) ?></span>
                                <span class="session-icons">
                                    <?= $session->carenotes_completed ? '📋' : '' ?>
                                    <?= $session->tracker_completed ? '📊' : '' ?>
                                    <?= $session->tasks_completed ? '✅' : '' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1">
                <circle cx="12" cy="8" r="4"/>
                <path d="M5 14h14M5 18h14"/>
            </svg>
        </div>
        <h3>No Active Patients</h3>
        <p>There are no patients currently admitted to <?= e($ward) ?> ward.</p>
        <button onclick="openAdmitModal()" class="btn-primary">Admit First Patient</button>
    </div>
<?php endif; ?>

<!-- ===============================
QUICK LINKS SECTION - Modern Cards
=================================-->
<div class="quick-links-section">
    <h2 class="section-title">Patient Records</h2>
    
    <div class="quick-links-grid">
        <!-- Archived Patients Card -->
        <a href="<?= url('wards/' . strtolower($ward) . '/archived-patients') ?>" class="quick-link-card">
            <div class="quick-link-icon" style="background: linear-gradient(135deg, #f97316, #fb923c);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <rect x="2" y="6" width="20" height="16" rx="2"/>
                    <path d="M8 10h8M8 14h4"/>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Archived Patients</h3>
                <p>View archived patient records</p>
                <span class="quick-link-count"><?= count($archivedPatients ?? []) ?> records</span>
            </div>
            <div class="quick-link-arrow">→</div>
        </a>
        
        <!-- Archived Sessions Card -->
        <a href="<?= url('wards/' . strtolower($ward) . '/archived-sessions') ?>" class="quick-link-card">
            <div class="quick-link-icon" style="background: linear-gradient(135deg, #3b82f6, #60a5fa);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Archived Sessions</h3>
                <p>View archived session records</p>
                <span class="quick-link-count"><?= count($archivedSessions ?? []) ?> records</span>
            </div>
            <div class="quick-link-arrow">→</div>
        </a>
        
        <!-- Discharged Patients Card -->
        <a href="<?= url('wards/' . strtolower($ward) . '/discharged-patients') ?>" class="quick-link-card">
            <div class="quick-link-icon" style="background: linear-gradient(135deg, #64748b, #94a3b8);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M18 4v16M8 4v16M4 8h16M4 16h16"/>
                </svg>
            </div>
            <div class="quick-link-content">
                <h3>Discharged Patients</h3>
                <p>View discharged patient records</p>
                <span class="quick-link-count"><?= count($discharged ?? []) ?> records</span>
            </div>
            <div class="quick-link-arrow">→</div>
        </a>
    </div>
</div>

<!-- ===============================
MODERN MODALS - All preserved with updated styling
=================================-->

<!-- Edit Room Modal -->
<div id="editRoomModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change Patient Room</h2>
            <button type="button" class="modal-close" onclick="closeEditRoomModal()">✕</button>
        </div>
        <form method="POST" action="<?= url('patients/update-room') ?>" class="modal-form">
            <input type="hidden" name="patient_id" id="editPatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div class="form-group">
                <label>New Room Number</label>
                <select name="room_number" id="new_room_number" required>
                    <option value="">Select Room</option>
                    <?php for($i = 1; $i <= (($ward === 'Hope') ? 12 : 10); $i++): ?>
                        <option value="<?= $i ?>">Room <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Reason for change (optional)</label>
                <textarea name="reason" rows="2" placeholder="e.g., Clinical need, patient request..."></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="closeEditRoomModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Update Room</button>
            </div>
        </form>
    </div>
</div>

<!-- Patient Details Modal -->
<div id="patientDetailsModal" class="modal" style="display:none;">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Patient: <span id="viewPatientName"></span></h2>
            <button type="button" class="modal-close" onclick="closePatientDetailsModal()">✕</button>
        </div>

        <div class="patient-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Ward</span>
                    <span class="summary-value" id="viewPatientWard"><?= e($ward) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Room</span>
                    <span class="summary-value" id="viewPatientRoom"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Admitted</span>
                    <span class="summary-value" id="viewPatientAdmission"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">CORE-10</span>
                    <span class="summary-value" id="viewPatientCore"></span>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('sessions')" id="sessionsTabBtn">📋 All Sessions</button>
            <button class="tab-btn" onclick="switchTab('admission')" id="admissionTabBtn">📝 Admission Notes</button>
            <button class="tab-btn" onclick="switchTab('discharge')" id="dischargeTabBtn">🚪 Discharge Notes</button>
        </div>

        <div id="sessionsTab" class="tab-pane active">
            <div id="sessionsList" class="sessions-list"></div>
        </div>
        <div id="admissionTab" class="tab-pane">
            <div id="admissionNotes" class="notes-card"></div>
        </div>
        <div id="dischargeTab" class="tab-pane">
            <div id="dischargeNotes" class="notes-card"></div>
        </div>

        <div class="modal-actions">
            <button type="button" onclick="closePatientDetailsModal()" class="btn-secondary">Close</button>
            <button onclick="newSessionFromView()" class="btn-primary">➕ New Session</button>
        </div>
    </div>
</div>

<!-- Discharge Modal -->
<div id="dischargeModal" class="modal" style="display:none;">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Discharge Patient: <span id="dischargePatientName"></span></h2>
            <button type="button" class="modal-close" onclick="closeDischargeModal()">✕</button>
        </div>

        <div class="patient-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">Ward</span>
                    <span class="summary-value" id="dischargePatientWard"><?= e($ward) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Room</span>
                    <span class="summary-value" id="dischargePatientRoom"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Admitted</span>
                    <span class="summary-value" id="dischargePatientAdmission"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">CORE-10</span>
                    <span class="summary-value" id="dischargePatientCore"></span>
                </div>
            </div>
        </div>

        <div class="stats-mini">
            <div class="stat-mini">
                <span class="stat-mini-label">Total Sessions</span>
                <span class="stat-mini-value" id="dischargeSessionCount">0</span>
            </div>
            <div class="stat-mini">
                <span class="stat-mini-label">CareNotes</span>
                <span class="stat-mini-value" id="dischargeCareNotesCount">0</span>
            </div>
            <div class="stat-mini">
                <span class="stat-mini-label">Trackers</span>
                <span class="stat-mini-value" id="dischargeTrackersCount">0</span>
            </div>
            <div class="stat-mini">
                <span class="stat-mini-label">Tasks</span>
                <span class="stat-mini-value" id="dischargeTasksCount">0</span>
            </div>
        </div>

        <div class="recent-preview">
            <h4>Recent Sessions</h4>
            <div id="dischargeRecentSessions" class="preview-list"></div>
        </div>

        <form method="POST" action="<?= url('patients/discharge') ?>" class="modal-form">
            <input type="hidden" name="patient_id" id="dischargePatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <label class="checkbox-label">
                <input type="checkbox" name="core10_discharge" checked>
                <span>CORE-10 completed at discharge</span>
            </label>

            <div class="form-group">
                <label>Discharge Notes</label>
                <textarea name="notes" rows="4" placeholder="Enter discharge summary and follow-up plans..."></textarea>
            </div>

            <div class="warning-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v5M12 16h.01"/>
                </svg>
                <p>Patient will be moved to discharged list. Can be readmitted later.</p>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeDischargeModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-danger">Confirm Discharge</button>
            </div>
        </form>
    </div>
</div>

<!-- Admit Modal -->
<div id="admitModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Admit Patient to <?= e($ward) ?> Ward</h2>
            <button type="button" class="modal-close" onclick="closeAdmitModal()">✕</button>
        </div>

        <form method="POST" action="<?= url('patients/store') ?>" class="modal-form">
            <input type="hidden" name="ward" value="<?= e($ward) ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Room Number</label>
                <select name="room_number" required>
                    <option value="">Select Room</option>
                    <?php for($i = 1; $i <= (($ward === 'Hope') ? 12 : 10); $i++): ?>
                        <option value="<?= $i ?>">Room <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Patient Initials</label>
                <input type="text" name="initials" maxlength="3" placeholder="e.g., JD" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Admission Date</label>
                    <input type="date" name="admission_date" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="core10_admission">
                        <span>CORE-10 completed</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Clinical Notes</label>
                <textarea name="notes" rows="3" placeholder="Add admission notes..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeAdmitModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Admit Patient</button>
            </div>
        </form>
    </div>
</div>

<!-- Session Panel -->
<div id="sessionPanel" class="session-panel" style="display:none;">
    <div class="session-panel-content">
        <div class="session-panel-header">
            <h3>Record Session for <span id="patientName"></span></h3>
            <button onclick="closeSessionPanel()" class="icon-btn">✕</button>
        </div>

        <form method="POST" action="<?= url('sessions/store') ?>">
            <input type="hidden" name="patient_id" id="sessionPatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Date & Time</label>
                <input type="datetime-local" name="datetime" value="<?= date('Y-m-d\TH:i') ?>" required>
            </div>

            <div class="checkbox-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="carenotes"> CareNotes
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tracker"> Tracker
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tasks"> Tasks
                </label>
            </div>

            <div class="form-group">
                <label>Session Notes</label>
                <textarea name="notes" rows="3" placeholder="Document session..."></textarea>
            </div>

            <div class="form-actions">
                <button type="button" onclick="closeSessionPanel()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save Session</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODERN STYLES ===== -->
<style>
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
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    --radius: 12px;
    --radius-sm: 8px;
}

/* Animations */
@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.animate-slide {
    animation: slideIn 0.3s ease;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 5px 0;
    letter-spacing: -0.5px;
}

.page-subtitle {
    color: var(--gray-500);
    margin: 0;
    font-size: 15px;
}

/* Buttons */
.btn-primary, .btn-outline, .btn-secondary, .btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: var(--primary);
    color: white;
    box-shadow: var(--shadow);
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: white;
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.btn-outline:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
}

.btn-secondary:hover {
    background: var(--gray-200);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    border: 1px solid var(--gray-100);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-content {
    flex: 1;
}

.stat-value {
    display: block;
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-800);
    line-height: 1.2;
}

.stat-label {
    font-size: 13px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.stat-footer {
    font-size: 12px;
    color: var(--gray-400);
    margin-top: 4px;
}

/* Patients Header */
.patients-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--gray-200), transparent);
}

.search-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box svg {
    position: absolute;
    left: 14px;
    pointer-events: none;
}

.search-input {
    padding: 12px 16px 12px 42px;
    border: 1px solid var(--gray-200);
    border-radius: 40px;
    font-size: 14px;
    width: 240px;
    transition: all 0.3s;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.filter-select {
    padding: 12px 20px;
    border: 1px solid var(--gray-200);
    border-radius: 40px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    min-width: 140px;
}

/* Patients Grid */
.patients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

.patient-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s;
    border: 1px solid var(--gray-100);
}

.patient-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.patient-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.patient-avatar {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
    color: white;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    box-shadow: var(--shadow);
}

.patient-status {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.room-badge {
    background: var(--gray-100);
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-700);
}

.icon-btn {
    background: none;
    border: none;
    padding: 8px;
    border-radius: 8px;
    color: var(--gray-500);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-btn:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.patient-info {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
}

.info-row {
    display: flex;
    margin-bottom: 8px;
    font-size: 14px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    width: 80px;
    color: var(--gray-500);
}

.info-value {
    flex: 1;
    color: var(--gray-800);
    font-weight: 500;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fed7aa;
    color: #92400e;
}

/* Patient Actions */
.patient-actions {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    position: relative;
}

.btn-icon {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    background: white;
    color: var(--gray-700);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-icon.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-icon.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-icon:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    transform: translateY(-2px);
}

/* Dropdown Menu */
.action-dropdown {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    min-width: 160px;
    z-index: 10;
    display: none;
}

.action-dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: none;
    color: var(--gray-700);
    font-size: 13px;
    text-align: left;
    cursor: pointer;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background: var(--gray-50);
}

.dropdown-item.text-danger {
    color: var(--danger);
}

/* Session Preview */
.session-preview {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 16px;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 12px;
    color: var(--gray-500);
    font-weight: 600;
    letter-spacing: 0.3px;
}

.view-all-link {
    background: none;
    border: none;
    color: var(--primary);
    font-size: 11px;
    cursor: pointer;
    text-decoration: underline;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 12px;
    color: var(--gray-600);
    border-bottom: 1px solid var(--gray-200);
}

.preview-item:last-child {
    border-bottom: none;
}

/* Quick Links */
.quick-links-section {
    margin: 40px 0;
}

.quick-links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.quick-link-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
    text-decoration: none;
    transition: all 0.3s;
}

.quick-link-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--gray-200);
}

.quick-link-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-link-content {
    flex: 1;
}

.quick-link-content h3 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--gray-800);
}

.quick-link-content p {
    margin: 0 0 4px 0;
    font-size: 12px;
    color: var(--gray-500);
}

.quick-link-count {
    font-size: 11px;
    font-weight: 600;
    color: var(--primary);
}

.quick-link-arrow {
    font-size: 20px;
    color: var(--gray-400);
    transition: all 0.3s;
}

.quick-link-card:hover .quick-link-arrow {
    transform: translateX(5px);
    color: var(--primary);
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
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 24px;
    padding: 28px;
    max-width: 500px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
    animation: slideIn 0.3s ease;
}

.modal-lg {
    max-width: 800px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    color: var(--gray-800);
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--gray-500);
    padding: 5px;
}

.modal-close:hover {
    color: var(--gray-700);
}

.modal-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Form Elements */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-weight: 500;
    color: var(--gray-700);
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.3s;
    background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.form-row {
    display: flex;
    gap: 15px;
    align-items: flex-end;
}

.form-row .form-group {
    flex: 1;
}

.checkbox-group {
    display: flex;
    align-items: center;
    padding-bottom: 12px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    cursor: pointer;
    color: var(--gray-700);
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

/* Session Panel */
.session-panel {
    position: fixed;
    bottom: 0;
    right: 20px;
    width: 380px;
    z-index: 900;
    animation: slideIn 0.3s ease;
}

.session-panel-content {
    background: white;
    border-radius: 20px 20px 0 0;
    padding: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
}

.session-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.session-panel-header h3 {
    margin: 0;
    font-size: 18px;
    color: var(--gray-800);
}

.checkbox-row {
    display: flex;
    gap: 20px;
    margin: 15px 0;
}

/* Patient Summary */
.patient-summary {
    background: var(--gray-50);
    border-radius: 16px;
    padding: 16px;
    margin: 16px 0;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
}

.summary-item {
    text-align: left;
}

.summary-label {
    display: block;
    font-size: 11px;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}

.summary-value {
    font-size: 15px;
    font-weight: 600;
    color: var(--gray-800);
}

/* Tabs */
.tabs {
    display: flex;
    gap: 10px;
    margin: 20px 0;
    border-bottom: 2px solid var(--gray-200);
    padding-bottom: 10px;
}

.tab-btn {
    padding: 8px 16px;
    background: none;
    border: none;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-500);
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s;
}

.tab-btn:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.tab-btn.active {
    background: var(--primary);
    color: white;
}

.tab-pane {
    display: none;
    max-height: 400px;
    overflow-y: auto;
}

.tab-pane.active {
    display: block;
}

/* Stats Mini */
.stats-mini {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin: 20px 0;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 16px;
}

.stat-mini {
    text-align: center;
}

.stat-mini-label {
    display: block;
    font-size: 10px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.stat-mini-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: var(--gray-800);
}

/* Warning Message */
.warning-message {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #fff3cd;
    border: 1px solid #ffeeba;
    border-radius: 12px;
    color: #856404;
    font-size: 13px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-100);
}

.empty-icon {
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 18px;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--gray-500);
    margin-bottom: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        gap: 12px;
    }
    
    .header-actions .btn-primary,
    .header-actions .btn-outline {
        flex: 1;
        justify-content: center;
    }
    
    .search-filters {
        width: 100%;
        flex-direction: column;
    }
    
    .search-box,
    .search-input,
    .filter-select {
        width: 100%;
    }
    
    .patients-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-mini {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .tabs {
        flex-direction: column;
    }
    
    .session-panel {
        width: 100%;
        right: 0;
        bottom: 0;
    }
}
</style>


<!-- ===== JAVASCRIPT ===== -->
<script>
// Store current patient ID for view modal
let currentViewPatientId = null;
let currentViewPatientName = '';

// ===== ADMIT MODAL FUNCTIONS =====
function openAdmitModal() {
    document.getElementById('admitModal').style.display = 'flex';
}

function closeAdmitModal() {
    document.getElementById('admitModal').style.display = 'none';
}

// ===== EDIT ROOM FUNCTIONS =====
function openEditRoomModal(patientId, currentRoom) {
    document.getElementById('editPatientId').value = patientId;
    
    // Pre-select current room
    const roomSelect = document.getElementById('new_room_number');
    for (let i = 0; i < roomSelect.options.length; i++) {
        if (roomSelect.options[i].value == currentRoom) {
            roomSelect.options[i].selected = true;
            break;
        }
    }
    
    document.getElementById('editRoomModal').style.display = 'flex';
}

function closeEditRoomModal() {
    document.getElementById('editRoomModal').style.display = 'none';
}

// ===== DISCHARGE MODAL FUNCTIONS =====
function openDischargeModal(patientId, initials, room, admissionDate, core10Admission) {
    currentViewPatientId = patientId;
    document.getElementById('dischargePatientName').innerText = initials;
    document.getElementById('dischargePatientId').value = patientId;
    document.getElementById('dischargePatientRoom').innerText = room;
    document.getElementById('dischargePatientAdmission').innerText = admissionDate;
    document.getElementById('dischargePatientCore').innerHTML = core10Admission ? 
        '<span class="core-done">Done</span>' : 
        '<span class="core-pending">Pending</span>';
    
    // Load patient sessions for stats
    loadDischargeSessionData(patientId);
    
    // Show modal
    document.getElementById('dischargeModal').style.display = 'flex';
}

function closeDischargeModal() {
    document.getElementById('dischargeModal').style.display = 'none';
    document.getElementById('dischargeForm').reset();
}

function loadDischargeSessionData(patientId) {
    fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            // Update stats
            document.getElementById('dischargeSessionCount').innerText = data.length;
            
            const careNotesCount = data.filter(s => s.carenotes_completed).length;
            const trackersCount = data.filter(s => s.tracker_completed).length;
            const tasksCount = data.filter(s => s.tasks_completed).length;
            
            document.getElementById('dischargeCareNotesCount').innerText = careNotesCount;
            document.getElementById('dischargeTrackersCount').innerText = trackersCount;
            document.getElementById('dischargeTasksCount').innerText = tasksCount;
            
            // Show recent sessions
            displayRecentSessions(data.slice(0, 3));
        })
        .catch(error => {
            console.error('Error loading sessions:', error);
        });
}

function displayRecentSessions(sessions) {
    const container = document.getElementById('dischargeRecentSessions');
    
    if (!sessions || sessions.length === 0) {
        container.innerHTML = '<div class="no-sessions">No sessions recorded</div>';
        return;
    }
    
    let html = '';
    sessions.forEach(s => {
        const date = new Date(s.datetime);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        html += `<div class="preview-session-item">
            <span class="preview-session-date">${formattedDate}</span>
            <span class="preview-session-icons">
                ${s.carenotes_completed ? '📋' : '⬜'}
                ${s.tracker_completed ? '📊' : '⬜'}
                ${s.tasks_completed ? '✅' : '⬜'}
            </span>
        </div>`;
    });
    
    container.innerHTML = html;
}

// ===== PATIENT DETAILS FUNCTIONS =====
function viewPatientDetails(patientId, patientName) {
    currentViewPatientId = patientId;
    currentViewPatientName = patientName;
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
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';
            document.getElementById('viewPatientAdmission').innerText = data.admission_date || 'N/A';
            document.getElementById('viewPatientCore').innerHTML = data.core10_admission ? 
                '<span class="core-done">Done</span>' : 
                '<span class="core-pending">Pending</span>';
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
        container.innerHTML = '<div class="no-sessions">No sessions recorded for this patient</div>';
        return;
    }
    
    let html = '<table class="sessions-table">';
    html += '<thead><tr><th>Date & Time</th><th>CareNotes</th><th>Tracker</th><th>Tasks</th><th>Notes</th><th>Actions</th></tr></thead>';
    html += '<tbody>';
    
    sessions.forEach(s => {
        const date = new Date(s.datetime);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        html += `<tr>
            <td>${formattedDate}</td>
            <td class="status-icon">${s.carenotes_completed ? '✅' : '❌'}</td>
            <td class="status-icon">${s.tracker_completed ? '✅' : '❌'}</td>
            <td class="status-icon">${s.tasks_completed ? '✅' : '❌'}</td>
            <td class="notes-cell" title="${s.notes || ''}">${s.notes ? s.notes.substring(0, 30) + (s.notes.length > 30 ? '...' : '') : '-'}</td>
            <td class="session-actions">
                <form method="POST" action="<?= url('sessions/archive') ?>" class="inline-form" onsubmit="return confirm('Archive this session?')">
                    <input type="hidden" name="id" value="${s.id}">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="ward" value="<?= $ward ?>">
                    <button type="submit" class="action-link archive-link" title="Archive Session">📦</button>
                </form>
                <form method="POST" action="<?= url('sessions/delete') ?>" class="inline-form" onsubmit="return confirm('⚠️ Permanently delete this session?')">
                    <input type="hidden" name="id" value="${s.id}">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="action-link delete-link" title="Delete Session">🗑️</button>
                </form>
            </td>
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
                const formattedNotes = data.notes.replace(/\n/g, '<br>');
                container.innerHTML = `<div class="notes-content">${formattedNotes}</div>`;
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
                const formattedNotes = data.notes.replace(/\n/g, '<br>');
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

// Tab switching function
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

function newSessionFromView() {
    closePatientDetailsModal();
    selectPatient(currentViewPatientId, currentViewPatientName);
}

// ===== SESSION PANEL FUNCTIONS =====
function selectPatient(id, name) {
    document.getElementById('sessionPanel').style.display = 'block';
    document.getElementById('sessionPatientId').value = id;
    document.getElementById('patientName').innerText = name;
    window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
}

function closeSessionPanel() {
    document.getElementById('sessionPanel').style.display = 'none';
}

// ===== CLOSE MODALS WHEN CLICKING OUTSIDE =====
window.onclick = function(event) {
    const admitModal = document.getElementById('admitModal');
    const editModal = document.getElementById('editRoomModal');
    const detailsModal = document.getElementById('patientDetailsModal');
    const dischargeModal = document.getElementById('dischargeModal');
    
    if (event.target == admitModal) {
        admitModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
    if (event.target == detailsModal) {
        detailsModal.style.display = 'none';
    }
    if (event.target == dischargeModal) {
        dischargeModal.style.display = 'none';
    }
}
</script>