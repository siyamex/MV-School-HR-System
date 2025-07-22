<?php
/**
 * Employee Controller
 */
class EmployeeController extends Controller {
    private $employeeModel;
    private $userModel;
    private $departmentModel;
    
    public function __construct() {
        parent::__construct();
        // Temporarily comment out role check to debug
        // $this->checkRole(['super_admin', 'admin']);
        
        $this->employeeModel = $this->loadModel('Employee');
        $this->userModel = $this->loadModel('User');
        $this->departmentModel = $this->loadModel('Department');
    }
    
    public function index() {
        error_log("EmployeeController: index() method called");
        
        try {
            error_log("EmployeeController: About to call getAllWithDepartments");
            $employees = $this->employeeModel->getAllWithDepartments();
            error_log("EmployeeController: Retrieved " . count($employees) . " employees");
            
            error_log("EmployeeController: About to call getActive departments");
            $departments = $this->departmentModel->getActive();
            error_log("EmployeeController: Retrieved " . count($departments) . " departments");
            
            // Get statistics - simplified to avoid issues
            error_log("EmployeeController: About to get statistics");
            $stats = [
                'total' => count($employees),
                'active' => 0,
                'inactive' => 0,
                'terminated' => 0
            ];
            
            // Count statuses from the employees array
            foreach ($employees as $employee) {
                if (isset($employee['status'])) {
                    if ($employee['status'] === 'active') $stats['active']++;
                    elseif ($employee['status'] === 'inactive') $stats['inactive']++;
                    elseif ($employee['status'] === 'terminated') $stats['terminated']++;
                }
            }
            
            error_log("EmployeeController: Generated stats: " . print_r($stats, true));
            
            error_log("EmployeeController: About to load view employees/index");
            
            // Check if view file exists
            $viewPath = APP_PATH . '/views/employees/index.php';
            if (!file_exists($viewPath)) {
                error_log("EmployeeController: View file does not exist: " . $viewPath);
                throw new Exception("View file not found: " . $viewPath);
            }
            
            $this->loadView('employees/index', [
                'pageTitle' => 'Employee Management',
                'employees' => $employees,
                'departments' => $departments,
                'stats' => $stats
            ]);
            error_log("EmployeeController: View loaded successfully");
            
        } catch (Exception $e) {
            error_log("EmployeeController: Error in index() - " . $e->getMessage());
            error_log("EmployeeController: Stack trace - " . $e->getTraceAsString());
            
            // Show the error on screen for debugging
            echo "<h1>Employee Controller Error</h1>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            exit;
        }
    }
    
    public function create() {
        $departments = $this->departmentModel->getActive();
        $managers = $this->employeeModel->getManagers(); // Get employees who can be managers
        
        $this->loadView('employees/create', [
            'pageTitle' => 'Add New Employee',
            'departments' => $departments,
            'managers' => $managers
        ]);
    }
    
