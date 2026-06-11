<?php $title = 'Home'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ===== CLINICAL DASHBOARD STYLES (REFACTORED - CLEAN) ===== */

/* ─────────────────────────────────────────────────────────
   1. VARIABLES & BASE
   ───────────────────────────────────────────────────────── */
:root {
    --clinical-blue: #1e3a8a;
    --clinical-soft: #e8f0fe;
    --clinical-gray: #f8fafc;
    --clinical-border: #e2e8f0;
    --clinical-success: #0d9488;
    --clinical-warning: #b45309;
    --clinical-danger: #b91c1c;
    --ward-hope: #eab308;
    --ward-lakeside: #22c55e;
    --ward-manor: #3b82f6;
}

html, body {
    overflow-x: hidden;
}

body {
    background-color: var(--clinical-gray);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* ─────────────────────────────────────────────────────────
   2. LAYOUT & CONTAINERS
   ───────────────────────────────────────────────────────── */
.container,
.dashboard-wrapper {
    padding-top: 10px;
}

.card {
    padding: 14px;
}

.section-title {
    font-weight: 600;
    font-size: 16px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

footer {
    opacity: 0.9;
    font-size: 13px;
}

/* ─────────────────────────────────────────────────────────
   3. QUICK ACTIONS
   ───────────────────────────────────────────────────────── */
.quick-action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.quick-action-card {
    background: white;
    border-radius: 1rem;
    padding: 1rem 0.5rem;
    text-align: center;
    text-decoration: none;
    color: #1e293b;
    border: 1px solid var(--clinical-border);
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    display: block;
}

.quick-action-card:hover {
    border-color: var(--clinical-blue);
    transform: translateY(-3px);
    box-shadow: 0 12px 20px -12px rgba(30, 58, 138, 0.3);
    text-decoration: none;
    color: var(--clinical-blue);
}

.quick-action-card i {
    font-size: 1.8rem;
    color: var(--clinical-blue);
    margin-bottom: 0.5rem;
    display: block;
}

.quick-action-card span {
    font-weight: 600;
    font-size: 0.9rem;
}

.quick-actions {
    margin-bottom: 10px;
}

.quick-actions h6 {
    font-size: 14px;
}

.quick-actions .card {
    padding: 12px 10px;
}

.quick-actions i {
    color: #0d6efd;
    margin-right: 6px;
}

/* ─────────────────────────────────────────────────────────
   4. DROPDOWNS
   ───────────────────────────────────────────────────────── */
.dropdown-menu {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    padding: 0.5rem 0;
}

.dropdown-item {
    padding: 0.6rem 1.2rem;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: var(--clinical-soft);
    color: var(--clinical-blue);
    padding-left: 1.5rem;
}

/* ─────────────────────────────────────────────────────────
   5. STATS & PILLS
   ───────────────────────────────────────────────────────── */
.quick-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.stat-pill {
    background: white;
    border-radius: 2rem;
    padding: 0.5rem 1.2rem;
    border: 1px solid var(--clinical-border);
    font-size: 0.9rem;
    font-weight: 500;
    color: #334155;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
}

.stat-pill strong {
    color: var(--clinical-blue);
    margin-right: 0.25rem;
}

/* ─────────────────────────────────────────────────────────
   6. SELECTED PATIENT CARD
   ───────────────────────────────────────────────────────── */
.selected-patient-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--clinical-border);
    display: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.selected-patient-card.visible {
    display: block;
}

.selected-patient-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.selected-info h3 {
    margin: 0 0 0.25rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.selected-info p {
    margin: 0;
    color: #64748b;
    font-size: 0.85rem;
}

.selected-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    padding-top: 0.75rem;
    border-top: 1px solid var(--clinical-border);
}

.detail-badge {
    background: var(--clinical-gray);
    padding: 0.4rem 1rem;
    border-radius: 2rem;
    font-size: 0.85rem;
}

.detail-badge strong {
    color: var(--clinical-blue);
    margin-right: 0.25rem;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
    justify-content: flex-end;
}

/* ─────────────────────────────────────────────────────────
   7. TODAY'S SESSIONS SECTION
   ───────────────────────────────────────────────────────── */
.today-section {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    border: 1px solid var(--clinical-border);
}

.today-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.today-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--clinical-blue);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.session-count {
    background: #e8f0fe;
    color: var(--clinical-blue);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.15rem 0.6rem;
    border-radius: 2rem;
    margin-left: 0.5rem;
}

/* ─────────────────────────────────────────────────────────
   8. WARD FILTERS
   ───────────────────────────────────────────────────────── */
.ward-filters {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.ward-filter {
    display: inline-flex;
    align-items: center;
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 2rem;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
    transition: all 0.2s;
    cursor: pointer;
}

.ward-filter:hover {
    background: var(--clinical-soft);
    border-color: var(--clinical-blue);
}

.ward-filter input {
    margin-right: 0.4rem;
    accent-color: var(--clinical-blue);
}

.ward-filter-wrapper {
    text-align: center;
    margin-top: 5px;
}

/* ─────────────────────────────────────────────────────────
   9. SESSIONS LIST & CARDS
   ───────────────────────────────────────────────────────── */
.sessions-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.session-card {
    background: var(--clinical-gray);
    border-radius: 0.75rem;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    transition: all 0.2s;
    border: 1px solid transparent;
    cursor: pointer;
}

.session-card:hover {
    background: white;
    border-color: var(--clinical-border);
    transform: translateX(4px);
}

.session-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    flex: 1;
}

.session-patient {
    font-weight: 600;
    color: var(--clinical-blue);
    min-width: 60px;
}

.session-details {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.session-time {
    background: white;
    padding: 0.3rem 0.8rem;
    border-radius: 2rem;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.session-ward {
    padding: 0.3rem 0.8rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.session-icons {
    display: flex;
    gap: 0.5rem;
}

.session-icons i {
    font-size: 1rem;
    color: #64748b;
}

.session-actions {
    display: flex;
    gap: 0.5rem;
}

/* ─────────────────────────────────────────────────────────
   10. WARD COLOURS
   ───────────────────────────────────────────────────────── */
.ward-hope { background: var(--ward-hope); }
.ward-lakeside { background: var(--ward-lakeside); }
.ward-manor { background: var(--ward-manor); }

/* ─────────────────────────────────────────────────────────
   11. ACTION ICONS & TOOLTIPS
   ───────────────────────────────────────────────────────── */
.action-icon {
    background: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid var(--clinical-border);
    color: #555;
    position: relative;
}

.action-icon:hover {
    background: var(--clinical-blue);
    color: white;
    transform: translateY(-2px);
}

.clear-patient.action-icon:hover {
    background: var(--clinical-danger);
    color: white;
    border-color: var(--clinical-danger);
}

.action-icon[data-tooltip] {
    position: relative;
}

.action-icon[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    background: #111;
    color: #fff;
    padding: 5px 8px;
    font-size: 12px;
    border-radius: 6px;
    top: -35px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    z-index: 2000;
}

/* ─────────────────────────────────────────────────────────
   12. EMPTY STATE
   ───────────────────────────────────────────────────────── */
.empty-sessions {
    text-align: center;
    color: #94a3b8;
    padding: 2rem;
}

.empty-sessions i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-sessions .btn-add-session {
    margin-top: 1rem;
    background: var(--clinical-blue);
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 2rem;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.empty-sessions .btn-add-session:hover {
    background: #1a2f6b;
    transform: translateY(-2px);
}

/* ─────────────────────────────────────────────────────────
   13. PATIENT SECTION
   ───────────────────────────────────────────────────────── */
.patient-section {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--clinical-border);
}

.patient-section h2 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--clinical-blue);
    margin: 0 0 1.2rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.patient-controls {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: flex-end;
}

.patient-select {
    flex: 2;
    min-width: 200px;
}

.patient-select label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.patient-select select {
    width: 100%;
    padding: 0.6rem 1rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    background: white;
}

.ward-checkboxes {
    flex: 1;
    min-width: 160px;
}

.ward-checkboxes label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.checkbox-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.ward-option {
    display: inline-flex;
    align-items: center;
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 2rem;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.ward-option:hover {
    background: var(--clinical-soft);
    border-color: var(--clinical-blue);
}

.ward-option input {
    margin-right: 0.4rem;
    accent-color: var(--clinical-blue);
}

.patient-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.patient-actions button {
    margin-right: 8px;
    flex: 1 1 auto;
}

/* ─────────────────────────────────────────────────────────
   14. MODALS
   ───────────────────────────────────────────────────────── */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

#changeRoomModal,
#dischargeModal {
    z-index: 1100;
}

body.modal-open {
    overflow: hidden;
}

.modal-content {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-lg {
    max-width: 950px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-header h2 {
    font-size: 1.4rem;
    color: var(--clinical-blue);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.modal-form .form-group {
    margin-bottom: 1rem;
}

.modal-form label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 500;
    font-size: 0.85rem;
}

.modal-form input,
.modal-form select,
.modal-form textarea {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    font-size: 0.9rem;
}

.modal-form input[readonly] {
    background: var(--clinical-soft);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

/* Modal scrollbar hiding (per-modal) */
#sessionModal .modal-content,
#admitModal .modal-content,
#groupSessionModal .modal-content,
#singleSessionModal .modal-content {
    max-height: 90vh;
    overflow-y: auto;
}

#sessionModal .modal-content::-webkit-scrollbar,
#admitModal .modal-content::-webkit-scrollbar,
#groupSessionModal .modal-content::-webkit-scrollbar,
#singleSessionModal .modal-content::-webkit-scrollbar,
#groupSessionDetailsModal .modal-content::-webkit-scrollbar {
    width: 0px;
}

/* ─────────────────────────────────────────────────────────
   15. BUTTONS
   ───────────────────────────────────────────────────────── */
.btn-secondary {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    cursor: pointer;
}

.btn-primary {
    background: var(--clinical-blue);
    color: white;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #1a2f6b;
    transform: translateY(-2px);
}

.btn-danger {
    background: #dc2626;
    color: white;
    border: none;
    padding: 0.5rem 1.2rem;
    border-radius: 2rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #b91c1c;
    transform: translateY(-2px);
}

/* ─────────────────────────────────────────────────────────
   16. PATIENT DETAILS MODAL - SUMMARY & TABS
   ───────────────────────────────────────────────────────── */
.patient-summary {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.summary-item .summary-label {
    font-size: 12px;
    color: #64748b;
    display: block;
}

.summary-item .summary-value {
    font-size: 16px;
    font-weight: 500;
    color: #1e293b;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
}

.tab-btn {
    padding: 8px 16px;
    border-radius: 30px;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
    font-weight: 500;
}

.tab-btn.active {
    background: var(--clinical-blue);
    color: white;
    border-color: var(--clinical-blue);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

.sessions-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}
.sessions-table th,
.sessions-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.status-icon {
    text-align: center;
}

.loading,
.error,
.no-notes {
    text-align: center;
    padding: 40px;
    color: #94a3b8;
}

.notes-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    min-height: 200px;
}

.notes-content {
    line-height: 1.6;
    white-space: pre-wrap;
}

/* ─────────────────────────────────────────────────────────
   17. ACTION CARDS
   ───────────────────────────────────────────────────────── */
.action-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.action-card {
    flex: 1 1 calc(50% - 10px);
}

/* ─────────────────────────────────────────────────────────
   18. AVATAR SYSTEM
   ───────────────────────────────────────────────────────── */
.avatar-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    background: #e2e8f0;
    color: #1e293b;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.app-navbar .avatar-circle {
    width: 36px;
    height: 36px;
    font-size: 0.9rem;
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

#selectedAvatar.avatar-circle {
    width: 56px;
    height: 56px;
    font-size: 1.2rem;
}

#selectedAvatar.avatar-hope {
    background: var(--ward-hope);
    color: white;
}

#selectedAvatar.avatar-lakeside {
    background: var(--ward-lakeside);
    color: white;
}

#selectedAvatar.avatar-manor {
    background: var(--ward-manor);
    color: white;
}

.selected-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--clinical-blue), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
    color: white;
}

/* ─────────────────────────────────────────────────────────
   19. CALENDAR WIDGET
   ───────────────────────────────────────────────────────── */
.bp-cal-panel {
    background: white;
    border-radius: 1rem;
    border: 1px solid var(--clinical-border);
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 1rem;
}

.bp-cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.bp-cal-title {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--clinical-blue);
}

.bp-cal-nav {
    background: none;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--clinical-blue);
    font-size: 0.8rem;
    transition: all 0.15s;
}

.bp-cal-nav:hover {
    background: var(--clinical-soft);
    border-color: var(--clinical-blue);
}

.bp-cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 4px;
}

.bp-cal-weekdays span {
    text-align: center;
    font-size: 0.68rem;
    font-weight: 600;
    color: #94a3b8;
    padding: 4px 0;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.bp-cal-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 2px;
}

.bp-cal-day {
    min-width: 0;
    overflow: hidden;
    min-height: 62px;
    border-radius: 0.5rem;
    padding: 2px;
    cursor: pointer;
    transition: background 0.15s;
    position: relative;
    border: 1px solid transparent;
}

.bp-cal-day:hover {
    background: var(--clinical-soft);
    border-color: var(--clinical-border);
}

.bp-cal-day.bp-cal-empty {
    cursor: default;
}

.bp-cal-day.bp-cal-empty:hover {
    background: none;
    border-color: transparent;
}

.bp-cal-day.bp-cal-today .bp-cal-num {
    background: var(--clinical-blue);
    color: white;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bp-cal-day.bp-cal-past {
    opacity: 0.55;
}

.bp-cal-num {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    line-height: 1;
    margin-bottom: 3px;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bp-cal-sessions {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
    overflow: hidden;
}

.bp-cal-session-chip {
    display: block;
    max-width: 100%;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    box-sizing: border-box;
    font-size: 0.65rem;
    font-weight: 500;
    padding: 1px 3px;
    border-radius: 2px;
    margin-bottom: 1px;
    color: white;
    cursor: pointer;
    line-height: 1.5;
}

.bp-cal-session-chip:hover {
    opacity: 0.85;
}

.bp-cal-session-chip.gs-chip {
    background: #8b5cf6;
}

.bp-cal-session-chip.ward-hope,
.bp-cal-session-chip.ward-lakeside,
.bp-cal-session-chip.ward-manor {
    color: white;
}

.bp-cal-more {
    font-size: 0.62rem;
    color: var(--clinical-blue);
    font-weight: 600;
    padding: 1px 2px;
    cursor: pointer;
}

.bp-cal-legend {
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    gap: 0.3rem;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--clinical-border);
    overflow-x: auto;
}

.bp-cal-legend::-webkit-scrollbar {
    display: none;
}

.bp-cal-legend .ward-filter {
    padding: 0.2rem 0.5rem;
    font-size: 0.72rem;
    flex-shrink: 0;
    white-space: nowrap;
}

.bp-cal-legend-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: #94a3b8;
    letter-spacing: 0.04em;
    flex-shrink: 0;
    white-space: nowrap;
}

.bp-cal-legend span .bp-cal-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 4px;
}

.bp-cal-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 3px;
    vertical-align: middle;
}

.bp-cal-leg-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.7rem;
    color: #475569;
    cursor: pointer;
    user-select: none;
    padding: 0.15rem 0.35rem;
    border-radius: 2rem;
    border: 1px solid #e2e8f0;
    background: white;
    transition: all 0.15s;
    white-space: nowrap;
    flex-shrink: 0;
}

.bp-cal-leg-item:hover {
    border-color: #1e3a8a;
    background: #eff6ff;
    color: #1e3a8a;
}

.bp-cal-leg-item input[type="checkbox"] {
    width: 12px;
    height: 12px;
    accent-color: #1e3a8a;
    cursor: pointer;
    margin: 0;
    flex-shrink: 0;
}

.bp-cal-leg-item.active {
    color: #1e3a8a;
    font-weight: 600;
}

.bp-cal-check {
    display: inline-flex;
    width: 11px;
    height: 11px;
    min-width: 11px;
    min-height: 11px;
    border: 1.5px solid #94a3b8;
    border-radius: 2px;
    background: white;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
    box-sizing: border-box;
}

.bp-cal-leg-item.active .bp-cal-check {
    background: #1e3a8a;
    border-color: #1e3a8a;
}

.bp-cal-leg-item.active .bp-cal-check::after {
    content: '';
    display: block;
    width: 4px;
    height: 7px;
    border: 1.5px solid white;
    border-top: none;
    border-left: none;
    transform: rotate(45deg) translate(-0.5px, -1px);
    flex-shrink: 0;
}

.bp-cal-loading {
    grid-column: 1/-1;
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
    font-size: 0.85rem;
}

/* ─────────────────────────────────────────────────────────
   20. CALENDAR DAY MODAL
   ───────────────────────────────────────────────────────── */
#calDayModal .modal-content {
    display: flex;
    flex-direction: column;
    max-height: 80vh;
    overflow: hidden;
    padding-bottom: 0;
}

#calDayList {
    overflow-y: auto;
    flex: 1;
    max-height: 60vh;
    padding: 0.5rem 0;
    min-height: 0;
}

#calDayList::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}

.day-session-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid var(--clinical-border);
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
}

.day-session-item:hover {
    background: var(--clinical-soft);
    transform: translateX(2px);
}

#calDayModal .day-session-item:hover {
    background: var(--clinical-soft);
    transform: none;
}

.session-initials {
    font-weight: 700;
    color: var(--clinical-blue);
    font-size: 0.85rem;
    min-width: 32px;
}

.session-time {
    font-size: 0.78rem;
    color: #64748b;
    background: transparent;
    padding: 0;
}

.day-session-item .session-ward {
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    padding: 2px 8px;
    border-radius: 2rem;
    display: inline-block;
}

.session-room {
    font-size: 0.7rem;
    font-weight: 500;
    color: #64748b;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 4px;
}

.session-arrow {
    margin-left: auto;
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Day modal session row (alt class) */
.bp-day-session-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid var(--clinical-border);
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: background 0.15s;
}

.bp-day-session-row:hover {
    background: var(--clinical-soft);
}

.bp-day-session-initials {
    font-weight: 700;
    color: var(--clinical-blue);
    font-size: 0.85rem;
    min-width: 32px;
}

.bp-day-session-time {
    font-size: 0.78rem;
    color: #64748b;
}

.bp-day-session-ward {
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    padding: 2px 8px;
    border-radius: 2rem;
}

/* ─────────────────────────────────────────────────────────
   21. GROUP SESSION MODAL & ATTENDANCE TABLE
   ───────────────────────────────────────────────────────── */
.gs-modal-container {
    max-width: 1100px;
    width: 95%;
    padding: 1.25rem;
}

#groupSessionModal .gs-modal-container {
    max-width: 1100px;
    width: 95%;
    padding: 1.5rem;
}

.gs-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.gs-checkbox-group-horizontal {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: center;
    margin: 0.25rem 0;
}

.gs-checkbox-group-vertical {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin: 0.25rem 0;
}

.gs-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
    font-size: 0.9rem;
    margin: 0;
}

.gs-checkbox input {
    margin: 0;
    width: 16px;
    height: 16px;
    accent-color: var(--clinical-blue);
}

.gs-checkbox span {
    color: #1e293b;
}

.form-group {
    margin-bottom: 0.75rem;
}

.gs-select,
.gs-textarea,
.gs-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    background: white;
}

.gs-textarea {
    resize: vertical;
}

#groupSessionDatetime {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    background: white;
}

.gs-attendance-table-wrapper {
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    overflow-x: auto;
    background: white;
    margin-top: 0.5rem;
}

.gs-register {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    min-width: 700px;
}

.gs-register thead tr {
    background: var(--clinical-soft);
}

.gs-register th {
    background: #f8fafc;
    padding: 8px 6px;
    text-align: left;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid var(--clinical-border);
    white-space: nowrap;
}

.gs-register td {
    padding: 8px 6px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    white-space: nowrap;
}

.gs-register tr:last-child td {
    border-bottom: none;
}

.gs-register tr:hover td {
    background: #fafbfc;
}

.gs-register td.gs-radio-cell {
    text-align: center;
    width: 70px;
    white-space: nowrap;
}

