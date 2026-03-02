<?php $title = $ward . ' Ward - Clinical Console'; ?>

<style>
/* ===== SAME LAYOUT AS ACTIVITIES PAGE ===== */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f0f2f5;
    color: var(--gray-800);
    line-height: 1.5;
    margin: 0 auto;
    padding: 30px;
    max-width: 1400px;
}

/* Shared container for all content (including navbar now) */
.content-container {
    width: 100%;
}

/* ===== NAVBAR INSIDE CONTAINER ===== */
.navbar {
    background: linear-gradient(90deg, #1e3a8a, #2563eb);
    border-radius: 12px;
    margin-bottom: 30px;
    padding: 0.5rem 1rem;
}

.navbar-brand {
    color: white;
    font-weight: bold;
    font-size: 1.5rem;
}

.navbar-nav .nav-link {
    color: rgba(255,255,255,0.9) !important;
    font-weight: 500;
}

.navbar-nav .nav-link:hover {
    color: white !important;
}

.dropdown-menu {
    border-radius: 8px;
    box-shadow: var(--shadow);
}


</style>

<!-- Full content container (centers everything) -->
<div class="content-container">


    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success animate-slide" id="successAlert">
            <div class="alert-icon"></div>
            <div class="alert-content"><?= $_SESSION['success'] ?></div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error animate-slide" id="errorAlert">
            <div class="alert-icon"></div>
            <div class="alert-content"><?= $_SESSION['error'] ?></div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Header -->
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
                <span class="btn-text">Dashboard</span>
            </a>
            <button onclick="openAdmitModal()" class="btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span class="btn-text">Admit Patient</span>
            </button>
        </div>
    </div>

    <!-- Ward Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon hope">
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
            <div class="stat-icon beds">
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
            <div class="stat-icon sessions">
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
            <div class="stat-icon discharged">
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

    <!-- CORE-10 Stats (separate) -->
    <div class="stats-grid secondary-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M12 2v4M12 22v-4M4 12H2M8 12H6M18 12h-2M22 12h-2M19.07 4.93l-2.83 2.83M4.93 19.07l2.83-2.83M19.07 19.07l-2.83-2.83M4.93 4.93l2.83 2.83"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value" id="admissionCore10Count"><?= $stats['core10_admission_completed'] ?? $stats['core10_completed'] ?? 0 ?>/<?= count($patients) ?></span>
                <span class="stat-label">Admission CORE-10</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #be185d);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M18 4v16M8 4v16M4 8h16M4 16h16"/>
                </svg>
            </div>
            <div class="stat-content">
                <span class="stat-value" id="dischargeCore10Count"><?= isset($stats['core10_discharge_completed']) ? $stats['core10_discharge_completed'] : 0 ?>/<?= $totalDischarged ?? 0 ?></span>
                <span class="stat-label">Discharge CORE-10</span>
            </div>
        </div>
    </div>

    <!-- Patient List -->
    <div class="patients-header">
        <h2 class="section-title">Active Patients</h2>
        <div class="search-filters">
            <div class="search-box">
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
        <div class="patients-grid" id="patientsGrid">
            <?php foreach($patients as $p): ?>
                <?php
                $dischargeCompleted = property_exists($p, 'core10_discharge') && $p->core10_discharge;
                $admissionCompleted = property_exists($p, 'core10_admission') && $p->core10_admission;
                ?>
                <div class="patient-card"
                     data-patient-id="<?= $p->id ?>"
                     data-initials="<?= strtoupper(e($p->initials)) ?>"
                     data-room="<?= $p->room_number ?>"
                     data-core10-discharge="<?= $dischargeCompleted ? '1' : '0' ?>">

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
                        <div class="info-row core10-row">
                            <span class="info-label">CORE-10</span>
                            <div class="core10-badges">
                                <span class="core10-badge admission-badge <?= $admissionCompleted ? 'completed' : 'pending' ?>">
                                    Admission: <?= $admissionCompleted ? '✓' : '○' ?>
                                </span>
                                <span class="core10-badge discharge-badge <?= $dischargeCompleted ? 'completed' : 'pending' ?>">
                                    Discharge: <?= $dischargeCompleted ? '✓' : '○' ?>
                                </span>
                            </div>
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

                        <div class="dropdown-container">
                            <button class="btn-icon dropdown-toggle" onclick="toggleDropdown(this)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="1"/>
                                    <circle cx="12" cy="5" r="1"/>
                                    <circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu-custom">
                                <button onclick="openDischargeModal(<?= $p->id ?>, '<?= e($p->initials) ?>', <?= $p->room_number ?>, '<?= date('d/m/Y', strtotime($p->admission_date)) ?>', <?= $admissionCompleted ? 'true' : 'false' ?>, <?= $dischargeCompleted ? 'true' : 'false' ?>)" class="dropdown-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 4v16M8 4v16M4 8h16M4 16h16"/>
                                    </svg>
                                    Discharge
                                </button>
                                <form method="POST" action="<?= url('patients/archive') ?>" class="dropdown-form">
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
                                <form method="POST" action="<?= url('patients/delete') ?>" class="dropdown-form">
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

    <!-- Quick Links -->
    <div class="quick-links-section">
        <h2 class="section-title">Patient Records</h2>

        <div class="quick-links-grid">
            <a href="<?= url('wards/' . strtolower($ward) . '/archived-patients') ?>" class="quick-link-card">
                <div class="quick-link-icon archived-patients">
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

            <a href="<?= url('wards/' . strtolower($ward) . '/archived-sessions') ?>" class="quick-link-card">
                <div class="quick-link-icon archived-sessions">
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

            <a href="<?= url('wards/' . strtolower($ward) . '/discharged-patients') ?>" class="quick-link-card">
                <div class="quick-link-icon discharged">
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
</div> <!-- end .content-container -->

<!-- Modals (outside container) -->
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
                    <span class="summary-label">Admission CORE-10</span>
                    <span class="summary-value" id="viewPatientAdmissionCore"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Discharge CORE-10</span>
                    <span class="summary-value" id="viewPatientDischargeCore"></span>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('sessions')" id="sessionsTabBtn">📋 All Sessions</button>
            <button class="tab-btn" onclick="switchTab('admission')" id="admissionTabBtn">📝 Admission Notes</button>
            <button class="tab-btn" onclick="switchTab('discharge')" id="dischargeTabBtn">🚪 Discharge Notes</button>
        </div>

        <div id="sessionsTab" class="tab-pane active">
            <div id="sessionsList" class="sessions-list">
                <div class="loading">Loading sessions...</div>
            </div>
        </div>
        <div id="admissionTab" class="tab-pane">
            <div id="admissionNotes" class="notes-card">
                <div class="loading">Loading admission notes...</div>
            </div>
        </div>
        <div id="dischargeTab" class="tab-pane">
            <div id="dischargeNotes" class="notes-card">
                <div class="loading">Loading discharge notes...</div>
            </div>
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
                    <span class="summary-label">Admission CORE-10</span>
                    <span class="summary-value" id="dischargePatientAdmissionCore"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Discharge CORE-10</span>
                    <span class="summary-value" id="dischargePatientDischargeCore"></span>
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

        <form method="POST" action="<?= url('patients/discharge') ?>" class="modal-form" id="dischargeForm">
            <input type="hidden" name="patient_id" id="dischargePatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="core10-complete-row" id="core10CompleteRow">
                <!-- Populated by JS -->
            </div>

            <div class="form-group">
                <label>Discharge Notes</label>
                <textarea name="notes" rows="4" placeholder="Enter discharge summary and follow-up plans..."></textarea>
            </div>

            <div class="warning-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v5M12 16h.01"/>
                </svg>
                <p>Patient will be moved to discharged list.</p>
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
                        <span>CORE-10 completed on admission</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
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
            <button onclick="closeSessionPanel()" class="icon-btn close-btn">✕</button>
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

<!-- Edit Session Modal -->
<div id="editSessionModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Session</h2>
            <button type="button" class="modal-close" onclick="closeEditSessionModal()">✕</button>
        </div>

        <form method="POST" action="<?= url('sessions/update') ?>" class="modal-form" id="editSessionForm">
            <input type="hidden" name="session_id" id="editSessionId">
            <input type="hidden" name="patient_id" id="editSessionPatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Session Date & Time</label>
                <input type="datetime-local" name="datetime" id="editSessionDatetime" required>
            </div>

            <div class="checkbox-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="carenotes" id="editSessionCarenotes">
                    <span>CareNotes Completed</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tracker" id="editSessionTracker">
                    <span>Tracker Completed</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tasks" id="editSessionTasks">
                    <span>Tasks Completed</span>
                </label>
            </div>

            <div class="form-group">
                <label>Session Notes</label>
                <textarea name="notes" id="editSessionNotes" rows="4" placeholder="Session notes..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeEditSessionModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Update Session</button>
            </div>
        </form>
    </div>

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

<!-- Patient Details Modal (updated with two CORE-10 fields) -->
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
                    <span class="summary-label">Admission CORE-10</span>
                    <span class="summary-value" id="viewPatientAdmissionCore"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Discharge CORE-10</span>
                    <span class="summary-value" id="viewPatientDischargeCore"></span>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('sessions')" id="sessionsTabBtn">📋 All Sessions</button>
            <button class="tab-btn" onclick="switchTab('admission')" id="admissionTabBtn">📝 Admission Notes</button>
            <button class="tab-btn" onclick="switchTab('discharge')" id="dischargeTabBtn">🚪 Discharge Notes</button>
        </div>

        <div id="sessionsTab" class="tab-pane active">
            <div id="sessionsList" class="sessions-list">
                <div class="loading">Loading sessions...</div>
            </div>
        </div>
        <div id="admissionTab" class="tab-pane">
            <div id="admissionNotes" class="notes-card">
                <div class="loading">Loading admission notes...</div>
            </div>
        </div>
        <div id="dischargeTab" class="tab-pane">
            <div id="dischargeNotes" class="notes-card">
                <div class="loading">Loading discharge notes...</div>
            </div>
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
                    <span class="summary-label">Admission CORE-10</span>
                    <span class="summary-value" id="dischargePatientAdmissionCore"></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Discharge CORE-10</span>
                    <span class="summary-value" id="dischargePatientDischargeCore"></span>
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

        <form method="POST" action="<?= url('patients/discharge') ?>" class="modal-form" id="dischargeForm">
            <input type="hidden" name="patient_id" id="dischargePatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="core10-complete-row" id="core10CompleteRow">
                <!-- Populated by JS -->
            </div>

            <div class="form-group">
                <label>Discharge Notes</label>
                <textarea name="notes" rows="4" placeholder="Enter discharge summary and follow-up plans..."></textarea>
            </div>

            <div class="warning-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v5M12 16h.01"/>
                </svg>
                <p>Patient will be moved to discharged list.</p>
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
                        <span>CORE-10 completed on admission</span>
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
            <button onclick="closeSessionPanel()" class="icon-btn close-btn">✕</button>
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

<!-- Edit Session Modal -->
<div id="editSessionModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Session</h2>
            <button type="button" class="modal-close" onclick="closeEditSessionModal()">✕</button>
        </div>

        <form method="POST" action="<?= url('sessions/update') ?>" class="modal-form" id="editSessionForm">
            <input type="hidden" name="session_id" id="editSessionId">
            <input type="hidden" name="patient_id" id="editSessionPatientId">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Session Date & Time</label>
                <input type="datetime-local" name="datetime" id="editSessionDatetime" required>
            </div>

            <div class="checkbox-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="carenotes" id="editSessionCarenotes">
                    <span>CareNotes Completed</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tracker" id="editSessionTracker">
                    <span>Tracker Completed</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="tasks" id="editSessionTasks">
                    <span>Tasks Completed</span>
                </label>
            </div>

            <div class="form-group">
                <label>Session Notes</label>
                <textarea name="notes" id="editSessionNotes" rows="4" placeholder="Session notes..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeEditSessionModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Update Session</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript (updated with robust admission CORE-10 handling) -->
<script>
// Store current patient ID for view modal
let currentViewPatientId = null;
let currentViewPatientName = '';
let notificationTimeout = null;

// Close all dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-container')) {
        document.querySelectorAll('.dropdown-menu-custom.show').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Toggle dropdown function
function toggleDropdown(button) {
    event.stopPropagation();
    const dropdown = button.nextElementSibling;

    // Close all other dropdowns
    document.querySelectorAll('.dropdown-menu-custom.show').forEach(menu => {
        if (menu !== dropdown) {
            menu.classList.remove('show');
        }
    });

    // Toggle current dropdown
    dropdown.classList.toggle('show');
}

// Prevent dropdown from closing when clicking inside
document.querySelectorAll('.dropdown-menu-custom').forEach(menu => {
    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});

// ===== SEARCH AND FILTER FUNCTIONS =====
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('patientSearch');
    const roomFilter = document.getElementById('roomFilter');
    const patientsGrid = document.getElementById('patientsGrid');

    if (searchInput && roomFilter && patientsGrid) {
        function filterPatients() {
            const searchTerm = searchInput.value.toUpperCase().trim();
            const roomValue = roomFilter.value;
            const patientCards = patientsGrid.querySelectorAll('.patient-card');
            let visibleCount = 0;

            patientCards.forEach(card => {
                const initials = card.getAttribute('data-initials') || '';
                const room = card.getAttribute('data-room');

                const matchesSearch = searchTerm === '' || initials.includes(searchTerm);
                const matchesRoom = roomValue === '' || room === roomValue;

                if (matchesSearch && matchesRoom) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show message if no patients match
            let noResultsMsg = document.getElementById('noSearchResults');
            if (visibleCount === 0 && patientCards.length > 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noSearchResults';
                    noResultsMsg.className = 'empty-state';
                    noResultsMsg.innerHTML = `
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 8v5M12 16h.01"/>
                            </svg>
                        </div>
                        <h3>No Matching Patients</h3>
                        <p>Try adjusting your search or filter criteria</p>
                    `;
                    patientsGrid.parentNode.insertBefore(noResultsMsg, patientsGrid.nextSibling);
                }
                patientsGrid.style.display = 'none';
                noResultsMsg.style.display = '';
            } else {
                if (noResultsMsg) {
                    noResultsMsg.style.display = 'none';
                }
                patientsGrid.style.display = 'grid';
            }
        }

        searchInput.addEventListener('input', filterPatients);
        roomFilter.addEventListener('change', filterPatients);
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
});

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

// ===== PATIENT DETAILS FUNCTIONS =====
function viewPatientDetails(patientId, patientName) {
    if (!patientId) {
        console.error('No patient ID provided');
        return;
    }

    currentViewPatientId = patientId;
    currentViewPatientName = patientName;
    document.getElementById('viewPatientName').innerText = patientName;

    document.getElementById('patientDetailsModal').style.display = 'flex';

    loadPatientSummary(patientId);
    loadAllSessions(patientId);
    loadAdmissionNotes(patientId);
    loadDischargeNotes(patientId);

    switchTab('sessions');
}

function loadPatientSummary(patientId) {
    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            console.log('Patient summary data:', data); // Debug: see what fields are returned
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';
            document.getElementById('viewPatientAdmission').innerText = data.admission_date || 'N/A';

            // Admission CORE-10: check both possible field names
            const admissionCompleted = data.core10_admission || data.core10_admission_completed;
            const admissionSpan = document.getElementById('viewPatientAdmissionCore');
            admissionSpan.innerHTML = admissionCompleted
                ? '<span class="badge badge-success">Completed</span>'
                : '<span class="badge badge-warning">Pending</span>';

            // Discharge CORE-10: check both possible field names
            const dischargeCompleted = data.core10_discharge || data.core10_discharge_completed;
            const dischargeSpan = document.getElementById('viewPatientDischargeCore');
            dischargeSpan.innerHTML = dischargeCompleted
                ? '<span class="badge badge-success">Completed</span>'
                : '<span class="badge badge-warning">Pending</span>';
        })
        .catch(error => {
            console.error('Error loading patient summary:', error);
            document.getElementById('viewPatientAdmissionCore').innerHTML = '<span class="badge badge-warning">Error</span>';
            document.getElementById('viewPatientDischargeCore').innerHTML = '<span class="badge badge-warning">Error</span>';
        });
}

