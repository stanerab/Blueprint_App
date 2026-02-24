<?php
// Define base paths with checks to prevent redefinition
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'app');
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', __DIR__);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
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

// Load helpers
require_once APP_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Helpers.php';

// Initialize router
require_once APP_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Router.php';
$router = new App\Core\Router();

// ========== ALL ROUTES MUST BE ADDED BEFORE DISPATCH ==========

// Auth routes
$router->add('GET', '/', 'AuthController@showLogin');
$router->add('GET', '/login', 'AuthController@showLogin');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/register', 'AuthController@showRegister');
$router->add('POST', '/register', 'AuthController@register');

// Dashboard routes
$router->add('GET', '/dashboard', 'DashboardController@index');

// Patient routes
$router->add('GET', '/patients', 'PatientController@index');
$router->add('POST', '/patients/add', 'PatientController@add');
$router->add('POST', '/patients/archive', 'PatientController@archive');
$router->add('POST', '/patients/delete', 'PatientController@delete');
$router->add('POST', '/patients/store', 'PatientController@store');
$router->add('POST', '/patients/discharge', 'PatientController@discharge');
$router->add('GET', '/patients/view/{id}', 'PatientController@view');
$router->add('POST', '/patients/update-room', 'PatientController@updateRoom');

// ===== AJAX ROUTES (USING QUERY PARAMETERS) =====
$router->add('GET', '/patients/get-summary', 'PatientController@getSummaryJson');
$router->add('GET', '/patients/get-notes', 'PatientController@getNotesJson');
$router->add('GET', '/patients/get-discharge-notes', 'PatientController@getDischargeNotesJson');
$router->add('GET', '/sessions/get-by-patient', 'SessionController@getByPatientJson');

// Session routes
$router->add('GET', '/sessions', 'SessionController@index');
$router->add('POST', '/sessions/add', 'SessionController@add');
$router->add('POST', '/sessions/archive', 'SessionController@archive');
$router->add('POST', '/sessions/delete', 'SessionController@delete');
$router->add('POST', '/sessions/store', 'SessionController@store');

// ===== SPECIFIC WARD ROUTES - PUT THESE FIRST =====
$router->add('GET', '/wards/hope', 'WardController@hope');
$router->add('GET', '/wards/manor', 'WardController@manor');
$router->add('GET', '/wards/lakeside', 'WardController@lakeside');

// ===== ARCHIVE/DISCHARGE ROUTES - PUT THESE SECOND =====
$router->add('GET', '/wards/{ward}/archived-patients', 'WardController@archivedPatients');
$router->add('GET', '/wards/{ward}/archived-sessions', 'WardController@archivedSessions');
$router->add('GET', '/wards/{ward}/discharged-patients', 'WardController@dischargedPatients');

// ===== GENERAL WARD ROUTE - PUT THIS LAST =====
$router->add('GET', '/wards/{ward}', 'WardController@show');

// Session routes
$router->add('GET', '/sessions', 'SessionController@index');
$router->add('POST', '/sessions/add', 'SessionController@add');
$router->add('POST', '/sessions/archive', 'SessionController@archive');
$router->add('POST', '/sessions/delete', 'SessionController@delete');
$router->add('POST', '/sessions/store', 'SessionController@store');
$router->add('POST', '/sessions/restore', 'SessionController@restore'); 

// Patient routes
$router->add('GET', '/patients', 'PatientController@index');
$router->add('POST', '/patients/add', 'PatientController@add');
$router->add('POST', '/patients/archive', 'PatientController@archive');
$router->add('POST', '/patients/delete', 'PatientController@delete');
$router->add('POST', '/patients/store', 'PatientController@store');
$router->add('POST', '/patients/discharge', 'PatientController@discharge');
$router->add('GET', '/patients/view/{id}', 'PatientController@view');
$router->add('POST', '/patients/update-room', 'PatientController@updateRoom');
$router->add('POST', '/patients/restore', 'PatientController@restore'); 
$router->add('POST', '/sessions/update', 'SessionController@update');
// Activity routes
$router->add('GET', '/activities', 'ActivityController@index');
$router->add('GET', '/activities/ward/{ward}', 'ActivityController@byWard');
// ========== NOW DISPATCH THE REQUEST ==========
$url = isset($_GET['url']) ? $_GET['url'] : '';
$router->dispatch($url);