.gs-register input[type="radio"] {
    width: 18px;
    height: 18px;
    margin: 0;
    accent-color: var(--clinical-blue);
    cursor: pointer;
}

.gs-radio-cell {
    text-align: center;
    width: 70px;
    white-space: nowrap;
}

.gs-radio-cell input[type="radio"] {
    width: 18px;
    height: 18px;
    margin: 0;
    accent-color: var(--clinical-blue);
    cursor: pointer;
}

.gs-ward-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 2rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
    text-align: center;
    min-width: 60px;
    white-space: nowrap;
}

.gs-notes-input {
    width: 100%;
    min-width: 150px;
    padding: 6px 10px;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    font-size: 0.75rem;
    background: white;
}

.gs-notes-input:focus {
    border-color: var(--clinical-blue);
    outline: none;
}

.gs-placeholder {
    color: #94a3b8;
    font-size: 0.85rem;
    padding: 2rem;
    text-align: center;
    margin: 0;
}

.gs-loading {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

#groupAttendanceTable {
    overflow-x: auto;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
}

#groupSessionDetailAttendance {
    overflow-x: auto;
    margin-top: 0.5rem;
}

#groupSessionDetailAttendance .gs-register {
    min-width: unset;
    width: 100%;
}

.form-text.text-muted {
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.4rem;
    display: block;
}

/* ─────────────────────────────────────────────────────────
   22. GROUP SESSIONS LIST
   ───────────────────────────────────────────────────────── */
.group-sessions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.group-session-card {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.2s;
}

.group-session-card:hover {
    border-color: var(--clinical-blue);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.group-session-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.group-session-type {
    font-size: 1rem;
    color: var(--clinical-blue);
}

.group-session-datetime {
    font-size: 0.75rem;
    color: #64748b;
}

.group-session-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 0.75rem;
}

.group-session-wards {
    font-size: 0.7rem;
    background: #f1f5f9;
    padding: 2px 8px;
    border-radius: 2rem;
    color: #475569;
}

.group-session-count {
    font-size: 0.7rem;
    color: #64748b;
}

.group-session-notes-preview {
    font-size: 0.8rem;
    color: #475569;
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 0.5rem;
}

/* ─────────────────────────────────────────────────────────
   23. GROUPED ATTENDANCE LAYOUT (ward groups)
   ───────────────────────────────────────────────────────── */
.attendance-by-ward {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.ward-attendance-group {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    overflow: hidden;
}

.ward-attendance-header {
    background: var(--clinical-gray);
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid var(--clinical-border);
}

.ward-name {
    font-weight: 700;
    font-size: 1rem;
    color: var(--clinical-blue);
}

.patient-count {
    font-size: 0.7rem;
    color: #64748b;
    background: white;
    padding: 2px 8px;
    border-radius: 2rem;
}

.ward-patients-list {
    padding: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.patient-attendance-item {
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
    transition: all 0.15s;
}

.patient-attendance-item:hover {
    background: white;
    border: 1px solid var(--clinical-border);
    padding: 0.7rem;
}

.patient-details {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.patient-room {
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    background: #e2e8f0;
    padding: 2px 6px;
    border-radius: 4px;
}

.patient-initials {
    font-weight: 700;
    color: var(--clinical-blue);
    font-size: 0.9rem;
}

.attendance-item-notes {
    width: 100%;
    font-size: 0.7rem;
    color: #64748b;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid var(--clinical-border);
}

.no-attendance {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
}

/* ─────────────────────────────────────────────────────────
   24. ATTENDANCE CARDS (separate grid layout)
   ───────────────────────────────────────────────────────── */
.attendance-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.attendance-card {
    background: #f8fafc;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.2s;
}

.attendance-card:hover {
    background: white;
    border-color: var(--clinical-blue);
    transform: translateY(-2px);
}

.attendance-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.attendance-card-details {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.attendance-notes {
    font-size: 0.75rem;
    color: #475569;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid var(--clinical-border);
}

.attendance-left small {
    display: block;
    color: #64748b;
    margin-top: 4px;
}

.patient-name {
    font-size: 1rem;
    color: var(--clinical-blue);
}

.attendance-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 2rem;
}

.ward-badge {
    padding: 2px 8px;
    border-radius: 2rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
}

.room-number {
    font-size: 0.7rem;
    color: #64748b;
}

/* ─────────────────────────────────────────────────────────
   25. STATUS BADGES
   ───────────────────────────────────────────────────────── */
.status-attended {
    background: #d1fae5;
    color: #065f46;
}

.status-declined {
    background: #fee2e2;
    color: #991b1b;
}

.status-dna {
    background: #fed7aa;
    color: #92400e;
}

.status-pending {
    background: #f1f5f9;
    color: #64748b;
}

.status-not-marked {
    background: #f1f5f9;
    color: #64748b;
}

.status-done {
    background: #d1fae5;
    color: #065f46;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 2rem;
}

.badge-success {
    background: #dcfce7;
    color: #166534;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 6px 12px;
    border-radius: 20px;
}

.badge-secondary {
    background: #e2e8f0;
    color: #475569;
    padding: 6px 12px;
    border-radius: 20px;
}

/* ─────────────────────────────────────────────────────────
   26. SIMPLE ATTENDANCE TABLE
   ───────────────────────────────────────────────────────── */
.attendance-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.attendance-table th,
.attendance-table td {
    border-bottom: 1px solid var(--clinical-border);
    padding: 6px;
    text-align: left;
}

.attendance-table th {
    background: var(--clinical-gray);
}

/* ─────────────────────────────────────────────────────────
   27. UTILITIES
   ───────────────────────────────────────────────────────── */
.subtle-text {
    color: #94a3b8;
    font-size: 13px;
}

.section-label,
.filter-label,
.calendar-hint {
    font-size: 13px;
    color: #64748b;
}

.section-label {
    font-weight: 600;
    margin-bottom: 10px;
    padding-left: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-hint {
    margin-bottom: 8px;
    text-align: center;
}

.text-center {
    text-align: center;
}

select:invalid {
    box-shadow: none;
}

#sessionPatient:invalid {
    border-color: var(--clinical-border);
}

/* ═════════════════════════════════════════════════════════
   RESPONSIVE OVERRIDES — largest to smallest breakpoint
   ═════════════════════════════════════════════════════════ */

/* ─── Tablet & below: 900px ─── */
@media (max-width: 900px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .bp-cal-panel {
        position: static;
    }
}

/* ─── Tablet portrait: 768px ─── */
@media (max-width: 768px) {
    .quick-action-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .patient-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .session-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .session-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .selected-details {
        flex-direction: column;
        gap: 0.5rem;
    }

    .today-section {
        padding: 1rem;
        border-radius: 0.75rem;
    }

    .today-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .today-header h2 {
        font-size: 1.05rem;
    }

    .ward-filter-wrapper {
        width: 100%;
        text-align: left !important;
    }

    .ward-filters {
        gap: 0.5rem;
    }

    .ward-filter {
        padding: 0.35rem 0.75rem;
        font-size: 0.78rem;
    }

    .patient-section {
        padding: 1rem;
        border-radius: 0.75rem;
    }

    .patient-select,
    .ward-checkboxes {
        width: 100%;
        min-width: unset;
    }

    .checkbox-group {
        gap: 0.5rem;
    }

    .ward-option {
        padding: 0.35rem 0.75rem;
        font-size: 0.78rem;
    }

    .gs-form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    #groupSessionModal .gs-modal-container {
        width: 98%;
        padding: 1rem;
    }

    .gs-register th,
    .gs-register td {
        padding: 8px 4px;
    }

    .gs-radio-cell {
        width: 50px;
    }

    .gs-notes-input {
        min-width: 100px;
    }

    /* Prevent hover transforms causing horizontal scroll */
    .quick-action-card:hover {
        transform: none;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.15);
    }

    .btn-primary:hover,
    .btn-danger:hover,
    .btn-secondary:hover {
        transform: none;
    }

    .action-icon:hover {
        transform: none;
    }
}

/* ─── Mobile: 600px ─── */
@media (max-width: 600px) {
    /* Quick actions */
    .quick-action-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }

    .quick-action-card {
        padding: 0.75rem 0.25rem;
        border-radius: 0.75rem;
    }

    .quick-action-card i {
        font-size: 1.4rem;
        margin-bottom: 0.3rem;
    }

    .quick-action-card span {
        font-size: 0.72rem;
    }

    /* Ward stats */
    .quick-stats {
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .stat-pill {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }

    /* Session cards */
    .session-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.75rem;
    }

    .session-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.4rem;
        width: 100%;
    }

    .session-details {
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .session-actions {
        width: 100%;
        justify-content: flex-end;
        padding-top: 0.4rem;
        border-top: 1px solid var(--clinical-border);
    }

    .session-card:hover {
        transform: none;
    }

    /* Selected patient card */
    .selected-patient-card {
        padding: 1rem;
        border-radius: 0.75rem;
    }

    .selected-patient-header {
        gap: 0.75rem;
    }

    .selected-details {
        flex-direction: column;
        gap: 0.4rem;
    }

    .detail-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
    }

    .card-actions {
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 0.5rem;
    }

    .card-actions .btn-primary,
    .card-actions .btn-secondary,
    .card-actions .btn-danger {
        flex: 1 1 calc(50% - 0.25rem);
        text-align: center;
        font-size: 0.8rem;
        padding: 0.45rem 0.75rem;
    }

    .card-actions .clear-patient {
        flex: 0 0 auto;
        align-self: center;
    }

    /* Calendar */
    .bp-cal-panel {
        padding: 0.75rem;
        border-radius: 0.75rem;
    }

    .bp-cal-day {
        min-height: 44px;
        padding: 2px;
    }

    .bp-cal-num {
        font-size: 0.65rem;
        width: 18px;
        height: 18px;
    }

    .bp-cal-session-chip {
        font-size: 0.55rem;
        padding: 1px 2px;
    }

    .bp-cal-more {
        font-size: 0.55rem;
    }

    .bp-cal-weekdays span {
        font-size: 0.58rem;
        padding: 2px 0;
    }

    .bp-cal-legend {
        gap: 0.5rem;
        font-size: 0.7rem;
        flex-wrap: wrap;
    }

    .bp-cal-title {
        font-size: 0.85rem;
    }

    /* Modals */
    .modal {
        padding: 10px;
        align-items: flex-end;
    }

    .modal-content {
        border-radius: 1.25rem 1.25rem 0 0;
        padding: 1.25rem 1rem;
        max-height: 92vh;
        width: 100%;
        max-width: 100%;
    }

    .modal-content.modal-lg {
        max-width: 100%;
        border-radius: 1.25rem 1.25rem 0 0;
    }

    .modal-header h2 {
        font-size: 1.1rem;
    }

    .modal-actions {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .modal-actions button {
        flex: 1 1 calc(50% - 0.25rem);
        font-size: 0.82rem;
        padding: 0.5rem 0.75rem;
        text-align: center;
    }

    /* Patient details modal */
    #patientDetailsModal .modal-header {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    #patientDetailsModal .modal-header h2 {
        width: 100%;
        order: -1;
    }

    #patientDetailsModal .modal-header .btn-secondary,
    #patientDetailsModal .modal-header .btn-danger {
        flex: 1;
        text-align: center;
        margin: 0 !important;
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }

    #patientDetailsModal .modal-header .modal-close {
        order: -2;
        margin-left: auto;
    }

    /* Tabs */
    .tabs {
        gap: 0.4rem;
        overflow-x: auto;
        padding-bottom: 8px;
        flex-wrap: nowrap;
    }

    .tab-btn {
        padding: 6px 10px;
        font-size: 0.78rem;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* Group session modal */
    #groupSessionForm > div[style*="grid"] {
        grid-template-columns: 1fr !important;
    }

    .gs-form-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .gs-register th,
    .gs-register td {
        white-space: normal;
        word-break: break-word;
    }

    .gs-register th:last-child,
    .gs-register td:last-child {
        display: none;
    }

    /* Summary grid */
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    /* Sessions table scroll */
    #sessionsList {
        overflow-x: auto;
    }

    .sessions-table {
        min-width: 480px;
    }

    /* Single session modal actions */
    #singleSessionModal .modal-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    #singleSessionModal .modal-actions button,
    #singleSessionModal .modal-actions > div {
        width: 100%;
    }

    #singleSessionModal .modal-actions > div {
        display: flex;
        gap: 0.5rem;
    }

    #singleSessionModal .modal-actions > div button {
        flex: 1;
    }

    /* Calendar day modal */
    #calDayModal .modal-content {
        max-width: 100%;
    }

    .day-session-item {
        padding: 0.5rem;
        gap: 0.5rem;
    }

    .session-room {
        display: none;
    }

    /* Notification toast */
    #notification {
        left: 10px;
        right: 10px;
        bottom: 10px;
        text-align: center;
        font-size: 0.82rem;
    }

    /* Section label */
    .section-label {
        font-size: 0.72rem;
    }

    /* Patient attendance items stack */
    .patient-attendance-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .patient-details {
        width: 100%;
        justify-content: space-between;
    }

    .attendance-badge {
        align-self: flex-start;
    }

    /* Attendance cards stack */
    .attendance-cards {
        grid-template-columns: 1fr;
    }

    .group-session-header {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* ─── Small mobile: 480px ─── */
@media (max-width: 480px) {
    .quick-action-grid {
        grid-template-columns: 1fr;
    }

    .patient-actions button {
        width: 100%;
    }

    .action-card {
        flex: 1 1 100%;
    }
}

/* ─── Very small mobile: 380px ─── */
@media (max-width: 380px) {
    .quick-action-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* ─── PATIENT DETAILS MODAL - mobile improvements (≤600px) ─── */
@media (max-width: 600px) {
   /* Tighten modal padding */
    #patientDetailsModal .modal-content {
        padding: 1rem 1rem 1.5rem;
    }

    /* Reduce gap between header and summary */
    #patientDetailsModal .patient-summary {
        margin-top: 0;
    }

    /* Compact header — title + close on top row, buttons full-width below */
    #patientDetailsModal .modal-header {
        display: grid;
        grid-template-columns: 1fr auto;
        grid-template-areas:
            "title close"
            "actions actions";
        gap: 0.5rem 0.75rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--clinical-border);
    }

    #patientDetailsModal .modal-header h2 {
        grid-area: title;
        width: auto !important;
        order: 0 !important;
        font-size: 1rem;
        margin: 0;
        line-height: 1.3;
    }

    #patientDetailsModal .modal-header .modal-close {
        grid-area: close;
        order: 0 !important;
        margin: 0 !important;
        align-self: start;
        font-size: 1.4rem;
        padding: 0;
        line-height: 1;
    }
