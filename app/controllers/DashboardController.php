<?php
/**
 * Dashboard Controller
 */
class DashboardController extends Controller {
    private $employeeModel;
    private $leaveModel;
    private $overtimeModel;
    private $attendanceModel;
    
    public function __construct() {
        parent::__construct();
        $this->employeeModel = $this->loadModel('Employee');
        $this->leaveModel = $this->loadModel('LeaveRequest');
        $this->overtimeModel = $this->loadModel('OvertimeRequest');
        $this->attendanceModel = $this->loadModel('Attendance');
    }
    
    public function index() {
        $user = Auth::user();
        $role = $user['role'];
        
        switch ($role) {
            case 'super_admin':
                $this->superAdminDashboard();
                break;
            case 'admin':
                $this->adminDashboard();
                break;
            case 'supervisor':
                $this->supervisorDashboard();
                break;
            case 'staff':
                $this->staffDashboard();
                break;
            default:
                header('Location: ' . APP_URL . '/login');
                exit;
        }
    }
    
    private function superAdminDashboard() {
        try {
            error_log("Dashboard: Starting superAdminDashboard");
            
            // Simplified stats with safe defaults
            $stats = [
                'total_staff' => 0,
                'pending_leaves' => 0,
                'pending_overtime' => 0,
                'departments' => 0
            ];
            
            // Try to get real stats, but use defaults if there are errors
            try {
                $stats['total_staff'] = $this->employeeModel->count(['status' => 'active']);
            } catch (Exception $e) {
                error_log("Dashboard: Error counting employees: " . $e->getMessage());
            }
            
            try {
                $stats['pending_leaves'] = $this->leaveModel->count(['status' => 'pending']);
            } catch (Exception $e) {
                error_log("Dashboard: Error counting leaves: " . $e->getMessage());
            }
            
            try {
                $stats['pending_overtime'] = $this->overtimeModel->count(['status' => 'pending']);
            } catch (Exception $e) {
                error_log("Dashboard: Error counting overtime: " . $e->getMessage());
            }
            
            try {
                $stats['departments'] = $this->loadModel('Department')->count(['is_active' => 1]);
            } catch (Exception $e) {
                error_log("Dashboard: Error counting departments: " . $e->getMessage());
            }
            
            // Safe defaults for other data
            $recentLeaves = [];
            $recentOvertime = [];
            $attendanceToday = ['present' => 0, 'absent' => 0];
            $lastAttendanceSync = null;
            
            try {
                $recentLeaves = $this->leaveModel->getByStatus('pending');
            } catch (Exception $e) {
                error_log("Dashboard: Error getting recent leaves: " . $e->getMessage());
            }
            
            try {
                $recentOvertime = $this->overtimeModel->getByStatus('pending');
            } catch (Exception $e) {
                error_log("Dashboard: Error getting recent overtime: " . $e->getMessage());
            }
            
            try {
                $attendanceToday = $this->attendanceModel->getTodayStats();
                $lastAttendanceSync = $this->attendanceModel->getLastSyncTime();
            } catch (Exception $e) {
                error_log("Dashboard: Error getting attendance data: " . $e->getMessage());
            }
            
            error_log("Dashboard: Final stats: " . print_r($stats, true));
            
            $this->loadView('dashboard/super-admin', [
                'pageTitle' => 'Super Admin Dashboard',
                'stats' => $stats,
                'recentLeaves' => array_slice($recentLeaves, 0, 5),
                'recentOvertime' => array_slice($recentOvertime, 0, 5),
                'attendanceToday' => $attendanceToday,
                'lastAttendanceSync' => $lastAttendanceSync
            ]);
            
        } catch (Exception $e) {
            error_log("Dashboard: Fatal error in superAdminDashboard - " . $e->getMessage());
            // Show a basic dashboard with error message
            $this->loadView('dashboard/super-admin', [
                'pageTitle' => 'Super Admin Dashboard',
                'stats' => ['total_staff' => 0, 'pending_leaves' => 0, 'pending_overtime' => 0, 'departments' => 0],
                'recentLeaves' => [],
                'recentOvertime' => [],
                'attendanceToday' => ['present' => 0, 'absent' => 0],
                'lastAttendanceSync' => null,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function adminDashboard() {
        $stats = [
            'total_staff' => $this->employeeModel->count(['status' => 'active']),
            'pending_leaves' => $this->leaveModel->count(['status' => 'pending']),
            'pending_overtime' => $this->overtimeModel->count(['status' => 'pending']),
            'staff_on_leave_today' => $this->leaveModel->getStaffOnLeaveToday()
        ];
        
        $recentLeaves = $this->leaveModel->getByStatus('pending');
        $recentOvertime = $this->overtimeModel->getByStatus('pending');
        $attendanceToday = $this->attendanceModel->getTodayStats();
        
        $this->loadView('dashboard/admin', [
            'pageTitle' => 'HR Admin Dashboard',
            'stats' => $stats,
            'recentLeaves' => array_slice($recentLeaves, 0, 5),
            'recentOvertime' => array_slice($recentOvertime, 0, 5),
            'attendanceToday' => $attendanceToday
        ]);
    }
    
    private function supervisorDashboard() {
        $user = Auth::user();
        $employee = $this->employeeModel->findByUserId($user['id']);
        
        if (!$employee) {
            Session::flash('error', 'Employee record not found.');
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        $departmentId = $employee['department_id'];
        
        $stats = [
            'department_staff' => $this->employeeModel->count([
                'department_id' => $departmentId, 
                'status' => 'active'
            ]),
            'pending_leaves' => count($this->leaveModel->getByDepartmentAndStatus($departmentId, 'pending')),
            'pending_overtime' => count($this->overtimeModel->getByDepartmentAndStatus($departmentId, 'pending')),
            'staff_on_leave_today' => $this->leaveModel->getStaffOnLeaveToday($departmentId)
        ];
        
        $recentLeaves = $this->leaveModel->getByDepartmentAndStatus($departmentId, 'pending');
        $recentOvertime = $this->overtimeModel->getByDepartmentAndStatus($departmentId, 'pending');
        
        $this->loadView('dashboard/supervisor', [
            'pageTitle' => 'Supervisor Dashboard',
            'stats' => $stats,
            'recentLeaves' => array_slice($recentLeaves, 0, 5),
            'recentOvertime' => array_slice($recentOvertime, 0, 5),
            'employee' => $employee
        ]);
    }
    
    private function staffDashboard() {
        $user = Auth::user();
        $employee = $this->employeeModel->findByUserId($user['id']);
        
        if (!$employee) {
            Session::flash('error', 'Employee record not found.');
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        $currentMonth = date('Y-m');
        $currentYear = date('Y');
        
        // Get personal stats
        $myLeaves = $this->leaveModel->getByEmployee($employee['id']);
        $myOvertime = $this->overtimeModel->getByEmployee($employee['id']);
        $myAttendance = $this->attendanceModel->getByEmployeeAndMonth($employee['id'], $currentMonth);
        
        // Get salary slip for current month
        $salaryModel = $this->loadModel('SalarySlip');
        $currentSalarySlip = $salaryModel->findByEmployeeAndMonth(
            $employee['id'], 
            (int)date('n'), 
            $currentYear
        );
        
        // Get leave balances
        $leaveTypes = $this->loadModel('LeaveType')->getActive();
        $leaveBalances = [];
        foreach ($leaveTypes as $leaveType) {
            $leaveBalances[$leaveType['id']] = $this->leaveModel->getLeaveBalance(
                $employee['id'], 
                $leaveType['id'], 
                $currentYear
            );
        }
        
        $stats = [
            'pending_leaves' => count(array_filter($myLeaves, fn($l) => $l['status'] === 'pending')),
            'approved_leaves_this_year' => count(array_filter($myLeaves, fn($l) => 
                $l['status'] === 'approved' && date('Y', strtotime($l['start_date'])) == $currentYear
            )),
            'attendance_this_month' => count($myAttendance),
            'working_days_this_month' => $this->getWorkingDaysInMonth($currentMonth)
        ];
        
        $this->loadView('dashboard/staff', [
            'pageTitle' => 'My Dashboard',
            'stats' => $stats,
            'employee' => $employee,
            'myLeaves' => array_slice($myLeaves, 0, 5),
            'myOvertime' => array_slice($myOvertime, 0, 5),
            'myAttendance' => $myAttendance,
            'currentSalarySlip' => $currentSalarySlip,
            'leaveBalances' => $leaveBalances,
            'leaveTypes' => $leaveTypes
        ]);
    }
    
    private function getWorkingDaysInMonth($month) {
        $year = (int)date('Y', strtotime($month));
        $monthNum = (int)date('n', strtotime($month));
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
        
        $workingDays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dayOfWeek = date('N', mktime(0, 0, 0, $monthNum, $day, $year));
            if ($dayOfWeek <= 5) { // Monday to Friday
                $workingDays++;
            }
        }
        
        return $workingDays;
    }
    
    // Debug method (remove in production)
    public function debug() {
        echo "<h2>Authentication Debug</h2>";
        echo "<p><strong>Auth::check():</strong> " . (Auth::check() ? 'true' : 'false') . "</p>";
        
        if (Auth::check()) {
            $user = Auth::user();
            echo "<p><strong>User ID:</strong> " . ($user['id'] ?? 'not set') . "</p>";
            echo "<p><strong>User Role:</strong> " . ($user['role'] ?? 'not set') . "</p>";
            echo "<p><strong>User Email:</strong> " . ($user['email'] ?? 'not set') . "</p>";
            echo "<p><strong>User Active:</strong> " . ($user['is_active'] ?? 'not set') . "</p>";
            
            echo "<p><strong>Has super_admin role:</strong> " . (Auth::hasRole(['super_admin']) ? 'true' : 'false') . "</p>";
            echo "<p><strong>Has admin role:</strong> " . (Auth::hasRole(['admin']) ? 'true' : 'false') . "</p>";
        }
        
        echo "<h3>Session Data:</h3>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        
        exit;
    }
}
