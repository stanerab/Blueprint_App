<?php $title = 'User Management'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .admin-page { padding: 1.5rem; }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .admin-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0 0 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-header p { color: #64748b; font-size: 0.9rem; margin: 0; }

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

    .users-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .users-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .users-card-header h2 {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #1e3a8a;
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-create:hover {
        background: #1e40af;
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table td {
        padding: 0.9rem 1rem;
        font-size: 0.85rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .users-table tr:last-child td { border-bottom: none; }
    .users-table tr:hover td { background: #f8fafc; }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e3a8a, #0369a1);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        margin-right: 0.5rem;
        flex-shrink: 0;
    }

    .user-info { display: flex; align-items: center; }
    .user-name { font-weight: 600; color: #1e293b; }
    .user-username { font-size: 0.75rem; color: #64748b; }

    .badge-admin {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        background: #ede9fe;
        color: #6d28d9;
    }

    .badge-active {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        background: #fee2e2;
        color: #991b1b;
    }

    .action-btns { display: flex; gap: 0.5rem; align-items: center; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.7rem;
        border-radius: 0.4rem;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid;
        transition: all 0.15s;
        background: white;
    }

    .btn-action.edit   { border-color: #e2e8f0; color: #475569; }
    .btn-action.edit:hover { background: #f8fafc; border-color: #1e3a8a; color: #1e3a8a; }

    .btn-action.deactivate { border-color: #fed7aa; color: #92400e; }
    .btn-action.deactivate:hover { background: #fff7ed; }

    .btn-action.activate { border-color: #bbf7d0; color: #065f46; }
    .btn-action.activate:hover { background: #f0fdf4; }

    .btn-action.admin-toggle { border-color: #e9d5ff; color: #6d28d9; }
    .btn-action.admin-toggle:hover { background: #faf5ff; }

    .btn-action.delete { border-color: #fecaca; color: #991b1b; }
    .btn-action.delete:hover { background: #fef2f2; }

    .you-badge {
        display: inline-block;
        padding: 1px 6px;
        border-radius: 2rem;
        font-size: 0.65rem;
        font-weight: 600;
        background: #e0f2fe;
        color: #0369a1;
        margin-left: 0.3rem;
    }

    /* Edit modal */
    .edit-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .edit-modal.open { display: flex; }

    .edit-modal-content {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .edit-modal-content h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0 0 1.25rem;
    }

    .edit-modal-content .form-group { margin-bottom: 1rem; }

    .edit-modal-content label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .edit-modal-content input {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.88rem;
        color: #1e293b;
        background: #f8fafc;
        box-sizing: border-box;
        outline: none;
    }

    .edit-modal-content input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
        background: white;
    }

    .edit-modal-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .btn-cancel {
        padding: 0.6rem 1.2rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background: white;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-save {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        background: #1e3a8a;
        color: white;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-save:hover { background: #1e40af; }

    .toast-msg {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        background: #1e3a8a;
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        z-index: 9999;
        display: none;
    }

    .toast-msg.error { background: #dc2626; }
    .toast-msg.show { display: block; }
</style>

<div class="admin-page">
    <div class="admin-header">
        <div>
            <h1><i class="bi bi-people"></i> User Management</h1>
            <p>Manage Blueprint user accounts. create, edit, activate or deactivate.</p>
        </div>
        <a href="<?= url('dashboard') ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="users-card">
        <div class="users-card-header">
            <h2>All Accounts (<?= count($users) ?>)</h2>
            <a href="<?= url('register') ?>" class="btn-create">
                <i class="bi bi-person-plus"></i> Create Account
            </a>
        </div>

        <div style="overflow-x:auto;">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Users</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <?php foreach ($users as $u): ?>
                    <tr id="user-row-<?= $u->id ?>">
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($u->full_name ?? $u->username, 0, 2)) ?>
                                </div>
                                <div>
                                    <div class="user-name">
                                        <?= htmlspecialchars($u->full_name ?? $u->username) ?>
                                        <?php if ($u->id == $_SESSION['user_id']): ?>
                                            <span class="you-badge">You</span>
                                        <?php endif; ?>
                                        <?php if ($u->is_admin): ?>
                                            <span class="badge-admin">Admin</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="user-username">@<?= htmlspecialchars($u->username) ?> · <?= htmlspecialchars($u->email) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($u->role ?? '—') ?></td>
                        <td>
                            <span class="<?= $u->is_active ? 'badge-active' : 'badge-inactive' ?>" id="status-badge-<?= $u->id ?>">
                                <?= $u->is_active ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td><?= $u->last_login ? date('d/m/Y H:i', strtotime($u->last_login)) : '—' ?></td>
                        <td><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
                        <td>
                            <?php if ($u->id != $_SESSION['user_id']): ?>
                            <div class="action-btns">
                                <button class="btn-action edit" onclick="openEditModal(<?= $u->id ?>, '<?= htmlspecialchars($u->full_name ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($u->role ?? '', ENT_QUOTES) ?>')">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <?php if ($u->is_active): ?>
                                <button class="btn-action deactivate" onclick="toggleActive(<?= $u->id ?>, 0)">
                                    <i class="bi bi-person-dash"></i> Deactivate
                                </button>
                                <?php else: ?>
                                <button class="btn-action activate" onclick="toggleActive(<?= $u->id ?>, 1)">
                                    <i class="bi bi-person-check"></i> Activate
                                </button>
                                <?php endif; ?>
                                <button class="btn-action admin-toggle" onclick="toggleAdmin(<?= $u->id ?>, <?= $u->is_admin ? 0 : 1 ?>)" id="admin-btn-<?= $u->id ?>">
                                    <i class="bi bi-shield<?= $u->is_admin ? '-x' : '-check' ?>"></i>
                                    <?= $u->is_admin ? 'Remove Admin' : 'Make Admin' ?>
                                </button>
                                <button class="btn-action delete" onclick="deleteUser(<?= $u->id ?>, '<?= htmlspecialchars($u->full_name ?? $u->username, ENT_QUOTES) ?>')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                            <?php else: ?>
                            <span style="color:#94a3b8;font-size:0.78rem;">Your account</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="edit-modal" id="editModal">
    <div class="edit-modal-content">
        <h3><i class="bi bi-pencil-square"></i> Edit Account</h3>
        <input type="hidden" id="editUserId">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="editFullName" placeholder="Full name">
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" id="editRole" placeholder="e.g. Psychologist, Art Therapist" maxlength="50">
        </div>
        <div class="edit-modal-actions">
            <button class="btn-cancel" onclick="closeEditModal()">Cancel</button>
            <button class="btn-save" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>
</div>

<div class="toast-msg" id="toastMsg"></div>

<script>
    const CSRF = '<?= csrf_token() ?>';

    function showToast(msg, isError = false) {
        const t = document.getElementById('toastMsg');
        t.textContent = msg;
        t.className = 'toast-msg show' + (isError ? ' error' : '');
        setTimeout(() => t.className = 'toast-msg', 3000);
    }

    // ==================== EDIT ====================
    function openEditModal(userId, fullName, role) {
        document.getElementById('editUserId').value = userId;
        document.getElementById('editFullName').value = fullName;
        document.getElementById('editRole').value = role;
        document.getElementById('editModal').classList.add('open');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('open');
    }

    async function saveEdit() {
        const userId   = document.getElementById('editUserId').value;
        const fullName = document.getElementById('editFullName').value.trim();
        const role     = document.getElementById('editRole').value.trim();

        if (!fullName || !role) { showToast('Name and role are required', true); return; }

        const formData = new FormData();
        formData.append('csrf_token', CSRF);
        formData.append('user_id', userId);
        formData.append('full_name', fullName);
        formData.append('role', role);

        try {
            const res  = await fetch('<?= url('admin/edit-user') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast('Account updated successfully');
                closeEditModal();
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.error || 'Failed to update', true);
            }
        } catch (err) { showToast('Network error', true); }
    }

    // ==================== TOGGLE ACTIVE ====================
    async function toggleActive(userId, active) {
        const action = active ? 'activate' : 'deactivate';
        if (!confirm(`Are you sure you want to ${action} this account?`)) return;

        const formData = new FormData();
        formData.append('csrf_token', CSRF);
        formData.append('user_id', userId);
        formData.append('is_active', active);

        try {
            const res  = await fetch('<?= url('admin/toggle-active') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast(`Account ${action}d successfully`);
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.error || 'Failed to update', true);
            }
        } catch (err) { showToast('Network error', true); }
    }

    // ==================== TOGGLE ADMIN ====================
    async function toggleAdmin(userId, makeAdmin) {
        const action = makeAdmin ? 'grant admin rights to' : 'remove admin rights from';
        if (!confirm(`Are you sure you want to ${action} this account?`)) return;

        const formData = new FormData();
        formData.append('csrf_token', CSRF);
        formData.append('user_id', userId);
        formData.append('is_admin', makeAdmin);

        try {
            const res  = await fetch('<?= url('admin/toggle-admin') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast('Admin status updated');
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(data.error || 'Failed to update', true);
            }
        } catch (err) { showToast('Network error', true); }
    }

    // ==================== DELETE ====================
    async function deleteUser(userId, name) {
        if (!confirm(`⚠️ Permanently delete the account for ${name}? This cannot be undone.`)) return;

        const formData = new FormData();
        formData.append('csrf_token', CSRF);
        formData.append('user_id', userId);

        try {
            const res  = await fetch('<?= url('admin/delete-user') ?>', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                showToast('Account deleted');
                const row = document.getElementById(`user-row-${userId}`);
                if (row) row.remove();
            } else {
                showToast(data.error || 'Failed to delete', true);
            }
        } catch (err) { showToast('Network error', true); }
    }
</script>