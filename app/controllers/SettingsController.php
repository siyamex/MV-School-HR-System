<?php
/**
 * Settings Controller
 * Handles system settings management
 */
class SettingsController extends Controller {
    private $settingsModel;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->settingsModel = new SystemSettings();
        $this->userModel = new User();
        
        // Check if user is logged in and is super admin
        if (!Auth::check()) {
            $this->redirect('auth/login');
        }
        
        if (!Auth::hasRole(['super_admin'])) {
            $this->show403();
        }
    }

    public function index() {
        try {
            $settings = $this->settingsModel->getAllSettings();
            
            // Convert settings array to key-value pairs for easier access
            $settingsData = [];
            foreach ($settings as $setting) {
                $settingsData[$setting['setting_key']] = $setting['setting_value'];
            }
            
            $this->loadView('settings/index', [
                'pageTitle' => 'System Settings',
                'settings' => $settingsData
            ]);
        } catch (Exception $e) {
            error_log("SettingsController: Error in index() - " . $e->getMessage());
            Session::flash('error', 'Error loading settings: ' . $e->getMessage());
            $this->redirect('dashboard');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('settings');
        }
        
        $this->validateCSRF();
        
        try {
            // Handle file uploads first
            $uploadFields = ['app_logo', 'app_favicon'];
            foreach ($uploadFields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $uploadPath = $this->uploadFile($_FILES[$field], 'system');
                    if ($uploadPath) {
                        $_POST[$field] = $uploadPath;
                    }
                }
            }
            
            // Update settings
            foreach ($_POST as $key => $value) {
                if ($key !== 'csrf_token') {
                    $this->settingsModel->updateSetting($key, $value);
                }
            }
            
            Session::flash('success', 'Settings updated successfully');
            $this->redirect('settings');
            
        } catch (Exception $e) {
            error_log("SettingsController: Error in update() - " . $e->getMessage());
            Session::flash('error', 'Error updating settings: ' . $e->getMessage());
            $this->redirect('settings');
        }
    }

    public function emailTemplates() {
        try {
            $this->loadView('settings/email-templates', [
                'pageTitle' => 'Email Templates'
            ]);
        } catch (Exception $e) {
            error_log("SettingsController: Error in emailTemplates() - " . $e->getMessage());
            Session::flash('error', 'Error loading email templates: ' . $e->getMessage());
            $this->redirect('settings');
        }
    }
}