    public function show($id) {
        $employee = $this->employeeModel->findWithUserInfo($id);
        
        if (!$employee) {
            Session::flash('error', 'Employee not found');
            $this->redirect('employees');
        }
        
        $this->loadView('employees/show', [
            'pageTitle' => 'Employee Details',
            'employee' => $employee
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('employees');
        }
        
        $this->validateCSRF();
        
        try {
            // Combine first_name and last_name into name for validation and processing
            if (!empty($_POST['first_name']) && !empty($_POST['last_name'])) {
                $_POST['name'] = trim($_POST['first_name'] . ' ' . $_POST['last_name']);
            } else if (!empty($_POST['first_name'])) {
                $_POST['name'] = trim($_POST['first_name']);
            } else if (!empty($_POST['last_name'])) {
                $_POST['name'] = trim($_POST['last_name']);
            }
            
            // Validate input
            $errors = $this->validateEmployeeData($_POST);
            if (!empty($errors)) {
                Session::flash('error', 'Please fix the following errors: ' . implode(', ', $errors));
                $this->redirect('employees/create');
            }
            
            // Prepare user data
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'] ?? 'staff',
                'is_active' => 1
            ];
            
            // Set password if provided
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
            }
            
            // Prepare employee data
            $employeeData = [
                'employee_id' => $_POST['employee_id'] ?? null,
                'department_id' => $_POST['department_id'] ?: null,
                'designation' => $_POST['position'] ?? null,  // Form uses 'position' but DB uses 'designation'
                'phone' => $_POST['phone'] ?? null,
                'address' => $_POST['address'] ?? null,
                'hire_date' => $_POST['hire_date'] ?: null,
                'salary' => $_POST['salary'] ?: null,
                'bank_account' => $_POST['bank_account'] ?? null,
                'emergency_contact' => $_POST['emergency_contact'] ?? null,
                'emergency_phone' => $_POST['emergency_phone'] ?? null,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Handle profile photo upload
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                $photoPath = $this->uploadFile($_FILES['profile_photo'], 'profiles');
                if ($photoPath) {
                    $employeeData['profile_photo'] = $photoPath;
                }
            }
            
            // Create employee
            $employeeId = $this->employeeModel->createEmployee($userData, $employeeData);
            
            // Handle document uploads
            $this->handleDocumentUploads($employeeId);
            
            $this->logActivity("Created employee: {$userData['name']}");
            
            Session::flash('success', 'Employee added successfully.');
            $this->redirect('employees');
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to create employee: ' . $e->getMessage());
            $this->redirect('employees/create');
        }
    }
    
    public function edit($id) {
        $employee = $this->employeeModel->findWithUserInfo($id);
        
        if (!$employee) {
            Session::flash('error', 'Employee not found.');
            $this->redirect('employees');
        }
        
        $departments = $this->departmentModel->getActive();
        
        $this->loadView('employees/edit', [
            'pageTitle' => 'Edit Employee - ' . $employee['name'],
            'employee' => $employee,
            'departments' => $departments
        ]);
    }
    
    public function update($id) {
        error_log("EmployeeController: update() method called for ID: $id");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("EmployeeController: Not a POST request, redirecting to employees");
            $this->redirect('employees');
        }
        
        try {
            error_log("EmployeeController: Validating CSRF token");
            $this->validateCSRF();
            error_log("EmployeeController: CSRF token validated successfully");
        } catch (Exception $e) {
            error_log("EmployeeController: CSRF validation failed: " . $e->getMessage());
            Session::flash('error', 'Invalid request. Please try again.');
            $this->redirect("employees/edit/{$id}");
        }
        
        try {
            $employee = $this->employeeModel->find($id);
            if (!$employee) {
                error_log("EmployeeController: Employee not found for ID: $id");
                throw new Exception('Employee not found');
            }
            error_log("EmployeeController: Employee found: " . print_r($employee, true));
            
            // Log POST data (excluding sensitive info)
            $logData = $_POST;
            if (isset($logData['password'])) $logData['password'] = '[HIDDEN]';
            error_log("EmployeeController: POST data received: " . print_r($logData, true));
            
            // Validate input
            $errors = $this->validateEmployeeData($_POST, $id);
            if (!empty($errors)) {
                error_log("EmployeeController: Validation errors: " . implode(', ', $errors));
                Session::flash('error', 'Please fix the following errors: ' . implode(', ', $errors));
                $this->redirect("employees/edit/{$id}");
            }
            error_log("EmployeeController: Validation passed");
            
            // Prepare user data
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'] ?? 'staff'
            ];
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $userData['password'] = $_POST['password'];
                error_log("EmployeeController: Password update requested");
            }
            
            // Prepare employee data
            $employeeData = [
                'employee_id' => $_POST['employee_id'],
                'department_id' => $_POST['department_id'] ?: null,
                'designation' => $_POST['designation'] ?? null,
                'phone' => $_POST['phone'] ?? null,
                'address' => $_POST['address'] ?? null,
                'hire_date' => $_POST['hire_date'] ?: null,
                'salary' => $_POST['salary'] ?: null,
                'bank_account' => $_POST['bank_account'] ?? null,
                'emergency_contact' => $_POST['emergency_contact'] ?? null,
                'emergency_phone' => $_POST['emergency_phone'] ?? null,
                'status' => $_POST['status'] ?? 'active'
            ];
            
            error_log("EmployeeController: Prepared user data: " . print_r($userData, true));
            error_log("EmployeeController: Prepared employee data: " . print_r($employeeData, true));
            
            // Handle profile photo upload
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                error_log("EmployeeController: Profile photo upload detected");
                $photoPath = $this->uploadFile($_FILES['profile_photo'], 'profiles');
                if ($photoPath) {
                    $employeeData['profile_photo'] = $photoPath;
                    error_log("EmployeeController: Profile photo uploaded to: $photoPath");
                }
            }
            
            // Update employee
            error_log("EmployeeController: Starting employee update process");
            $this->employeeModel->updateEmployee($id, $userData, $employeeData);
            error_log("EmployeeController: Employee updated successfully");
            
            // Handle document uploads
            $this->handleDocumentUploads($id);
            
            $this->logActivity("Updated employee: {$userData['name']}");
            
            Session::flash('success', 'Employee updated successfully.');
            error_log("EmployeeController: Success message set, redirecting to employees");
            $this->redirect('employees');
            
        } catch (Exception $e) {
            error_log("EmployeeController: Error in update: " . $e->getMessage());
            error_log("EmployeeController: Stack trace: " . $e->getTraceAsString());
            Session::flash('error', 'Failed to update employee: ' . $e->getMessage());
            $this->redirect("employees/edit/{$id}");
        }
    }
    
    public function delete($id) {
        $this->checkRole(['super_admin']); // Only super admin can delete
        
        try {
            $employee = $this->employeeModel->findWithUserInfo($id);
            if (!$employee) {
                throw new Exception('Employee not found');
            }
            
            $this->employeeModel->deleteEmployee($id);
            
            $this->logActivity("Deleted employee: {$employee['name']}");
            
            Session::flash('success', 'Employee deleted successfully.');
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to delete employee: ' . $e->getMessage());
        }
        
        $this->redirect('employees');
    }
    
    public function import() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleImport();
            return;
        }
        
        $this->loadView('employees/import', [
            'pageTitle' => 'Import Employees'
        ]);
    }
    
    private function handleImport() {
        $this->validateCSRF();
        
        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Please select a valid Excel file.');
            $this->redirect('employees/import');
        }
        
        try {
            // This is a basic implementation - in production, use PhpSpreadsheet
            $file = $_FILES['import_file'];
            $csvData = $this->parseCSV($file['tmp_name']);
            
            $imported = 0;
            $errors = [];
            
            foreach ($csvData as $row => $data) {
                try {
                    if ($row === 0) continue; // Skip header
                    
                    // Map CSV columns to database fields
                    $userData = [
                        'name' => $data[0] ?? '',
                        'email' => $data[1] ?? '',
                        'role' => $data[2] ?? 'staff'
                    ];
                    
                    $employeeData = [
                        'employee_id' => $data[3] ?? null,
                        'department_id' => $this->findDepartmentByName($data[4] ?? ''),
                        'designation' => $data[5] ?? null,
                        'phone' => $data[6] ?? null,
                        'hire_date' => $data[7] ? date('Y-m-d', strtotime($data[7])) : null,
                        'salary' => $data[8] ?? null
                    ];
                    
                    // Validate required fields
                    if (empty($userData['name']) || empty($userData['email'])) {
                        $errors[] = "Row {$row}: Name and email are required";
                        continue;
                    }
                    
                    // Check if email already exists
                    if ($this->userModel->findByEmail($userData['email'])) {
                        $errors[] = "Row {$row}: Email {$userData['email']} already exists";
                        continue;
                    }
                    
                    $this->employeeModel->createEmployee($userData, $employeeData);
                    $imported++;
                    
                } catch (Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                }
            }
            
            $message = "Imported {$imported} employees successfully.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " and " . (count($errors) - 5) . " more...";
                }
            }
            
            Session::flash('success', $message);
            
        } catch (Exception $e) {
            Session::flash('error', 'Import failed: ' . $e->getMessage());
        }
        
        $this->redirect('employees');
    }
    
    public function export() {
        try {
            $employees = $this->employeeModel->getAllWithUserInfo();
            
            // Generate CSV
            $filename = 'employees_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // Write header
            fputcsv($output, [
                'Name', 'Email', 'Employee ID', 'Department', 'Designation', 
                'Phone', 'Hire Date', 'Salary', 'Status', 'Role'
            ]);
            
            // Write data
            foreach ($employees as $employee) {
                fputcsv($output, [
                    $employee['name'],
                    $employee['email'],
                    $employee['employee_id'],
                    $employee['department_name'] ?? '',
                    $employee['designation'] ?? '',
                    $employee['phone'] ?? '',
                    $employee['hire_date'] ?? '',
                    $employee['salary'] ?? '',
                    $employee['status'],
                    $employee['role']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            Session::flash('error', 'Export failed: ' . $e->getMessage());
            $this->redirect('employees');
        }
    }
    
    private function validateEmployeeData($data, $employeeId = null) {
        $errors = [];
        
        // Required fields
        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } else {
            // Check for duplicate email
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser) {
                if (!$employeeId || $existingUser['id'] !== $this->employeeModel->find($employeeId)['user_id']) {
                    $errors[] = 'Email already exists';
                }
            }
        }
        
        // Employee ID validation
        if (!empty($data['employee_id'])) {
            $existingEmployee = $this->employeeModel->findByEmployeeId($data['employee_id']);
            if ($existingEmployee && (!$employeeId || $existingEmployee['id'] != $employeeId)) {
                $errors[] = 'Employee ID already exists';
            }
        }
        
        // Salary validation
        if (!empty($data['salary']) && !is_numeric($data['salary'])) {
            $errors[] = 'Salary must be a valid number';
        }
        
        // Date validation
        if (!empty($data['hire_date']) && !strtotime($data['hire_date'])) {
            $errors[] = 'Invalid hire date format';
        }
        
        return $errors;
    }
    
    private function handleDocumentUploads($employeeId) {
        if (!isset($_FILES['documents'])) {
            return;
        }
        
        $documentModel = $this->loadModel('EmployeeDocument');
        
        foreach ($_FILES['documents']['name'] as $key => $name) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $_FILES['documents']['name'][$key],
                    'type' => $_FILES['documents']['type'][$key],
                    'tmp_name' => $_FILES['documents']['tmp_name'][$key],
                    'error' => $_FILES['documents']['error'][$key],
                    'size' => $_FILES['documents']['size'][$key]
                ];
                
                $filePath = $this->uploadFile($file, 'documents');
                if ($filePath) {
                    $documentModel->create([
                        'employee_id' => $employeeId,
                        'document_type' => $_POST['document_types'][$key] ?? 'other',
                        'document_name' => $name,
                        'file_path' => $filePath,
                        'file_size' => $file['size'],
                        'uploaded_by' => Auth::id()
                    ]);
                }
            }
        }
    }
    
    private function parseCSV($filepath) {
        $data = [];
        if (($handle = fopen($filepath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
    
    private function findDepartmentByName($name) {
        if (empty($name)) {
            return null;
        }
        
        $department = $this->departmentModel->db->fetch(
            "SELECT id FROM departments WHERE name LIKE ? AND is_active = 1",
            ["%{$name}%"]
        );
        
        return $department ? $department['id'] : null;
    }
    
    private function logActivity($action) {
        try {
            $this->db = Database::getInstance();
            $this->db->query(
                "INSERT INTO activity_logs (user_id, action, model, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [
                    Auth::id(),
                    $action,
                    'Employee',
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]
            );
        } catch (Exception $e) {
            error_log('Activity log error: ' . $e->getMessage());
        }
    }
}
