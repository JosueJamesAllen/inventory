<?php

// ── Bootstrap ─────────────────────────────────────────────
define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH . '/app/Views');

$appConfig = require BASE_PATH . '/config/app.php';

date_default_timezone_set($appConfig['timezone']);

if ($appConfig['debug']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

// ── Autoloader (PSR-4, no Composer needed) ────────────────
spl_autoload_register(function (string $class): void {
    // Map namespaces to directories
    $prefixes = [
        'App\\'  => BASE_PATH . '/app/',
        'Core\\' => BASE_PATH . '/core/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) !== 0) continue;

        $relative = substr($class, $len);
        $file     = $baseDir . str_replace('\\', '/', $relative) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// ── Session ───────────────────────────────────────────────
use Core\Session;
use Core\Request;
use Core\Router;

Session::start();

// ── Router ────────────────────────────────────────────────
$router  = new Router();
$request = new Request();

// Auth
$router->get( '/login',  'AuthController@loginForm');
$router->post('/login',  'AuthController@login');
$router->post('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');
$router->get('/',          'DashboardController@index');

// Scan / Transactions
$router->get( '/scan',         'ScanController@index');
$router->post('/scan/borrow',  'ScanController@borrow');
$router->post('/scan/return',  'ScanController@return');
$router->get( '/scan/check-device', 'ScanController@checkDevice');

// Devices
$router->get( '/devices',            'DeviceController@index');
$router->get( '/devices/history',    'DeviceController@history');
$router->post('/devices',            'DeviceController@store');
$router->post('/devices/update',     'DeviceController@update');
$router->post('/devices/bulk',       'DeviceController@bulkUpdate');
$router->post('/devices/reconcile',  'DeviceController@reconcile');

// Employees
$router->get( '/employees',         'EmployeeController@index');
$router->post('/employees',         'EmployeeController@store');
$router->post('/employees/update',  'EmployeeController@update');

// Audit
$router->get('/audit',        'AuditController@index');
$router->get('/audit/export', 'AuditController@exportCsv');

// My Borrows
$router->get('/my-borrows', 'MyBorrowsController@index');

// Activity Log
$router->get( '/activity',     'ActivityLogController@index');
$router->post('/activity/log', 'ActivityLogController@logAction');

// Maintenance
$router->get( '/maintenance',                    'MaintenanceController@index');
$router->post('/maintenance/locations',          'MaintenanceController@storeLocation');
$router->post('/maintenance/locations/delete',   'MaintenanceController@deleteLocation');
$router->get( '/api/locations',                  'MaintenanceController@locationsJson');

// ── Dispatch ──────────────────────────────────────────────
$router->dispatch($request);