function loadAllSessions(patientId) {
    const sessionsList = document.getElementById('sessionsList');
    sessionsList.innerHTML = '<div class="loading">Loading sessions...</div>';

    fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
        .then(response => {
            if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
            return response.json();
        })
        .then(data => displayAllSessions(data))
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

    let html = '<div class="table-responsive"><table class="sessions-table"><thead><tr><th>Date & Time</th><th>CareNotes</th><th>Tracker</th><th>Tasks</th><th>Notes</th><th>Actions</th></tr></thead><tbody>';

    sessions.forEach(s => {
        const date = new Date(s.datetime);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const datetimeLocal = s.datetime.substring(0, 16);
        const escapedNotes = s.notes ? s.notes.replace(/'/g, "\\'").replace(/\n/g, '\\n') : '';

        html += `<tr>
            <td data-label="Date & Time">${formattedDate}</td>
            <td data-label="CareNotes" class="status-icon">${s.carenotes_completed ? '✅' : '❌'}</td>
            <td data-label="Tracker" class="status-icon">${s.tracker_completed ? '✅' : '❌'}</td>
            <td data-label="Tasks" class="status-icon">${s.tasks_completed ? '✅' : '❌'}</td>
            <td data-label="Notes" class="notes-cell" title="${s.notes || ''}">${s.notes ? s.notes.substring(0,30) + (s.notes.length>30?'...':'') : '-'}</td>
            <td data-label="Actions" class="session-actions">
                <button onclick="editSession(${s.id}, ${s.patient_id || 0}, '${datetimeLocal}', ${s.carenotes_completed}, ${s.tracker_completed}, ${s.tasks_completed}, '${escapedNotes}')" class="action-link edit-link" title="Edit Session">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                </button>
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

    html += '</tbody></table></div>';
    container.innerHTML = html;
}

// ===== DISCHARGE MODAL FUNCTIONS =====
function openDischargeModal(patientId, initials, room, admissionDate, core10Admission, core10Discharge) {
    currentViewPatientId = patientId;
    document.getElementById('dischargePatientName').innerText = initials;
    document.getElementById('dischargePatientId').value = patientId;
    document.getElementById('dischargePatientRoom').innerText = room;
    document.getElementById('dischargePatientAdmission').innerText = admissionDate;

    // Show current CORE-10 statuses
    document.getElementById('dischargePatientAdmissionCore').innerHTML = core10Admission
        ? '<span class="badge badge-success">Completed</span>'
        : '<span class="badge badge-warning">Pending</span>';

    let coreHtml = core10Discharge
        ? '<span class="badge badge-success">Completed</span>'
        : '<span class="badge badge-warning">Pending</span>';
    document.getElementById('dischargePatientDischargeCore').innerHTML = coreHtml;

    let core10CompleteRow = document.getElementById('core10CompleteRow');

    if (core10Discharge) {
        core10CompleteRow.innerHTML = '<input type="hidden" name="core10_discharge" value="1">';
    } else {
        core10CompleteRow.innerHTML = `
            <div class="core10-complete-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="core10_discharge" id="core10Checkbox" value="1" checked>
                    <span>CORE-10 completed at discharge</span>
                </label>
            </div>
        `;
    }

    loadDischargeSessionData(patientId);
    document.getElementById('dischargeModal').style.display = 'flex';
}

function markCore10Completed(patientId) {
    const markBtn = document.getElementById('markCore10Btn');
    if (!markBtn) return;

    const originalText = markBtn.innerText;
    markBtn.innerText = 'Updating...';
    markBtn.disabled = true;

    fetch('<?= url('patients/update-core10-status') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            patient_id: patientId,
            core10_discharge: 1,
            csrf_token: '<?= csrf_token() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update discharge modal UI
            const statusSpan = document.getElementById('dischargePatientDischargeCore');
            if (statusSpan) {
                statusSpan.innerHTML = '<span class="badge badge-success">Completed</span>';
            }

            const core10Row = document.getElementById('core10CompleteRow');
            if (core10Row) {
                core10Row.innerHTML = `
                    <div class="core10-complete-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="core10_discharge" checked disabled>
                            <span>CORE-10 completed at discharge</span>
                        </label>
                    </div>`;
            }

            // Update patient card badges
            const patientCard = document.querySelector(`.patient-card[data-patient-id="${patientId}"]`);
            if (patientCard) {
                const dischargeBadge = patientCard.querySelector('.discharge-badge');
                if (dischargeBadge) {
                    dischargeBadge.className = 'core10-badge discharge-badge completed';
                    dischargeBadge.innerHTML = 'Discharge: ✓';
                }
                // Admission badge remains unchanged (already set)
                patientCard.setAttribute('data-core10-discharge', '1');
            }

            // Update patient details modal if open
            if (currentViewPatientId == patientId) {
                const modalDischargeSpan = document.getElementById('viewPatientDischargeCore');
                if (modalDischargeSpan) {
                    modalDischargeSpan.innerHTML = '<span class="badge badge-success">Completed</span>';
                }
            }

            // Update stats counter optimistically
            const dischargeCount = document.getElementById('dischargeCore10Count');
            if (dischargeCount) {
                let parts = dischargeCount.innerText.split('/');
                if (parts.length === 2) {
                    let completed = parseInt(parts[0]) + 1;
                    dischargeCount.innerText = completed + '/' + parts[1];
                }
            }

            showNotification('CORE-10 marked as completed', 'success');
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
            markBtn.innerText = originalText;
            markBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update CORE-10 status');
        markBtn.innerText = originalText;
        markBtn.disabled = false;
    });
}
function showNotification(message, type) {
    // Clear any existing timeout
    if (notificationTimeout) clearTimeout(notificationTimeout);
    
    // Remove any existing notification
    const existing = document.querySelector('.custom-notification');
    if (existing) existing.remove();

    // Create container
    const notification = document.createElement('div');
    notification.className = `custom-notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 16px;
        background: ${type === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        border-radius: 6px;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease;
        min-width: 250px;
        cursor: pointer;
    `;

    // Message span only (no close button)
    const span = document.createElement('span');
    span.textContent = message;
    notification.appendChild(span);

    // Click anywhere on notification removes it immediately
    notification.addEventListener('click', () => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    });

    document.body.appendChild(notification);

    // Auto‑remove after 3 seconds
    notificationTimeout = setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function closeDischargeModal() {
    document.getElementById('dischargeModal').style.display = 'none';
    const form = document.getElementById('dischargeForm');
    if (form) form.reset();
}

function loadDischargeSessionData(patientId) {
    fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('dischargeSessionCount').innerText = data.length;
            const careNotesCount = data.filter(s => s.carenotes_completed).length;
            const trackersCount = data.filter(s => s.tracker_completed).length;
            const tasksCount = data.filter(s => s.tasks_completed).length;
            document.getElementById('dischargeCareNotesCount').innerText = careNotesCount;
            document.getElementById('dischargeTrackersCount').innerText = trackersCount;
            document.getElementById('dischargeTasksCount').innerText = tasksCount;
            displayRecentSessions(data.slice(0, 3));
        })
        .catch(error => console.error('Error loading sessions:', error));
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
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
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

// ===== EDIT SESSION FUNCTIONS =====
function editSession(sessionId, patientId, datetime, carenotes, tracker, tasks, notes) {
    if (!sessionId) { alert('Error: Missing session ID'); return; }
    if (!patientId) patientId = currentViewPatientId;
    if (!patientId) { alert('Error: Missing patient ID'); return; }

    document.getElementById('editSessionId').value = sessionId;
    document.getElementById('editSessionPatientId').value = patientId;
    document.getElementById('editSessionDatetime').value = datetime;
    document.getElementById('editSessionCarenotes').checked = carenotes == 1 || carenotes === true;
    document.getElementById('editSessionTracker').checked = tracker == 1 || tracker === true;
    document.getElementById('editSessionTasks').checked = tasks == 1 || tasks === true;
    document.getElementById('editSessionNotes').value = notes ? notes.replace(/\\n/g, '\n') : '';

    document.getElementById('editSessionModal').style.display = 'flex';
}

function closeEditSessionModal() {
    document.getElementById('editSessionModal').style.display = 'none';
    document.getElementById('editSessionForm').reset();
}

// ===== NOTES FUNCTIONS =====
function loadAdmissionNotes(patientId) {
    const container = document.getElementById('admissionNotes');
    container.innerHTML = '<div class="loading">Loading admission notes...</div>';
    fetch('<?= url('patients/get-notes') ?>?id=' + patientId)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.trim() !== '') {
                container.innerHTML = `<div class="notes-content admission-notes">${data.notes.replace(/\n/g, '<br>')}</div>`;
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
                container.innerHTML = `<div class="notes-content">${data.notes.replace(/\n/g, '<br>')}</div>`;
            } else {
                container.innerHTML = '<div class="no-notes">No discharge notes available</div>';
            }
        })
        .catch(error => {
            console.error('Error loading discharge notes:', error);
            container.innerHTML = '<div class="error">Failed to load discharge notes</div>';
        });
}

// ===== TAB FUNCTIONS =====
function switchTab(tab) {
    const sessionsTab = document.getElementById('sessionsTab');
    const admissionTab = document.getElementById('admissionTab');
    const dischargeTab = document.getElementById('dischargeTab');
    const sessionsBtn = document.getElementById('sessionsTabBtn');
    const admissionBtn = document.getElementById('admissionTabBtn');
    const dischargeBtn = document.getElementById('dischargeTabBtn');

    [sessionsTab, admissionTab, dischargeTab].forEach(t => t.classList.remove('active'));
    [sessionsBtn, admissionBtn, dischargeBtn].forEach(b => b.classList.remove('active'));

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
    const modals = ['admitModal', 'editRoomModal', 'patientDetailsModal', 'dischargeModal', 'editSessionModal'];
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if (event.target == modal) modal.style.display = 'none';
    });
};

// Add CSS for badges and responsive design
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    .ml-2 { margin-left: 8px; }
    .btn-sm { padding: 6px 12px; font-size: 13px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500; }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .btn-success:disabled { opacity: 0.6; cursor: not-allowed; }
    .core10-complete-options { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 6px; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 0 -1px; }

    /* CORE-10 badge styles */
    .core10-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    .core10-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .core10-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 500;
    }
    .core10-badge.admission-badge.completed {
        background: #d4edda;
        color: #155724;
    }
    .core10-badge.admission-badge.pending {
        background: #fff3cd;
        color: #856404;
    }
    .core10-badge.discharge-badge.completed {
        background: #d4edda;
        color: #155724;
    }
    .core10-badge.discharge-badge.pending {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .sessions-table { min-width: 600px; }
        .btn-text { display: none; }
        .header-actions .btn-primary, .header-actions .btn-outline { padding: 8px; }
        .stats-grid { grid-template-columns: 1fr; gap: 12px; }
        .patients-header { flex-direction: column; gap: 12px; }
        .search-filters { width: 100%; flex-direction: column; }
        .search-box, .filter-select { width: 100%; }
        .patients-grid { grid-template-columns: 1fr; }
        .quick-links-grid { grid-template-columns: 1fr; }
        .modal-content { width: 95%; max-height: 90vh; }
        .summary-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .tabs { flex-wrap: wrap; }
        .tab-btn { flex: 1 1 auto; padding: 10px; font-size: 13px; }
        .stats-mini { grid-template-columns: 1fr 1fr; }
        .core10-complete-options { flex-direction: column; align-items: flex-start; }
        .core10-complete-options button { width: 100%; }
    }
    @media (max-width: 480px) {
        .summary-grid { grid-template-columns: 1fr; }
        .patient-actions { flex-wrap: wrap; }
        .patient-actions button { flex: 1 1 auto; }
        .modal-actions { flex-direction: column; }
        .modal-actions button { width: 100%; }
    }
`;
document.head.appendChild(style);
</script>