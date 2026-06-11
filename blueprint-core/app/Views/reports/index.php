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

/* ── HEADER ── */
.reports-header { margin-bottom: 1.5rem; }
.reports-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--clinical-blue);
    margin: 0 0 0.25rem;
}
.reports-header p { color: #64748b; font-size: 0.9rem; margin: 0; }

/* ── FILTERS ── */
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

.filter-group select {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: white;
    width: 100%;
    color: #475569;
    cursor: pointer;
}

.filter-group .date-picker {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: white;
    width: 100%;
    color: #94a3b8;
    cursor: pointer;
    height: 2.5rem;
    box-sizing: border-box;
}

.filter-group .date-picker.has-value {
    color: #1e293b;
}

.filter-group .date-picker:focus {
    outline: none;
    border-color: var(--clinical-blue);
    box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
}

.date-input-wrapper input[type="date"] {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--clinical-border);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    background: white;
    color: #475569;
    cursor: pointer;
    font-family: inherit;
    height: 2.5rem;
    box-sizing: border-box;
}

.date-input-wrapper input[type="date"]:focus {
    outline: none;
    border-color: var(--clinical-blue);
    box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
}

.date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.5;
}

.filter-group select:focus {
    outline: none;
    border-color: var(--clinical-blue);
    box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
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
}
.btn-export:hover { background: #e2e8f0; }

/* ── GOVERNANCE REPORT CONTAINER ── */
.governance-report {
    background: white;
    border: 1px solid var(--clinical-border);
    border-radius: 1rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

/* ── GOVERNANCE REPORT HEADER ── */
.gov-report-header {
    background: var(--clinical-blue);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: 3px solid #1a2f6b;
}

.gov-report-header h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 1rem;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: white !important;
}

.gov-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.gov-meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.gov-meta-label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    opacity: 0.7;
    color: white;
}

.gov-meta-value {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

/* ── REPORT BODY ── */
.gov-report-body {
    padding: 2rem;
}

.gov-section-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin: 0 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--clinical-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* ── WARD GOVERNANCE TABLE ── */
.ward-gov-block {
    margin-bottom: 2rem;
}

.ward-gov-block:last-child { margin-bottom: 0; }

.ward-gov-heading {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 0.6rem;
}

.ward-gov-heading h3 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin: 0;
}

.ward-colour-bar {
    height: 3px;
    border-radius: 2px;
    margin-bottom: 0.75rem;
}

.gov-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
    overflow: hidden;
}

.gov-table thead tr {
    background: #f1f5f9;
}

.gov-table th {
    padding: 0.65rem 1rem;
    text-align: left;
    font-weight: 700;
    color: #374151;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #cbd5e1;
}

.gov-table th:last-child { text-align: right; }

.gov-table td {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    color: #1e293b;
}

.gov-table tr:last-child td { border-bottom: none; }

