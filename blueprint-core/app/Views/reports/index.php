<?php $title = 'Reports'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
:root {
    --clinical-blue: #1e3a8a;
    --clinical-soft: #e8f0fe;
    --clinical-gray: #f8fafc;
    --clinical-border: #e2e8f0;
    --ward-hope: #eab308;
    --ward-lakeside: #22c55e;
    --ward-manor: #3b82f6;
}

.reports-page { padding: 1.5rem; }

.reports-header { margin-bottom: 1.5rem; }
.reports-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--clinical-blue);
    margin: 0 0 0.25rem;
}
.reports-header p { color: #64748b; font-size: 0.9rem; margin: 0; }

.filters-bar {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 1rem;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    min-width: 150px;
    flex: 1;
}

.filter-group label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.filter-group input,
.filter-group select {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: white;
}

.btn-generate {
    background: var(--clinical-blue);
    color: white;
    border: none;
    padding: 0.55rem 1.4rem;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    align-self: flex-end;
    white-space: nowrap;
}
.btn-generate:hover { background: #1a2f6b; }

.btn-export {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid var(--clinical-border);
    padding: 0.45rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    text-decoration: none;
}
.btn-export:hover { background: #e2e8f0; }

.report-section {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.report-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.report-section-header h2 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--clinical-blue);
    margin: 0;
}

/* Ward block styling — matches Ross's format */
.ward-report-block {
    background: #f8fafc;
    border: 1px solid var(--clinical-border);
    border-radius: 0.75rem;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.ward-report-block:last-child { margin-bottom: 0; }

.ward-report-title {
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ward-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.ward-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.75rem;
}

.stat-box {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    text-align: center;
    transition: all 0.15s;
}

.stat-box.clickable {
    cursor: pointer;
}

.stat-box.clickable:hover {
    border-color: var(--clinical-blue);
    background: var(--clinical-soft);
    transform: translateY(-1px);
}

.stat-box-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 0.35rem;
}

.stat-box-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--clinical-blue);
    line-height: 1;
}

