<?php
/**
 * Bootstrap Configuration
 * Loads all necessary classes and configurations
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Define constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'hrschool_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'HR School Management');
define('APP_URL', 'http://localhost/HRSCHOOL');

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/core/',
        APP_PATH . '/helpers/',
        APP_PATH . '/middlewares/',
        APP_PATH . '/services/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load core classes
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/helpers/Auth.php';
require_once APP_PATH . '/helpers/Session.php';
require_once APP_PATH . '/helpers/CSRF.php';

// Helper functions
if (!function_exists('csrf_field')) {
    function csrf_field() {
        return CSRF::field();
    }
}

// Load vendor autoload if composer is used
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}
