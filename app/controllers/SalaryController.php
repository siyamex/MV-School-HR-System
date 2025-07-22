<?php

class SalaryController extends Controller {
    private $salaryModel;
    private $employeeModel;
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->salaryModel = new SalarySlip();
        $this->employeeModel = new Employee();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $this->checkRole(['super_admin', 'admin', 'supervisor', 'staff']);
        
        $user = Auth::user();
        $currentRole = $user['role'];
        
        // Get filter parameters
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        $employeeId = $_GET['employee_id'] ?? '';
        $departmentId = $_GET['department_id'] ?? '';
        
        try {
            // Build query based on role
            $salarySlips = [];
            
            if (in_array($currentRole, ['super_admin', 'admin'])) {
                // Admin can see all salary slips
                $salarySlips = $this->getAllSalarySlips($month, $year, $employeeId, $departmentId);
            } elseif ($currentRole === 'supervisor') {
                // Supervisor can see their department's salary slips
                $salarySlips = $this->getDepartmentSalarySlips($user['id'], $month, $year, $employeeId);
            } else {
                // Staff can only see their own salary slips
                $employee = $this->employeeModel->findByUserId($user['id']);
                if ($employee) {
                    $salarySlips = $this->getEmployeeSalarySlips($employee['id'], $month, $year);
                }
            }
            
            // Get additional data for filters
            $employees = [];
            $departments = [];
            
            if (in_array($currentRole, ['super_admin', 'admin'])) {
                $employees = $this->employeeModel->getAllActive();
                $departments = $this->db->query("
                    SELECT id, name 
                    FROM departments 
                    WHERE is_active = 1 
                    ORDER BY name
                ")->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Generate statistics
            $stats = $this->generateStats($salarySlips);
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to load salary slips: ' . $e->getMessage());
            $salarySlips = [];
            $employees = [];
            $departments = [];
            $stats = [];
        }
        
        $this->loadView('salary/index', [
            'pageTitle' => 'Salary Slips',
            'salarySlips' => $salarySlips,
            'employees' => $employees,
            'departments' => $departments,
            'stats' => $stats,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'selectedEmployee' => $employeeId,
            'selectedDepartment' => $departmentId,
            'currentRole' => $currentRole
        ]);
    }
    
    public function upload() {
        $this->checkRole(['super_admin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF
                if (!CSRF::validate($_POST['csrf_token'] ?? '')) {
                    throw new Exception('Invalid security token');
                }
                
                $employeeId = $_POST['employee_id'] ?? '';
                $month = $_POST['month'] ?? '';
                $year = $_POST['year'] ?? '';
                $basicSalary = $_POST['basic_salary'] ?? 0;
                $allowances = $_POST['allowances'] ?? 0;
                $overtimePay = $_POST['overtime_pay'] ?? 0;
                $deductions = $_POST['deductions'] ?? 0;
                $tax = $_POST['tax'] ?? 0;
                
                // Validate required fields
                if (empty($employeeId) || empty($month) || empty($year)) {
                    throw new Exception('Employee, month, and year are required');
                }
                
                // Calculate gross and net salary
                $grossSalary = $basicSalary + $allowances + $overtimePay;
                $netSalary = $grossSalary - $deductions - $tax;
                
                // Handle file upload if present
                $filePath = null;
                if (isset($_FILES['salary_file']) && $_FILES['salary_file']['error'] === UPLOAD_ERR_OK) {
                    $filePath = $this->handleFileUpload($_FILES['salary_file'], $employeeId, $month, $year);
                }
                
                // Check if salary slip already exists
                $existing = $this->db->query("
                    SELECT id FROM salary_slips 
                    WHERE employee_id = ? AND month = ? AND year = ?
                ", [$employeeId, $month, $year])->fetch();
                
                if ($existing) {
                    // Update existing record
                    $this->db->query("
                        UPDATE salary_slips SET
                            basic_salary = ?,
                            allowances = ?,
                            overtime_pay = ?,
                            gross_salary = ?,
                            deductions = ?,
                            tax = ?,
                            net_salary = ?,
                            file_path = COALESCE(?, file_path),
                            updated_at = CURRENT_TIMESTAMP
                        WHERE id = ?
                    ", [
                        $basicSalary, $allowances, $overtimePay, $grossSalary,
                        $deductions, $tax, $netSalary, $filePath, $existing['id']
                    ]);
                    
                    Session::flash('success', 'Salary slip updated successfully');
                } else {
                    // Insert new record
                    $this->db->query("
                        INSERT INTO salary_slips (
                            employee_id, month, year, basic_salary, allowances,
                            overtime_pay, gross_salary, deductions, tax, net_salary, file_path
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ", [
                        $employeeId, $month, $year, $basicSalary, $allowances,
                        $overtimePay, $grossSalary, $deductions, $tax, $netSalary, $filePath
                    ]);
                    
                    Session::flash('success', 'Salary slip created successfully');
                }
                
                $this->redirect('salary-slips');
                
            } catch (Exception $e) {
                Session::flash('error', 'Failed to save salary slip: ' . $e->getMessage());
            }
        }
        
        // Get employees for the form
        $employees = $this->employeeModel->getAllActive();
        
        $this->loadView('salary/upload', [
            'pageTitle' => 'Upload Salary Slip',
            'employees' => $employees
        ]);
    }
    
    public function bulkUpload() {
        $this->checkRole(['super_admin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF
                if (!CSRF::validate($_POST['csrf_token'] ?? '')) {
                    throw new Exception('Invalid security token');
                }
                
                if (!isset($_FILES['bulk_file']) || $_FILES['bulk_file']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('Please select a CSV file to upload');
                }
                
                $file = $_FILES['bulk_file'];
                $month = $_POST['month'] ?? '';
                $year = $_POST['year'] ?? '';
                
                if (empty($month) || empty($year)) {
                    throw new Exception('Month and year are required');
                }
                
                // Validate file type
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if ($fileExtension !== 'csv') {
                    throw new Exception('Only CSV files are allowed');
                }
                
                // Process bulk upload
                $result = $this->processBulkUpload($file['tmp_name'], $month, $year);
                
                Session::flash('success', 
                    "Bulk upload completed successfully. " .
                    "Created: {$result['created']}, Updated: {$result['updated']}, " .
                    "Errors: {$result['errors']}"
                );
                
                if (!empty($result['errorMessages'])) {
                    Session::flash('warning', 'Some records had errors: ' . implode(', ', array_slice($result['errorMessages'], 0, 5)));
                }
                
                $this->redirect('salary-slips');
                
            } catch (Exception $e) {
                Session::flash('error', 'Bulk upload failed: ' . $e->getMessage());
            }
        }
        
        $this->loadView('salary/bulk-upload', [
            'pageTitle' => 'Bulk Upload Salary Slips'
        ]);
    }
    
    public function downloadSample() {
        $this->checkRole(['super_admin', 'admin']);
        
        try {
            // Generate sample CSV file
            $filename = 'salary_slips_sample_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: no-cache, must-revalidate');
            
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'employee_id',
                'basic_salary',
                'allowances',
                'overtime_pay',
                'deductions',
                'tax',
                'remarks'
            ]);
            
