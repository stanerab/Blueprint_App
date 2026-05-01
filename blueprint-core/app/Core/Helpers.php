<?php
// ======================================================
// GLOBAL HELPERS (WORKS LOCAL + HOSTINGER)
// ======================================================

/*
|--------------------------------------------------------------------------
| View Loader
|--------------------------------------------------------------------------
*/
function view($view, $data = [])
{
    extract($data);

    $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($viewPath)) {
        die("View file not found: {$viewPath}");
    }

    require APP_PATH . '/Views/layouts/header.php';
    require $viewPath;
    require APP_PATH . '/Views/layouts/footer.php';
}


/*
|--------------------------------------------------------------------------
| Base URL (FIXED)
|--------------------------------------------------------------------------
*/
function base_url($path = '')
{
    // Detect protocol
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? "https://"
        : "http://";

    // Domain (localhost or live domain)
    $host = $_SERVER['HTTP_HOST'];

    // Detect project folder correctly
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = str_replace('\\', '/', dirname($scriptName));

    // DO NOT remove /public (this was your bug)
    return rtrim($protocol . $host . $baseDir, '/') . '/' . ltrim($path, '/');
}


/*
|--------------------------------------------------------------------------
| URL Helper
|--------------------------------------------------------------------------
*/
function url($path = '')
{
    return base_url($path);
}


/*
|--------------------------------------------------------------------------
| Redirect Helper
|--------------------------------------------------------------------------
*/
function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}


/*
|--------------------------------------------------------------------------
| Asset Loader (FIXED)
|--------------------------------------------------------------------------
*/
function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}


/*
|--------------------------------------------------------------------------
| Old Form Value
|--------------------------------------------------------------------------
*/
function old($key, $default = '')
{
    return htmlspecialchars($_POST[$key] ?? $default);
}


/*
|--------------------------------------------------------------------------
| Escape Output (XSS Protection)
|--------------------------------------------------------------------------
*/
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}


/*
|--------------------------------------------------------------------------
| CSRF Verification
|--------------------------------------------------------------------------
*/
function verify_csrf($token)
{
    return !empty($_SESSION['csrf_token']) &&
           !empty($token) &&
           hash_equals($_SESSION['csrf_token'], $token);
}

/*
|--------------------------------------------------------------------------
| Auth Middleware
|--------------------------------------------------------------------------
*/
function require_auth()
{
    if (empty($_SESSION['user_id'])) {
        redirect('login');
    }
}


/*
|--------------------------------------------------------------------------
| Current User Helper
|--------------------------------------------------------------------------
*/
function user()
{
    return $_SESSION ?? null;
}


/*
|--------------------------------------------------------------------------
| Debug Helper
|--------------------------------------------------------------------------
*/
function dd($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}