.stat-box.clickable:hover .stat-box-value { color: #1a2f6b; }

/* Group report table */
.report-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.report-table th {
    background: #f8fafc;
    padding: 0.65rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid var(--clinical-border);
}

.report-table td {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
}

.report-table tr:last-child td { border-bottom: none; }
.report-table tbody tr:hover td { background: #f8fafc; }

.clickable-num {
    color: var(--clinical-blue);
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
    text-decoration-style: dotted;
    white-space: nowrap;
}
.clickable-num:hover { color: #1a2f6b; }

.group-type-heading {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    padding: 0.75rem 0 0.5rem;
    margin-top: 0.5rem;
    border-top: 1px solid var(--clinical-border);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.group-type-heading:first-of-type { border-top: none; margin-top: 0; padding-top: 0; }

.ward-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 2rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: white;
}
.ward-hope     { background: var(--ward-hope); }
.ward-lakeside { background: var(--ward-lakeside); }
.ward-manor    { background: var(--ward-manor); }

.status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 2rem;
    font-size: 0.72rem;
    font-weight: 600;
}
.status-offered   { background: #e0f2fe; color: #0369a1; }
.status-completed { background: #d1fae5; color: #065f46; }
.status-declined  { background: #fee2e2; color: #991b1b; }
.status-dna       { background: #fed7aa; color: #92400e; }
.status-attended  { background: #d1fae5; color: #065f46; }

.empty-report {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
    font-size: 0.9rem;
}

.period-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 400;
    margin-left: 0.5rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    max-width: 900px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-group input[type="date"] {
    width: 100%;
    min-width: 140px;
    color: #475569;
    cursor: pointer;
}

.filter-group input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.6;
}

.filter-group input[type="date"]:focus {
    outline: none;
    border-color: var(--clinical-blue);
    box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
}
.modal-header h2 {
    font-size: 1.1rem;
    color: var(--clinical-blue);
    margin: 0;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .filters-bar { flex-direction: column; }
    .filter-group { min-width: unset; width: 100%; }
    .ward-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .report-table { font-size: 0.75rem; }
    .report-table th, .report-table td { padding: 0.5rem; }
}
</style>

<div class="reports-page">

    <div class="reports-header">
        <h1>Clinical Reports</h1>
        <p>Session activity by ward and date range for governance and audit purposes</p>
    </div>

    <!-- FILTERS -->
    <div class="filters-bar">
        <div class="filter-group">
            <label>Start Date</label>
<input type="date" id="reportStart" placeholder="DD/MM/YYYY" style="width:100%;">
        </div>
        <div class="filter-group">
            <label>End Date</label>
<input type="date" id="reportEnd" placeholder="DD/MM/YYYY" style="width:100%;">
        </div>
        <div class="filter-group">
            <label>Ward</label>
            <select id="reportWard">
                <option value="all">All Wards</option>
                <option value="Hope">Hope</option>
                <option value="Lakeside">Lakeside</option>
                <option value="Manor">Manor</option>
            </select>
        </div>
        <button class="btn-generate" onclick="generateReports()">
            <i class="bi bi-arrow-clockwise"></i> Generate Report
        </button>
    </div>

    <!-- INDIVIDUAL SESSIONS -->
    <div class="report-section">
        <div class="report-section-header">
            <h2>
                <i class="bi bi-person"></i> Individual Sessions
                <span class="period-label" id="individualPeriodLabel"></span>
            </h2>
            <button class="btn-export" onclick="exportCsv('individual')">
                <i class="bi bi-download"></i> Export CSV
            </button>
        </div>
        <div id="individualReportContainer">
            <div class="empty-report">Set a date range and click Generate Report</div>
        </div>
    </div>

    <!-- GROUP SESSIONS -->
    <div class="report-section">
        <div class="report-section-header">
            <h2>
                <i class="bi bi-people"></i> Group Sessions
                <span class="period-label" id="groupPeriodLabel"></span>
            </h2>
            <button class="btn-export" onclick="exportCsv('group')">
                <i class="bi bi-download"></i> Export CSV
            </button>
        </div>
        <div id="groupReportContainer">
            <div class="empty-report">Set a date range and click Generate Report</div>
        </div>
    </div>

</div>

<!-- DRILL-DOWN MODAL -->
<div id="drilldownModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="drilldownTitle">Session Details</h2>
            <div style="display:flex;gap:0.5rem;align-items:center;">
                <button class="btn-export" id="drilldownExportBtn">
                    <i class="bi bi-download"></i> Export CSV
                </button>
                <button class="modal-close" onclick="closeDrilldown()">✕</button>
            </div>
        </div>
        <div id="drilldownContent">
            <div class="loading-spinner">Loading...</div>
        </div>
    </div>
</div>

<script>
let _lastReportParams = {};

function formatPeriod(start, end) {
    const s = new Date(start).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
    const e = new Date(end).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
    return s + ' – ' + e;
}

async function generateReports() {
    const start = document.getElementById('reportStart').value;
    const end   = document.getElementById('reportEnd').value;
    const ward  = document.getElementById('reportWard').value;

    if (!start || !end) { alert('Please select a start and end date'); return; }
    if (start > end)    { alert('Start date must be before end date'); return; }

    _lastReportParams = { start, end, ward };

    const period = formatPeriod(start, end);
    document.getElementById('individualPeriodLabel').textContent = period;
    document.getElementById('groupPeriodLabel').textContent = period;

    loadIndividualReport(start, end, ward);
    loadGroupReport(start, end, ward);
}

// ===== INDIVIDUAL =====
async function loadIndividualReport(start, end, ward) {
    const container = document.getElementById('individualReportContainer');
    container.innerHTML = '<div class="loading-spinner"><i class="bi bi-arrow-repeat"></i> Loading...</div>';

    const res  = await fetch(`<?= url('reports/individual-json') ?>?start=${start}&end=${end}&ward=${ward}`);
    const data = await res.json();

if (!data.length) {
    container.innerHTML = `<div class="empty-report">
        <i class="bi bi-clipboard-x" style="font-size:2rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>
        No sessions recorded for this criteria within the selected period.
    </div>`;
    return;
}

    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
    let html = '';

    data.forEach(row => {
        const colour = wardColours[row.ward] || '#94a3b8';
        const offered   = parseInt(row.total_offered)   || 0;
        const completed = parseInt(row.total_completed) || 0;
        const declined  = parseInt(row.total_declined)  || 0;
        const dna       = parseInt(row.total_dna)       || 0;

        html += `
        <div class="ward-report-block">
            <div class="ward-report-title">
                <span class="ward-dot" style="background:${colour};"></span>
                ${escapeHtml(row.ward)} Ward
            </div>
            <div class="ward-stats-grid">
                <div class="stat-box clickable" onclick="openDrilldown('individual','${row.ward}','all','${start}','${end}')">
                    <div class="stat-box-label">Total 1:1 Offered</div>
                    <div class="stat-box-value">${offered}</div>
                </div>
                <div class="stat-box clickable" onclick="openDrilldown('individual','${row.ward}','completed','${start}','${end}')">
                    <div class="stat-box-label">Completed</div>
                    <div class="stat-box-value" style="color:#065f46;">${completed}</div>
                </div>
                <div class="stat-box clickable" onclick="openDrilldown('individual','${row.ward}','declined','${start}','${end}')">
                    <div class="stat-box-label">Declined</div>
                    <div class="stat-box-value" style="color:#991b1b;">${declined}</div>
                </div>
                <div class="stat-box clickable" onclick="openDrilldown('individual','${row.ward}','dna','${start}','${end}')">
                    <div class="stat-box-label">DNA</div>
                    <div class="stat-box-value" style="color:#92400e;">${dna}</div>
                </div>
            </div>
        </div>`;
    });

    container.innerHTML = html;
}

// ===== GROUP =====
async function loadGroupReport(start, end, ward) {
    const container = document.getElementById('groupReportContainer');
    container.innerHTML = '<div class="loading-spinner"><i class="bi bi-arrow-repeat"></i> Loading...</div>';

    const res  = await fetch(`<?= url('reports/group-json') ?>?start=${start}&end=${end}&ward=${ward}`);
    const data = await res.json();

    if (!data.length) {
container.innerHTML = '<div class="empty-report"><i class="bi bi-clipboard-x" style="font-size:2rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>No group sessions recorded for this criteria within the selected period.</div>';        return;
    }

    // Organise: { groupType: { ward: { offered, accepted, declined, dna } } }
    const byType = {};
    data.forEach(row => {
        const type = row.group_type;
        const w    = row.ward_name;
        if (!byType[type]) byType[type] = {};
        if (!byType[type][w]) byType[type][w] = { offered: 0, accepted: 0, declined: 0, dna: 0 };
        byType[type][w].offered  += parseInt(row.offered)  || 0;
        byType[type][w].accepted += parseInt(row.accepted) || 0;
        byType[type][w].declined += parseInt(row.declined) || 0;
        byType[type][w].dna      += parseInt(row.dna)      || 0;
    });

    const wardOrder = ['Hope', 'Lakeside', 'Manor'];
    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };

    let html = '';

    Object.entries(byType).forEach(([type, wards]) => {
        html += `
        <div class="group-type-heading">
            <i class="bi bi-people-fill" style="color:#8b5cf6;"></i>
            ${escapeHtml(type)}
        </div>
        <div style="overflow-x:auto;margin-bottom:1rem;">
        <table class="report-table">
            <thead>
                <tr>
                    <th>Ward</th>
                    <th>Offered</th>
                    <th>Accepted</th>
                    <th>Declined</th>
                    <th>DNA</th>
                </tr>
            </thead>
            <tbody>`;

        // Show wards in fixed order, only those with data
        const wardsToShow = wardOrder.filter(w => wards[w]);
        // If ward filter is active, only show that ward
        wardsToShow.forEach(w => {
            const d = wards[w];
            const colour = wardColours[w] || '#94a3b8';
            html += `<tr>
                <td><span class="ward-badge" style="background:${colour};">${w}</span></td>
                <td><span class="clickable-num" onclick="openDrilldown('group','${w}','all','${start}','${end}','${escapeHtml(type)}')">${d.offered}</span></td>
                <td><span class="clickable-num" onclick="openDrilldown('group','${w}','attended','${start}','${end}','${escapeHtml(type)}')">${d.accepted}</span></td>
                <td><span class="clickable-num" onclick="openDrilldown('group','${w}','declined','${start}','${end}','${escapeHtml(type)}')">${d.declined}</span></td>
                <td><span class="clickable-num" onclick="openDrilldown('group','${w}','dna','${start}','${end}','${escapeHtml(type)}')">${d.dna}</span></td>
            </tr>`;
        });

        html += `</tbody></table></div>`;
    });

    container.innerHTML = html;
}

// ===== DRILLDOWN =====
async function openDrilldown(type, ward, status, start, end, groupType = 'all') {
    const modal   = document.getElementById('drilldownModal');
    const content = document.getElementById('drilldownContent');
    const title   = document.getElementById('drilldownTitle');

    const statusLabel = status === 'all' ? 'All Sessions' : status.charAt(0).toUpperCase() + status.slice(1);
    const wardLabel   = ward  === 'all' ? 'All Wards' : ward + ' Ward';
    const typeLabel   = groupType !== 'all' ? ` — ${groupType}` : '';
    title.textContent = `${statusLabel} · ${wardLabel}${typeLabel}`;

    content.innerHTML = '<div class="loading-spinner">Loading records...</div>';
    modal.style.display = 'flex';

    let fetchUrl;
    if (type === 'individual') {
        fetchUrl = `<?= url('reports/individual-drilldown') ?>?start=${start}&end=${end}&ward=${ward}&status=${status}`;
    } else {
        fetchUrl = `<?= url('reports/group-drilldown') ?>?start=${start}&end=${end}&ward=${ward}&group_type=${encodeURIComponent(groupType)}&att_status=${status}`;
    }

   document.getElementById('drilldownExportBtn').onclick = () => {
    if (!confirm('Download this report as a CSV file?')) return;
    const base = type === 'individual'
        ? `<?= url('reports/export-csv') ?>?type=individual&start=${start}&end=${end}&ward=${ward}&status=${status}`
        : `<?= url('reports/export-csv') ?>?type=group&start=${start}&end=${end}&ward=${ward}&group_type=${encodeURIComponent(groupType)}&att_status=${status}`;
    window.location.href = base;
};

    const res  = await fetch(fetchUrl);
    const data = await res.json();

    if (!data.length) {
        content.innerHTML = '<div class="empty-report"><i class="bi bi-clipboard-x" style="font-size:2rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>No sessions recorded for this criteria within the selected period.</div>';
        return;
    }

    let html = `<table class="report-table"><thead><tr>
        <th>Patient</th>
        <th>Ward</th>
        <th>Date</th>
        <th>Clinician</th>
        <th>Status</th>
    </tr></thead><tbody>`;

    data.forEach(row => {
        const wardClass  = (row.ward || '').toLowerCase();
        const dateStr    = new Date(row.session_date).toLocaleDateString('en-GB');
        const statusKey  = (type === 'individual' ? row.status : row.attendance_status || '').toLowerCase();
       const statusText = statusKey === 'dna' ? 'DNA' : statusKey.charAt(0).toUpperCase() + statusKey.slice(1);

        html += `<tr>
            <td><strong>${escapeHtml(row.patient_name || '—')}</strong></td>
            <td><span class="ward-badge ward-${wardClass}">${escapeHtml(row.ward || '—')}</span></td>
            <td>${dateStr}</td>
            <td>${escapeHtml(row.clinician || '—')}</td>
            <td><span class="status-badge status-${statusKey}">${statusText}</span></td>
        </tr>`;
    });

    html += '</tbody></table>';
    content.innerHTML = html;
}

function closeDrilldown() {
    document.getElementById('drilldownModal').style.display = 'none';
}

function exportCsv(type) {
    const { start, end, ward } = _lastReportParams;
    if (!start) { alert('Please generate a report first'); return; }
    if (!confirm('Download this report as a CSV file?')) return;
    window.location.href = `<?= url('reports/export-csv') ?>?type=${type}&start=${start}&end=${end}&ward=${ward}`;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

window.onclick = e => {
    if (e.target.id === 'drilldownModal') closeDrilldown();
};
</script>