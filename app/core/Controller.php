<?php
/**
 * Base Controller Class
 */
abstract class Controller {
    protected $view;
    protected $model;
    
    public function __construct() {
        // Check authentication for protected routes
        $this->checkAuth();
        
        // Initialize view data
        $this->view = new stdClass();
        $this->view->pageTitle = '';
        $this->view->content = '';
    }
    
    protected function checkAuth() {
        $publicRoutes = ['login', 'auth/google', 'auth/google/callback', ''];
        $currentRoute = $_GET['url'] ?? '';
        
        if (!in_array($currentRoute, $publicRoutes) && !Auth::check()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
    
    protected function loadView($view, $data = []) {
        extract($data);
        
        // Check if user is authenticated for layout
        $user = Auth::user();
        $systemSettings = $this->getSystemSettings();
        
        ob_start();
        include APP_PATH . "/views/{$view}.php";
        $content = ob_get_clean();
        
        // Load layout based on authentication
        if (Auth::check()) {
            include APP_PATH . '/views/layouts/app.php';
        } else {
            include APP_PATH . '/views/layouts/auth.php';
        }
    }
    
    protected function loadModel($model) {
        require_once APP_PATH . "/models/{$model}.php";
        return new $model();
    }
    
    protected function redirect($url = '') {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function validateCSRF() {
        if (!CSRF::validate()) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }
    
    protected function checkRole($allowedRoles) {
        if (!Auth::hasRole($allowedRoles)) {
            $this->show403();
        }
    }
    
    protected function show403() {
        header('HTTP/1.0 403 Forbidden');
        $this->loadView('errors/403');
        exit;
    }
    
    protected function getSystemSettings() {
        $settingsModel = new SystemSettings();
        $settings = $settingsModel->getAllSettings();
        
        // Convert to key-value pairs
        $settingsData = [];
        foreach ($settings as $setting) {
            $settingsData[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $settingsData;
    }
    
    protected function uploadFile($file, $directory = 'general') {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        $uploadDir = UPLOAD_PATH . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . '/' . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $directory . '/' . $fileName;
        }
        
        return false;
    }
}
