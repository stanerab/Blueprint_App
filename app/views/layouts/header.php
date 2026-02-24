<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blueprint - <?= $title ?? 'Task Management'; ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/ward-console.css') ?>">
</head>

<body class="bg-light">

<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark py-3 shadow-sm"
style="background: linear-gradient(90deg,#1e3a8a,#1e40af);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('dashboard') ?>">Blueprint</a>

        <div class="d-flex align-items-center ms-auto">
            <span class="text-white me-3">
                Welcome, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?>
            </span>

            <span class="badge bg-primary me-3 text-capitalize">
                <?= $_SESSION['role'] ?? 'user'; ?>
            </span>

            <a href="<?= url('logout'); ?>" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>
<?php endif; ?>

<!-- Main Page Container -->
<div class="container py-4"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>