            // Add sample data
            $employees = $this->employeeModel->getAllActive();
            $sampleCount = min(5, count($employees)); // Show max 5 samples
            
            for ($i = 0; $i < $sampleCount; $i++) {
                $employee = $employees[$i];
                fputcsv($output, [
                    $employee['employee_id'],
                    $employee['salary'] ?? 5000,
                    500, // allowances
                    200, // overtime_pay
                    100, // deductions
                    150, // tax
                    'Sample entry for ' . $employee['name']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to generate sample file: ' . $e->getMessage());
            $this->redirect('salary-slips');
        }
    }
    
    public function view($id) {
        $this->checkRole(['super_admin', 'admin', 'supervisor', 'staff']);
        
        try {
            $user = Auth::user();
            $salarySlip = $this->getSalarySlipWithDetails($id);
            
            if (!$salarySlip) {
                Session::flash('error', 'Salary slip not found');
                $this->redirect('salary-slips');
                return;
            }
            
            // Check permission based on role
            if (!$this->canViewSalarySlip($user, $salarySlip)) {
                Session::flash('error', 'You do not have permission to view this salary slip');
                $this->redirect('salary-slips');
                return;
            }
            
            $this->loadView('salary/view', [
                'pageTitle' => 'View Salary Slip',
                'salarySlip' => $salarySlip
            ]);
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to load salary slip: ' . $e->getMessage());
            $this->redirect('salary-slips');
        }
    }
    
    public function download($id) {
        $this->checkRole(['super_admin', 'admin', 'supervisor', 'staff']);
        
        try {
            $user = Auth::user();
            $salarySlip = $this->getSalarySlipWithDetails($id);
            
            if (!$salarySlip) {
                Session::flash('error', 'Salary slip not found');
                $this->redirect('salary-slips');
                return;
            }
            
            // Check permission
            if (!$this->canViewSalarySlip($user, $salarySlip)) {
                Session::flash('error', 'You do not have permission to download this salary slip');
                $this->redirect('salary-slips');
                return;
            }
            
            if (!$salarySlip['file_path'] || !file_exists($salarySlip['file_path'])) {
                Session::flash('error', 'Salary slip file not found');
                $this->redirect('salary-slips');
                return;
            }
            
            // Download the file
            $fileName = basename($salarySlip['file_path']);
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . filesize($salarySlip['file_path']));
            readfile($salarySlip['file_path']);
            exit;
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to download salary slip: ' . $e->getMessage());
            $this->redirect('salary-slips');
        }
    }
    
