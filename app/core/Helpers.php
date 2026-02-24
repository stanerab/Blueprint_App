<?php
// ======================================================
// GLOBAL HELPERS
// ======================================================

/*
|--------------------------------------------------------------------------
| Base URL
|--------------------------------------------------------------------------
| Change this once if project folder changes
*/
if (!defined('BASE_URL')) {
    define('BASE_URL', '/blueprint/public');
}


/*
|--------------------------------------------------------------------------
| View Loader
|--------------------------------------------------------------------------
*/
if (!function_exists('view')) {
    function view($view, $data = [])
    {
        extract($data);

        $viewPath = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.view.php';

        if (!file_exists($viewPath)) {
            die("View '{$view}' not found.");
        }

        require APP_PATH . '/views/layouts/header.php';
        require $viewPath;
        require APP_PATH . '/views/layouts/footer.php';
    }
}


/*
|--------------------------------------------------------------------------
| URL Generator
|--------------------------------------------------------------------------
*/
if (!function_exists('url')) {
    function url($path = '')
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/
if (!function_exists('redirect')) {
    function redirect($path = '')
    {
        header("Location: " . url($path));
        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Asset Loader
|--------------------------------------------------------------------------
*/
if (!function_exists('asset')) {
    function asset($path)
    {
        return BASE_URL . '/assets/' . ltrim($path, '/');
    }
}


/*
|--------------------------------------------------------------------------
| Old Form Value
|--------------------------------------------------------------------------
*/
if (!function_exists('old')) {
    function old($key, $default = '')
    {
        return htmlspecialchars($_POST[$key] ?? $default);
    }
}


/*
|--------------------------------------------------------------------------
| Escape Output (XSS Protection)
|--------------------------------------------------------------------------
*/
if (!function_exists('e')) {
    function e($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}


/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}


/*
|--------------------------------------------------------------------------
| CSRF Verification
|--------------------------------------------------------------------------
*/
if (!function_exists('verify_csrf')) {
    function verify_csrf($token)
    {
        if (
            empty($_SESSION['csrf_token']) ||
            empty($token) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            die('Invalid CSRF token.');
        }
    }
}


/*
|--------------------------------------------------------------------------
| Auth Check Middleware
|--------------------------------------------------------------------------
*/
if (!function_exists('require_auth')) {
    function require_auth()
    {
        if (empty($_SESSION['user_id'])) {
            redirect('login.php?timeout=1');
        }
    }
}


/*
|--------------------------------------------------------------------------
| Logged User Helper
|--------------------------------------------------------------------------
*/
if (!function_exists('user')) {
    function user()
    {
        return $_SESSION['user'] ?? null;
    }
}


/*
|--------------------------------------------------------------------------
| Debug Helper (Development Only)
|--------------------------------------------------------------------------
*/
if (!function_exists('dd')) {
    function dd($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }
}