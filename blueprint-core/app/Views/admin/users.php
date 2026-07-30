<?php $title = 'Admin Panel'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .admin-page {
        padding: 1.5rem;
        min-height: calc(100vh - 60px);
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
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

    /* Stats strip */
    .admin-stats {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .admin-stat {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 160px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .admin-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .admin-stat-icon.blue  { background: #eff6ff; color: #1e3a8a; }
    .admin-stat-icon.green { background: #f0fdf4; color: #065f46; }
    .admin-stat-icon.amber { background: #fffbeb; color: #92400e; }
    .admin-stat-icon.purple { background: #faf5ff; color: #6d28d9; }

    .admin-stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .admin-stat-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Section title */
    .section-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 1rem;
    }

    /* Action cards */
    .admin-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        max-width: 640px;
    }

    .admin-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .admin-card:hover {
        border-color: #1e3a8a;
        box-shadow: 0 4px 16px rgba(30,58,138,0.1);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .admin-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .admin-card-icon.blue   { background: #eff6ff; color: #1e3a8a; }
    .admin-card-icon.green  { background: #f0fdf4; color: #065f46; }

    .admin-card-body { flex: 1; }

    .admin-card-body h3 {
        font-size: 0.92rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.2rem;
    }

    .admin-card-body p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .admin-card-arrow {
        font-size: 1rem;
        color: #cbd5e1;
        transition: color 0.2s;
        flex-shrink: 0;
    }

    .admin-card:hover .admin-card-arrow { color: #1e3a8a; }

    @media (max-width: 640px) {
        .admin-stats { flex-direction: column; }
        .admin-cards { grid-template-columns: 1fr; max-width: 100%; }
    }
</style>

<div class="admin-page">

    <div class="admin-header">
        <div>
            <h1><i class="bi bi-shield-lock"></i> Admin Panel</h1>
            <p>Manage Blueprint users and account settings.</p>
        </div>
        <a href="<?= url('dashboard') ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php
        // Fetch quick stats
        $db = \App\Config\Database::getInstance();
        $totalUsers    = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $activeUsers   = $db->query("SELECT COUNT(*) FROM users WHERE is_active = 1")->fetchColumn();
        $adminUsers    = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 1")->fetchColumn();
        $pendingInvites = $db->query("SELECT COUNT(*) FROM user_invites WHERE used = 0 AND expires_at > NOW()")->fetchColumn();
    ?>

    <div class="admin-stats">
        <div class="admin-stat">
            <div class="admin-stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="admin-stat-value"><?= $totalUsers ?></div>
                <div class="admin-stat-label">Total Users</div>
            </div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-icon green"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="admin-stat-value"><?= $activeUsers ?></div>
                <div class="admin-stat-label">Active Users</div>
            </div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-icon purple"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="admin-stat-value"><?= $adminUsers ?></div>
                <div class="admin-stat-label">Admins</div>
            </div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-icon amber"><i class="bi bi-envelope"></i></div>
            <div>
                <div class="admin-stat-value"><?= $pendingInvites ?></div>
                <div class="admin-stat-label">Pending Invites</div>
            </div>
        </div>
    </div>

    <div class="section-title">Quick Actions</div>

    <div class="admin-cards">
        <a href="<?= url('register') ?>" class="admin-card">
            <div class="admin-card-icon blue">
                <i class="bi bi-person-plus"></i>
            </div>
            <div class="admin-card-body">
                <h3>Invite User</h3>
                <p>Send a secure invitation email to a new team member.</p>
            </div>
            <div class="admin-card-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>

        <a href="<?= url('admin/users/list') ?>" class="admin-card">
            <div class="admin-card-icon green">
                <i class="bi bi-people"></i>
            </div>
            <div class="admin-card-body">
                <h3>View Users</h3>
                <p>Manage accounts, roles, and permissions.</p>
            </div>
            <div class="admin-card-arrow"><i class="bi bi-chevron-right"></i></div>
        </a>
    </div>

</div>