.gov-table tr:hover td { background: #f8fafc; }

.gov-table td:last-child {
    text-align: right;
    font-weight: 700;
}

.gov-metric-label {
    font-size: 0.85rem;
    color: #374151;
}

.clickable-num {
    color: var(--clinical-blue);
    font-weight: 700;
    cursor: pointer;
    text-decoration: underline;
    text-decoration-style: dotted;
    font-size: 0.95rem;
}
.clickable-num:hover { color: #1a2f6b; }

/* ── GROUP GOVERNANCE TABLE ── */
.group-gov-block {
    margin-bottom: 2.5rem;
}

.group-gov-block:last-child { margin-bottom: 0; }

.group-gov-heading {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.group-gov-heading::before {
    content: '';
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #8b5cf6;
    flex-shrink: 0;
}

.gov-group-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    border: 1px solid #cbd5e1;
    overflow: hidden;
}

.gov-group-table thead tr {
    background: #f1f5f9;
}

.gov-group-table th {
    padding: 0.65rem 1rem;
    text-align: center;
    font-weight: 700;
    color: #374151;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #cbd5e1;
    border-right: 1px solid #e2e8f0;
}

.gov-group-table th:first-child {
    text-align: left;
    border-right: 2px solid #cbd5e1;
}

.gov-group-table th:last-child { border-right: none; }

.gov-group-table td {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    text-align: center;
    color: #1e293b;
}

.gov-group-table td:first-child {
    text-align: left;
    font-weight: 500;
    color: #374151;
    border-right: 2px solid #cbd5e1;
    background: #fafafa;
}

.gov-group-table td:last-child { border-right: none; }
.gov-group-table tr:last-child td { border-bottom: none; }
.gov-group-table tbody tr:hover td { background: #f8fafc; }
.gov-group-table tbody tr:hover td:first-child { background: #f1f5f9; }

/* Ward header cells */
.ward-th-hope     { background: #fef9c3 !important; color: #854d0e !important; }
.ward-th-lakeside { background: #dcfce7 !important; color: #166534 !important; }
.ward-th-manor    { background: #dbeafe !important; color: #1e40af !important; }

/* ── EMPTY / LOADING ── */
.empty-report {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
    font-size: 0.9rem;
}

/* ── MODAL ── */
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

.drilldown-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.drilldown-table th {
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
.drilldown-table td {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
}
.drilldown-table tr:last-child td { border-bottom: none; }
.drilldown-table tbody tr:hover td { background: #f8fafc; }

/* ── PRINT ── */
@media print {
    .filters-bar, .btn-generate, .btn-export, #drilldownModal { display: none !important; }
    .governance-report { border: none; box-shadow: none; }
    .gov-report-header { background: #1e3a8a !important; -webkit-print-color-adjust: exact; }
    .gov-table, .gov-group-table { page-break-inside: avoid; }
    .ward-gov-block, .group-gov-block { page-break-inside: avoid; }
}

@media (max-width: 768px) {
    .filters-bar { flex-direction: column; }
    .filter-group { min-width: unset; width: 100%; }
    .gov-report-body { padding: 1rem; }
    .gov-meta-grid { grid-template-columns: repeat(2, 1fr); }
    .gov-group-table { font-size: 0.75rem; }
    .gov-group-table th, .gov-group-table td { padding: 0.5rem 0.6rem; }
}
</style>

<div class="reports-page">

    <div class="reports-header">
        <h1>Clinical Reports <i class="bi bi-bar-chart-line"></i></h1>
        <p>Governance reporting for clinical audit and quality assurance</p>
    </div>

    <!-- FILTERS -->
   <div class="filters-bar">
    <div class="filter-group">
        <label>Start Date</label>
        <input type="text" id="reportStart" class="date-picker" placeholder="DD/MM/YYYY" readonly>
    </div>
    <div class="filter-group">
        <label>End Date</label>
        <input type="text" id="reportEnd" class="date-picker" placeholder="DD/MM/YYYY" readonly>
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

    <!-- GOVERNANCE REPORT OUTPUT -->
    <div id="governanceReportOutput" style="display:none;">

        <!-- REPORT HEADER -->
        <div class="governance-report">
            <div class="gov-report-header">
                <h2><i class="bi bi-clipboard2-pulse"></i> Clinical Report</h2>
                <div class="gov-meta-grid">
                    <div class="gov-meta-item">
                        <span class="gov-meta-label">Date Range</span>
                        <span class="gov-meta-value" id="metaDateRange">—</span>
                    </div>
                    <div class="gov-meta-item">
                        <span class="gov-meta-label">Generated</span>
                        <span class="gov-meta-value" id="metaGenerated">—</span>
                    </div>
                    <div class="gov-meta-item">
                        <span class="gov-meta-label">Generated By</span>
                        <span class="gov-meta-value"><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Unknown') ?></span>
                    </div>
                    <div class="gov-meta-item">
                        <span class="gov-meta-label">Ward</span>
                        <span class="gov-meta-value" id="metaWard">—</span>
                    </div>
                </div>
            </div>

            <!-- INDIVIDUAL SESSIONS SECTION -->
            <div class="gov-report-body">
                <div class="gov-section-title">
                    <span><i class="bi bi-person"></i> Individual Sessions — 1:1</span>
                    <button class="btn-export" onclick="exportCsv('individual')">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>
                <div id="individualReportContainer">
                    <div class="empty-report">Generating...</div>
                </div>
            </div>

            <!-- GROUP SESSIONS SECTION -->
            <div class="gov-report-body" style="border-top: 1px solid var(--clinical-border); padding-top: 2rem;">
                <div class="gov-section-title">
                    <span><i class="bi bi-people"></i> Group Sessions</span>
                    <button class="btn-export" onclick="exportCsv('group')">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>
                <div id="groupReportContainer">
                    <div class="empty-report">Generating...</div>
                </div>
            </div>

        </div>
    </div>

    <!-- PLACEHOLDER before generation -->
    <div id="reportPlaceholder" style="background:white;border:1px solid var(--clinical-border);border-radius:1rem;padding:4rem;text-align:center;color:#94a3b8;">
        <i class="bi bi-clipboard2-pulse" style="font-size:3rem;opacity:0.3;display:block;margin-bottom:1rem;"></i>
        <p style="margin:0;font-size:0.95rem;">Select a date range and click <strong>Generate Report</strong> to produce a governance report.</p>
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

function formatPeriodShort(start, end) {
    const s = new Date(start).toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    const e = new Date(end).toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    return s + ' – ' + e;
}

async function generateReports() {
    const start = document.getElementById('reportStart').value;
    const end   = document.getElementById('reportEnd').value;
    const ward  = document.getElementById('reportWard').value;

    if (!start || !end) { alert('Please select a start and end date'); return; }
    if (start > end)    { alert('Start date must be before end date'); return; }

    _lastReportParams = { start, end, ward };

    // Show report output, hide placeholder
    document.getElementById('reportPlaceholder').style.display = 'none';
    document.getElementById('governanceReportOutput').style.display = 'block';

    // Update meta
    document.getElementById('metaDateRange').textContent = formatPeriodShort(start, end);
    document.getElementById('metaGenerated').textContent = new Date().toLocaleDateString('en-GB') + ' ' + new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    document.getElementById('metaWard').textContent = ward === 'all' ? 'All Wards' : ward + ' Ward';

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
        container.innerHTML = '<div class="empty-report"><i class="bi bi-clipboard-x" style="font-size:2rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>No individual sessions recorded for this criteria within the selected period.</div>';
        return;
    }

    // Build period label for ward headings
  const startDate = new Date(start);
const endDate   = new Date(end);
const startLabel = startDate.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }).toUpperCase();
const endLabel   = endDate.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }).toUpperCase();
const periodLabel = startLabel === endLabel ? startLabel : startLabel + ' – ' + endLabel;

    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
    let html = '';

    data.forEach(row => {
        const colour   = wardColours[row.ward] || '#94a3b8';
        const offered  = parseInt(row.total_offered)   || 0;
        const completed = parseInt(row.total_completed) || 0;
        const declined  = parseInt(row.total_declined)  || 0;
        const dna       = parseInt(row.total_dna)        || 0;
        const wardUpper = (row.ward || '').toUpperCase();

        html += `
        <div class="ward-gov-block">
            <div class="ward-gov-heading">
                <h3>${wardUpper} WARD – ${periodLabel}</h3>
            </div>
            <div class="ward-colour-bar" style="background:${colour};"></div>
            <table class="gov-table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="gov-metric-label">Total 1:1 Sessions Offered</td>
                        <td><span class="clickable-num" onclick="openDrilldown('individual','${row.ward}','all','${start}','${end}')">${offered}</span></td>
                    </tr>
                    <tr>
                        <td class="gov-metric-label">Total 1:1 Sessions Completed</td>
                        <td><span class="clickable-num" onclick="openDrilldown('individual','${row.ward}','completed','${start}','${end}')">${completed}</span></td>
                    </tr>
                    <tr>
                        <td class="gov-metric-label">Total 1:1 Sessions Declined</td>
                        <td><span class="clickable-num" onclick="openDrilldown('individual','${row.ward}','declined','${start}','${end}')">${declined}</span></td>
                    </tr>
                    <tr>
                        <td class="gov-metric-label">Total 1:1 Sessions DNA</td>
                        <td><span class="clickable-num" onclick="openDrilldown('individual','${row.ward}','dna','${start}','${end}')">${dna}</span></td>
                    </tr>
                </tbody>
            </table>
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
        container.innerHTML = '<div class="empty-report"><i class="bi bi-clipboard-x" style="font-size:2rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>No group sessions recorded for this criteria within the selected period.</div>';
        return;
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

    const wardOrder   = ['Hope', 'Lakeside', 'Manor'];
    const wardColours = { Hope: '#eab308', Lakeside: '#22c55e', Manor: '#3b82f6' };
    const wardThClass = { Hope: 'ward-th-hope', Lakeside: 'ward-th-lakeside', Manor: 'ward-th-manor' };

    let html = '';

    Object.entries(byType).forEach(([type, wards]) => {
        const wardsToShow = wardOrder.filter(w => wards[w]);
        const typeUpper   = type.toUpperCase();

        // Ward header cells
        const wardHeaders = wardsToShow.map(w =>
            `<th class="${wardThClass[w] || ''}">${w}</th>`
        ).join('');

        // Row builder
        const makeRow = (label, metric, attStatus) => {
            const cells = wardsToShow.map(w => {
                const val = wards[w][metric] || 0;
                return `<td><span class="clickable-num" onclick="openDrilldown('group','${w}','${attStatus}','${start}','${end}','${escapeHtml(type)}')">${val}</span></td>`;
            }).join('');
            return `<tr><td>${label}</td>${cells}</tr>`;
        };

        html += `
        <div class="group-gov-block">
            <div class="group-gov-heading">${typeUpper} GROUPS</div>
            <div style="overflow-x:auto;">
            <table class="gov-group-table">
                <thead>
                    <tr>
                        <th>${escapeHtml(type)} Groups</th>
                        ${wardHeaders}
                    </tr>
                </thead>
                <tbody>
                    ${makeRow('Total Offered',  'offered',  'all')}
                    ${makeRow('Total Accepted', 'accepted', 'attended')}
                    ${makeRow('Total Declined', 'declined', 'declined')}
                    ${makeRow('Total DNA',      'dna',      'dna')}
                </tbody>
            </table>
            </div>
        </div>`;
    });

    container.innerHTML = html;
}

// ===== DRILLDOWN ===== (unchanged logic)
async function openDrilldown(type, ward, status, start, end, groupType = 'all') {
    const modal   = document.getElementById('drilldownModal');
    const content = document.getElementById('drilldownContent');
    const title   = document.getElementById('drilldownTitle');

    const statusLabel = status === 'all' ? 'All Sessions' : status === 'dna' ? 'DNA' : status.charAt(0).toUpperCase() + status.slice(1);
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

    let html = `<table class="drilldown-table"><thead><tr>
        <th>Patient</th>
        <th>Ward</th>
        <th>Date</th>
        <th>Clinician</th>
        <th>Status</th>
    </tr></thead><tbody>`;

    data.forEach(row => {
        const wardClass  = (row.ward || '').toLowerCase();
        const rawDate    = row.session_date || '';
        const dateStr    = rawDate ? new Date(rawDate).toLocaleDateString('en-GB') : '—';
        const rawStatus  = (type === 'individual' ? row.status : row.attendance_status) || 'offered';
        const statusKey  = rawStatus.toLowerCase();
        const statusText = statusKey === 'dna' ? 'DNA' : statusKey.charAt(0).toUpperCase() + statusKey.slice(1);

        html += `<tr>
            <td><strong>${escapeHtml(row.patient_name || '—')}</strong></td>
            <td>${wardClass ? `<span class="ward-badge ward-${wardClass}">${escapeHtml(row.ward)}</span>` : '—'}</td>
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

// ===== LIGHTWEIGHT DATE PICKER =====
(function() {
    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const DAYS   = ['Mo','Tu','We','Th','Fr','Sa','Su'];

    let activeInput = null;
    let pickerEl    = null;
    let pickerYear  = new Date().getFullYear();
    let pickerMonth = new Date().getMonth();

    // Internal value store (YYYY-MM-DD) separate from display
    const inputValues = {};

    function getInputValue(input) {
        return inputValues[input.id] || '';
    }

    function buildPicker() {
        const el = document.createElement('div');
        el.id = 'datePicker';
        el.style.cssText = `
            position: fixed;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            padding: 1rem;
            z-index: 99999;
            width: 260px;
            font-family: inherit;
        `;
        document.body.appendChild(el);
        return el;
    }

    function renderPicker() {
        if (!pickerEl) return;

        const today = new Date();
        const firstDay = new Date(pickerYear, pickerMonth, 1);
        const daysInMonth = new Date(pickerYear, pickerMonth + 1, 0).getDate();
        let startDow = (firstDay.getDay() + 6) % 7; // Monday = 0

        let html = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
            <button onclick="window._dpPrev(event)" style="background:none;border:1px solid #e2e8f0;border-radius:0.4rem;width:28px;height:28px;cursor:pointer;font-size:0.9rem;">‹</button>
            <strong style="font-size:0.85rem;color:#1e293b;">${MONTHS[pickerMonth]} ${pickerYear}</strong>
            <button onclick="window._dpNext(event)" style="background:none;border:1px solid #e2e8f0;border-radius:0.4rem;width:28px;height:28px;cursor:pointer;font-size:0.9rem;">›</button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:4px;">
            ${DAYS.map(d => `<div style="text-align:center;font-size:0.68rem;font-weight:600;color:#94a3b8;padding:2px 0;">${d}</div>`).join('')}
        </div>
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;">
        `;

        for (let i = 0; i < startDow; i++) {
            html += `<div></div>`;
        }

        const selectedVal = getInputValue(activeInput);

        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${pickerYear}-${String(pickerMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const isToday = today.getFullYear() === pickerYear && today.getMonth() === pickerMonth && today.getDate() === d;
            const isSelected = selectedVal === dateStr;

            let bg = 'transparent', color = '#1e293b', fw = '400';
            if (isSelected) { bg = '#1e3a8a'; color = 'white'; fw = '600'; }
            else if (isToday) { bg = '#e8f0fe'; color = '#1e3a8a'; fw = '600'; }

            html += `<div onclick="window._dpSelect('${dateStr}')" style="text-align:center;padding:5px 2px;border-radius:0.35rem;cursor:pointer;font-size:0.8rem;background:${bg};color:${color};font-weight:${fw};" onmouseover="if('${isSelected}'!='true')this.style.background='#f1f5f9'" onmouseout="if('${isSelected}'!='true')this.style.background='${isSelected?'#1e3a8a':'transparent'}'">${d}</div>`;
        }

        html += `</div>`;
        pickerEl.innerHTML = html;
    }

    function positionPicker() {
        if (!activeInput || !pickerEl) return;
        const rect = activeInput.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const pickerH = 300;

        pickerEl.style.left = rect.left + 'px';
        if (spaceBelow < pickerH && rect.top > pickerH) {
            pickerEl.style.top = (rect.top - pickerH + window.scrollY) + 'px';
        } else {
            pickerEl.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        }
    }

    function openPicker(input) {
        activeInput = input;
        const val = getInputValue(input);
        if (val) {
            const d = new Date(val);
            pickerYear  = d.getFullYear();
            pickerMonth = d.getMonth();
        } else {
            pickerYear  = new Date().getFullYear();
            pickerMonth = new Date().getMonth();
        }
        if (!pickerEl) pickerEl = buildPicker();
        pickerEl.style.display = 'block';
        renderPicker();
        positionPicker();
    }

    function closePicker() {
        if (pickerEl) pickerEl.style.display = 'none';
        activeInput = null;
    }

  window._dpPrev = function(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    pickerMonth--;
    if (pickerMonth < 0) { pickerMonth = 11; pickerYear--; }
    renderPicker();
};

window._dpNext = function(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    pickerMonth++;
    if (pickerMonth > 11) { pickerMonth = 0; pickerYear++; }
    renderPicker();
};

    window._dpSelect = function(dateStr) {
        if (!activeInput) return;
        inputValues[activeInput.id] = dateStr;
        const [y, m, d] = dateStr.split('-');
        activeInput.value = `${d}/${m}/${y}`;
        activeInput.classList.add('has-value');
        closePicker();
    };

    // Patch generateReports to read internal values
    const _origGenerate = window.generateReports;
    window.generateReports = function() {
        const startInput = document.getElementById('reportStart');
        const endInput   = document.getElementById('reportEnd');
        const startVal   = inputValues['reportStart'] || '';
        const endVal     = inputValues['reportEnd']   || '';

        if (!startVal || !endVal) { alert('Please select a start and end date'); return; }
        if (startVal > endVal)    { alert('Start date must be before end date'); return; }

        _lastReportParams = { start: startVal, end: endVal, ward: document.getElementById('reportWard').value };

        document.getElementById('reportPlaceholder').style.display = 'none';
        document.getElementById('governanceReportOutput').style.display = 'block';

        document.getElementById('metaDateRange').textContent = formatPeriodShort(startVal, endVal);
        document.getElementById('metaGenerated').textContent = new Date().toLocaleDateString('en-GB') + ' ' + new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
        document.getElementById('metaWard').textContent = _lastReportParams.ward === 'all' ? 'All Wards' : _lastReportParams.ward + ' Ward';

        loadIndividualReport(startVal, endVal, _lastReportParams.ward);
        loadGroupReport(startVal, endVal, _lastReportParams.ward);
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.date-picker').forEach(input => {
            input.addEventListener('click', () => openPicker(input));
        });

document.addEventListener('click', e => {
    if (!pickerEl || pickerEl.style.display === 'none') return;
    if (pickerEl.contains(e.target)) return;
    if (e.target.classList.contains('date-picker')) return;
    closePicker();
});

        window.addEventListener('scroll', () => { if (activeInput) positionPicker(); });
        window.addEventListener('resize', () => { if (activeInput) positionPicker(); });
    });
})();
</script>