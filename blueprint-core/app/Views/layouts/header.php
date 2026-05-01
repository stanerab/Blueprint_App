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
/* NAVBAR AVATAR ONLY (scoped properly) */
.app-navbar .avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,0.2);
}

/* Prevent navbar style leaking */
.avatar-circle {
    background: #e2e8f0;
    color: #1e293b;
}

/* Optional: text styling */
.navbar .nav-link span {
    font-weight: 500;
    opacity: 0.9;
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

            <!-- UPDATED SVG LOGO -->
            <svg width="36" height="36" viewBox="0 0 48 48"
                 xmlns="http://www.w3.org/2000/svg"
                 style="border-radius:12px; box-shadow:0 2px 6px rgba(0,0,0,0.15);">

                <defs>
                    <linearGradient id="bpGradient" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#5b8def"/>
                        <stop offset="100%" stop-color="#2f4fb3"/>
                    </linearGradient>
                </defs>

                <!-- Background -->
                <rect width="48" height="48" rx="12" fill="url(#bpGradient)"/>

                <!-- Grid lines -->
                <g stroke="white" stroke-opacity="0.15" stroke-width="2">
                    <line x1="16" y1="8" x2="16" y2="40"/>
                    <line x1="32" y1="8" x2="32" y2="40"/>
                    <line x1="8" y1="16" x2="40" y2="16"/>
                    <line x1="8" y1="32" x2="40" y2="32"/>
                </g>

                <!-- Letter -->
                <text x="50%" y="55%"
                      text-anchor="middle"
                      fill="white"
                      font-size="20"
                      font-weight="700">
                    B
                </text>
            </svg>

            <span>Blueprint</span>
        </a>

        <!-- MOBILE TOGGLE -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- RIGHT SIDE -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                        <!-- USER AVATAR -->
                        <div class="avatar-circle">
                            <?= strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'], 0, 1)); ?>
                        </div>

                        <!-- USER NAME -->
                        <span>
                            <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <span class="dropdown-item-text text-muted small">
                                Role: <?= ucfirst($_SESSION['role'] ?? 'user'); ?>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger"
                               href="<?= url('logout'); ?>">
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

<!-- Global Page Wrapper (OPEN – closes in footer.php) -->
<div class="container-fluid px-3 px-lg-5 py-2 py-lg-4">