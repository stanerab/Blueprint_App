<?php
date_default_timezone_set('Europe/London');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| PATH DEFINITIONS
|--------------------------------------------------------------------------
*/

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/blueprint-core');
}

if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', __DIR__);
}

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| AUTOLOADER
|--------------------------------------------------------------------------
*/
spl_autoload_register(function ($class) {

    $prefix = 'App\\';
    $base_dir = APP_PATH . DIRECTORY_SEPARATOR;

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

/*
|--------------------------------------------------------------------------
| LOAD CORE FILES
|--------------------------------------------------------------------------
*/
require_once APP_PATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Helpers.php';
require_once APP_PATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Router.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new App\Core\Router();

/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

/* AUTH */
$router->add('GET', '/', 'AuthController@showLogin');
$router->add('GET', '/login', 'AuthController@showLogin');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/register', 'AuthController@showRegister');
$router->add('POST', '/register', 'AuthController@register');

/* DASHBOARD */
$router->add('GET', '/dashboard', 'DashboardController@index');

/* PATIENTS */
$router->add('GET', '/patients', 'PatientController@index');
$router->add('POST', '/patients/add', 'PatientController@add');
$router->add('POST', '/patients/store', 'PatientController@store');
$router->add('POST', '/patients/archive', 'PatientController@archive');
$router->add('POST', '/patients/discharge', 'PatientController@discharge');
$router->add('POST', '/patients/delete', 'PatientController@delete');
$router->add('POST', '/patients/restore', 'PatientController@restore');
$router->add('POST', '/patients/update-room', 'PatientController@updateRoom');
$router->add('GET', '/patients/view/{id}', 'PatientController@view');

/* SESSIONS */
$router->add('GET', '/sessions', 'SessionController@index');
$router->add('POST', '/sessions/add', 'SessionController@add');
$router->add('POST', '/sessions/store', 'SessionController@store');
$router->add('POST', '/sessions/archive', 'SessionController@archive');
$router->add('POST', '/sessions/delete', 'SessionController@delete');
$router->add('POST', '/sessions/restore', 'SessionController@restore');
$router->add('POST', '/sessions/update', 'SessionController@update');
$router->add('GET', '/sessions/get-by-patient', 'SessionController@getByPatientJson');
$router->add('GET', '/sessions/by-ward-month', 'SessionController@byWardMonth');
$router->add('GET', '/patients/get-by-ward', 'PatientController@getByWard');
$router->add('POST', '/group-sessions/store', 'GroupSessionController@store');
$router->add('GET', '/group-sessions/list-json', 'GroupSessionController@listJson');
$router->add('GET', '/group-sessions/get-json', 'GroupSessionController@getJson');
$router->add('POST', '/public/group-sessions/store', 'GroupSessionController@store');
$router->add('POST', '/group-sessions/take-attendance', 'GroupSessionController@takeAttendance');
$router->add('GET', '/group-sessions/today-json', 'GroupSessionController@todayJson');
$router->add('POST', '/group-sessions/update', 'GroupSessionController@update');
$router->add('GET', '/group-sessions/get-by-date', 'GroupSessionController@getByDateJson');
$router->add('POST', '/group-sessions/delete', 'GroupSessionController@delete');
$router->add('POST', '/group-sessions/complete', 'GroupSessionController@complete');


/* AJAX PATIENT ROUTES */
$router->add('GET', '/patients/get-summary', 'PatientController@getSummaryJson');
$router->add('GET', '/patients/get-notes', 'PatientController@getNotesJson');
$router->add('GET', '/patients/get-discharge-notes', 'PatientController@getDischargeNotesJson');

/* NEW AJAX ROUTES (must come before wildcard routes) */
$router->add('POST', '/patients/change-room', 'PatientController@changeRoom');
$router->add('POST', '/patients/update-core10', 'PatientController@updateCore10');
$router->add('POST', '/patients/update-discharge-core10', 'PatientController@updateDischargeCore10');

/* ACTIVITIES */
$router->add('GET', '/activities', 'ActivityController@index');
$router->add('GET', '/activities/ward/{ward}', 'ActivityController@byWard');

/*
|--------------------------------------------------------------------------
| WARD ROUTES (IMPORTANT ORDER)
|--------------------------------------------------------------------------
*/

/* Specific nested routes FIRST */
$router->add('GET', '/wards/{ward}/archived-patients', 'WardController@archivedPatients');
$router->add('GET', '/wards/{ward}/archived-sessions', 'WardController@archivedSessions');
$router->add('GET', '/wards/{ward}/discharged-patients', 'WardController@dischargedPatients');


/* REPORTS */
$router->add('GET', '/reports', 'ReportsController@index');
$router->add('GET', '/reports/individual-json', 'ReportsController@individualJson');
$router->add('GET', '/reports/group-json', 'ReportsController@groupJson');
$router->add('GET', '/reports/individual-drilldown', 'ReportsController@individualDrilldown');
$router->add('GET', '/reports/group-drilldown', 'ReportsController@groupDrilldown');
$router->add('GET', '/reports/export-csv', 'ReportsController@exportCsv');

/* DISCHARGED + ARCHIVED (GLOBAL ROUTES) */
$router->add('GET', '/patients/discharged', 'PatientController@discharged');
$router->add('GET', '/patients/archived', 'PatientController@archived');
$router->add('GET', '/sessions/archived', 'SessionController@archived');


/* General ward route LAST */
$router->add('GET', '/wards/{ward}', 'WardController@show');
$router->add('POST', '/patients/transfer-ward', 'PatientController@transferWard');
$router->add('GET',  '/patients/ward-history',  'PatientController@wardHistoryJson');
// Admin routes
$router->add('GET',  '/admin/users',        'AdminController@users');
$router->add('POST', '/admin/toggle-active', 'AdminController@toggleActive');
$router->add('POST', '/admin/toggle-admin',  'AdminController@toggleAdmin');
$router->add('POST', '/admin/delete-user',   'AdminController@deleteUser');
$router->add('POST', '/admin/edit-user',     'AdminController@editUser');
$router->add('GET', '/patients/room-history', 'PatientController@roomHistoryJson');


// Password reset routes
$router->add('GET', '/forgot-password', 'AuthController@showForgotForm');
$router->add('POST', '/forgot-password', 'AuthController@sendResetLink');
$router->add('GET', '/reset-password', 'AuthController@showResetForm');
$router->add('POST', '/reset-password', 'AuthController@updatePassword');

$router->add('GET', '/sessions/get-all-json', 'SessionController@getAllJson');
/*
|--------------------------------------------------------------------------
| DISPATCH (FIXED)
|--------------------------------------------------------------------------
*/

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->add('POST', '/group-sessions/store', 'GroupSessionController@store');
$router->add('POST', '*', function() { // catch-all for debugging
    if ($_SERVER['REQUEST_URI'] == '/group-sessions/store' || strpos($_SERVER['REQUEST_URI'], 'group-sessions/store') !== false) {
        $controller = new App\Controllers\GroupSessionController();
        $controller->store();
        exit;
    }
});

// Get script directory (e.g. /blueprint/public)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptDir = rtrim($scriptDir, '/');

// Remove base path
if (strpos($uri, $scriptDir) === 0) {
    $uri = substr($uri, strlen($scriptDir));
}

// Clean path
$path = '/' . trim($uri, '/');

// Handle root
if ($path === '/') {
    $router->dispatch('/');
} else {
    $router->dispatch($path);
}