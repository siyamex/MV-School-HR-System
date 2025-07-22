<?php
/**
 * School HR Management System
 * Entry Point - Front Controller
 */

// Show errors on screen for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/bootstrap.php';

// Start session
session_start();

// Get the requested URL
$request = $_GET['url'] ?? '';

// If no URL parameter, try parsing from REQUEST_URI
if (empty($request)) {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Remove script name from request URI
    if ($script_name && strpos($request_uri, $script_name) === 0) {
        $request = substr($request_uri, strlen($script_name));
    } else {
        // Remove base path
        $base_path = str_replace('/index.php', '', $script_name);
        if ($base_path && strpos($request_uri, $base_path) === 0) {
            $request = substr($request_uri, strlen($base_path));
        } else {
            $request = $request_uri;
        }
    }
    
    // Remove query string
    if (($pos = strpos($request, '?')) !== false) {
        $request = substr($request, 0, $pos);
    }
}

$request = trim($request, '/');
$request = filter_var($request, FILTER_SANITIZE_URL);

// Debug: Log the request (remove this in production)
error_log("Requested URL: " . $request);
error_log("Full REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set'));
error_log("GET parameters: " . print_r($_GET, true));

// Basic routing
$router = new Router();

// Authentication routes
$router->add('', 'AuthController@login');
$router->add('login', 'AuthController@login');
$router->add('logout', 'AuthController@logout');
$router->add('auth/google', 'AuthController@googleAuth');
$router->add('auth/google/callback', 'AuthController@googleCallback');

// Dashboard routes
$router->add('dashboard', 'DashboardController@index');

// Employee routes
$router->add('employees', 'EmployeeController@index');
$router->add('employees/create', 'EmployeeController@create');
$router->add('employees/store', 'EmployeeController@store');
$router->add('employees/view/{id}', 'EmployeeController@show');
$router->add('employees/edit/{id}', 'EmployeeController@edit');
$router->add('employees/update/{id}', 'EmployeeController@update');
$router->add('employees/delete/{id}', 'EmployeeController@delete');
$router->add('employees/import', 'EmployeeController@import');
$router->add('employees/export', 'EmployeeController@export');

// Department routes
$router->add('departments', 'DepartmentController@index');
$router->add('departments/create', 'DepartmentController@create');
$router->add('departments/store', 'DepartmentController@create');
$router->add('departments/edit/{id}', 'DepartmentController@edit');
$router->add('departments/update/{id}', 'DepartmentController@edit');
$router->add('departments/view/{id}', 'DepartmentController@view');
$router->add('departments/delete/{id}', 'DepartmentController@delete');
$router->add('departments/assign-head/{id}', 'DepartmentController@assignHead');

// Leave routes
$router->add('leaves', 'LeaveController@index');
$router->add('leaves/create', 'LeaveController@create');
$router->add('leaves/store', 'LeaveController@store');
$router->add('leaves/approve/{id}', 'LeaveController@approve');
$router->add('leaves/reject/{id}', 'LeaveController@reject');
$router->add('leaves/export', 'LeaveController@export');

// Overtime routes
$router->add('overtime', 'OvertimeController@index');
$router->add('overtime/create', 'OvertimeController@create');
$router->add('overtime/store', 'OvertimeController@store');
$router->add('overtime/approve/{id}', 'OvertimeController@approve');
$router->add('overtime/reject/{id}', 'OvertimeController@reject');

// Attendance routes
$router->add('attendance', 'AttendanceController@index');
$router->add('attendance/sync', 'AttendanceController@sync');
$router->add('attendance/manual', 'AttendanceController@manual');
$router->add('attendance/report', 'AttendanceController@report');
$router->add('attendance/export', 'AttendanceController@export');

// Salary routes
$router->add('salary-slips', 'SalaryController@index');
$router->add('salary-slips/upload', 'SalaryController@upload');
$router->add('salary-slips/bulk-upload', 'SalaryController@bulkUpload');
$router->add('salary-slips/download-sample', 'SalaryController@downloadSample');
$router->add('salary-slips/view/{id}', 'SalaryController@view');
$router->add('salary-slips/download/{id}', 'SalaryController@download');

// Settings routes (Super Admin only)
$router->add('settings', 'SettingsController@index');
$router->add('settings/update', 'SettingsController@update');
$router->add('settings/email-templates', 'SettingsController@emailTemplates');

// Profile routes
$router->add('profile', 'ProfileController@index');
$router->add('profile/update', 'ProfileController@update');
$router->add('profile/change-password', 'ProfileController@changePassword');

// API routes for AJAX
$router->add('api/departments', 'ApiController@departments');
$router->add('api/employees', 'ApiController@employees');

// Debug route (remove in production)
$router->add('debug', 'DashboardController@debug');
$router->add('debug/departments', 'DepartmentController@debug');
$router->add('test', 'TestController@index');

// Dispatch the route
try {
    // Show debug info if requested
    if (isset($_GET['debug'])) {
        echo "<h2>Debug Information</h2>";
        echo "<p><strong>Requested URL:</strong> '$request'</p>";
        echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "</p>";
        echo "<p><strong>GET parameters:</strong></p>";
        echo "<pre>" . print_r($_GET, true) . "</pre>";
        echo "<p><strong>Available routes:</strong></p>";
        echo "<pre>";
        $reflection = new ReflectionObject($router);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue($router);
        foreach ($routes as $route => $action) {
            echo "'$route' => '$action'\n";
        }
        echo "</pre>";
        exit;
    }
    
    // Add specific debug for employees route
    if ($request === 'employees') {
        error_log("Index.php: About to dispatch 'employees' route");
        echo "<!-- Debug: Attempting to load employees route -->\n";
    }
    
    $router->dispatch($request);
    
} catch (Exception $e) {
    error_log('Router Error: ' . $e->getMessage());
    error_log('Router Error Stack: ' . $e->getTraceAsString());
    
    // If it's employees route, show the error instead of 404
    if ($request === 'employees') {
        echo "<h1>Router Error for Employees</h1>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        exit;
    }
    
    http_response_code(404);
    include 'app/views/errors/404.php';
}
