<?php
/**
 * Leave Controller
 */
class LeaveController extends Controller {
    private $leaveModel;
    private $leaveTypeModel;
    private $employeeModel;
    private $emailService;
    
    public function __construct() {
        parent::__construct();
        
        $this->leaveModel = $this->loadModel('LeaveRequest');
        $this->leaveTypeModel = $this->loadModel('LeaveType');
        $this->employeeModel = $this->loadModel('Employee');
        $this->emailService = $this->loadEmailService();
    }
    
    public function index() {
        $user = Auth::user();
        
        switch ($user['role']) {
            case 'super_admin':
            case 'admin':
                $leaves = $this->leaveModel->getAllWithDetails();
                break;
            case 'supervisor':
                $employee = $this->employeeModel->findByUserId($user['id']);
                $leaves = $this->leaveModel->getByDepartmentAndStatus($employee['department_id']);
                break;
            case 'staff':
                $employee = $this->employeeModel->findByUserId($user['id']);
                $leaves = $this->leaveModel->getByEmployee($employee['id']);
                break;
            default:
                $leaves = [];
        }
        
        $this->loadView('leaves/index', [
            'pageTitle' => 'Leave Management',
            'leaves' => $leaves,
            'userRole' => $user['role']
        ]);
    }
    
    public function create() {
        $leaveTypes = $this->leaveTypeModel->getActive();
        $user = Auth::user();
        $employee = $this->employeeModel->findByUserId($user['id']);
        
        if (!$employee) {
            Session::flash('error', 'Employee record not found.');
            $this->redirect('leaves');
        }
        
        // Get leave balances
        $leaveBalances = [];
        foreach ($leaveTypes as $leaveType) {
            $leaveBalances[$leaveType['id']] = $this->leaveModel->getLeaveBalance(
                $employee['id'], 
                $leaveType['id'], 
                date('Y')
            );
        }
        
        $this->loadView('leaves/create', [
            'pageTitle' => 'Apply for Leave',
            'leaveTypes' => $leaveTypes,
            'employee' => $employee,
            'leaveBalances' => $leaveBalances
        ]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('leaves');
        }
        
        $this->validateCSRF();
        
        try {
            $user = Auth::user();
            $employee = $this->employeeModel->findByUserId($user['id']);
            
            if (!$employee) {
                throw new Exception('Employee record not found');
            }
            
            // Validate input
            $errors = $this->validateLeaveData($_POST, $employee['id']);
            if (!empty($errors)) {
                Session::flash('error', 'Please fix the following errors: ' . implode(', ', $errors));
                $this->redirect('leaves/create');
            }
            
            // Calculate days
            $startDate = new DateTime($_POST['start_date']);
            $endDate = new DateTime($_POST['end_date']);
            $days = $startDate->diff($endDate)->days + 1;
            
            // Remove weekends if needed (basic implementation)
            $actualDays = $this->calculateWorkingDays($_POST['start_date'], $_POST['end_date']);
            
            $leaveData = [
                'employee_id' => $employee['id'],
                'leave_type_id' => $_POST['leave_type_id'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'days_requested' => $actualDays,
                'reason' => $_POST['reason'],
                'status' => 'pending'
            ];
            
            $leaveId = $this->leaveModel->create($leaveData);
            
            $this->logActivity("Applied for leave: {$_POST['start_date']} to {$_POST['end_date']}");
            
            // Send notification email to supervisors/admins
            $this->sendLeaveNotification($leaveId, 'applied');
            
            Session::flash('success', 'Leave application submitted successfully.');
            $this->redirect('leaves');
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to submit leave application: ' . $e->getMessage());
            $this->redirect('leaves/create');
        }
    }
    
    public function approve($id) {
        $this->checkRole(['super_admin', 'admin', 'supervisor']);
        
        try {
            $leave = $this->leaveModel->findWithDetails($id);
            if (!$leave) {
                throw new Exception('Leave request not found');
            }
            
            // Check if user can approve this leave
            if (!$this->canApproveLeave($leave)) {
                throw new Exception('You do not have permission to approve this leave');
            }
            
            $this->leaveModel->approve($id, Auth::id());
            
            $this->logActivity("Approved leave request for: {$leave['employee_name']}");
            
            // Send approval email
            $this->sendLeaveNotification($id, 'approved');
            
            Session::flash('success', 'Leave request approved successfully.');
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to approve leave: ' . $e->getMessage());
        }
        
        $this->redirect('leaves');
    }
    
    public function reject($id) {
        $this->checkRole(['super_admin', 'admin', 'supervisor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            try {
                $leave = $this->leaveModel->findWithDetails($id);
                if (!$leave) {
                    throw new Exception('Leave request not found');
                }
                
                // Check if user can reject this leave
                if (!$this->canApproveLeave($leave)) {
                    throw new Exception('You do not have permission to reject this leave');
                }
                
                $reason = $_POST['rejection_reason'] ?? 'No reason provided';
                
                $this->leaveModel->reject($id, Auth::id(), $reason);
                
                $this->logActivity("Rejected leave request for: {$leave['employee_name']}");
                
                // Send rejection email
                $this->sendLeaveNotification($id, 'rejected');
                
                Session::flash('success', 'Leave request rejected successfully.');
                
            } catch (Exception $e) {
                Session::flash('error', 'Failed to reject leave: ' . $e->getMessage());
            }
            
            $this->redirect('leaves');
        }
        
        // Show rejection form
        $leave = $this->leaveModel->findWithDetails($id);
        if (!$leave) {
            Session::flash('error', 'Leave request not found.');
            $this->redirect('leaves');
        }
        
        $this->loadView('leaves/reject', [
            'pageTitle' => 'Reject Leave Request',
            'leave' => $leave
        ]);
    }
    
    public function export() {
        $this->checkRole(['super_admin', 'admin']);
        
        try {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-t');
            $status = $_GET['status'] ?? null;
            
            $leaves = $this->getLeaveReportData($startDate, $endDate, $status);
            
            $filename = 'leave_report_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // Write header
            fputcsv($output, [
                'Employee Name', 'Employee ID', 'Department', 'Leave Type', 
                'Start Date', 'End Date', 'Days', 'Status', 'Applied Date', 
                'Approved By', 'Approved Date'
            ]);
            
            // Write data
            foreach ($leaves as $leave) {
                fputcsv($output, [
                    $leave['employee_name'],
                    $leave['employee_id'],
                    $leave['department_name'] ?? '',
                    $leave['leave_type_name'],
                    $leave['start_date'],
                    $leave['end_date'],
                    $leave['days_requested'],
                    ucfirst($leave['status']),
                    date('Y-m-d', strtotime($leave['created_at'])),
                    $leave['approver_name'] ?? '',
                    $leave['approved_at'] ? date('Y-m-d', strtotime($leave['approved_at'])) : ''
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            Session::flash('error', 'Export failed: ' . $e->getMessage());
            $this->redirect('leaves');
        }
    }
    
    private function validateLeaveData($data, $employeeId) {
        $errors = [];
        
        // Required fields
        if (empty($data['leave_type_id'])) {
            $errors[] = 'Leave type is required';
        }
        
        if (empty($data['start_date'])) {
            $errors[] = 'Start date is required';
        }
        
        if (empty($data['end_date'])) {
            $errors[] = 'End date is required';
        }
        
        if (empty($data['reason'])) {
            $errors[] = 'Reason is required';
        }
        
        // Date validation
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = strtotime($data['start_date']);
            $endDate = strtotime($data['end_date']);
            
            if ($startDate === false || $endDate === false) {
                $errors[] = 'Invalid date format';
            } elseif ($startDate > $endDate) {
                $errors[] = 'End date must be after start date';
            } elseif ($startDate < strtotime('today')) {
                $errors[] = 'Start date cannot be in the past';
            } else {
                // Check for overlapping leave requests
                if ($this->leaveModel->checkOverlapping($employeeId, $data['start_date'], $data['end_date'])) {
                    $errors[] = 'You already have approved leave during this period';
                }
                
                // Check leave balance
                if (!empty($data['leave_type_id'])) {
                    $days = $this->calculateWorkingDays($data['start_date'], $data['end_date']);
                    $balance = $this->leaveModel->getLeaveBalance($employeeId, $data['leave_type_id']);
                    
                    if ($balance['remaining'] < $days) {
                        $errors[] = "Insufficient leave balance. Available: {$balance['remaining']} days, Requested: {$days} days";
                    }
                }
            }
        }
        
        return $errors;
    }
    
    private function calculateWorkingDays($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $workingDays = 0;
        
        while ($start <= $end) {
            $dayOfWeek = $start->format('N');
            if ($dayOfWeek <= 5) { // Monday to Friday
                $workingDays++;
            }
            $start->add(new DateInterval('P1D'));
        }
        
        return $workingDays;
    }
    
    private function canApproveLeave($leave) {
        $user = Auth::user();
        
        // Super admin and admin can approve any leave
        if (in_array($user['role'], ['super_admin', 'admin'])) {
            return true;
        }
        
        // Supervisor can approve leaves in their department
        if ($user['role'] === 'supervisor') {
            $employee = $this->employeeModel->findByUserId($user['id']);
            return $employee && $employee['department_id'] == $leave['department_id'];
        }
        
        return false;
    }
    
    private function sendLeaveNotification($leaveId, $type) {
        try {
            $leave = $this->leaveModel->findWithDetails($leaveId);
            if (!$leave) {
                return;
            }
            
            $templateName = $type === 'applied' ? 'leave_application' : "leave_{$type}";
            $template = $this->getEmailTemplate($templateName);
            
            if (!$template) {
                return; // No template found
            }
            
            $placeholders = [
                'employee_name' => $leave['employee_name'],
                'leave_type' => $leave['leave_type_name'],
                'date_from' => date('d M Y', strtotime($leave['start_date'])),
                'date_to' => date('d M Y', strtotime($leave['end_date'])),
                'approver_name' => $leave['approver_name'] ?? Auth::user()['name'],
                'rejection_reason' => $leave['rejection_reason'] ?? ''
            ];
            
            $subject = $this->replacePlaceholders($template['subject'], $placeholders);
            $body = $this->replacePlaceholders($template['body'], $placeholders);
            
            if ($type === 'applied') {
                // Send to supervisors/admins
                $recipients = $this->getLeaveApprovers($leave['department_id']);
            } else {
                // Send to employee
                $recipients = [$leave['employee_email']];
            }
            
            foreach ($recipients as $email) {
                $this->emailService->send($email, $subject, $body);
            }
            
        } catch (Exception $e) {
            error_log('Email notification error: ' . $e->getMessage());
        }
    }
    
    private function getLeaveReportData($startDate, $endDate, $status = null) {
        $sql = "SELECT lr.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name,
                       lt.name as leave_type_name,
                       approver.name as approver_name
                FROM leave_requests lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users approver ON lr.approved_by = approver.id
                WHERE lr.start_date >= ? AND lr.end_date <= ?";
        
        $params = [$startDate, $endDate];
        
        if ($status) {
            $sql .= " AND lr.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        $db = Database::getInstance();
        return $db->fetchAll($sql, $params);
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
    
    private function replacePlaceholders($text, $placeholders) {
        foreach ($placeholders as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }
    
    private function getLeaveApprovers($departmentId) {
        $approvers = [];
        
        // Get department head
        $department = $this->departmentModel->findWithHead($departmentId);
        if ($department && $department['head_id']) {
            $head = $this->userModel->find($department['head_id']);
            if ($head) {
                $approvers[] = $head['email'];
            }
        }
        
        // Get all admins
        $admins = $this->userModel->getAdmins();
        foreach ($admins as $admin) {
            $approvers[] = $admin['email'];
        }
        
        return array_unique($approvers);
    }
    
    private function logActivity($action) {
        try {
            $this->db = Database::getInstance();
            $this->db->query(
                "INSERT INTO activity_logs (user_id, action, model, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [
                    Auth::id(),
                    $action,
                    'LeaveRequest',
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]
            );
        } catch (Exception $e) {
            error_log('Activity log error: ' . $e->getMessage());
        }
    }
}
