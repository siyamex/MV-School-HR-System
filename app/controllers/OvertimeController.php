<?php
/**
 * Overtime Controller
 */
class OvertimeController extends Controller {
    private $overtimeModel;
    private $employeeModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->overtimeModel = new OvertimeRequest();
        $this->employeeModel = new Employee();
        $this->userModel = new User();
        
        // Check if user is logged in
        if (!Auth::check()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
    
    public function index() {
        $user = Auth::user();
        $userRole = $user['role'];
        $userId = $user['id'];
        
        // Get overtime requests based on role
        if (in_array($userRole, ['super_admin', 'admin'])) {
            // Super admin and admin can see all overtime requests
            $overtimes = $this->overtimeModel->getAllWithDetails();
        } else if ($userRole === 'supervisor') {
            // Supervisors can see overtime requests from their department
            $employee = $this->employeeModel->findByUserId($userId);
            if ($employee && $employee['department_id']) {
                $overtimes = $this->overtimeModel->getByDepartmentAndStatus($employee['department_id']);
            } else {
                $overtimes = [];
            }
        } else {
            // Staff can only see their own overtime requests
            $employee = $this->employeeModel->findByUserId($userId);
            if ($employee) {
                $overtimes = $this->overtimeModel->getByEmployee($employee['id']);
            } else {
                $overtimes = [];
            }
        }
        
        $data = [
            'overtimes' => $overtimes,
            'pageTitle' => 'Overtime Management',
            'userRole' => $userRole
        ];
        
        $this->loadView('overtimes/index', $data);
    }
    
    public function create() {
        $user = Auth::user();
        $userRole = $user['role'];
        
        // Only staff, admin, and super_admin can create overtime requests
        if (!in_array($userRole, ['super_admin', 'admin', 'staff'])) {
            $_SESSION['error'] = 'You do not have permission to create overtime requests.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        $data = [
            'pageTitle' => 'New Overtime Request',
            'userRole' => $userRole
        ];
        
        $this->loadView('overtimes/create', $data);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Validate CSRF token
        if (!CSRF::validate()) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            header('Location: ' . APP_URL . '/overtime/create');
            exit;
        }
        
        $user = Auth::user();
        $userRole = $user['role'];
        $userId = $user['id'];
        
        // Only staff, admin, and super_admin can create overtime requests
        if (!in_array($userRole, ['super_admin', 'admin', 'staff'])) {
            $_SESSION['error'] = 'You do not have permission to create overtime requests.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Get employee record
        $employee = $this->employeeModel->findByUserId($userId);
        if (!$employee) {
            $_SESSION['error'] = 'Employee record not found.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Validate input data
        $overtimeDate = trim($_POST['overtime_date'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');
        $reason = trim($_POST['reason'] ?? '');
        
        $errors = [];
        
        if (empty($overtimeDate)) {
            $errors[] = 'Overtime date is required.';
        } else if (strtotime($overtimeDate) < strtotime(date('Y-m-d'))) {
            $errors[] = 'Overtime date cannot be in the past.';
        }
        
        if (empty($startTime)) {
            $errors[] = 'Start time is required.';
        }
        
        if (empty($endTime)) {
            $errors[] = 'End time is required.';
        }
        
        if (empty($reason)) {
            $errors[] = 'Reason is required.';
        }
        
        if (!empty($startTime) && !empty($endTime)) {
            $start = strtotime($startTime);
            $end = strtotime($endTime);
            
            if ($end <= $start) {
                $errors[] = 'End time must be after start time.';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            header('Location: ' . APP_URL . '/overtime/create');
            exit;
        }
        
        // Calculate hours requested
        $hoursRequested = (strtotime($endTime) - strtotime($startTime)) / 3600;
        
        // Create overtime request
        $data = [
            'employee_id' => $employee['id'],
            'overtime_date' => $overtimeDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'hours_requested' => $hoursRequested,
            'reason' => $reason,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $overtimeId = $this->overtimeModel->create($data);
            
            if ($overtimeId) {
                $_SESSION['success'] = 'Overtime request submitted successfully.';
                
                // Log activity
                $this->logActivity(
                    'create',
                    'overtime_requests',
                    $overtimeId,
                    ['action' => 'Overtime request created', 'overtime_date' => $overtimeDate]
                );
            } else {
                $_SESSION['error'] = 'Failed to submit overtime request.';
            }
            
        } catch (Exception $e) {
            error_log('Overtime creation error: ' . $e->getMessage());
            $_SESSION['error'] = 'An error occurred while submitting the overtime request.';
        }
        
        header('Location: ' . APP_URL . '/overtime');
        exit;
    }
    
    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Validate CSRF token
        if (!CSRF::validate()) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        $user = Auth::user();
        $userRole = $user['role'];
        $userId = $user['id'];
        
        // Only supervisors, admins, and super_admins can approve overtime requests
        if (!in_array($userRole, ['super_admin', 'admin', 'supervisor'])) {
            $_SESSION['error'] = 'You do not have permission to approve overtime requests.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Get overtime request
        $overtime = $this->overtimeModel->findWithDetails($id);
        if (!$overtime) {
            $_SESSION['error'] = 'Overtime request not found.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Check if already processed
        if ($overtime['status'] !== 'pending') {
            $_SESSION['error'] = 'This overtime request has already been processed.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // For supervisors, check if the employee is in their department
        if ($userRole === 'supervisor') {
            $supervisorEmployee = $this->employeeModel->findByUserId($userId);
            if (!$supervisorEmployee || $supervisorEmployee['department_id'] != $overtime['department_id']) {
                $_SESSION['error'] = 'You can only approve overtime requests from your department.';
                header('Location: ' . APP_URL . '/overtime');
                exit;
            }
        }
        
        // Approve overtime request
        $updateData = [
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $updated = $this->overtimeModel->update($id, $updateData);
            
            if ($updated) {
                $_SESSION['success'] = 'Overtime request approved successfully.';
                
                // Log activity
                $this->logActivity(
                    'approve',
                    'overtime_requests',
                    $id,
                    ['action' => 'Overtime request approved', 'employee' => $overtime['employee_name']]
                );
                
                // Send notification email
                $this->sendNotificationEmail($overtime, 'approved');
                
            } else {
                $_SESSION['error'] = 'Failed to approve overtime request.';
            }
            
        } catch (Exception $e) {
            error_log('Overtime approval error: ' . $e->getMessage());
            $_SESSION['error'] = 'An error occurred while approving the overtime request.';
        }
        
        header('Location: ' . APP_URL . '/overtime');
        exit;
    }
    
    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Validate CSRF token
        if (!CSRF::validate()) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        $user = Auth::user();
        $userRole = $user['role'];
        $userId = $user['id'];
        
        // Only supervisors, admins, and super_admins can reject overtime requests
        if (!in_array($userRole, ['super_admin', 'admin', 'supervisor'])) {
            $_SESSION['error'] = 'You do not have permission to reject overtime requests.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Get overtime request
        $overtime = $this->overtimeModel->findWithDetails($id);
        if (!$overtime) {
            $_SESSION['error'] = 'Overtime request not found.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // Check if already processed
        if ($overtime['status'] !== 'pending') {
            $_SESSION['error'] = 'This overtime request has already been processed.';
            header('Location: ' . APP_URL . '/overtime');
            exit;
        }
        
        // For supervisors, check if the employee is in their department
        if ($userRole === 'supervisor') {
            $supervisorEmployee = $this->employeeModel->findByUserId($userId);
            if (!$supervisorEmployee || $supervisorEmployee['department_id'] != $overtime['department_id']) {
                $_SESSION['error'] = 'You can only reject overtime requests from your department.';
                header('Location: ' . APP_URL . '/overtime');
                exit;
            }
        }
        
        $rejectionReason = trim($_POST['reason'] ?? '');
        
        // Reject overtime request
        $updateData = [
            'status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $rejectionReason,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            $updated = $this->overtimeModel->update($id, $updateData);
            
            if ($updated) {
                $_SESSION['success'] = 'Overtime request rejected successfully.';
                
                // Log activity
                $this->logActivity(
                    'reject',
                    'overtime_requests',
                    $id,
                    ['action' => 'Overtime request rejected', 'employee' => $overtime['employee_name'], 'reason' => $rejectionReason]
                );
                
                // Send notification email
                $this->sendNotificationEmail($overtime, 'rejected', $rejectionReason);
                
            } else {
                $_SESSION['error'] = 'Failed to reject overtime request.';
            }
            
        } catch (Exception $e) {
            error_log('Overtime rejection error: ' . $e->getMessage());
            $_SESSION['error'] = 'An error occurred while rejecting the overtime request.';
        }
        
        header('Location: ' . APP_URL . '/overtime');
        exit;
    }
    
    private function sendNotificationEmail($overtime, $action, $reason = '') {
        try {
            $emailService = $this->loadEmailService();
            
            if ($emailService && $emailService->isConfigured()) {
                $templateName = $action === 'approved' ? 'overtime_approved' : 'overtime_rejected';
                $template = $this->getEmailTemplate($templateName);
                
                if ($template) {
                    $user = Auth::user();
                    $approver = $this->userModel->find($user['id']);
                    
                    $placeholders = [
                        '{employee_name}' => $overtime['employee_name'],
                        '{overtime_date}' => date('M d, Y', strtotime($overtime['overtime_date'])),
                        '{approver_name}' => $approver['name'] ?? 'System',
                        '{rejection_reason}' => $reason
                    ];
                    
                    $subject = str_replace(array_keys($placeholders), array_values($placeholders), $template['subject']);
                    $body = str_replace(array_keys($placeholders), array_values($placeholders), $template['body']);
                    
                    $emailService->send(
                        $overtime['employee_email'],
                        $overtime['employee_name'],
                        $subject,
                        $body
                    );
                }
            }
            
        } catch (Exception $e) {
            error_log('Email notification error: ' . $e->getMessage());
        }
    }
    
    private function loadEmailService() {
        require_once APP_PATH . '/services/EmailService.php';
        return new EmailService();
    }
    
    private function getEmailTemplate($name) {
        $db = Database::getInstance();
        $template = $db->fetch(
            "SELECT * FROM email_templates WHERE name = ? AND is_active = 1",
            [$name]
        );
        
        return $template;
    }
    
    private function logActivity($action, $model, $modelId, $changes) {
        try {
            $user = Auth::user();
            $db = Database::getInstance();
            $db->query(
                "INSERT INTO activity_logs (user_id, action, model, model_id, changes, ip_address, user_agent, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $user['id'],
                    $action,
                    $model,
                    $modelId,
                    json_encode($changes),
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null,
                    date('Y-m-d H:i:s')
                ]
            );
        } catch (Exception $e) {
            error_log('Activity logging error: ' . $e->getMessage());
        }
    }
}
