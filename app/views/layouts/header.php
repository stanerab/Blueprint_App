<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Blueprint - <?= $title ?? 'Clinical Dashboard' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS (order matters) -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ward-console.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
</head>

<body class="bg-light">

<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark py-3 shadow-sm"
     style="background: linear-gradient(90deg, #1e3a8a, #2563eb);">
    
    <div class="container-fluid px-4 px-lg-5">
        
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold"
   href="<?= url('dashboard') ?>">

    <!-- Modern Blueprint Monogram Logo -->
    <svg width="36" height="36" viewBox="0 0 48 48"
         xmlns="http://www.w3.org/2000/svg"
         class="blueprint-logo">

        <!-- Glass background -->
        <rect width="48" height="48" rx="14"
              fill="white" opacity="0.12"/>

        <!-- Subtle grid -->
        <path d="M10 18H38M10 28H38" stroke="white"
              stroke-width="1" opacity="0.3"/>
        <path d="M18 10V38M28 10V38" stroke="white"
              stroke-width="1" opacity="0.3"/>

        <!-- Bold B -->
        <text x="50%" y="55%"
              text-anchor="middle"
              fill="white"
              font-size="22"
              font-weight="700"
              font-family="Arial, Helvetica, sans-serif">
            B
        </text>
    </svg>

    <span class="text-white">Blueprint</span>
</a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">
                        <span class="me-2">👤</span>
                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <span class="dropdown-item-text text-muted">
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
<div class="container-fluid px-4 px-lg-5 py-4"></div>