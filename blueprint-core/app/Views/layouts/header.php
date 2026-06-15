<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Blueprint - <?= $title ?? 'Clinical Dashboard' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ward-console.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>?v=10">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= asset('images/favicon.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/favicon.png') ?>">

    <style>
    /* ── NAVBAR ── */
    .app-navbar {
        background: linear-gradient(135deg, #1e3a8a 0%, #2f4fb3 100%);
        border-bottom: 1px solid rgba(255,255,255,0.08);
        min-height: 60px;
    }

    .app-navbar .navbar-brand span {
        font-size: 1.1rem;
        letter-spacing: -0.01em;
    }

    /* Nav links */
    .app-navbar .nav-link {
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.4rem 0.85rem;
        border-radius: 0.5rem;
        color: rgba(255,255,255,0.75) !important;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .app-navbar .nav-link:hover {
        background: rgba(255,255,255,0.1);
        color: white !important;
    }

    .app-navbar .nav-link.active {
        background: rgba(255,255,255,0.15);
        color: white !important;
        font-weight: 600;
    }

    .app-navbar .nav-link svg {
        opacity: 0.7;
        flex-shrink: 0;
    }

    .app-navbar .nav-link:hover svg,
    .app-navbar .nav-link.active svg {
        opacity: 1;
    }

    /* Divider between nav links */
    .nav-divider {
        width: 1px;
        height: 18px;
        background: rgba(255,255,255,0.15);
        margin: 0 0.25rem;
        align-self: center;
    }

    /* Avatar */
    .app-navbar .avatar-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1.5px solid rgba(255,255,255,0.25);
        flex-shrink: 0;
    }

    .avatar-circle {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* User dropdown trigger */
    .app-navbar .user-trigger {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.75rem 0.35rem 0.4rem;
        border-radius: 2rem;
        border: 1px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.08);
        transition: all 0.15s;
        color: white !important;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
    }

    .app-navbar .user-trigger:hover {
        background: rgba(255,255,255,0.15);
        border-color: rgba(255,255,255,0.3);
    }

    .app-navbar .user-trigger .bi-chevron-down {
        font-size: 0.7rem;
        opacity: 0.7;
    }

    /* Dropdown menu */
    .app-navbar .dropdown-menu {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        padding: 0.4rem;
        min-width: 180px;
        margin-top: 0.5rem !important;
    }

    .app-navbar .dropdown-item {
        border-radius: 0.5rem;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        transition: background 0.15s;
    }

    .app-navbar .dropdown-item:hover {
        background: #f1f5f9;
    }

    .app-navbar .dropdown-item.text-danger:hover {
        background: #fee2e2;
    }

    .app-navbar .dropdown-divider {
        margin: 0.3rem 0;
        border-color: #f1f5f9;
    }

    /* Mobile */
    @media (max-width: 991px) {
        .app-navbar .navbar-collapse {
            padding: 0.75rem 0 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 0.5rem;
        }

        .app-navbar .nav-link {
            padding: 0.5rem 0.75rem;
        }

        .nav-divider { display: none; }

        .app-navbar .user-trigger {
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
    }
    </style>
</head>

<body class="bg-light">

<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
    <div class="container-fluid px-4 px-lg-5">

        <!-- BRAND -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold"
           href="<?= url('dashboard') ?>">
            <svg width="34" height="34" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"
                 style="border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                <defs>
                    <linearGradient id="bpGradient" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#7aa7f5"/>
                        <stop offset="100%" stop-color="#4a6fd4"/>
                    </linearGradient>
                </defs>
                <rect width="48" height="48" rx="12" fill="url(#bpGradient)"/>
                <g stroke="white" stroke-opacity="0.2" stroke-width="1.5">
                    <line x1="16" y1="8" x2="16" y2="40"/>
                    <line x1="32" y1="8" x2="32" y2="40"/>
                    <line x1="8" y1="16" x2="40" y2="16"/>
                    <line x1="8" y1="32" x2="40" y2="32"/>
                </g>
                <text x="50%" y="55%" text-anchor="middle" fill="white" font-size="20" font-weight="700">B</text>
            </svg>
            <span>Blueprint</span>
        </a>

        <!-- MOBILE TOGGLE -->
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
<!--
            <ul class="navbar-nav me-auto align-items-center gap-1 ms-lg-3">

                <li class="nav-item">
                    <a class="nav-link <?= ($title ?? '') === 'Home' ? 'active' : '' ?>"
                       href="<?= url('dashboard') ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                <div class="nav-divider"></div>

                <li class="nav-item">
                    <a class="nav-link <?= ($title ?? '') === 'Reports' ? 'active' : '' ?>"
                       href="<?= url('reports') ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 20V10M12 20V4M6 20v-6"/>
                        </svg>
                        Reports
                    </a>
                </li>

                <div class="nav-divider"></div>

                <li class="nav-item">
                    <a class="nav-link <?= ($title ?? '') === 'Activity Log' ? 'active' : '' ?>"
                       href="<?= url('activities') ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Activity
                    </a>
                </li>

            </ul>

-->

            <!-- RIGHT: USER DROPDOWN -->
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="user-trigger dropdown-toggle"
                       href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <div class="avatar-circle">
                            <?= strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'], 0, 1)); ?>
                        </div>
                        <span><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text text-muted" style="font-size:0.78rem;padding:0.4rem 0.75rem;">
                                <?= ucwords($_SESSION['role'] ?? 'user'); ?>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center gap-2"
                               href="<?= url('logout'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Global Page Wrapper -->
<div class="container-fluid px-3 px-lg-5 py-2 py-lg-4"></div>