<?php
/**
 * Profile Controller
 * Handles user profile management
 */
class ProfileController extends Controller {
    private $userModel;
    private $employeeModel;
    private $departmentModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->employeeModel = new Employee();
        $this->departmentModel = new Department();
        
        // Check if user is logged in
        if (!Auth::check()) {
            $this->redirect('auth/login');
        }
    }

    public function index() {
        try {
            $userId = Auth::id();
            $user = $this->userModel->find($userId);
            
            if (!$user) {
                Session::flash('error', 'User not found');
                $this->redirect('dashboard');
            }
            
            // Get employee information if exists
            $employee = $this->employeeModel->findByUserId($userId);
            $departments = $this->departmentModel->getActive();
            
            $this->loadView('profile/index', [
                'pageTitle' => 'My Profile',
                'user' => $user,
                'employee' => $employee,
                'departments' => $departments
            ]);
            
        } catch (Exception $e) {
            error_log("ProfileController: Error in index() - " . $e->getMessage());
            Session::flash('error', 'Error loading profile: ' . $e->getMessage());
            $this->redirect('dashboard');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }
        
        $this->validateCSRF();
        
        try {
            $userId = Auth::id();
            $errors = $this->validateProfileData($_POST, $userId);
            
            if (!empty($errors)) {
                Session::flash('error', 'Please fix the following errors: ' . implode(', ', $errors));
                $this->redirect('profile');
            }
            
            // Update user information
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            
            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $avatarPath = $this->uploadFile($_FILES['avatar'], 'avatars');
                if ($avatarPath) {
                    $userData['avatar'] = $avatarPath;
                }
            }
            
            $this->userModel->update($userId, $userData);
            
            // Update employee information if exists
            $employee = $this->employeeModel->findByUserId($userId);
            if ($employee) {
                $employeeData = [
                    'phone' => $_POST['phone'] ?? null,
                    'address' => $_POST['address'] ?? null,
                    'emergency_contact' => $_POST['emergency_contact'] ?? null,
                    'emergency_phone' => $_POST['emergency_phone'] ?? null,
                    'bank_account' => $_POST['bank_account'] ?? null,
                ];
                
                // Handle profile photo upload
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                    $photoPath = $this->uploadFile($_FILES['profile_photo'], 'profiles');
                    if ($photoPath) {
                        $employeeData['profile_photo'] = $photoPath;
                    }
                }
                
                $this->employeeModel->update($employee['id'], $employeeData);
            }
            
            // Update session data
            Session::put('user_name', $_POST['name']);
            Session::put('user_email', $_POST['email']);
            
            Session::flash('success', 'Profile updated successfully');
            $this->redirect('profile');
            
        } catch (Exception $e) {
            error_log("ProfileController: Error in update() - " . $e->getMessage());
            Session::flash('error', 'Error updating profile: ' . $e->getMessage());
            $this->redirect('profile');
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }
        
        $this->validateCSRF();
        
        try {
            $userId = Auth::id();
            $user = $this->userModel->find($userId);
            
            // Validate current password
            if (!password_verify($_POST['current_password'], $user['password'])) {
                Session::flash('error', 'Current password is incorrect');
                $this->redirect('profile');
            }
            
            // Validate new password
            if (strlen($_POST['new_password']) < 8) {
                Session::flash('error', 'New password must be at least 8 characters long');
                $this->redirect('profile');
            }
            
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                Session::flash('error', 'New passwords do not match');
                $this->redirect('profile');
            }
            
            // Update password
            $this->userModel->update($userId, [
                'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
            ]);
            
            Session::flash('success', 'Password changed successfully');
            $this->redirect('profile');
            
        } catch (Exception $e) {
            error_log("ProfileController: Error in changePassword() - " . $e->getMessage());
            Session::flash('error', 'Error changing password: ' . $e->getMessage());
            $this->redirect('profile');
        }
    }

    private function validateProfileData($data, $userId) {
        $errors = [];
        
        // Name validation
        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }
        
        // Email validation
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } else {
            // Check for duplicate email (excluding current user)
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                $errors[] = 'Email already exists';
            }
        }
        
        return $errors;
    }
}