    private function getAllSalarySlips($month, $year, $employeeId, $departmentId) {
        $conditions = [];
        $params = [];
        
        if ($month) {
            $conditions[] = "s.month = ?";
            $params[] = $month;
        }
        
        if ($year) {
            $conditions[] = "s.year = ?";
            $params[] = $year;
        }
        
        if ($employeeId) {
            $conditions[] = "s.employee_id = ?";
            $params[] = $employeeId;
        }
        
        if ($departmentId) {
            $conditions[] = "e.department_id = ?";
            $params[] = $departmentId;
        }
        
        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "
            SELECT s.*, u.name as employee_name, e.employee_id, d.name as department_name
            FROM salary_slips s
            JOIN employees e ON s.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause
            ORDER BY s.year DESC, s.month DESC, u.name
        ";
        
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getDepartmentSalarySlips($supervisorId, $month, $year, $employeeId) {
        // Get supervisor's department(s)
        $departments = $this->db->query("
            SELECT id FROM departments WHERE head_id = ?
        ", [$supervisorId])->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($departments)) {
            return [];
        }
        
        $conditions = ["e.department_id IN (" . implode(',', array_fill(0, count($departments), '?')) . ")"];
        $params = $departments;
        
        if ($month) {
            $conditions[] = "s.month = ?";
            $params[] = $month;
        }
        
        if ($year) {
            $conditions[] = "s.year = ?";
            $params[] = $year;
        }
        
        if ($employeeId) {
            $conditions[] = "s.employee_id = ?";
            $params[] = $employeeId;
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        
        $sql = "
            SELECT s.*, u.name as employee_name, e.employee_id, d.name as department_name
            FROM salary_slips s
            JOIN employees e ON s.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause
            ORDER BY s.year DESC, s.month DESC, u.name
        ";
        
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getEmployeeSalarySlips($employeeId, $month, $year) {
        $conditions = ["s.employee_id = ?"];
        $params = [$employeeId];
        
        if ($month) {
            $conditions[] = "s.month = ?";
            $params[] = $month;
        }
        
        if ($year) {
            $conditions[] = "s.year = ?";
            $params[] = $year;
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        
        $sql = "
            SELECT s.*, u.name as employee_name, e.employee_id, d.name as department_name
            FROM salary_slips s
            JOIN employees e ON s.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            $whereClause
            ORDER BY s.year DESC, s.month DESC
        ";
        
        return $this->db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getSalarySlipWithDetails($id) {
        $sql = "
            SELECT s.*, u.name as employee_name, e.employee_id, 
                   d.name as department_name, e.designation, e.hire_date
            FROM salary_slips s
            JOIN employees e ON s.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE s.id = ?
        ";
        
        return $this->db->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }
    
    private function canViewSalarySlip($user, $salarySlip) {
        $role = $user['role'];
        
        if (in_array($role, ['super_admin', 'admin'])) {
            return true;
        }
        
        if ($role === 'supervisor') {
            // Check if user is head of the employee's department
            $isHead = $this->db->query("
                SELECT 1 FROM departments d
                JOIN employees e ON d.id = e.department_id
                WHERE e.id = ? AND d.head_id = ?
            ", [$salarySlip['employee_id'], $user['id']])->fetch();
            
            return (bool) $isHead;
        }
        
        if ($role === 'staff') {
            // Check if this is the employee's own salary slip
            $employee = $this->employeeModel->findByUserId($user['id']);
            return $employee && $employee['id'] == $salarySlip['employee_id'];
        }
        
        return false;
    }
    
    private function generateStats($salarySlips) {
        if (empty($salarySlips)) {
            return [
                'total_employees' => 0,
                'total_gross' => 0,
                'total_deductions' => 0,
                'total_net' => 0
            ];
        }
        
        return [
            'total_employees' => count($salarySlips),
            'total_gross' => array_sum(array_column($salarySlips, 'gross_salary')),
            'total_deductions' => array_sum(array_column($salarySlips, 'deductions')) + array_sum(array_column($salarySlips, 'tax')),
            'total_net' => array_sum(array_column($salarySlips, 'net_salary'))
        ];
    }
    
    private function handleFileUpload($file, $employeeId, $month, $year) {
        $uploadDir = APP_PATH . '/storage/salary_slips/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Validate file type
        $allowedTypes = ['application/pdf'];
        $fileType = $file['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Only PDF files are allowed');
        }
        
        // Generate unique filename
        $fileName = "salary_slip_{$employeeId}_{$year}_{$month}_" . time() . '.pdf';
        $filePath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Failed to upload file');
        }
        
        return $filePath;
    }
    
    private function processBulkUpload($filePath, $month, $year) {
        $created = 0;
        $updated = 0;
        $errors = 0;
        $errorMessages = [];
        
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            $row = 0;
            $headers = [];
            
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row++;
                
                if ($row === 1) {
                    // Store headers
                    $headers = array_map('strtolower', array_map('trim', $data));
                    
                    // Validate required headers
                    $requiredHeaders = ['employee_id', 'basic_salary'];
                    foreach ($requiredHeaders as $header) {
                        if (!in_array($header, $headers)) {
                            throw new Exception("Missing required column: {$header}");
                        }
                    }
                    continue;
                }
                
                try {
                    // Map data to headers
                    $record = [];
                    foreach ($headers as $index => $header) {
                        $record[$header] = isset($data[$index]) ? trim($data[$index]) : '';
                    }
                    
                    // Validate required fields
                    if (empty($record['employee_id']) || empty($record['basic_salary'])) {
                        throw new Exception("Row {$row}: Employee ID and Basic Salary are required");
                    }
                    
                    // Find employee
                    $employee = $this->db->query("
                        SELECT id FROM employees WHERE employee_id = ? AND status = 'active'
                    ", [$record['employee_id']])->fetch();
                    
                    if (!$employee) {
                        throw new Exception("Row {$row}: Employee not found: {$record['employee_id']}");
                    }
                    
                    // Prepare salary data
                    $basicSalary = floatval($record['basic_salary']);
                    $allowances = floatval($record['allowances'] ?? 0);
                    $overtimePay = floatval($record['overtime_pay'] ?? 0);
                    $deductions = floatval($record['deductions'] ?? 0);
                    $tax = floatval($record['tax'] ?? 0);
                    
                    $grossSalary = $basicSalary + $allowances + $overtimePay;
                    $netSalary = $grossSalary - $deductions - $tax;
                    
                    // Check if record exists
                    $existing = $this->db->query("
                        SELECT id FROM salary_slips 
                        WHERE employee_id = ? AND month = ? AND year = ?
                    ", [$employee['id'], $month, $year])->fetch();
                    
                    if ($existing) {
                        // Update existing record
                        $this->db->query("
                            UPDATE salary_slips SET
                                basic_salary = ?, allowances = ?, overtime_pay = ?,
                                gross_salary = ?, deductions = ?, tax = ?, net_salary = ?,
                                updated_at = CURRENT_TIMESTAMP
                            WHERE id = ?
                        ", [
                            $basicSalary, $allowances, $overtimePay,
                            $grossSalary, $deductions, $tax, $netSalary, $existing['id']
                        ]);
                        $updated++;
                    } else {
                        // Insert new record
                        $this->db->query("
                            INSERT INTO salary_slips (
                                employee_id, month, year, basic_salary, allowances,
                                overtime_pay, gross_salary, deductions, tax, net_salary
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ", [
                            $employee['id'], $month, $year, $basicSalary, $allowances,
                            $overtimePay, $grossSalary, $deductions, $tax, $netSalary
                        ]);
                        $created++;
                    }
                    
                } catch (Exception $e) {
                    $errors++;
                    $errorMessages[] = $e->getMessage();
                    error_log('Bulk upload error: ' . $e->getMessage());
                }
            }
            
            fclose($handle);
        }
        
        return [
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
            'errorMessages' => $errorMessages
        ];
    }
}