/* The wrapping div that holds the 3 action buttons */
    #patientDetailsModal .modal-header > div {
        grid-area: actions;
        display: grid !important;
        grid-template-columns: 1fr 1fr 1fr !important;
        gap: 0.4rem !important;
        margin: 0 !important;
        width: 100% !important;
        align-items: stretch !important;
    }

   #patientDetailsModal .modal-header .btn-primary,
    #patientDetailsModal .modal-header .btn-secondary,
    #patientDetailsModal .modal-header .btn-danger {
        flex: 1 1 0 !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0.5rem 0.25rem;
        font-size: 0.72rem;
        white-space: normal;
        line-height: 1.2;
        border-radius: 0.6rem;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    /* Compact patient summary */
    #patientDetailsModal .patient-summary {
        padding: 0.85rem;
        margin-bottom: 1rem;
        border-radius: 0.75rem;
    }

    #patientDetailsModal .summary-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem 1rem;
    }

    #patientDetailsModal .summary-item {
        min-width: 0;
    }

    #patientDetailsModal .summary-label {
        font-size: 0.65rem !important;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #94a3b8 !important;
    }

    #patientDetailsModal .summary-value {
        font-size: 0.95rem !important;
        line-height: 1.3;
    }

    /* Edit Core-10 button: smaller and inline */
    #patientDetailsModal #editCore10Btn,
    #patientDetailsModal #editDischargeCore10Btn {
        display: inline-block;
        margin-left: 0 !important;
        margin-top: 0.35rem !important;
        font-size: 0.7rem !important;
        padding: 2px 10px !important;
        border-radius: 1rem;
    }

    /* Tabs visible row */
    #patientDetailsModal .tabs {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
    }

    /* Close button at the bottom — make it the proper full-width primary action */
    #patientDetailsModal .modal-actions {
        margin-top: 1rem;
    }

    #patientDetailsModal .modal-actions .btn-secondary {
        width: 100%;
        flex: unset !important;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
    }
}

    </style>
    <div class="container-fluid px-4 px-lg-5 py-4">

    <!-- QUICK ACTION GRID (simplified – no dropdowns) -->
    <h5 class="section-label mb-2">
        <i class="bi bi-lightning-charge-fill"></i> Quick Actions
    </h5>
    <div class="quick-action-grid mb-4">
        <!-- Admit Patient – direct link (no dropdown) -->
        <a href="#" class="quick-action-card" onclick="openAdmitModal(); return false;">
            <i class="bi bi-person-plus"></i><span>Admit Patient</span>
        </a>

        <!-- Add Session – direct link (no dropdown) -->
        <a href="#" class="quick-action-card" onclick="openSessionModal(null, null); return false;">
            <i class="bi bi-calendar-plus"></i><span>Add Single Session</span>
        </a>

    <a href="#" class="quick-action-card" onclick="openGroupSessionModal(); return false;">
        <i class="bi bi-people"></i><span>Add Group Session</span>
    </a>
    <a href="#" class="quick-action-card" onclick="openViewGroupSessionsModal(); return false;">
        <i class="bi bi-table"></i><span>View Group Sessions</span>
    </a>

        <a href="<?= url('activities') ?>" class="quick-action-card"><i class="bi bi-activity"></i><span>Activity log</span></a>
        <a href="<?= url('sessions/archived') ?>" class="quick-action-card"><i class="bi bi-archive"></i><span>Archived Sessions</span></a>
        <a href="<?= url('reports') ?>" class="quick-action-card"><i class="bi bi-bar-chart-line"></i><span>Reports</span></a>
        <a href="<?= url('patients/discharged') ?>" class="quick-action-card"><i class="bi bi-box-arrow-right"></i><span>Discharged</span></a>
        
    </div>

        <?php
        $patients = $patients ?? [];
        $todaySessions = $todaySessions ?? [];
        if (is_int($todaySessions)) $todaySessions = [];

        $wardCounts = ['Hope' => 0, 'Lakeside' => 0, 'Manor' => 0];
        foreach ($patients as $p) {
            if (isset($wardCounts[$p->ward])) $wardCounts[$p->ward]++;
        }
        ?>
        <div class="quick-stats">
            <div class="stat-pill"> <strong>Hope:</strong> <?= $wardCounts['Hope'] ?> patients</div>
            <div class="stat-pill"> <strong>Lakeside:</strong> <?= $wardCounts['Lakeside'] ?> patients</div>
            <div class="stat-pill"> <strong>Manor:</strong> <?= $wardCounts['Manor'] ?> patients</div>
        </div>

        <!-- DASHBOARD GRID (LEFT: Today's Sessions, RIGHT: Calendar) -->
        <div class="dashboard-grid">
            <!-- LEFT COLUMN -->
            <div class="today-section">
                <div class="today-header">
                    <h2><i class="bi bi-calendar-check"></i> Today's Sessions
                        <span class="session-count" id="sessionCountBadge"><?= count($todaySessions) ?></span>
                    </h2>
                    <div class="ward-filter-wrapper text-center mt-2">
                        <span class="filter-title">Filter by ward:</span>
                   <div class="ward-filters d-inline-flex gap-3 mt-1">
    <label class="ward-filter"><input type="checkbox" id="sessionFilterAll" checked onchange="toggleAllSessions(this)"> All</label>
    <label class="ward-filter"><input type="checkbox" value="hope" onchange="syncSessionAll()"> Hope</label>
    <label class="ward-filter"><input type="checkbox" value="lakeside" onchange="syncSessionAll()"> Lakeside</label>
    <label class="ward-filter"><input type="checkbox" value="manor" onchange="syncSessionAll()"> Manor</label>
</div>
                    </div>
                </div>
                <?php if (!empty($todaySessions)): ?>
                    <div class="sessions-list">
                        <?php usort($todaySessions, fn($a,$b) => strtotime($a->datetime) - strtotime($b->datetime)); ?>
                        <?php foreach ($todaySessions as $session): ?>
                            <?php if (empty($session->is_discharged)): ?>
                            <div class="session-card" 
                                data-ward="<?= strtolower($session->ward ?? '') ?>" 
                                data-session-id="<?= $session->id ?>"
                                data-patient-id="<?= $session->patient_id ?>"
                                data-patient-name="<?= htmlspecialchars($session->patient_initials ?? '') ?>"
                                data-session-datetime="<?= htmlspecialchars($session->datetime) ?>"
                                data-session-ward="<?= htmlspecialchars($session->ward ?? '') ?>"
                                data-session-room="<?= htmlspecialchars($session->room_number ?? '') ?>"
                                data-session-carenotes="<?= $session->carenotes_completed ? 1 : 0 ?>"
                                data-session-tracker="<?= $session->tracker_completed ? 1 : 0 ?>"
                                data-session-tasks="<?= $session->tasks_completed ? 1 : 0 ?>"
                                data-session-notes="<?= addslashes($session->notes ?? '') ?>"
                                data-session-status="<?= htmlspecialchars($session->status ?? 'offered') ?>">
                                <div class="session-info">
                                    <span class="session-patient"><?= htmlspecialchars($session->patient_initials ?? '') ?></span>
                                    <div class="session-details">
                                        <span class="session-time"><i class="bi bi-clock"></i> <?= date('H:i', strtotime($session->datetime)) ?></span>
                                        <span class="session-ward ward-<?= strtolower($session->ward ?? 'hope') ?>"><?= htmlspecialchars($session->ward ?? '') ?></span>
                                        <span class="session-icons">
                                            <?= !empty($session->carenotes_completed) ? '<i class="bi bi-journal-text" title="CareNotes completed"></i>' : '' ?>
                                            <?= !empty($session->tracker_completed) ? '<i class="bi bi-graph-up" title="Tracker completed"></i>' : '' ?>
                                            <?= !empty($session->tasks_completed) ? '<i class="bi bi-check-circle" title="Tasks completed"></i>' : '' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="session-actions">
                                    <button type="button" onclick="event.stopPropagation(); editSession(<?= $session->id ?>, <?= $session->patient_id ?>, '<?= htmlspecialchars($session->datetime) ?>', <?= $session->carenotes_completed ? 1 : 0 ?>, <?= $session->tracker_completed ? 1 : 0 ?>, <?= $session->tasks_completed ? 1 : 0 ?>, '<?= addslashes($session->notes) ?>', '<?= $session->status ?? 'offered' ?>')" class="action-icon" data-tooltip="Edit session"><i class="bi bi-pencil"></i></button>
                                    <button type="button" onclick="event.stopPropagation(); archiveSession(<?= $session->id ?>, '<?= $session->ward ?>')" class="action-icon archive" data-tooltip="Archive session"><i class="bi bi-archive"></i></button>
                                    <button type="button" onclick="event.stopPropagation(); deleteSession(<?= $session->id ?>, '<?= $session->ward ?>', event)" class="action-icon delete" title="Delete session"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-sessions">
                        <i class="bi bi-calendar-x"></i>
                        <p>No sessions scheduled for today<br><span class="subtle-text">You're all caught up</span></p>
                        <button onclick="openSessionModal(null, null)" class="btn-add-session">+ Add a session</button>
                    </div>
                <?php endif; ?>
                <div id="filteredEmpty" class="empty-sessions" style="display:none;">
                    <i class="bi bi-funnel"></i>
                    <p>No sessions match the current filters</p>
                    <button onclick="openSessionModal(null, null)" class="btn-add-session">+ Add a session</button>
                </div>
            </div>

            <!-- RIGHT COLUMN: CALENDAR WIDGET -->
            <div class="bp-cal-panel">
                <p class="calendar-hint">Select a date to view sessions or add a new one</p>
                <div class="bp-cal-header">
                    <button class="bp-cal-nav" onclick="CalendarWidget.prevMonth()" title="Previous month"><i class="bi bi-chevron-left"></i></button>
                    <span class="bp-cal-title" id="calTitle">Loading...</span>
                    <button class="bp-cal-nav" onclick="CalendarWidget.nextMonth()" title="Next month"><i class="bi bi-chevron-right"></i></button>
                </div>
                <div class="bp-cal-weekdays">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
                <div class="bp-cal-grid" id="calGrid"><div class="bp-cal-loading">Loading calendar...</div></div>

<div class="bp-cal-legend">
    <span class="bp-cal-legend-label">Filter by:</span>
    <label class="ward-filter">
        <input type="checkbox" id="calFilterAll" checked onchange="CalendarWidget.handleFilter('all', this)">
        All
    </label>
    <label class="ward-filter">
        <input type="checkbox" id="calFilterHope" onchange="CalendarWidget.handleFilter('hope', this)">
        Hope<span class="bp-cal-dot" style="background:#eab308;margin-left:3px;"></span>
    </label>
    <label class="ward-filter">
        <input type="checkbox" id="calFilterLakeside" onchange="CalendarWidget.handleFilter('lakeside', this)">
        Lakeside<span class="bp-cal-dot" style="background:#22c55e;margin-left:3px;"></span>
    </label>
    <label class="ward-filter">
        <input type="checkbox" id="calFilterManor" onchange="CalendarWidget.handleFilter('manor', this)">
        Manor<span class="bp-cal-dot" style="background:#3b82f6;margin-left:3px;"></span>
    </label>
    <label class="ward-filter">
        <input type="checkbox" id="calFilterGroup" onchange="CalendarWidget.handleFilter('group', this)">
        Group<span class="bp-cal-dot" style="background:#8b5cf6;margin-left:3px;"></span>
    </label>
</div>
            </div>
        </div>

    <?php
    $ward = strtolower($patient['ward'] ?? '');
    $avatarClass = $ward === 'hope' ? 'avatar-hope' : ($ward === 'manor' ? 'avatar-manor' : ($ward === 'lakeside' ? 'avatar-lakeside' : ''));
    ?>

    <!-- PATIENT SELECTION -->
    <div class="patient-section">
        <h2><i class="bi bi-people"></i> Select Patient</h2>
        <div class="patient-controls">
            <div class="patient-select">
                <label>Choose a patient to view details</label>
                <select id="patientSelect" onchange="onPatientSelect(this.value)">
                    <option value="">— Select a patient —</option>
                    <?php
                    $grouped = ['Hope' => [], 'Lakeside' => [], 'Manor' => []];
                    foreach ($patients as $patient) {
                        if ($patient->is_discharged) continue;
                        $w = trim($patient->ward);
                        if (isset($grouped[$w])) $grouped[$w][] = $patient;
                        else $grouped[$w][] = $patient;
                    }
                    foreach ($grouped as &$list) {
                        usort($list, fn($a, $b) => (int)$a->room_number - (int)$b->room_number);
                    }
                    unset($list);
                    $rendered = [];
                    foreach (['Hope', 'Lakeside', 'Manor'] as $wardName) {
                        if (empty($grouped[$wardName])) continue;
                        $rendered[] = $wardName;
                        echo '<optgroup label="' . htmlspecialchars($wardName) . ' Ward">';
                        foreach ($grouped[$wardName] as $p) {
                            echo '<option value="' . (int)$p->id . '" data-ward="' . htmlspecialchars(strtolower($p->ward)) . '">';
                            // CHANGED: "Room X – Initials" format
                            echo 'Room ' . (int)$p->room_number . ' – ' . htmlspecialchars($p->initials);
                            echo '</option>';
                        }
                        echo '</optgroup>';
                    }
                    foreach ($grouped as $wardName => $list) {
                        if (in_array($wardName, $rendered) || empty($list)) continue;
                        echo '<optgroup label="' . htmlspecialchars($wardName) . ' Ward">';
                        foreach ($list as $p) {
                            echo '<option value="' . (int)$p->id . '" data-ward="' . htmlspecialchars(strtolower($p->ward)) . '">';
                            echo 'Room ' . (int)$p->room_number . ' – ' . htmlspecialchars($p->initials);
                            echo '</option>';
                        }
                        echo '</optgroup>';
                    }
                    ?>
                </select>
            </div>
            <div class="ward-checkboxes">
                <label>Filter by ward</label>
          <div class="checkbox-group">
    <label class="ward-option"><input type="checkbox" id="patientFilterAll" checked onchange="toggleAllPatients(this)"> All</label>
    <label class="ward-option"><input type="checkbox" value="hope" onchange="syncPatientAll()"> Hope</label>
    <label class="ward-option"><input type="checkbox" value="lakeside" onchange="syncPatientAll()"> Lakeside</label>
    <label class="ward-option"><input type="checkbox" value="manor" onchange="syncPatientAll()"> Manor</label>
</div>
            </div>
        </div>
    </div>
    </div>

   <!-- ADMIT MODAL -->
<div id="admitModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h2><i class="bi bi-person-plus"></i> Admit Patient</h2><button class="modal-close" onclick="closeAdmitModal()">✕</button></div>
        <form id="admitForm" onsubmit="submitAdmitForm(event)" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="form-group"><label>Ward</label><select name="ward" id="admitWard" onchange="updateAdmitRoomOptions()"><option value="">Select Ward</option><option value="Hope">Hope Ward</option><option value="Lakeside">Lakeside Ward</option><option value="Manor">Manor Ward</option></select></div>
            <div class="form-group"><label>Room Number</label><select name="room_number" id="admitRoom"><option value="">Select Ward first</option></select></div>
            <div class="form-group"><label>Patient Initials</label><input type="text" name="initials" maxlength="3" placeholder="e.g., JD"></div>
            <div class="form-group"><label>Admission Date</label><input type="date" name="admission_date" value="<?= date('Y-m-d') ?>"></div>
            <label class="checkbox-label"><input type="checkbox" name="core10_admission"> <i class="bi bi-check2-circle"></i> CORE-10 completed on admission</label>
            <div class="form-group"><label>Notes</label><textarea name="notes" rows="3" placeholder="Add admission notes..."></textarea></div>
            <div class="modal-actions"><button type="button" onclick="closeAdmitModal()" class="btn-secondary">Cancel</button><button type="submit" class="btn-primary">Admit Patient</button></div>
        </form>
    </div>
</div>

  <!-- SESSION MODAL -->
<div id="sessionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h2><i class="bi bi-calendar-plus"></i> Add Session</h2><button class="modal-close" onclick="closeSessionModal()">✕</button></div>
        <form id="sessionForm" onsubmit="submitSessionForm(event)" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="form-group"><label>Ward</label><select name="ward" id="sessionWard" onchange="filterPatientsByWard()"><option value="">All Patients</option><option value="Hope">Hope Ward</option><option value="Lakeside">Lakeside Ward</option><option value="Manor">Manor Ward</option></select></div>
            <div class="form-group">
                <label>Patient</label>
<select name="patient_id" id="sessionPatient">
    <option value="">Select Patient</option>
                    <?php
                    $sessionGrouped = ['Hope' => [], 'Lakeside' => [], 'Manor' => []];
                    foreach ($patients as $p) {
                        if ($p->is_discharged) continue;
                        $sessionGrouped[trim($p->ward)][] = $p;
                    }
                    foreach ($sessionGrouped as &$list) {
                        usort($list, fn($a, $b) => (int)$a->room_number - (int)$b->room_number);
                    }
                    unset($list);
                    foreach (['Hope', 'Lakeside', 'Manor'] as $wardName) {
                        if (empty($sessionGrouped[$wardName])) continue;
                        echo '<optgroup label="' . htmlspecialchars($wardName) . ' Ward">';
                        foreach ($sessionGrouped[$wardName] as $p) {
                            echo '<option value="' . (int)$p->id . '" data-ward="' . htmlspecialchars($p->ward) . '">';
                            echo 'Room ' . (int)$p->room_number . ' – ' . htmlspecialchars($p->initials);
                            echo '</option>';
                        }
                        echo '</optgroup>';
                    }
                    ?>
                </select>
                <small id="wardFilterMsg" style="display:none; color:#b45309; font-size:0.75rem;">No active patients in this ward</small>
            </div>
            <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="datetime" id="sessionDatetime" required></div>
            <div class="form-group">
    <label>Session Status</label>
    <select name="status" id="sessionStatus">
        <option value="offered" selected>Offered</option>
        <option value="completed">Completed</option>
        <option value="declined">Declined</option>
        <option value="dna">DNA</option>
    </select>
</div>
            <div class="form-group"><label>Components</label><div style="display: flex; gap: 1rem;"><label class="checkbox-label"><input type="checkbox" name="carenotes" value="1"> <i class="bi bi-journal-text"></i> CareNotes</label><label class="checkbox-label"><input type="checkbox" name="tracker" value="1"> <i class="bi bi-graph-up"></i> Tracker</label><label class="checkbox-label"><input type="checkbox" name="tasks" value="1"> <i class="bi bi-check-circle"></i> Tasks</label></div><input type="hidden" name="carenotes" value="0"><input type="hidden" name="tracker" value="0"><input type="hidden" name="tasks" value="0"></div>
            <div class="form-group"><label>Session Notes</label><textarea name="notes" rows="3" placeholder="Document session..."></textarea></div>
            <div class="modal-actions"><button type="button" onclick="closeSessionModal()" class="btn-secondary">Cancel</button><button type="submit" class="btn-primary">Save Session</button></div>
        </form>
    </div>
</div>

    <!-- EDIT SESSION MODAL -->
    <div id="editSessionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h2>Edit Session</h2><button class="modal-close" onclick="closeEditSessionModal()">✕</button></div>
            <form id="editSessionForm" onsubmit="submitEditSessionForm(event)">
                <input type="hidden" name="session_id" id="editSessionId"><input type="hidden" name="patient_id" id="editSessionPatientId"><input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="form-group"><label>Session Date & Time</label><input type="datetime-local" name="datetime" id="editSessionDatetime" required></div>
                <div class="form-group">
    <label>Session Status</label>
    <select name="status" id="editSessionStatus">
        <option value="offered">Offered</option>
        <option value="completed">Completed</option>
        <option value="declined">Declined</option>
        <option value="dna">DNA</option>
    </select>
</div>
                <div class="form-group"><label>Components</label><div style="display: flex; gap: 1rem;"><label class="checkbox-label"><input type="checkbox" name="carenotes" id="editSessionCarenotes" value="1"> CareNotes</label><label class="checkbox-label"><input type="checkbox" name="tracker" id="editSessionTracker" value="1"> Tracker</label><label class="checkbox-label"><input type="checkbox" name="tasks" id="editSessionTasks" value="1"> Tasks</label></div><input type="hidden" name="carenotes" value="0"><input type="hidden" name="tracker" value="0"><input type="hidden" name="tasks" value="0"></div>
                <div class="form-group"><label>Session Notes</label><textarea name="notes" id="editSessionNotes" rows="4"></textarea></div>
                <div class="modal-actions"><button type="button" onclick="closeEditSessionModal()" class="btn-secondary">Cancel</button><button type="submit" class="btn-primary">Update Session</button></div>
            </form>
        </div>
    </div>

    <!-- CHANGE ROOM MODAL -->
    <div id="changeRoomModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h2><i class="bi bi-door-open"></i> Change Room</h2><button class="modal-close" onclick="closeChangeRoomModal()">✕</button></div>
            <form id="changeRoomForm" onsubmit="submitChangeRoom(event)"><input type="hidden" name="csrf_token" value="<?= csrf_token() ?>"><input type="hidden" name="patient_id" id="changeRoomPatientId"><div class="form-group"><label>Ward</label><input type="text" id="changeRoomWard" readonly></div><div class="form-group"><label>New Room Number</label><select name="room_number" id="changeRoomSelect" required><option value="">Select Room</option></select></div><div class="form-group"><label>Reason (optional)</label><textarea name="reason" rows="2" placeholder="e.g., Clinical need, patient request..."></textarea></div><div class="modal-actions"><button type="button" onclick="closeChangeRoomModal()" class="btn-secondary">Cancel</button><button type="submit" class="btn-primary">Update Room</button></div></form>
        </div>
    </div>

    <!-- DISCHARGE MODAL (with backdate) -->
    <div id="dischargeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="bi bi-box-arrow-right"></i> Discharge Patient</h2>
                <button class="modal-close" onclick="closeDischargeModal()">✕</button>
            </div>
            <form id="dischargeForm" onsubmit="submitDischarge(event)">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="patient_id" id="dischargePatientId">
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="core10_discharge" checked> CORE-10 completed at discharge
                    </label>
                </div>

                <!-- NEW: Discharge Date (backdate allowed) -->
                <div class="form-group">
                    <label for="dischargeDate">Discharge Date</label>
                    <input type="date" name="discharge_date" id="dischargeDate" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Discharge Notes</label>
                    <textarea name="notes" rows="4" placeholder="Enter discharge summary and follow-up plans..."></textarea>
                </div>

                <div class="warning-message" style="background:#fef3c7; padding:0.75rem; border-radius:8px; margin:1rem 0;">
                    <p style="margin:0; font-size:0.85rem;">⚠️ Patient will be moved to discharged list.</p>
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="closeDischargeModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Confirm Discharge</button>
                </div>
            </form>
        </div>
    </div>

   <!-- PATIENT DETAILS MODAL -->
<div id="patientDetailsModal" class="modal">
    <div class="modal-content modal-lg">

        <div class="modal-header">
            <h2>Patient: <span id="viewPatientName"></span></h2>
            <button class="modal-close" onclick="closePatientDetailsModal()">✕</button>
            <div style="display:flex; gap:0.5rem; margin-left:auto; align-items:center;">
            <button class="btn-primary" onclick="openSessionModalFromProfile()">+ Add Session</button>
            <button class="btn-secondary" onclick="openChangeRoomModal()">Change Room</button>
            <button class="btn-secondary" id="changeWardBtn" onclick="openChangeWardModal()" style="display:none;">Change Ward</button>
            <button class="btn-danger" onclick="openDischargeModal()">Discharge Patient</button>
            </div>
        </div>

        <!-- Patient Summary -->
        <div class="patient-summary">
            <div class="summary-grid">

                <div class="summary-item">
                    <span class="summary-label">Ward</span>
                    <span class="summary-value" id="viewPatientWard"></span>
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
                    <button class="btn-sm btn-secondary"
                        onclick="toggleCore10Admission()"
                        id="editCore10Btn"
                        style="margin-left: 8px; font-size: 11px; padding: 2px 8px;">
                        ✎ Edit
                    </button>
                </div>

                <div class="summary-item">
                    <span class="summary-label">Discharge CORE-10</span>
                    <span class="summary-value" id="viewPatientDischargeCore"></span>
                    <button class="btn-sm btn-secondary"
                        onclick="toggleCore10Discharge()"
                        id="editDischargeCore10Btn"
                        style="margin-left: 8px; font-size: 11px; padding: 2px 8px;">
                        ✎ Edit
                    </button>
                </div>

            </div>
        </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('sessions')" id="sessionsTabBtn">
                    All Sessions
                </button>

                <button class="tab-btn" onclick="switchTab('admission')" id="admissionTabBtn">
                    Admission Notes
                </button>

                <button class="tab-btn" onclick="switchTab('discharge')" id="dischargeTabBtn">
                    Discharge Notes
                </button>
                <button class="tab-btn" onclick="switchTab('transfer')" id="transferTabBtn" style="display:none;">
    Transfer History
</button>
            </div>
<div id="sessionsTab" class="tab-pane active">
    <div id="sessionsList" class="sessions-list" style="overflow-x:auto;">
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

            <div id="transferTab" class="tab-pane">
    <div id="transferHistory" class="sessions-list" style="overflow-x:auto;">
        <div class="loading">Loading transfer history...</div>
    </div>
</div>

            <div class="modal-actions">
                <button onclick="closePatientDetailsModal()" class="btn-secondary">
                    Close
                </button>
            </div>

        </div>
    </div>

    <!-- SINGLE SESSION VIEW MODAL -->
    <div id="singleSessionModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header"><h2><i class="bi bi-calendar-event"></i> Session Details</h2><button class="modal-close" onclick="closeSingleSessionModal()">✕</button></div>
            <div class="session-detail-content">
                <div class="detail-group"><label>Patient</label><div class="detail-value" id="sessionDetailPatient">—</div></div>
                <div class="detail-group"><label>Date & Time</label><div class="detail-value" id="sessionDetailDatetime">—</div></div>
                <div class="detail-group"><label>Ward</label><div class="detail-value" id="sessionDetailWard">—</div></div>
                <div class="detail-group"><label>Room</label><div class="detail-value" id="sessionDetailRoom">—</div></div>
                <div class="detail-group"><label>Components Completed</label><div class="detail-value" id="sessionDetailComponents">—</div></div>
                <div class="detail-group"><label>Session Status</label><div class="detail-value" id="sessionDetailStatus">—</div></div>
                <div class="detail-group"><label>Session Notes</label><div class="detail-value notes-content" id="sessionDetailNotes">—</div></div>
            </div>
            <div class="modal-actions" style="justify-content: space-between; margin-top: 1.5rem;">
                <button onclick="closeSingleSessionModal()" class="btn-secondary">Close</button>
                <div>
                    <button onclick="editCurrentSession()" class="btn-primary" id="editSessionBtn">Edit Session</button>
                    <button onclick="viewFullHistoryFromSession()" class="btn-secondary" id="viewHistoryBtn">View Full History</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GROUP SESSION MODAL (accessibility‑fixed) -->
    <div id="groupSessionModal" class="modal">
        <div class="modal-content gs-modal-container">

            <div class="modal-header">
                <h2><i class="bi bi-people-fill"></i> Add Group Session</h2>
                <button class="modal-close" onclick="closeGroupSessionModal()">✕</button>
            </div>

            <form id="groupSessionForm" onsubmit="submitGroupSession(event)" novalidate>
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="group_session_id" id="groupSessionId">

                <!-- Row 1: Group Type + Ward Filter -->
                <div class="gs-form-row">
                    <div class="form-group gs-form-group">
                        <label for="groupType">Group Type</label>
                   <select name="group_type" id="groupType" class="gs-select" onchange="toggleCustomGroupType()">
    <option value="" disabled selected>Select group type…</option>
        <option value="CBT">CBT</option>
        <option value="DBT">DBT</option>
        <option value="Skills">Skills</option>
        <option value="Music Therapy">Music Therapy</option>
        <option value="Art Therapy">Art Therapy</option>
        <option value="Other">Other</option>
</select>

                        <input type="text" id="customGroupType" name="custom_group_type" class="gs-input" placeholder="Enter custom group type" style="display:none; margin-top:10px;">
                    </div>

                    <div class="form-group gs-form-group">
                        <label>Filter Patients by Ward</label>
                        <div class="gs-checkbox-group-vertical">
                            <label class="checkbox-label gs-checkbox">
                                <input type="checkbox" id="filterWardHope" value="Hope" onchange="loadGroupAttendanceTable()">
                                <span>Hope</span>
                            </label>
                            <label class="checkbox-label gs-checkbox">
                                <input type="checkbox" id="filterWardLakeside" value="Lakeside" onchange="loadGroupAttendanceTable()">
                                <span>Lakeside</span>
                            </label>
                            <label class="checkbox-label gs-checkbox">
                                <input type="checkbox" id="filterWardManor" value="Manor" onchange="loadGroupAttendanceTable()">
                                <span>Manor</span>
                            </label>
                        </div>
                        <small class="form-text text-muted">Select one or multiple wards</small>
                    </div>
                </div>

                <!-- Date & Time -->
                <div class="form-group">
                    <label for="groupSessionDatetime">Date & Time</label>
                    <input type="datetime-local" name="datetime" id="groupSessionDatetime" required class="gs-input">
                </div>

                <!-- Attendance Register -->
                <div class="form-group">
                    <label>Patient Attendance Register</label>
                    <div id="groupAttendanceTable" class="gs-attendance-table-wrapper">
                        <p class="gs-placeholder">Select ward(s) to load patients</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="groupSessionNotes">Group Session Notes</label>
                    <textarea name="notes" id="groupSessionNotes" rows="4" class="gs-textarea" placeholder="Enter group session notes..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="closeGroupSessionModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Group Session</button>
                </div>
            </form>
        </div>
    </div>


    <!-- PATIENT NOTE MODAL -->
<div id="patientNoteModal" class="modal" style="z-index:1200;">
    <div class="modal-content" style="max-width:560px;">
        <div class="modal-header">
            <h2 style="font-size:1.1rem;">Session Note</h2>
            <button class="modal-close" onclick="closePatientNoteModal()">✕</button>
        </div>
        <div style="padding:0 0 1rem;">
            <div id="patientNoteModalContent" class="notes-content" style="background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;min-height:80px;max-height:400px;overflow-y:auto;white-space:pre-wrap;line-height:1.6;font-size:0.9rem;"></div>
            <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-top:1rem;">
                <button id="patientNoteCopyBtn" onclick="copyPatientNote()" style="padding:0.5rem 1.2rem;border-radius:2rem;border:1px solid #e2e8f0;background:#f1f5f9;cursor:pointer;font-size:0.85rem;">Copy</button>
                <button onclick="closePatientNoteModal()" style="padding:0.5rem 1.2rem;border-radius:2rem;border:none;background:#1e3a8a;color:white;cursor:pointer;font-size:0.85rem;">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- VIEW GROUP SESSIONS MODAL -->
    <div id="viewGroupSessionsModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2><i class="bi bi-table"></i> Group Sessions</h2>
                <button class="modal-close" onclick="closeViewGroupSessionsModal()">✕</button>
            </div>
            <div id="groupSessionsList" style="max-height: 70vh; overflow-y: auto;">
                <div class="loading">Loading...</div>
            </div>
            <div class="modal-actions">
                <button onclick="closeViewGroupSessionsModal()" class="btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- GROUP SESSION DETAILS MODAL (view only) -->
    <div id="groupSessionDetailsModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2 id="groupSessionDetailTitle"><i class="bi bi-people-fill"></i> Group Session</h2>
                <button class="modal-close" onclick="closeGroupSessionDetailsModal()">✕</button>
            </div>
            <div class="session-detail-content">
                <div class="detail-group"><label>Date & Time</label><div class="detail-value" id="groupSessionDetailDatetime">—</div></div>
                <div class="detail-group"><label>Ward</label><div class="detail-value" id="groupSessionDetailWard">—</div></div>
                <div class="detail-group"><label>Notes</label><div class="detail-value notes-content" id="groupSessionDetailNotes">—</div></div>
                <div class="detail-group"><label>Attendance</label><div id="groupSessionDetailAttendance" class="detail-value">—</div></div>
            </div>
            <div class="modal-actions">
                <button onclick="closeGroupSessionDetailsModal()" class="btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- CHANGE WARD MODAL -->
<div id="changeWardModal" class="modal" style="z-index:1100;">
    <div class="modal-content" style="max-width:480px;">
        <div class="modal-header">
            <h2><i class="bi bi-arrow-left-right"></i> Change Patient Ward</h2>
            <button class="modal-close" onclick="closeChangeWardModal()">✕</button>
        </div>
        <form id="changeWardForm" onsubmit="submitWardTransfer(event)">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="patient_id" id="changeWardPatientId">
            <div class="form-group">
                <label>Current Ward</label>
                <input type="text" id="changeWardFrom" readonly style="background:#f8fafc;">
            </div>
            <div class="form-group">
    <label>Transfer To</label>
    <input type="text" id="changeWardTo" readonly style="background:#f8fafc;font-weight:600;">
</div>
<div class="form-group">
    <label>Available Room in <span id="changeWardToLabel"></span></label>
    <select name="room_number" id="changeWardRoomSelect" required>
        <option value="">Loading rooms...</option>
    </select>
</div>
            <div class="form-group">
                <label>Reason for Transfer <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
                <textarea name="reason" id="changeWardReason" rows="3" placeholder="e.g. Clinical transfer, ward move..."></textarea>
            </div>
            <div style="background:#fef9c3;padding:0.75rem;border-radius:0.5rem;margin-bottom:1rem;font-size:0.82rem;color:#854d0e;">
                <i class="bi bi-info-circle"></i> This will transfer the patient to another ward while preserving all clinical records and history.
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeChangeWardModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Transfer Patient</button>
            </div>
        </form>
    </div>
</div>



<!-- CALENDAR DAY DETAIL MODAL -->
<div id="calDayModal" class="modal" style="z-index:1050;">
    <div class="modal-content" style="max-width:460px;">
        <div class="modal-header">
            <h2 id="calDayTitle" style="font-size:1.1rem;"><i class="bi bi-calendar-day"></i> Sessions</h2>
            <button class="modal-close" onclick="document.getElementById('calDayModal').style.display='none'">✕</button>
        </div>
     
<div class="cal-day-filters" id="calDayFilters" style="padding:0.5rem 0.75rem;border-bottom:1px solid #e2e8f0;display:flex;gap:0.5rem;flex-wrap:wrap;">
    <select id="calDayWardFilter" onchange="filterCalDayList()" style="font-size:0.78rem;padding:0.3rem 0.5rem;border:1px solid #e2e8f0;border-radius:0.4rem;background:white;">
        <option value="all">All Wards</option>
        <option value="Hope">Hope</option>
        <option value="Lakeside">Lakeside</option>
        <option value="Manor">Manor</option>
    </select>
    <select id="calDayGroupFilter" onchange="filterCalDayList()" style="font-size:0.78rem;padding:0.3rem 0.5rem;border:1px solid #e2e8f0;border-radius:0.4rem;background:white;">
        <option value="all">All Groups</option>
        <option value="CBT">CBT</option>
        <option value="DBT">DBT</option>
         <option value="Skills">Skills</option>
        <option value="Music Therapy">Music Therapy</option>
        <option value="Art Therapy">Art Therapy</option>
        <option value="Other">Other</option>
    </select>
</div>
<div id="calDayList" style="min-height:60px;"></div>

      <div class="modal-actions" style="margin:0 0.5rem 0.5rem;padding-top:1rem;border-top:1px solid var(--clinical-border);display:flex;gap:0.75rem;flex-shrink:0;position:sticky;bottom:0;background:white;z-index:10;">
    <button onclick="document.getElementById('calDayModal').style.display='none'" style="padding:0.5rem 1.2rem;font-size:0.9rem;border-radius:2rem;border:1px solid #e2e8f0;background:#f1f5f9;cursor:pointer;font-weight:500;">Close</button>
    <button id="calDayAddBtn" style="padding:0.5rem 1.2rem;font-size:0.9rem;border-radius:2rem;border:none;background:var(--clinical-blue);color:white;cursor:pointer;font-weight:500;white-space:nowrap;">+ Add Session</button>
    <button id="calDayAddGroupBtn" style="padding:0.5rem 1.2rem;font-size:0.9rem;border-radius:2rem;border:1px solid #e2e8f0;background:#f1f5f9;cursor:pointer;font-weight:500;white-space:nowrap;">+ Add Group Session</button>
</div>
    </div>
</div>

    <!-- Notification toast -->
<div id="notification" style="display:none; position:fixed; bottom:20px; right:20px; background:#1e3a8a; color:white; padding:0.7rem 1.2rem; border-radius:0.5rem; z-index:99999; box-shadow:0 4px 12px rgba(0,0,0,0.15);"></div>
    <style>
    .session-detail-content { display: flex; flex-direction: column; gap: 1rem; }
    .detail-group { display: flex; flex-direction: column; gap: 0.25rem; }
    .detail-group label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
    .detail-group .detail-value { font-size: 0.9rem; color: #1e293b; background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; }
    .detail-group .notes-content { white-space: pre-wrap; line-height: 1.5; max-height: 200px; overflow-y: auto; }
    .component-badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.7rem; font-weight: 500; margin-right: 0.5rem; margin-bottom: 0.25rem; }
    .component-badge.completed { background: #d1fae5; color: #065f46; }
    .component-badge.pending { background: #fee2e2; color: #991b1b; }
    </style>
    <script>
    // ==================== PATIENT DATA FOR ROOM AVAILABILITY ====================
    const allActivePatients = <?php 
        $activePatients = array_filter($patients ?? [], function($p) { return !$p->is_discharged; });
        $patientData = array_map(function($p) { return ['id' => $p->id, 'ward' => $p->ward, 'room_number' => (int)$p->room_number]; }, array_values($activePatients));
        echo json_encode($patientData);
    ?>;

    function getOccupiedRooms(ward, excludePatientId = null) {
        const occupied = [];
        for (const p of allActivePatients) { if (p.ward === ward && (excludePatientId === null || p.id != excludePatientId)) { occupied.push(p.room_number); } }
        return occupied;
    }

    function updateAdmitRoomOptions() {
        const wardSelect = document.getElementById('admitWard');
        const roomSelect = document.getElementById('admitRoom');
        const selectedWard = wardSelect.value;
        roomSelect.innerHTML = '<option value="">Select Room</option>';
        if (!selectedWard) return;
        const totalRooms = (selectedWard === 'Hope') ? 12 : 10;
        const occupiedRooms = getOccupiedRooms(selectedWard);
        for (let i = 1; i <= totalRooms; i++) {
            const option = document.createElement('option');
            option.value = i;
            let text = 'Room ' + i;
            if (occupiedRooms.includes(i)) { text += ' (Occupied)'; option.disabled = true; }
            option.textContent = text;
            roomSelect.appendChild(option);
        }
    }

    window.openAdmitModal = function(ward = null) {
        const form = document.getElementById('admitForm');
        if (form) form.reset();
        const wardSelect = document.getElementById('admitWard');
        if (wardSelect) wardSelect.value = '';
        updateAdmitRoomOptions();
        document.getElementById('admitModal').style.display = 'flex';
    };

    window.openChangeRoomModal = async function() {
        const patientId = currentViewPatientId || currentSelectedPatientId;
        if (!patientId) { showMessage('Please select a patient first', true); return; }
        document.getElementById('changeRoomPatientId').value = patientId;
        try {
            const response = await fetch('<?= url('patients/get-summary') ?>?id=' + patientId);
            const data = await response.json();
            document.getElementById('changeRoomWard').value = data.ward;
            const roomSelect = document.getElementById('changeRoomSelect');
            roomSelect.innerHTML = '<option value="">Select Room</option>';
            const totalRooms = (data.ward === 'Hope') ? 12 : 10;
            const occupiedRooms = getOccupiedRooms(data.ward, patientId);
            for (let i = 1; i <= totalRooms; i++) {
                const option = document.createElement('option');
                option.value = i;
                let text = 'Room ' + i;
                if (occupiedRooms.includes(i)) { text += ' (Occupied)'; option.disabled = true; }
                if (i == data.room_number) option.selected = true;
                option.textContent = text;
                roomSelect.appendChild(option);
            }
            document.getElementById('changeRoomModal').style.display = 'flex';
            bringModalToFront('changeRoomModal');
        } catch (err) { showMessage('Error loading patient data', true); }
    };

    document.getElementById('admitWard')?.addEventListener('change', updateAdmitRoomOptions);

    // ==================== STATE MANAGEMENT ====================
    let currentSelectedPatientId = null;
    let currentViewPatientId = null;
    let selectedPatientName = '';
    let core10EditMode = false;
    let originalZIndex = {};
    let currentSingleSession = null;

    function bringModalToFront(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        if (!originalZIndex[modalId]) originalZIndex[modalId] = window.getComputedStyle(modal).zIndex || '1000';
        let maxZ = 1000;
        document.querySelectorAll('.modal').forEach(m => { if (m.style.display === 'flex') { const z = parseInt(window.getComputedStyle(m).zIndex); if (!isNaN(z) && z > maxZ) maxZ = z; } });
        modal.style.zIndex = maxZ + 100;
    }

    function addBodyClass() {
        const anyModalOpen = Array.from(document.querySelectorAll('.modal')).some(m => m.style.display === 'flex');
        if (anyModalOpen) document.body.classList.add('modal-open');
        else document.body.classList.remove('modal-open');
    }
    const modalDisplayObserver = new MutationObserver(function(mutations) { mutations.forEach(function(mutation) { if (mutation.attributeName === 'style') setTimeout(addBodyClass, 50); }); });
    document.querySelectorAll('.modal').forEach(modal => { modalDisplayObserver.observe(modal, { attributes: true }); });

    // ==================== PATIENT SELECTION ====================
   function onPatientSelect(patientId) {
    if (!patientId) {
        currentSelectedPatientId = null;
        selectedPatientName = '';
        applyAllFilters();
        return;
    }

    modalStack.length = 0; // clear stack — direct navigation from dropdown

    currentSelectedPatientId = patientId;

    const select = document.getElementById('patientSelect');
    const selectedOpt = select.options[select.selectedIndex];
   const optLabel = selectedOpt ? selectedOpt.textContent.trim() : '—';
const initialsMatch = optLabel.match(/–\s*([A-Z]+)$/);
const roomMatch = optLabel.match(/Room\s*(\d+)/);
const initials = initialsMatch ? initialsMatch[1] : optLabel;
const room = roomMatch ? ', Room ' + roomMatch[1] : '';
const displayName = initials + room;
viewPatientDetails(patientId, displayName);

    select.value = '';
}

    function clearSelectedPatient() {
        currentSelectedPatientId = null;
        selectedPatientName = '';
        document.getElementById('patientSelect').value = '';
        applyAllFilters();
    }

    function addSessionForSelectedPatient() { openSessionModal(null, null); }

    // ==================== PATIENT DETAILS MODAL ====================
 function viewPatientDetails(patientId, patientName) {
    if (!patientId) return;
    currentViewPatientId = patientId;
    document.getElementById('viewPatientName').innerText = patientName;
    document.getElementById('patientDetailsModal').style.display = 'flex';
    bringModalToFront('patientDetailsModal');
    loadPatientSummary(patientId);
    loadAllSessions(patientId);
    loadAdmissionNotes(patientId);
    loadDischargeNotes(patientId);
    loadWardTransferHistory(patientId);
    switchTab('sessions');

    // Show Change Ward button only for Manor/Lakeside
    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(r => r.json())
        .then(data => {
            const btn = document.getElementById('changeWardBtn');
            if (btn) {
                btn.style.display = (data.ward === 'Manor' || data.ward === 'Lakeside') ? '' : 'none';
            }
        });
}

function closePatientDetailsModal() {
    currentViewPatientId = null;
    document.getElementById('patientDetailsModal').style.display = 'none';
    
    if (modalStack.length > 0) {
        const previous = modalStack.pop();
        if (previous && document.getElementById(previous)) {
            document.getElementById(previous).style.display = 'flex';
            bringModalToFront(previous);
            // If going back to single session modal, refresh its display
            if (previous === 'singleSessionModal' && currentSingleSession) {
                displaySingleSession(currentSingleSession, currentSingleSession.initials);
            }
        }
    }
}
    function loadPatientSummary(patientId) {
    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(r => r.json())
        .then(data => {
            document.getElementById('viewPatientWard').innerText = data.ward || 'N/A';
            document.getElementById('viewPatientRoom').innerText = data.room_number || 'N/A';
            document.getElementById('viewPatientAdmission').innerText = data.admission_date || 'N/A';
            document.getElementById('viewPatientAdmissionCore').innerHTML = data.core10_admission ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-warning">Pending</span>';
            document.getElementById('viewPatientDischargeCore').innerHTML = data.core10_discharge ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-warning">Pending</span>';

            // Always update header with initials + room for consistency
            const currentName = document.getElementById('viewPatientName').innerText;
            const initials = currentName.split(',')[0].trim();
            if (data.room_number) {
                document.getElementById('viewPatientName').innerText = `${initials}, Room ${data.room_number}`;
            }
        });
}

    function loadAllSessions(patientId) {
        const container = document.getElementById('sessionsList');
        container.innerHTML = '<div class="loading">Loading sessions...</div>';
        fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
            .then(r => r.json())
            .then(data => {
                if (!data.length) { container.innerHTML = '<div class="no-notes">No sessions recorded for this patient</div>'; return; }
let html = '<table class="sessions-table"><thead><tr><th>Date & Time</th><th>Session Status</th><th>CareNotes</th><th>Tracker</th><th>Tasks</th><th>Notes</th><th>Actions</th></tr></thead><tbody>';          
                data.forEach(s => {
                    const date = new Date(s.datetime);
                    const formatted = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                  const fullNote = s.notes || '';
                    
                 const sessionStatus = (s.status || 'offered').toLowerCase();
const statusColours = {
    offered:   { bg: '#e0f2fe', color: '#0369a1' },
    completed: { bg: '#d1fae5', color: '#065f46' },
    declined:  { bg: '#fed7aa', color: '#92400e' },
    dna:       { bg: '#fee2e2', color: '#991b1b' }
};
const sc = statusColours[sessionStatus] || statusColours['offered'];
const statusBadge = `<span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${sc.bg};color:${sc.color};">${sessionStatus.toUpperCase()}</span>`;

html += `<tr data-session-id="${s.id}">
    <td style="white-space: nowrap;">${formatted}</td>
    <td>${statusBadge}</td>
    <td class="status-icon">${s.carenotes_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">○ Pending</span>'}</td>
    <td class="status-icon">${s.tracker_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">○ Pending</span>'}</td>
    <td class="status-icon">${s.tasks_completed ? '<span class="component-badge completed">✓ Completed</span>' : '<span class="component-badge pending">○ Pending</span>'}</td>
    <td>${fullNote.trim() ? `<button onclick="openPatientNoteModal(${s.id}, \`${escapeHtml(fullNote).replace(/`/g, '&#96;')}\`)" style="font-size:0.7rem;padding:2px 8px;border-radius:4px;border:1px solid #e2e8f0;background:#f8fafc;color:#2563eb;cursor:pointer;white-space:nowrap;">View</button>` : '<span style="font-size:0.75rem;color:#94a3b8;font-style:italic;">No notes recorded</span>'}</td>
    <td class="session-actions">
        <button onclick="event.stopPropagation(); editSession(${s.id}, ${s.patient_id}, '${s.datetime}', ${s.carenotes_completed}, ${s.tracker_completed}, ${s.tasks_completed}, '${(s.notes || '').replace(/'/g, "\\'")}', '${s.status || 'offered'}')" class="action-icon" title="Edit session"><i class="bi bi-pencil"></i></button>
        <button onclick="event.stopPropagation(); archiveSession(${s.id}, '${s.ward}')" class="action-icon" title="Archive session"><i class="bi bi-archive"></i></button>
        <button onclick="event.stopPropagation(); deleteSession(${s.id}, '${s.ward}', event)" class="action-icon" title="Delete session"><i class="bi bi-trash"></i></button>
    </td>
</tr>`;
                });
                html += '</tbody></table>';
                container.innerHTML = html;
            });
    }

    // ==================== SESSION FILTER ALL ====================
function toggleAllSessions(allCb) {
    document.querySelectorAll('.ward-filter input[type="checkbox"]:not(#sessionFilterAll)')
        .forEach(cb => cb.checked = false);
    if (!allCb.checked) allCb.checked = true;
    applyAllFilters();
}

function syncSessionAll() {
    const allCb = document.getElementById('sessionFilterAll');
    if (allCb) allCb.checked = false;
    const specific = document.querySelectorAll('.ward-filter input[type="checkbox"]:not(#sessionFilterAll)');
    const anyChecked = Array.from(specific).some(cb => cb.checked);
    if (!anyChecked) {
        if (allCb) allCb.checked = true;
    }
    applyAllFilters();
}

function toggleAllPatients(allCb) {
    document.querySelectorAll('.ward-option input[type="checkbox"]:not(#patientFilterAll)')
        .forEach(cb => cb.checked = false);
    if (!allCb.checked) allCb.checked = true;
    filterPatients();
}

function syncPatientAll() {
    const allCb = document.getElementById('patientFilterAll');
    if (allCb) allCb.checked = false;
    const specific = document.querySelectorAll('.ward-option input[type="checkbox"]:not(#patientFilterAll)');
    const anyChecked = Array.from(specific).some(cb => cb.checked);
    if (!anyChecked) {
        if (allCb) allCb.checked = true;
    }
    filterPatients();
}

function openSessionModalFromProfile() {
    closePatientDetailsModal();
    setTimeout(() => {
        openSessionModal(null, null);
    }, 100);
}

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    function loadAdmissionNotes(patientId) {
        const container = document.getElementById('admissionNotes');
        container.innerHTML = '<div class="loading">Loading admission notes...</div>';
        fetch('<?= url('patients/get-notes') ?>?id=' + patientId)
            .then(r => r.json())
            .then(data => { if (data.notes && data.notes.trim()) container.innerHTML = `<div class="notes-content">${data.notes.replace(/\n/g, '<br>')}</div>`; else container.innerHTML = '<div class="no-notes">No admission notes available</div>'; });
    }
    function loadDischargeNotes(patientId) {
        const container = document.getElementById('dischargeNotes');
        container.innerHTML = '<div class="loading">Loading discharge notes...</div>';
        fetch('<?= url('patients/get-discharge-notes') ?>?id=' + patientId)
            .then(r => r.json())
            .then(data => {
                if (data.notes && data.notes.trim()) {
                    let notesText = data.notes;
                    const notesMatch = notesText.match(/Notes:\s*(.*?)(?:\n|$)/is);
                    if (notesMatch && notesMatch[1]) notesText = notesMatch[1].trim();
                    else { const notesIndex = notesText.indexOf('Notes:'); if (notesIndex !== -1) notesText = notesText.substring(notesIndex + 6).trim(); }
                    notesText = notesText.replace(/={3,}/g, '').trim();
                    container.innerHTML = `<div class="notes-content">${notesText.replace(/\n/g, '<br>')}</div>`;
                } else container.innerHTML = '<div class="no-notes">No discharge notes available</div>';
            });
    }
    function switchTab(tab) {
        const sessionsTab = document.getElementById('sessionsTab');
        const admissionTab = document.getElementById('admissionTab');
        const dischargeTab = document.getElementById('dischargeTab');
        const sessionsBtn = document.getElementById('sessionsTabBtn');
        const admissionBtn = document.getElementById('admissionTabBtn');
        const dischargeBtn = document.getElementById('dischargeTabBtn');
       [sessionsTab, admissionTab, dischargeTab, document.getElementById('transferTab')].forEach(t => { if(t) t.classList.remove('active'); });
       [sessionsBtn, admissionBtn, dischargeBtn, document.getElementById('transferTabBtn')].forEach(b => { if(b) b.classList.remove('active'); });
        if (tab === 'sessions')  { sessionsTab.classList.add('active');  sessionsBtn.classList.add('active'); }
else if (tab === 'admission') { admissionTab.classList.add('active'); admissionBtn.classList.add('active'); }
else if (tab === 'discharge') { dischargeTab.classList.add('active'); dischargeBtn.classList.add('active'); }
else if (tab === 'transfer')  {
            document.getElementById('transferTab').classList.add('active');
            document.getElementById('transferTabBtn').classList.add('active');
        }
    }

    // ==================== SESSION MODAL: WARD FILTERING ====================
    function filterPatientsByWard() {
    const wardSelect = document.getElementById('sessionWard');
    const patientSelect = document.getElementById('sessionPatient');
    const msgSpan = document.getElementById('wardFilterMsg');
    const selectedWard = wardSelect.value;
    wardSelect.setCustomValidity('');
    let visibleCount = 0;

    // Hide/show options
    Array.from(patientSelect.options).forEach(opt => {
        if (opt.value === '') return;
        const patientWard = opt.getAttribute('data-ward');
        if (!selectedWard || patientWard === selectedWard) {
            opt.style.display = '';
            visibleCount++;
        } else {
            opt.style.display = 'none';
        }
    });

    // Hide/show optgroup labels
    Array.from(patientSelect.querySelectorAll('optgroup')).forEach(group => {
        const anyVisible = Array.from(group.querySelectorAll('option'))
            .some(opt => opt.style.display !== 'none');
        group.style.display = anyVisible ? '' : 'none';
    });

    if (visibleCount === 0 && selectedWard) {
        msgSpan.style.display = 'inline-block';
        patientSelect.value = '';
    } else {
        msgSpan.style.display = 'none';
    }
}



async function deleteGroupSession(sessionId) {
    if (!confirm('⚠️ Permanently delete this group session and all attendance records? This cannot be undone.')) return;

    const formData = new FormData();
    formData.append('id', sessionId);
    formData.append('csrf_token', '<?= csrf_token() ?>');

    try {
        const response = await fetch('<?= url('group-sessions/delete') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            showMessage('Group session deleted');
            if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh();
            openViewGroupSessionsModal(); // Refresh the list
        } else {
            showMessage(data.error || 'Failed to delete', true);
        }
    } catch (err) {
        showMessage('Network error', true);
    }
}

    function resetPatientDropdown() { const patientSelect = document.getElementById('sessionPatient'); Array.from(patientSelect.options).forEach(opt => { opt.style.display = ''; }); document.getElementById('wardFilterMsg').style.display = 'none'; }

    // ==================== FILTERS ====================
   function applyAllFilters() {
    const allCb = document.getElementById('sessionFilterAll');
    const isAll = allCb && allCb.checked;
    const activeWards = Array.from(document.querySelectorAll('.ward-filter input[type="checkbox"]:not(#sessionFilterAll):checked')).map(cb => cb.value);
    const rows = document.querySelectorAll('.session-card');
    let visible = 0;
    rows.forEach(card => {
        const ward = card.dataset.ward;
        const show = isAll || activeWards.includes(ward);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    const badge = document.getElementById('sessionCountBadge');
    if (badge) badge.textContent = visible;
    const filteredEmpty = document.getElementById('filteredEmpty');
    if (filteredEmpty) filteredEmpty.style.display = (rows.length && visible === 0) ? '' : 'none';
}
   function filterPatients() {
    const allCb = document.getElementById('patientFilterAll');
    const isAll = allCb && allCb.checked;
    const selectedWards = Array.from(document.querySelectorAll('.ward-option input[type="checkbox"]:not(#patientFilterAll):checked')).map(cb => cb.value);
    const select = document.getElementById('patientSelect');

    Array.from(select.options).forEach((opt, idx) => {
        if (idx === 0) return;
        const ward = opt.getAttribute('data-ward');
        const match = isAll || selectedWards.includes(ward);
        opt.style.display = match ? '' : 'none';
    });

    Array.from(select.querySelectorAll('optgroup')).forEach(group => {
        const anyVisible = Array.from(group.querySelectorAll('option'))
            .some(opt => opt.style.display !== 'none');
        group.style.display = anyVisible ? '' : 'none';
    });
}
    

    // ==================== MODAL HELPERS ====================
    function closeAdmitModal() { document.getElementById('admitModal').style.display = 'none'; }
    function openSessionModal(ward, prefilledDate) {
        const modal = document.getElementById('sessionModal');
        const dtInput = document.getElementById('sessionDatetime');
        const form = document.getElementById('sessionForm');
        const wardSelect = document.getElementById('sessionWard');
        form.reset();
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('[name]').forEach(el => el.setCustomValidity(''));
        modal.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        resetPatientDropdown();
        if (prefilledDate) { dtInput.value = prefilledDate + 'T09:00'; }
        else { const now = new Date(); const year = now.getFullYear(); const month = String(now.getMonth() + 1).padStart(2, '0'); const day = String(now.getDate()).padStart(2, '0'); const hours = String(now.getHours()).padStart(2, '0'); dtInput.value = `${year}-${month}-${day}T${hours}:00`; }
        if (ward) { wardSelect.value = ward; wardSelect.setCustomValidity(''); wardSelect.dispatchEvent(new Event('change')); modal.style.display = 'flex'; }
        else if (currentSelectedPatientId && !prefilledDate) {
            fetch('<?= url('patients/get-summary') ?>?id=' + currentSelectedPatientId)
                .then(r => r.json())
                .then(data => { wardSelect.value = data.ward; filterPatientsByWard(); const patientSelect = document.getElementById('sessionPatient'); Array.from(patientSelect.options).forEach(opt => { if (opt.value == currentSelectedPatientId) { opt.selected = true; } }); modal.style.display = 'flex'; });
            return;
        } else { wardSelect.value = ""; wardSelect.setCustomValidity(''); wardSelect.dispatchEvent(new Event('change')); modal.style.display = 'flex'; }
    }
    function closeSessionModal() {
    document.getElementById('sessionModal').style.display = 'none';
    if (modalStack.length > 0) {
        const previous = modalStack.pop();
        if (previous && document.getElementById(previous)) {
            document.getElementById(previous).style.display = 'flex';
            bringModalToFront(previous);
            if (previous === 'calDayModal' && window._calDayDate) {
                CalendarWidget.dayClick(window._calDayDate);
            }
        }
    }
}

function filterCalDayList() {
    const wardFilter = document.getElementById('calDayWardFilter')?.value || 'all';
    const groupFilter = document.getElementById('calDayGroupFilter')?.value || 'all';

    document.querySelectorAll('.day-session-item').forEach(item => {
        const isGroup = item.classList.contains('gs-item');
        let show = true;

        if (wardFilter !== 'all') {
            if (isGroup) {
                const wardText = item.innerHTML;
                show = wardText.includes(wardFilter);
            } else {
                const wardSpan = item.querySelector('.session-ward');
                const ward = wardSpan ? wardSpan.className.replace('session-ward ward-', '') : '';
                show = ward.toLowerCase() === wardFilter.toLowerCase();
            }
        }

        if (show && groupFilter !== 'all') {
            if (isGroup) {
                const nameSpan = item.querySelector('.session-room');
                const name = nameSpan ? nameSpan.innerText : '';
                show = name.toLowerCase().includes(groupFilter.toLowerCase());
            } else {
                if (groupFilter !== 'all') show = false;
            }
        }

        item.style.display = show ? '' : 'none';
    });
}

function editSession(id, patient_id, datetime, carenotes, tracker, tasks, notes, status) {
    if (document.getElementById('patientDetailsModal').style.display === 'flex') {
        pushModal('patientDetailsModal');
    } else if (document.getElementById('singleSessionModal').style.display === 'flex') {
        pushModal('singleSessionModal');
    }

    document.getElementById('patientDetailsModal').style.display = 'none';
    document.getElementById('singleSessionModal').style.display = 'none';
    document.getElementById('editSessionId').value = id;
    document.getElementById('editSessionPatientId').value = patient_id;
    document.getElementById('editSessionDatetime').value = datetime;
    document.getElementById('editSessionCarenotes').checked = carenotes == 1;
    document.getElementById('editSessionTracker').checked = tracker == 1;
    document.getElementById('editSessionTasks').checked = tasks == 1;
    document.getElementById('editSessionNotes').value = notes || '';
    document.getElementById('editSessionModal').style.display = 'flex';
    document.getElementById('editSessionStatus').value = status || 'offered';
    bringModalToFront('editSessionModal');
}

function closeEditSessionModal() {
    document.getElementById('editSessionModal').style.display = 'none';
    if (modalStack.length > 0) {
        const previous = modalStack.pop();
        if (previous && document.getElementById(previous)) {
            document.getElementById(previous).style.display = 'flex';
            bringModalToFront(previous);
        }
    }
}

    // ==================== SINGLE SESSION VIEW ====================
    function openSingleSessionModal(sessionId, patientId, patientName) {
        const sessionCard = document.querySelector(`.session-card[data-session-id="${sessionId}"]`);
      if (sessionCard) {
            let notes = sessionCard.dataset.sessionNotes || '';
            notes = notes.replace(/\\'/g, "'");
            
            const sessionData = {
                id: sessionId,
                patient_id: patientId,
                initials: patientName,
                datetime: sessionCard.dataset.sessionDatetime,
                ward: sessionCard.dataset.sessionWard,
                room_number: sessionCard.dataset.sessionRoom,
                carenotes_completed: parseInt(sessionCard.dataset.sessionCarenotes),
                tracker_completed: parseInt(sessionCard.dataset.sessionTracker),
                tasks_completed: parseInt(sessionCard.dataset.sessionTasks),
                notes: notes,
                status: sessionCard.dataset.sessionStatus || 'offered'
            };
            currentSingleSession = sessionData;
            displaySingleSession(sessionData, patientName);
            document.getElementById('singleSessionModal').style.display = 'flex';
            bringModalToFront('singleSessionModal');
        } else {
            fetch('<?= url('sessions/get-by-patient') ?>?id=' + patientId)
                .then(r => r.json())
                .then(sessions => {
                    const session = sessions.find(s => s.id == sessionId);
                    if (session) {
                        const sessionData = {
                            id: session.id,
                            patient_id: session.patient_id,
                            initials: patientName,
                            datetime: session.datetime,
                            ward: session.ward || 'Hope',
                            room_number: session.room_number || '?',
                            carenotes_completed: session.carenotes_completed,
                            tracker_completed: session.tracker_completed,
                            tasks_completed: session.tasks_completed,
                            notes: session.notes || ''
                        };
                        currentSingleSession = sessionData;
                        displaySingleSession(sessionData, patientName);
                        document.getElementById('singleSessionModal').style.display = 'flex';
                        bringModalToFront('singleSessionModal');
                    } else {
                        showMessage('Session not found', true);
                    }
                })
                .catch(err => {
                    console.error('Error loading session:', err);
                    showMessage('Error loading session details', true);
                });
        }
    }
    function displaySingleSession(session, patientName) {
        const date = new Date(session.datetime);
        const formattedDatetime = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        
        document.getElementById('sessionDetailPatient').innerHTML = `<strong>${escapeHtml(patientName)}</strong>`;
        document.getElementById('sessionDetailDatetime').innerText = formattedDatetime;
        document.getElementById('sessionDetailWard').innerText = session.ward || '—';
        document.getElementById('sessionDetailRoom').innerText = session.room_number || '—';
        
        let componentsHtml = '';
        const components = [
            { name: 'CareNotes', completed: session.carenotes_completed },
            { name: 'Tracker', completed: session.tracker_completed },
            { name: 'Tasks', completed: session.tasks_completed }
        ];
        components.forEach(comp => {
            componentsHtml += `<span class="component-badge ${comp.completed ? 'completed' : 'pending'}">${comp.name}: ${comp.completed ? '✓ Completed' : '○ Pending'}</span>`;
        });
        document.getElementById('sessionDetailComponents').innerHTML = componentsHtml;
        const sessionStatus = (session.status || 'offered').toLowerCase();
const statusColours = {
    offered:   { bg: '#e0f2fe', color: '#0369a1' },
    completed: { bg: '#d1fae5', color: '#065f46' },
    declined:  { bg: '#fed7aa', color: '#92400e' },
    dna:       { bg: '#fee2e2', color: '#991b1b' }
};
const sc = statusColours[sessionStatus] || statusColours['offered'];
const statusText = sessionStatus === 'dna' ? 'DNA' : sessionStatus.charAt(0).toUpperCase() + sessionStatus.slice(1);
document.getElementById('sessionDetailStatus').innerHTML = `
    <span style="display:inline-block;padding:3px 12px;border-radius:2rem;font-size:0.78rem;font-weight:600;background:${sc.bg};color:${sc.color};">
        ${statusText}
    </span>`;
        
        // Display notes with proper apostrophe handling
        let notes = session.notes || '—';
        // Ensure any escaped characters are properly displayed
        if (notes !== '—') {
            notes = escapeHtml(notes).replace(/\\'/g, "'").replace(/\\"/g, '"');
            notes = notes.replace(/\n/g, '<br>');
        }
        document.getElementById('sessionDetailNotes').innerHTML = notes;
    }

  function closeSingleSessionModal() { 
    document.getElementById('singleSessionModal').style.display = 'none'; 
    currentSingleSession = null;
    if (modalStack.length > 0) {
        const previous = modalStack.pop();
        if (previous && document.getElementById(previous)) {
            // If going back to calendar day modal, reload its content
            if (previous === 'calDayModal' && window._calDayDate) {
                CalendarWidget.dayClick(window._calDayDate);
            } else {
                document.getElementById(previous).style.display = 'flex';
                bringModalToFront(previous);
            }
        }
    }
}

 function editCurrentSession() {
    if (currentSingleSession) {
        const session = currentSingleSession;
        pushModal('singleSessionModal');
        document.getElementById('singleSessionModal').style.display = 'none';
        // Don't null currentSingleSession — we need it if user comes back
        setTimeout(() => {
          editSession(
    session.id, 
    session.patient_id, 
    session.datetime, 
    session.carenotes_completed ? 1 : 0, 
    session.tracker_completed ? 1 : 0, 
    session.tasks_completed ? 1 : 0, 
    session.notes || '',
    session.status || 'offered'
);
        }, 100);
    }
}

 function viewFullHistoryFromSession() {
    if (currentSingleSession) {
        const patientName = currentSingleSession.initials || '';
        const patientId = currentSingleSession.patient_id;
        pushModal('singleSessionModal');
        document.getElementById('singleSessionModal').style.display = 'none';
        // Do NOT null currentSingleSession — needed when coming back
        setTimeout(() => {
            viewPatientDetails(patientId, patientName);
        }, 100);
    }
}

    function escapeHtml(text) { const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

    function makeSessionRowsClickable() { document.querySelectorAll('.session-card').forEach(card => { card.removeEventListener('click', handleSessionCardClick); card.addEventListener('click', handleSessionCardClick); }); }
   function handleSessionCardClick(event) {
    if (event.target.closest('.session-actions')) return;
    const sessionId  = this.dataset.sessionId;
    const patientId  = this.dataset.patientId;
    const initials   = this.dataset.patientName;
    const room       = this.dataset.sessionRoom;
    const patientName = room ? `${initials}, Room ${room}` : initials;
    if (sessionId && patientId && patientName) {
        openSingleSessionModal(sessionId, patientId, patientName);
    }
}

    // ==================== CHANGE ROOM ====================
    function closeChangeRoomModal() { document.getElementById('changeRoomModal').style.display = 'none'; }
    async function submitChangeRoom(event) {
        event.preventDefault();
        const form = document.getElementById('changeRoomForm');
        const formData = new FormData(form);
        try {
            const response = await fetch('<?= url('patients/change-room') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
             if (data.success) {
    showMessage(data.message || 'Patient transferred successfully');
    closeChangeRoomModal();
    // Refresh patient summary in modal if open
    if (currentViewPatientId) {
        setTimeout(() => loadPatientSummary(currentViewPatientId), 150);
    }
}          
            else showMessage(data.error || 'Failed to change room', true);
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }

    // ==================== DISCHARGE PATIENT (updated to pre-fill date) ====================
    function openDischargeModal() {
        const patientId = currentViewPatientId || currentSelectedPatientId;
        if (!patientId) {
            showMessage('Please select a patient first', true);
            return;
        }
        document.getElementById('dischargePatientId').value = patientId;
        
        // Pre‑fill discharge date with today's date (optional, but useful)
        const dateInput = document.getElementById('dischargeDate');
        if (dateInput) {
            dateInput.value = new Date().toISOString().slice(0, 10);
        }
        
        document.getElementById('dischargeModal').style.display = 'flex';
        bringModalToFront('dischargeModal');
    }

    function closeDischargeModal() {
        document.getElementById('dischargeModal').style.display = 'none';
    }

    function dischargePatient() {
        openDischargeModal();
    }

    // submitDischarge remains EXACTLY as you have it – no changes needed
    async function submitDischarge(event) {
        event.preventDefault();
        const form = document.getElementById('dischargeForm');
        const formData = new FormData(form);
        if (!confirm('Are you sure you want to discharge this patient? This action cannot be undone.')) return;
        
        const patientId = document.getElementById('dischargePatientId').value;
        let patientWard = '';
        const selectedOption = document.querySelector(`#patientSelect option[value="${patientId}"]`);
        if (selectedOption) patientWard = selectedOption.getAttribute('data-ward') || '';
        else if (currentSelectedPatientId == patientId) patientWard = document.getElementById('selectedWardRoom')?.innerText.split('Ward: ')[1]?.split(' |')[0] || '';
        
        try {
            const response = await fetch('<?= url('patients/discharge') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (data.success) {
                showMessage('Patient discharged successfully');
                const optionToRemove = document.querySelector(`#patientSelect option[value="${patientId}"]`);
                if (optionToRemove) optionToRemove.remove();
                if (currentSelectedPatientId == patientId) clearSelectedPatient();
                if (patientWard) {
                    const wardKey = patientWard.charAt(0).toUpperCase() + patientWard.slice(1).toLowerCase();
                    const statPill = Array.from(document.querySelectorAll('.stat-pill')).find(pill => pill.innerText.includes(wardKey));
                    if (statPill) {
                        const currentCount = parseInt(statPill.innerText.match(/\d+/)?.[0] || 0);
                        if (currentCount > 0) {
                            statPill.innerHTML = statPill.innerHTML.replace(/\d+/, currentCount - 1);
                        }
                    }
                }
                closeDischargeModal();
                closePatientDetailsModal();
                setTimeout(() => location.reload(), 800);
            } else {
                showMessage(data.error || 'Failed to discharge patient', true);
            }
        } catch (err) {
            showMessage('Network error', true);
            console.error(err);
        }
    }

    // ==================== MODAL STACK ====================
const modalStack = [];

function pushModal(modalId) {
    modalStack.push(modalId);
}

function popModal() {
    // Close current modal
    const current = modalStack.pop();
    if (current) document.getElementById(current).style.display = 'none';
    
    // Re-open previous modal if exists
    const previous = modalStack[modalStack.length - 1];
    if (previous) {
        document.getElementById(previous).style.display = 'flex';
        bringModalToFront(previous);
    }
}

    // ==================== CORE-10 ADMISSION EDITABLE ====================
    function toggleCore10Admission() {
        const coreSpan = document.getElementById('viewPatientAdmissionCore');
        const currentHtml = coreSpan.innerHTML;
        const isCompleted = currentHtml.includes('Completed');
        if (!core10EditMode) {
            core10EditMode = true;
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = 'core10EditCheckbox';
            checkbox.checked = isCompleted;
            const saveBtn = document.createElement('button');
            saveBtn.innerHTML = 'Save'; saveBtn.type = 'button'; saveBtn.className = 'btn-sm btn-primary'; saveBtn.style.marginLeft = '8px'; saveBtn.onclick = saveCore10Admission;
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = 'Cancel'; cancelBtn.type = 'button'; cancelBtn.className = 'btn-sm btn-secondary'; cancelBtn.style.marginLeft = '4px'; cancelBtn.onclick = cancelCore10Edit;
            coreSpan.innerHTML = '';
            coreSpan.appendChild(checkbox);
            coreSpan.appendChild(saveBtn);
            coreSpan.appendChild(cancelBtn);
            const editBtn = document.getElementById('editCore10Btn');
            if (editBtn) editBtn.style.display = 'none';
        }
    }
    function cancelCore10Edit() { core10EditMode = false; const patientId = currentViewPatientId; if (patientId) loadPatientSummary(patientId); const editBtn = document.getElementById('editCore10Btn'); if (editBtn) editBtn.style.display = 'inline-block'; }
    async function saveCore10Admission() {
        const patientId = currentViewPatientId;
        if (!patientId) return;
        const checkbox = document.getElementById('core10EditCheckbox');
        const completed = checkbox.checked ? 1 : 0;
        try {
            const response = await fetch('<?= url('patients/update-core10') ?>', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ patient_id: patientId, core10_admission: completed, csrf_token: '<?= csrf_token() ?>' }) });
            const data = await response.json();
            if (data.success) { showMessage('CORE-10 admission status updated'); core10EditMode = false; loadPatientSummary(patientId); const editBtn = document.getElementById('editCore10Btn'); if (editBtn) editBtn.style.display = 'inline-block'; }
            else showMessage(data.error || 'Failed to update CORE-10', true);
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }

    // ==================== CORE-10 DISCHARGE EDITABLE ====================
    let dischargeCore10EditMode = false;

    function toggleCore10Discharge() {
        const coreSpan = document.getElementById('viewPatientDischargeCore');
        const currentHtml = coreSpan.innerHTML;
        const isCompleted = currentHtml.includes('Completed');
        
        if (!dischargeCore10EditMode) {
            dischargeCore10EditMode = true;
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = 'dischargeCore10EditCheckbox';
            checkbox.checked = isCompleted;
            
            const saveBtn = document.createElement('button');
            saveBtn.innerHTML = 'Save';
            saveBtn.type = 'button';
            saveBtn.className = 'btn-sm btn-primary';
            saveBtn.style.marginLeft = '8px';
            saveBtn.onclick = saveCore10Discharge;
            
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = 'Cancel';
            cancelBtn.type = 'button';
            cancelBtn.className = 'btn-sm btn-secondary';
            cancelBtn.style.marginLeft = '4px';
            cancelBtn.onclick = cancelCore10DischargeEdit;
            
            coreSpan.innerHTML = '';
            coreSpan.appendChild(checkbox);
            coreSpan.appendChild(saveBtn);
            coreSpan.appendChild(cancelBtn);
            
            const editBtn = document.getElementById('editDischargeCore10Btn');
            if (editBtn) editBtn.style.display = 'none';
        }
    }

    function cancelCore10DischargeEdit() {
        dischargeCore10EditMode = false;
        const patientId = currentViewPatientId;
        if (patientId) loadPatientSummary(patientId);
        const editBtn = document.getElementById('editDischargeCore10Btn');
        if (editBtn) editBtn.style.display = 'inline-block';
    }

    async function saveCore10Discharge() {
        const patientId = currentViewPatientId;
        if (!patientId) return;
        
        const checkbox = document.getElementById('dischargeCore10EditCheckbox');
        const completed = checkbox.checked ? 1 : 0;
        
        try {
            const response = await fetch('<?= url('patients/update-discharge-core10') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    patient_id: patientId,
                    core10_discharge: completed,
                    csrf_token: '<?= csrf_token() ?>'
                })
            });
            const data = await response.json();
            if (data.success) {
                showMessage('CORE-10 discharge status updated');
                dischargeCore10EditMode = false;
                loadPatientSummary(patientId);
                const editBtn = document.getElementById('editDischargeCore10Btn');
                if (editBtn) editBtn.style.display = 'inline-block';
            } else {
                showMessage(data.error || 'Failed to update discharge CORE-10', true);
            }
        } catch (err) {
            showMessage('Network error', true);
            console.error(err);
        }
    }
    // ==================== AJAX SUBMISSIONS ====================
    function showMessage(msg, isError = false) { const toast = document.getElementById('notification'); toast.textContent = msg; toast.style.backgroundColor = isError ? '#b91c1c' : '#1e3a8a'; toast.style.display = 'block'; setTimeout(() => toast.style.display = 'none', 7000); }
    async function submitAdmitForm(event) {
        event.preventDefault();
        const form = document.getElementById('admitForm');
        const ward = document.getElementById('admitWard').value;
        const room = document.getElementById('admitRoom').value;
        const initials = document.getElementById('admitInitials')?.value || document.querySelector('#admitForm input[name="initials"]').value;
      if (!ward) { 
    showMessage('Please select a ward', true); 
    const wardSelect = document.getElementById('admitWard');
    wardSelect.style.borderColor = '#dc2626';
    wardSelect.focus();
    wardSelect.addEventListener('change', function clearError() {
        wardSelect.style.borderColor = '';
        wardSelect.removeEventListener('change', clearError);
    });
    return; 
}
if (!room) { 
    showMessage('Please select a room number', true); 
    const roomSelect = document.getElementById('admitRoom');
    roomSelect.style.borderColor = '#dc2626';
    roomSelect.focus();
    roomSelect.addEventListener('change', function clearError() {
        roomSelect.style.borderColor = '';
        roomSelect.removeEventListener('change', clearError);
    });
    return; 
}
if (!initials || initials.trim().length === 0) { 
    showMessage('Please enter patient initials', true);
    const initialsInput = document.querySelector('#admitForm input[name="initials"]');
    initialsInput.style.borderColor = '#dc2626';
    initialsInput.focus();
    initialsInput.addEventListener('input', function clearError() {
        initialsInput.style.borderColor = '';
        initialsInput.removeEventListener('input', clearError);
    });
    return; 
}
if (initials.length > 3) { showMessage('Initials must be 3 characters or less', true); return; }
        const formData = new FormData(form);
        try {
            const response = await fetch('<?= url('patients/store') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.success) { showMessage('Patient admitted successfully'); closeAdmitModal(); setTimeout(() => location.reload(), 800); }
            else { showMessage(data.error || 'Failed to admit patient', true); }
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }
    async function submitSessionForm(event) {
        event.preventDefault();
        const form = document.getElementById('sessionForm');
        const patientVal = document.getElementById('sessionPatient').value;
        if (!patientVal) { 
    showMessage('Please select a patient', true);
    const patientSelect = document.getElementById('sessionPatient');
    patientSelect.style.borderColor = '#dc2626';
    patientSelect.focus();
    patientSelect.addEventListener('change', function clearError() {
        patientSelect.style.borderColor = '';
        patientSelect.removeEventListener('change', clearError);
    });
    return; 
}        
        const formData = new FormData(form);
        formData.set('carenotes', form.querySelector('[name="carenotes"]')?.checked ? '1' : '0');
        formData.set('tracker', form.querySelector('[name="tracker"]')?.checked ? '1' : '0');
        formData.set('tasks', form.querySelector('[name="tasks"]')?.checked ? '1' : '0');
        try {
            const response = await fetch('<?= url('sessions/store') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.success) { showMessage('Session added successfully'); closeSessionModal(); if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh(); setTimeout(() => location.reload(), 800); }
            else showMessage(data.error || 'Failed to add session', true);
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }
    async function submitEditSessionForm(event) {
    event.preventDefault();
    const form = document.getElementById('editSessionForm');
   const formData = new FormData(form);
    formData.set('carenotes', form.querySelector('[name="carenotes"]')?.checked ? '1' : '0');
    formData.set('tracker', form.querySelector('[name="tracker"]')?.checked ? '1' : '0');
    formData.set('tasks', form.querySelector('[name="tasks"]')?.checked ? '1' : '0');
    formData.set('status', document.getElementById('editSessionStatus').value);
    try {
        const response = await fetch('<?= url('sessions/update') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await response.json();
        if (data.success) {
            showMessage('Session updated successfully');
            closeEditSessionModal();
            if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh();

            // Update today's session card in DOM without reload
            const sessionId = document.getElementById('editSessionId').value;
            const sessionCard = document.querySelector(`.session-card[data-session-id="${sessionId}"]`);
            if (sessionCard) {
                const newDatetime = document.getElementById('editSessionDatetime').value;
                const carenotes = form.querySelector('[name="carenotes"]')?.checked ? 1 : 0;
                const tracker = form.querySelector('[name="tracker"]')?.checked ? 1 : 0;
                const tasks = form.querySelector('[name="tasks"]')?.checked ? 1 : 0;
                const notes = document.getElementById('editSessionNotes').value;

                sessionCard.dataset.sessionDatetime = newDatetime;
                sessionCard.dataset.sessionCarenotes = carenotes;
                sessionCard.dataset.sessionTracker = tracker;
                sessionCard.dataset.sessionTasks = tasks;
                sessionCard.dataset.sessionNotes = notes;
                const status = document.getElementById('editSessionStatus').value;
sessionCard.dataset.sessionStatus = status;

                const timeEl = sessionCard.querySelector('.session-time');
                if (timeEl) timeEl.innerHTML = `<i class="bi bi-clock"></i> ${newDatetime.substring(11,16)}`;

                const iconsEl = sessionCard.querySelector('.session-icons');
                if (iconsEl) {
                    iconsEl.innerHTML =
                        (carenotes ? '<i class="bi bi-journal-text" title="CareNotes completed"></i>' : '') +
                        (tracker ? '<i class="bi bi-graph-up" title="Tracker completed"></i>' : '') +
                        (tasks ? '<i class="bi bi-check-circle" title="Tasks completed"></i>' : '');
                }
            }

          // Reload sessions in patient modal if open
if (currentViewPatientId) {
    setTimeout(() => loadAllSessions(currentViewPatientId), 150);
}

// Update currentSingleSession data if it was open
if (currentSingleSession && currentSingleSession.id == document.getElementById('editSessionId').value) {
    const newDatetime = document.getElementById('editSessionDatetime').value;
    const carenotes = form.querySelector('[name="carenotes"]')?.checked ? 1 : 0;
    const tracker = form.querySelector('[name="tracker"]')?.checked ? 1 : 0;
    const tasks = form.querySelector('[name="tasks"]')?.checked ? 1 : 0;
    const notes = document.getElementById('editSessionNotes').value;

currentSingleSession.datetime = newDatetime;
currentSingleSession.carenotes_completed = carenotes;
currentSingleSession.tracker_completed = tracker;
currentSingleSession.tasks_completed = tasks;
currentSingleSession.notes = notes;
currentSingleSession.status = document.getElementById('editSessionStatus').value;

    setTimeout(() => {
        if (document.getElementById('singleSessionModal').style.display === 'flex') {
            displaySingleSession(currentSingleSession, currentSingleSession.initials);
        }
    }, 200);
}

        } else {
            showMessage(data.error || 'Failed to update session', true);
        }
    } catch (err) { showMessage('Network error', true); console.error(err); }
}

    async function archiveSession(sessionId, ward) {
        if (!confirm('Archive this session?')) return;
        const formData = new FormData();
        formData.append('id', sessionId);
        formData.append('ward', ward);
        formData.append('csrf_token', '<?= csrf_token() ?>');
        try {
            const response = await fetch('<?= url('sessions/archive') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.success) { showMessage('Session archived successfully'); if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh(); setTimeout(() => location.reload(), 800); }
            else { showMessage(data.error || 'Failed to archive session', true); }
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }
    async function deleteSession(sessionId, ward, event) {
        if (event) { event.preventDefault(); event.stopPropagation(); }
        if (!confirm('⚠️ Permanently delete this session?')) return;
        const formData = new FormData();
        formData.append('id', sessionId);
        formData.append('ward', ward);
        formData.append('csrf_token', '<?= csrf_token() ?>');
        try {
            const response = await fetch('<?= url('sessions/delete') ?>', { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await response.json();
            if (data.success) { showMessage('Session permanently deleted'); if (event) { const target = event.target.closest('.session-card') || event.target.closest('tr'); if (target) target.remove(); } if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh(); setTimeout(() => location.reload(), 800); }
            else { showMessage(data.error || 'Failed to delete session', true); }
        } catch (err) { showMessage('Network error', true); console.error(err); }
    }


    // ==================== GROUP SESSION FUNCTIONS (FIXED) ====================

    function toggleCustomGroupType() {
        const groupType = document.getElementById('groupType').value;
        const customInput = document.getElementById('customGroupType');
        if (groupType === 'Other') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = '';
        }
    }

    function openGroupSessionModal(ward = null, prefilledDate = null) {
        const modal = document.getElementById('groupSessionModal');
        const form = document.getElementById('groupSessionForm');
        const dtInput = document.getElementById('groupSessionDatetime');
        const customInput = document.getElementById('customGroupType');

        form.reset();
        if (customInput) customInput.style.display = 'none';
        document.getElementById('groupSessionId').value = '';
        document.getElementById('groupAttendanceTable').innerHTML =
            '<p class="gs-placeholder">Select ward(s) to load patients</p>';

        // Reset checkboxes
        document.getElementById('filterWardHope').checked = false;
        document.getElementById('filterWardLakeside').checked = false;
        document.getElementById('filterWardManor').checked = false;

        // Set datetime
        if (prefilledDate) {
            dtInput.value = prefilledDate + 'T09:00';
        } else {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            dtInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        modal.style.display = 'flex';
        bringModalToFront('groupSessionModal');
    }

    function closeGroupSessionModal() {
    document.getElementById('groupSessionModal').style.display = 'none';
    if (modalStack.length > 0) {
        const previous = modalStack.pop();
        if (previous && document.getElementById(previous)) {
            document.getElementById(previous).style.display = 'flex';
            bringModalToFront(previous);
            if (previous === 'calDayModal' && window._calDayDate) {
                CalendarWidget.dayClick(window._calDayDate);
            }
        }
    }
}

    async function loadGroupAttendanceTable() {
    const selectedWards = [];
    if (document.getElementById('filterWardHope').checked) selectedWards.push('Hope');
    if (document.getElementById('filterWardLakeside').checked) selectedWards.push('Lakeside');
    if (document.getElementById('filterWardManor').checked) selectedWards.push('Manor');

    const container = document.getElementById('groupAttendanceTable');

    if (selectedWards.length === 0) {
        container.innerHTML = '<p class="gs-placeholder">Select at least one ward to load patients</p>';
        return;
    }

    container.innerHTML = '<div class="gs-loading"><i class="bi bi-arrow-repeat"></i> Loading patients...</div>';

    // Detect if session date is in the future
    const selectedDatetime = document.getElementById('groupSessionDatetime').value;
    const selectedDate = selectedDatetime ? selectedDatetime.substring(0, 10) : '';
    const today = new Date().toISOString().substring(0, 10);
    const isFuture = selectedDate > today;

    try {
        const results = await Promise.all(
            selectedWards.map(ward =>
                fetch('<?= url('patients/get-by-ward') ?>?ward=' + encodeURIComponent(ward)).then(r => r.json())
            )
        );

        let allPatients = [];
        results.forEach((patients, idx) => {
            const ward = selectedWards[idx];
            (patients || []).forEach(p => {
                p._ward = ward;
                allPatients.push(p);
            });
        });

        if (!allPatients.length) {
            container.innerHTML = '<p class="gs-placeholder">No active patients in the selected wards</p>';
            return;
        }

        const wardOrder = { Hope: 1, Lakeside: 2, Manor: 3 };
        allPatients.sort((a, b) => {
            const wa = wardOrder[a.ward || a._ward] || 9;
            const wb = wardOrder[b.ward || b._ward] || 9;
            if (wa !== wb) return wa - wb;
            return (parseInt(a.room_number) || 0) - (parseInt(b.room_number) || 0);
        });

        const wardColour = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };

        let html = `
            <table class="gs-register">
                <thead>
                    <tr>
                        <th style="width: 15%">Ward</th>
                        <th style="width: 10%">Room</th>
                        <th style="width: 15%">Patient</th>
                        ${isFuture ? 
                            '<th colspan="3" style="text-align:center;color:#94a3b8;font-style:italic;font-weight:400;">Attendance available on session day</th>' : 
                            '<th style="width: 12%; text-align:center;">Attended</th><th style="width: 12%; text-align:center;">Declined</th><th style="width: 12%; text-align:center;">DNA</th>'
                        }
                        <th style="width: 24%">Notes</th>
                    </tr>
                </thead>
                <tbody>
        `;

        const grouped = {};
        allPatients.forEach(p => {
            const ward = p.ward || p._ward;
            if (!grouped[ward]) grouped[ward] = [];
            grouped[ward].push(p);
        });

        for (const ward of ['Hope', 'Lakeside', 'Manor']) {
            const wardPatients = grouped[ward];
            if (!wardPatients) continue;
            const colour = wardColour[ward] || '#94a3b8';

            wardPatients.forEach((p, idx) => {
                if (idx === 0) {
                    html += `
                        <tr data-patient-id="${p.id}" class="ward-group-row">
                            <td style="background: ${colour}10; border-left: 4px solid ${colour};">
                                <span class="gs-ward-badge" style="background:${colour};">${ward} Ward</span>
                            </td>
                            <td>Bed ${p.room_number}</td>
                            <td><strong>${escapeHtml(p.initials)}</strong></td>
                            ${isFuture ?
                                `<td colspan="3" style="text-align:center;color:#94a3b8;font-size:0.8rem;">—</td>` :
                                `<td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="attended"></td>
                                 <td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="declined"></td>
                                 <td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="dna"></td>`
                            }
                            <td><input type="text" class="gs-notes-input" name="att_notes_${p.id}" placeholder="Optional note"></td>
                        </tr>
                    `;
                } else {
                    html += `
                        <tr data-patient-id="${p.id}" class="ward-patient-row">
                            <td style="background: ${colour}05; border-left: 4px solid ${colour};"></td>
                            <td>Bed ${p.room_number}</td>
                            <td><strong>${escapeHtml(p.initials)}</strong></td>
                            ${isFuture ?
                                `<td colspan="3" style="text-align:center;color:#94a3b8;font-size:0.8rem;">—</td>` :
                                `<td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="attended"></td>
                                 <td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="declined"></td>
                                 <td class="gs-radio-cell"><input type="radio" name="att_${p.id}" value="dna"></td>`
                            }
                            <td><input type="text" class="gs-notes-input" name="att_notes_${p.id}" placeholder="Optional note"></td>
                        </tr>
                    `;
                }
            });
        }

        html += '</tbody></table>';

        if (isFuture) {
            html += `<p style="margin-top:0.75rem;font-size:0.8rem;color:#94a3b8;text-align:center;">
                <i class="bi bi-info-circle"></i> This is a scheduled session. Attendance can be marked on the session day.
            </p>`;
        }

        container.innerHTML = html;

    } catch (err) {
        console.error('Error loading patients:', err);
        container.innerHTML = '<p class="error">Error loading patients. See console.</p>';
    }
}


  async function submitGroupSession(event) {
    event.preventDefault();

    let selectedGroupType = document.getElementById('groupType').value;
    if (selectedGroupType === 'Other') {
        const custom = document.getElementById('customGroupType').value.trim();
        if (!custom) {
            showMessage('Please enter a custom group type', true);
            return;
        }
        selectedGroupType = custom;
    }

    const datetime = document.getElementById('groupSessionDatetime').value;
    const notes = document.getElementById('groupSessionNotes').value;

    if (!selectedGroupType) { showMessage('Please select a group type', true); return; }
    if (!datetime) { showMessage('Please select a date and time', true); return; }

    const selectedDate = datetime.substring(0, 10);
    const today = new Date().toISOString().substring(0, 10);
    const isFuture = selectedDate > today;

    await new Promise(r => setTimeout(r, 50));

    const rows = document.querySelectorAll('#groupAttendanceTable tbody tr[data-patient-id]');
    if (rows.length === 0) {
        showMessage('No patients loaded – please select at least one ward and wait for the patient list to load.', true);
        return;
    }

   const attendance = [];
const unmarked = [];

rows.forEach(row => {
    const patientId = row.getAttribute('data-patient-id');
    const checkedRadio = row.querySelector('input[type="radio"]:checked');
    const status = isFuture ? 'not_set' : (checkedRadio ? checkedRadio.value : 'attended');
    const notesInput = row.querySelector(`input[name="att_notes_${patientId}"]`);

    // For non-future sessions, track patients with no attendance marked
    if (!isFuture && !checkedRadio) {
        const patientName = row.querySelector('td:nth-child(3)')?.innerText || 'Unknown';
        unmarked.push(patientName);
    }

    attendance.push({
        patient_id: patientId,
        status: status,
        notes: notesInput ? notesInput.value : ''
    });
});

// Block submission if any patient has no attendance marked (non-future sessions only)
if (!isFuture && unmarked.length > 0) {
    showMessage(`Please mark attendance for all patients before saving.`, true);
    // Highlight unmarked rows
    rows.forEach(row => {
        const patientId = row.getAttribute('data-patient-id');
        const checkedRadio = row.querySelector('input[type="radio"]:checked');
        if (!checkedRadio) {
            row.style.background = '#fef2f2';
            row.style.outline = '1px solid #fca5a5';
            // Clear highlight when any radio is selected
            row.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    row.style.background = '';
                    row.style.outline = '';
                }, { once: true });
            });
        }
    });
    return;
}

    const wardSnapshot = ['Hope','Lakeside','Manor']
        .filter(w => document.getElementById('filterWard' + w)?.checked)
        .join(',');

    const formData = new FormData();
    formData.append('csrf_token', document.querySelector('#groupSessionForm input[name="csrf_token"]').value);
    formData.append('group_type', selectedGroupType);
    formData.append('datetime', datetime);
    formData.append('notes', notes);
    formData.append('ward_snapshot', wardSnapshot);
    formData.append('attendance', JSON.stringify(attendance));

    try {
        const response = await fetch('<?= url('group-sessions/store') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            showMessage(isFuture ? 'Group session scheduled successfully' : 'Group session saved successfully');
            closeGroupSessionModal();
            if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh();
            setTimeout(() => location.reload(), 800);
        } else {
            showMessage(data.error || 'Failed to save group session', true);
        }
    } catch (err) {
        console.error('Submit error:', err);
        showMessage('Network error: ' + err.message, true);
    }
}

    // View functions (unchanged but kept for completeness)
    async function openViewGroupSessionsModal() {
    const modal = document.getElementById('viewGroupSessionsModal');
    const container = document.getElementById('groupSessionsList');
    container.innerHTML = '<div class="loading">Loading group sessions...</div>';
    modal.style.display = 'flex';
    bringModalToFront('viewGroupSessionsModal');

    try {
const response = await fetch('<?= url('group-sessions/list-json') ?>');
    const sessions = await response.json();

        if (!sessions.length) {
            container.innerHTML = '<div class="no-notes">No group sessions found</div>';
            return;
        }

        let html = '<div class="group-sessions-list">';
        sessions.forEach(s => {
            const sessionDate = new Date(s.session_date + 'T' + s.session_time);
            const formattedDate = sessionDate.toLocaleDateString('en-GB');
            const formattedTime = sessionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            html += `
                <div class="group-session-card" data-id="${s.id}">
                    <div class="group-session-header">
    <strong class="group-session-type">${escapeHtml(s.group_type)}</strong>
    <div style="display:flex;align-items:center;gap:0.5rem;">
        ${s.status === 'scheduled' ? 
            '<span style="background:#ede9fe;color:#6d28d9;font-size:0.7rem;font-weight:600;padding:2px 8px;border-radius:2rem;">Scheduled</span>' : 
            '<span style="background:#d1fae5;color:#065f46;font-size:0.7rem;font-weight:600;padding:2px 8px;border-radius:2rem;">Completed</span>'
        }
        <span class="group-session-datetime">${formattedDate} ${formattedTime}</span>
    </div>
</div>
                    <div class="group-session-details">
${(() => {
    const wardLabel = s.ward || s.ward_snapshot || 'Mixed';
    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
    return wardLabel.split(',').map(w => w.trim()).filter(Boolean)
        .map(w => `<span style="background:${wardColours[w] || '#8b5cf6'};color:white;padding:2px 8px;border-radius:2rem;font-size:0.7rem;font-weight:600;margin-right:3px;">${w}</span>`)
        .join('');
})()}
                    <span class="group-session-count">${s.patient_count} patient(s)</span>
                    </div>
                    ${s.notes ? `<div class="group-session-notes-preview">${escapeHtml(s.notes.substring(0, 100))}${s.notes.length > 100 ? '...' : ''}</div>` : ''}
<div style="display:flex; gap:0.5rem; margin-top:0.5rem;">
    <button class="btn-secondary" onclick="viewGroupSessionDetails(${s.id})">View Details</button>
    <button class="btn-danger" style="font-size:0.8rem; padding:0.4rem 0.9rem;" onclick="deleteGroupSession(${s.id})">Delete</button>
</div>
                    </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    } catch (err) {
        console.error('Error loading group sessions:', err);
        container.innerHTML = '<div class="error">Error loading group sessions</div>';
    }
}

function closeViewGroupSessionsModal() {
    document.getElementById('viewGroupSessionsModal').style.display = 'none';
}

function closeGroupSessionDetailsModal() {
    document.getElementById('groupSessionDetailsModal').style.display = 'none';
    const previous = modalStack[modalStack.length - 1];
    if (previous) {
        modalStack.pop();
        document.getElementById(previous).style.display = 'flex';
        bringModalToFront(previous);
    }
}

async function viewGroupSessionDetails(sessionId) {
    if (document.getElementById('viewGroupSessionsModal').style.display === 'flex') {
        pushModal('viewGroupSessionsModal');
    }
    try {
        const response = await fetch('<?= url('group-sessions/get-json') ?>?id=' + sessionId);
        const data = await response.json();
        const modal = document.getElementById('groupSessionDetailsModal');

        document.getElementById('groupSessionDetailTitle').innerHTML = `<i class="bi bi-people-fill"></i> ${escapeHtml(data.group_type)}`;
        const dt = new Date(data.session_date + ' ' + data.session_time);
        document.getElementById('groupSessionDetailDatetime').innerText = dt.toLocaleDateString('en-GB') + ' ' + dt.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'});
        const wardValue = data.ward || data.ward_snapshot || 'Mixed Wards';
        const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
        const wardBadgesHtml = wardValue.split(',').map(w => w.trim()).filter(Boolean)
            .map(w => `<span style="background:${wardColours[w] || '#8b5cf6'};color:white;padding:3px 10px;border-radius:2rem;font-size:0.75rem;font-weight:600;margin-right:4px;">${w}</span>`)
            .join('');
        document.getElementById('groupSessionDetailWard').innerHTML = wardBadgesHtml || 'Mixed Wards';
        document.getElementById('groupSessionDetailNotes').innerHTML = escapeHtml(data.notes || 'No notes');

const wardColour = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
const sessionDate = data.session_date;
const today = new Date().toISOString().substring(0, 10);
const isScheduled = data.status === 'scheduled';
const canComplete = isScheduled && sessionDate <= today;

if (isScheduled && !canComplete) {
    // Future scheduled session — show patient list without attendance
    let attHtml = '<table class="gs-register"><thead><tr><th>Ward</th><th>Room</th><th>Patient</th><th>Status</th></tr></thead><tbody>';
    (data.attendance || []).forEach(a => {
        const colour = wardColour[a.ward] || '#94a3b8';
        attHtml += `
            <tr>
                <td><span class="gs-ward-badge" style="background:${colour};">${escapeHtml(a.ward)}</span></td>
                <td>Bed ${a.room_number}</td>
                <td><strong>${escapeHtml(a.patient_initials)}</strong></td>
                <td style="color:#94a3b8;font-size:0.8rem;">Attendance available on session day</td>
            </tr>
        `;
    });
    attHtml += '</tbody></table>';
    document.getElementById('groupSessionDetailAttendance').innerHTML = `<div style="overflow-x:auto;">${attHtml}</div>`;

} else if (canComplete) {
    // Session date is today — show attendance form to complete it
    let attHtml = `
        <div style="background:#ede9fe;border-radius:0.5rem;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.85rem;color:#6d28d9;">
            <i class="bi bi-calendar-check"></i> This session is scheduled for today. Mark attendance and complete it.
        </div>
        <table class="gs-register" id="completeAttendanceTable">
            <thead>
                <tr>
                    <th>Ward</th><th>Room</th><th>Patient</th>
                    <th style="text-align:center;">Attended</th>
                    <th style="text-align:center;">Declined</th>
                    <th style="text-align:center;">DNA</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
    `;
    (data.attendance || []).forEach(a => {
        const colour = wardColour[a.ward] || '#94a3b8';
        attHtml += `
            <tr data-patient-id="${a.patient_id}">
                <td><span class="gs-ward-badge" style="background:${colour};">${escapeHtml(a.ward)}</span></td>
                <td>Bed ${a.room_number}</td>
                <td><strong>${escapeHtml(a.patient_initials)}</strong></td>
                <td class="gs-radio-cell"><input type="radio" name="complete_att_${a.patient_id}" value="attended"></td>
                <td class="gs-radio-cell"><input type="radio" name="complete_att_${a.patient_id}" value="declined"></td>
                <td class="gs-radio-cell"><input type="radio" name="complete_att_${a.patient_id}" value="dna"></td>
                <td><input type="text" class="gs-notes-input" name="complete_notes_${a.patient_id}" placeholder="Optional note" value="${escapeHtml(a.notes || '')}"></td>
            </tr>
        `;
    });
    attHtml += `</tbody></table>
        <div style="margin-top:1rem;text-align:right;">
            <button class="btn-primary" onclick="completeGroupSession(${data.id})">
                <i class="bi bi-check-circle"></i> Mark as Completed
            </button>
        </div>
    `;
    document.getElementById('groupSessionDetailAttendance').innerHTML = `<div style="overflow-x:auto;">${attHtml}</div>`;

} else {
    // Already completed — show read-only attendance table
    let attHtml = '<table class="gs-register"><thead><tr><th>Ward</th><th>Room</th><th>Patient</th><th>Status</th><th>Notes</th></tr></thead><tbody>';
    (data.attendance || []).forEach(a => {
        const colour = wardColour[a.ward] || '#94a3b8';
        let status, statusColour;
        if (a.attended)      { status = '✓ Attended'; statusColour = '#065f46'; }
        else if (a.declined) { status = '✗ Declined'; statusColour = '#991b1b'; }
        else if (a.dna)      { status = '⚠ DNA';      statusColour = '#92400e'; }
        else                 { status = '—';           statusColour = '#94a3b8'; }
        attHtml += `
            <tr>
                <td><span class="gs-ward-badge" style="background:${colour};">${escapeHtml(a.ward)}</span></td>
                <td>Bed ${a.room_number}</td>
                <td><strong>${escapeHtml(a.patient_initials)}</strong></td>
                <td style="color:${statusColour};font-weight:500;">${status}</td>
                <td>${escapeHtml(a.notes || '')}</td>
            </tr>
        `;
    });
    attHtml += '</tbody></table>';
    document.getElementById('groupSessionDetailAttendance').innerHTML = `<div style="overflow-x:auto;">${attHtml}</div>`;
}

        modal.style.display = 'flex';
        bringModalToFront('groupSessionDetailsModal');
    } catch (err) {
        console.error('Error loading group session details:', err);
        showMessage('Error loading details', true);
    }
}

    async function refreshGroupSessionsList() {
    const modal = document.getElementById('viewGroupSessionsModal');
    const container = document.getElementById('groupSessionsList');
    // If the modal is not open, we don't need to update it.
    if (!modal || modal.style.display !== 'flex') return;

    container.innerHTML = '<div class="loading">Refreshing...</div>';
    try {
const response = await fetch('<?= url('group-sessions/list-json') ?>');
        const sessions = await response.json();
        if (!sessions.length) {
            container.innerHTML = '<div class="no-notes">No group sessions found</div>';
            return;
        }
        let html = '<div class="group-sessions-list">';
        sessions.forEach(s => {
            const dt = new Date(s.session_date + 'T' + s.session_time);
            const formattedDate = dt.toLocaleDateString('en-GB');
            const formattedTime = dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            html += `
                <div class="group-session-card">
                    <div class="group-session-header">
                        <strong class="group-session-type">${escapeHtml(s.group_type)}</strong>
                        <span class="group-session-datetime">${formattedDate} ${formattedTime}</span>
                    </div>
                    <div class="group-session-details">
                    <span class="group-session-wards">${s.ward ? escapeHtml(s.ward) : (s.ward_snapshot ? escapeHtml(s.ward_snapshot) : 'Mixed Wards')}</span>
<span class="group-session-count">${s.patient_count} ${s.patient_count == 1 ? 'patient' : 'patients'}</span>
                    </div>
                    ${s.notes ? `<div class="group-session-notes-preview">${escapeHtml(s.notes.substring(0, 100))}${s.notes.length > 100 ? '...' : ''}</div>` : ''}
                    <button class="btn-secondary" onclick="viewGroupSessionDetails(${s.id})">View Details</button>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    } catch (err) {
        console.error('Error refreshing group sessions:', err);
        container.innerHTML = '<div class="error">Error refreshing list</div>';
    }
}

    async function completeGroupSession(sessionId) {
    const rows = document.querySelectorAll('#completeAttendanceTable tbody tr[data-patient-id]');
    const attendance = [];
    rows.forEach(row => {
        const patientId = row.getAttribute('data-patient-id');
        const checkedRadio = row.querySelector(`input[name="complete_att_${patientId}"]:checked`);
        const notesInput = row.querySelector(`input[name="complete_notes_${patientId}"]`);
        attendance.push({
            patient_id: patientId,
            status: checkedRadio ? checkedRadio.value : 'attended',
            notes: notesInput ? notesInput.value : ''
        });
    });

    const formData = new FormData();
    formData.append('id', sessionId);
    formData.append('attendance', JSON.stringify(attendance));
    formData.append('csrf_token', '<?= csrf_token() ?>');

    try {
        const response = await fetch('<?= url('group-sessions/complete') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            showMessage('Group session completed successfully');
            closeGroupSessionDetailsModal();
            if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh();
            setTimeout(() => location.reload(), 800);
        } else {
            showMessage(data.error || 'Failed to complete session', true);
        }
    } catch (err) {
        showMessage('Network error', true);
    }
}

let _currentPatientNote = '';

function openPatientNoteModal(sessionId, note) {
    _currentPatientNote = note || '';
    const container = document.getElementById('patientNoteModalContent');
    if (_currentPatientNote.trim()) {
        container.innerText = _currentPatientNote;
        container.style.color = '';
    } else {
        container.innerText = 'No notes recorded for this session.';
        container.style.color = '#94a3b8';
    }
    document.getElementById('patientNoteModal').style.display = 'flex';
    bringModalToFront('patientNoteModal');
}

function closePatientNoteModal() {
    document.getElementById('patientNoteModal').style.display = 'none';
}

function copyPatientNote() {
    if (!_currentPatientNote.trim()) return;
    navigator.clipboard.writeText(_currentPatientNote).then(() => {
        const btn = document.getElementById('patientNoteCopyBtn');
        const prev = btn.textContent;
        btn.textContent = '✓ Copied';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        setTimeout(() => {
            btn.textContent = prev;
            btn.style.background = '';
            btn.style.color = '';
        }, 1500);
    }).catch(() => showMessage('Could not copy — please copy manually', true));
}


// ==================== WARD TRANSFER ====================
function openChangeWardModal() {
    const patientId = currentViewPatientId;
    if (!patientId) { showMessage('No patient selected', true); return; }

    fetch('<?= url('patients/get-summary') ?>?id=' + patientId)
        .then(r => r.json())
        .then(data => {
            const ward = data.ward;
            if (ward === 'Hope') {
                showMessage('Hope ward patients cannot be transferred', true);
                return;
            }
            const toWard = ward === 'Manor' ? 'Lakeside' : 'Manor';
            document.getElementById('changeWardPatientId').value = patientId;
            document.getElementById('changeWardFrom').value = ward;
            document.getElementById('changeWardTo').value = toWard;
            document.getElementById('changeWardToLabel').textContent = toWard + ' Ward';
            document.getElementById('changeWardReason').value = '';

            // Load available rooms in destination ward
            const roomSelect = document.getElementById('changeWardRoomSelect');
            roomSelect.innerHTML = '<option value="">Loading rooms...</option>';

            fetch('<?= url('patients/get-by-ward') ?>?ward=' + encodeURIComponent(toWard))
                .then(r => r.json())
                .then(patients => {
                    const totalRooms = toWard === 'Hope' ? 12 : 10;
                    const occupiedRooms = patients.map(p => parseInt(p.room_number));

                    roomSelect.innerHTML = '<option value="">Select a room</option>';
                    for (let i = 1; i <= totalRooms; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        if (occupiedRooms.includes(i)) {
                            option.textContent = 'Room ' + i + ' (Occupied)';
                            option.disabled = true;
                        } else {
                            option.textContent = 'Room ' + i + ' (Available)';
                        }
                        roomSelect.appendChild(option);
                    }
                })
                .catch(() => {
                    roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                });

            document.getElementById('changeWardModal').style.display = 'flex';
            bringModalToFront('changeWardModal');
        });
}

function closeChangeWardModal() {
    document.getElementById('changeWardModal').style.display = 'none';
}

async function submitWardTransfer(event) {
    event.preventDefault();
    const fromWard = document.getElementById('changeWardFrom').value;
    const toWard   = document.getElementById('changeWardTo').value;

    if (!confirm(`Transfer this patient from ${fromWard} to ${toWard}? All clinical records will be preserved.`)) return;

    const form     = document.getElementById('changeWardForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('<?= url('patients/transfer-ward') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
           showMessage(data.message || 'Patient transferred successfully');
            closeChangeWardModal();
            if (currentViewPatientId) {
                setTimeout(() => {
                    loadPatientSummary(currentViewPatientId);
                    loadWardTransferHistory(currentViewPatientId);
                }, 150);
            }
            setTimeout(() => location.reload(), 800);
        } else {
            showMessage(data.error || 'Failed to transfer patient', true);
        }
    } catch (err) {
        showMessage('Network error', true);
    }
}

function loadWardTransferHistory(patientId) {
    const container = document.getElementById('transferHistory');
    container.innerHTML = '<div class="loading">Loading transfer history...</div>';

    fetch('<?= url('patients/ward-history') ?>?id=' + patientId)
        .then(r => r.json())
        .then(data => {
            const tabBtn = document.getElementById('transferTabBtn');

            if (!data.length) {
                tabBtn.style.display = 'none';
                container.innerHTML = '<div class="no-notes">No transfer history for this patient</div>';
                return;
            }

            // Show tab with count badge
            tabBtn.style.display = '';
            tabBtn.textContent = `Transfer History (${data.length})`;

            const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };

            let html = '<table class="sessions-table" style="min-width:600px;"><thead><tr><th>Date</th><th>From</th><th>To</th><th>Changed By</th><th>Reason</th></tr></thead><tbody>';

            data.forEach(row => {
                const date = new Date(row.transferred_at).toLocaleDateString('en-GB') + ' ' +
                             new Date(row.transferred_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                const fromColour = wardColours[row.from_ward] || '#94a3b8';
                const toColour   = wardColours[row.to_ward]   || '#94a3b8';

                html += `<tr>
                    <td style="white-space:nowrap;">${date}</td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${fromColour};color:white;">${escapeHtml(row.from_ward)}</span></td>
                    <td><span style="display:inline-block;padding:2px 10px;border-radius:2rem;font-size:0.72rem;font-weight:600;background:${toColour};color:white;">${escapeHtml(row.to_ward)}</span></td>
                    <td>${escapeHtml(row.changed_by)}</td>
                    <td style="color:#64748b;font-style:${row.transfer_reason ? 'normal' : 'italic'};">${row.transfer_reason ? escapeHtml(row.transfer_reason) : 'No reason given'}</td>
                </tr>`;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(err => {
            console.error('Ward history error:', err);
            container.innerHTML = '<div class="error">Error loading transfer history</div>';
        });
}

// ==================== CALENDAR WIDGET (shows individual + group sessions) ====================
const CalendarWidget = (() => {
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    let year = new Date().getFullYear();
    let month = new Date().getMonth();
  let sessionCache = {};
let groupCache = {};
let activeFilters = ['all'];
    function getWardClass(ward) {
        if (!ward) return 'ward-hope';
        const w = ward.toLowerCase();
        if (w === 'hope') return 'ward-hope';
        if (w === 'lakeside') return 'ward-lakeside';
        if (w === 'manor') return 'ward-manor';
        return 'ward-hope';
    }

    function monthKey() { return `${year}-${String(month+1).padStart(2,'0')}`; }

    // Fetch individual sessions (existing)
    function fetchSessions(key, cb) {
        if (sessionCache[key]) { cb(sessionCache[key]); return; }
        fetch('<?= url('sessions/get-all-json') ?>')
            .then(r => r.json())
            .then(data => {
                const sessions = Array.isArray(data) ? data : [];
                const mapped = sessions.map(s => ({
                    id: s.id, patient_id: s.patient_id, initials: s.initials || '',
                    datetime: s.datetime, ward: s.ward || 'Hope', room_number: s.room_number || '?',
                    carenotes_completed: s.carenotes_completed, tracker_completed: s.tracker_completed,
                    tasks_completed: s.tasks_completed, notes: s.notes, is_discharged: s.is_discharged,
                    type: 'individual'
                }));
                const filtered = mapped.filter(s => !s.is_discharged);
                const byMonth = {};
                filtered.forEach(s => {
                    const m = s.datetime.substring(0,7);
                    if (!byMonth[m]) byMonth[m] = [];
                    byMonth[m].push(s);
                });
                for (let m in byMonth) sessionCache[m] = byMonth[m];
                cb(sessionCache[key] || []);
            })
            .catch(err => { console.error('Calendar fetch error:', err); cb([]); });
    }

    // Fetch group sessions for the month
    function fetchGroupSessions(key, cb) {
        if (groupCache[key]) { cb(groupCache[key]); return; }
        fetch('<?= url('group-sessions/list-json') ?>')
            .then(r => r.json())
            .then(data => {
                const sessions = Array.isArray(data) ? data : [];
                const byMonth = {};
                sessions.forEach(s => {
                    const m = s.session_date.substring(0,7);
                    if (!byMonth[m]) byMonth[m] = [];
                  byMonth[m].push({
    id: s.id,
    title: s.group_type,
    date: s.session_date,
    time: s.session_time,
    ward: s.ward || 'Mixed',
    patient_count: s.patient_count,
    status: s.status || 'completed',
    type: 'group'
});
                });
                for (let m in byMonth) groupCache[m] = byMonth[m];
                cb(groupCache[key] || []);
            })
            .catch(err => { console.error('Group fetch error:', err); cb([]); });
    }

    // Render the calendar grid with combined sessions
    function render(indivSessions, groupSessions) {
        const grid = document.getElementById('calGrid');
        if (!grid) return;
        const title = document.getElementById('calTitle');
        if (title) title.textContent = MONTHS[month] + ' ' + year;

        // Combine both types, group by day
     let combined = [...indivSessions, ...groupSessions];
if (!activeFilters.includes('all')) {
    combined = combined.filter(s => {
        if (activeFilters.includes('group') && s.type === 'group') return true;
        if (s.type === 'individual' && activeFilters.includes(s.ward.toLowerCase())) return true;
        return false;
    });
}
        const byDay = {};
        combined.forEach(s => {
            let day;
            if (s.type === 'individual') day = s.datetime.substring(0,10);
            else day = s.date;
            if (!byDay[day]) byDay[day] = [];
            byDay[day].push(s);
        });

        const today = new Date(); today.setHours(0,0,0,0);
        const first = new Date(year, month, 1);
        let startDow = (first.getDay() + 6) % 7;
        const daysInMonth = new Date(year, month+1, 0).getDate();
        let html = '';
        for (let i = 0; i < startDow; i++) html += '<div class="bp-cal-day bp-cal-empty"></div>';
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const dayDate = new Date(year, month, d);
            const isToday = dayDate.getTime() === today.getTime();
            const isPast = dayDate < today;
            const daySessions = byDay[dateStr] || [];
            let cls = 'bp-cal-day';
            if (isToday) cls += ' bp-cal-today';
            if (isPast) cls += ' bp-cal-past';
            let chips = '';
            const MAX_CHIPS = 2;
            daySessions.slice(0, MAX_CHIPS).forEach(s => {
                if (s.type === 'individual') {
                    const time = s.datetime.substring(11,16);
                    const initials = s.initials || '';
                    const wardClass = getWardClass(s.ward);
                    chips += `<div class="bp-cal-session-chip ${wardClass}" onclick="event.stopPropagation();CalendarWidget.openSession(${s.id},${s.patient_id},'${initials}')" title="${initials} ${time} - ${s.ward} Ward">${initials} ${time}</div>`;
                } else {
                    // Group session chip
const time = s.time.substring(0,5);
const shortTitle = s.title.length > 3 ? s.title.substring(0, 3) + '…' : s.title;
const chipStyle = s.status === 'scheduled' ? 'opacity:0.65;border:1px dashed #8b5cf6;' : '';
chips += `<div class="bp-cal-session-chip gs-chip" style="${chipStyle}" onclick="event.stopPropagation();CalendarWidget.openGroupSession(${s.id})" title="${s.status === 'scheduled' ? 'Scheduled: ' : 'Group: '}${s.title} (${s.patient_count} patients)">${shortTitle} ${time}</div>`;
}
            });
            if (daySessions.length > MAX_CHIPS) chips += `<div class="bp-cal-more" onclick="event.stopPropagation();CalendarWidget.openDay('${dateStr}')">+${daySessions.length - MAX_CHIPS} more</div>`;
            html += `<div class="${cls}" onclick="CalendarWidget.dayClick('${dateStr}')"><div class="bp-cal-num">${d}</div><div class="bp-cal-sessions">${chips}</div></div>`;
        }
        grid.innerHTML = html;
    }

    function load() {
        const key = monthKey();
        const grid = document.getElementById('calGrid');
        if (grid) grid.innerHTML = '<div class="bp-cal-loading">Loading calendar...</div>';
        Promise.all([
            new Promise(resolve => fetchSessions(key, resolve)),
            new Promise(resolve => fetchGroupSessions(key, resolve))
        ]).then(([indiv, group]) => {
            render(indiv, group);
        });
    }

    // Fetch group sessions for a specific date (used in day modal)
    async function fetchGroupSessionsForDate(date) {
        try {
            const response = await fetch('<?= url('group-sessions/get-by-date') ?>?date=' + date);
            return await response.json();
        } catch (err) {
            console.error('Error fetching group sessions for date:', err);
            return [];
        }
    }

    return {
        prevMonth() { month--; if (month < 0) { month = 11; year--; } load(); },
        nextMonth() { month++; if (month > 11) { month = 0; year++; } load(); },
      refresh() { delete sessionCache[monthKey()]; delete groupCache[monthKey()]; load(); },
handleFilter(filter, checkbox) {
    const allCb    = document.getElementById('calFilterAll');
    const specific = ['Hope','Lakeside','Manor','Group'];

    if (filter === 'all') {
        specific.forEach(f => {
            const cb = document.getElementById('calFilter' + f);
            if (cb) cb.checked = false;
        });
        if (!checkbox.checked) checkbox.checked = true;
        activeFilters = ['all'];
    } else {
        if (allCb) allCb.checked = false;

        activeFilters = specific
            .map(f => f.toLowerCase())
            .filter(f => {
                const cb = document.getElementById('calFilter' + f.charAt(0).toUpperCase() + f.slice(1));
                return cb && cb.checked;
            });

        // If nothing is checked, revert to All
        if (activeFilters.length === 0) {
            if (allCb) allCb.checked = true;
            activeFilters = ['all'];
        }
    }
    load();
},
        dayClick: async function(dateStr) {
            // Individual sessions from cache
            const key = monthKey();
            const indivSessions = (sessionCache[key] || []).filter(s => s.datetime.startsWith(dateStr));
            // Group sessions via API
            const groupSessions = await fetchGroupSessionsForDate(dateStr);

            const d = new Date(dateStr + 'T00:00:00');
            const label = d.toLocaleDateString('en-GB', { weekday:'long', day:'numeric', month:'long' });
            const titleElem = document.getElementById('calDayTitle');
            if (titleElem) titleElem.innerHTML = `<i class="bi bi-calendar-day"></i> ${label}`;
            const listContainer = document.getElementById('calDayList');
            if (!listContainer) return;

            if (!indivSessions.length && !groupSessions.length) {
                listContainer.innerHTML = '<p style="text-align:center;color:#94a3b8;padding:1rem 0;font-size:0.85rem;">No sessions on this day</p>';
            } else {
                let html = '';
                if (indivSessions.length) {
                    html += '<h4 style="margin:0 0 0.5rem 0; font-size:0.85rem; color:#1e293b;">Individual Sessions</h4>';
                    indivSessions.forEach(s => {
                        const wardClass = getWardClass(s.ward);
                        html += `<div class="day-session-item" onclick="event.stopPropagation(); CalendarWidget.openSession(${s.id}, ${s.patient_id}, '${s.initials}')">
                            <span class="session-initials">${escapeHtml(s.initials)}</span>
                            <span class="session-time">${s.datetime.substring(11,16)}</span>
                            <span class="session-ward ${wardClass}">${escapeHtml(s.ward)}</span>
                            <span class="session-room">Rm ${s.room_number}</span>
                            <span class="session-arrow">→</span>
                        </div>`;
                    });
                }
                if (groupSessions.length) {
    html += '<h4 style="margin-top:0.75rem; margin-bottom:0.5rem; font-size:0.85rem; color:#1e293b;">Group Sessions</h4>';
    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
    groupSessions.forEach(gs => {
        const wardLabel = gs.ward || gs.ward_snapshot || 'Mixed';
        const wardParts = wardLabel.split(',').map(w => w.trim()).filter(Boolean);
        const wardBadges = wardParts.map(w => {
            const c = wardColours[w] || '#8b5cf6';
            return `<span style="background:${c};color:white;padding:2px 6px;border-radius:0.3rem;font-size:0.7rem;font-weight:600;margin-right:2px;">${w}</span>`;
        }).join('');

       html += `<div class="day-session-item gs-item" onclick="event.stopPropagation(); CalendarWidget.openGroupSession(${gs.id})">
    <span class="session-initials">👥</span>
   <span class="session-room" style="font-weight:600;">${escapeHtml(gs.title)}</span>
    <span>${wardBadges}</span>
    <span class="session-time">${gs.time.substring(0,5)}</span>
    <span class="session-arrow">→</span>
</div>`;
    });
}
                listContainer.innerHTML = html;
            }

            window._calDayDate = dateStr;
          const addBtn = document.getElementById('calDayAddBtn');
if (addBtn) addBtn.onclick = () => {
    pushModal('calDayModal');
    document.getElementById('calDayModal').style.display = 'none';
    openSessionModal(null, window._calDayDate);
};
const groupAddBtn = document.getElementById('calDayAddGroupBtn');
if (groupAddBtn) groupAddBtn.onclick = () => {
    pushModal('calDayModal');
    document.getElementById('calDayModal').style.display = 'none';
    openGroupSessionModal(null, window._calDayDate);
};
         // Reset filters
            const wardF = document.getElementById('calDayWardFilter');
            const groupF = document.getElementById('calDayGroupFilter');
            if (wardF) wardF.value = 'all';
            if (groupF) groupF.value = 'all';

            const modal = document.getElementById('calDayModal');
            if (modal) modal.style.display = 'flex';
        },
        openDay(dateStr) { this.dayClick(dateStr); },
       openSession(sessionId, patientId, initials) {
    // Only push calDayModal if it's actually open
    if (document.getElementById('calDayModal').style.display === 'flex') {
        pushModal('calDayModal');
        document.getElementById('calDayModal').style.display = 'none';
    }
    openSingleSessionModal(sessionId, patientId, initials);
},
     openGroupSession(sessionId) {
    // Only push calDayModal if it's actually open
    if (document.getElementById('calDayModal').style.display === 'flex') {
        pushModal('calDayModal');
        document.getElementById('calDayModal').style.display = 'none';
    }
    viewGroupSessionDetails(sessionId);
}
    };
})();

    function makeSessionRowsClickable() { document.querySelectorAll('.session-card').forEach(card => { card.removeEventListener('click', handleSessionCardClick); card.addEventListener('click', handleSessionCardClick); }); }
    function handleSessionCardClick(event) { if (event.target.closest('.session-actions')) return; const sessionId = this.dataset.sessionId; const patientId = this.dataset.patientId; const patientName = this.dataset.patientName; if (sessionId && patientId && patientName) { openSingleSessionModal(sessionId, patientId, patientName); } }
    // ==================== INIT ====================
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.ward-filter input').forEach(cb => cb.addEventListener('change', applyAllFilters));
        document.querySelectorAll('.ward-option input').forEach(cb => cb.addEventListener('change', filterPatients));
        applyAllFilters();
        filterPatients();
        if (typeof CalendarWidget !== 'undefined') CalendarWidget.refresh();
        makeSessionRowsClickable();
        window.onclick = e => {
            if (e.target.id === 'admitModal') closeAdmitModal();
            if (e.target.id === 'sessionModal') closeSessionModal();
            if (e.target.id === 'patientDetailsModal') closePatientDetailsModal();
            if (e.target.id === 'editSessionModal') closeEditSessionModal();
            if (e.target.id === 'changeRoomModal') closeChangeRoomModal();
            if (e.target.id === 'dischargeModal') closeDischargeModal();
            if (e.target.id === 'calDayModal') { modalStack.length = 0; document.getElementById('calDayModal').style.display = 'none'; }
            if (e.target.id === 'singleSessionModal') { modalStack.length = 0; closeSingleSessionModal(); }
            if (e.target.id === 'patientNoteModal') closePatientNoteModal();
            if (e.target.id === 'changeWardModal') closeChangeWardModal();
            if (e.target.id === 'patientDetailsModal') { modalStack.length = 0; closePatientDetailsModal(); }
        };
document.addEventListener('keydown', e => { 
    if (e.key === 'Escape') { 
        modalStack.length = 0; 
        document.querySelectorAll('.modal').forEach(m => m.style.display = 'none'); 
    }
});    
    
    });
    </script>