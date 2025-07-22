<?php
/**
 * Attendance Controller
 */
class AttendanceController extends Controller {
    private $attendanceModel;
    private $employeeModel;
    private $zktecoService;
    
    public function __construct() {
        parent::__construct();
        $this->checkRole(['super_admin', 'admin', 'staff']);
        
        $this->attendanceModel = $this->loadModel('Attendance');
        $this->employeeModel = $this->loadModel('Employee');
    }
    
    public function index() {
        $user = Auth::user();
        
        // Default date range - current month
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        $departmentId = $_GET['department_id'] ?? null;
        $employeeId = $_GET['employee_id'] ?? null;
        
        if ($user['role'] === 'staff') {
            // Staff can only view their own attendance
            $employee = $this->employeeModel->findByUserId($user['id']);
            if (!$employee) {
                Session::flash('error', 'Employee record not found.');
                $this->redirect('dashboard');
            }
            
            $attendance = $this->attendanceModel->getByEmployeeAndDateRange(
                $employee['id'], 
                $startDate, 
                $endDate
            );
            
            $stats = $this->attendanceModel->getMonthlyReport(
                $employee['id'], 
                (int)date('n'), 
                (int)date('Y')
            );
            
            $this->loadView('attendance/staff', [
                'pageTitle' => 'My Attendance',
                'attendance' => $attendance,
                'employee' => $employee,
                'stats' => $stats,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
            
        } else {
            // Admin/Supervisor view
            $attendance = $this->attendanceModel->getAllWithDetails(
                $startDate, 
                $endDate, 
                $departmentId
            );
            
            // Filter by employee if specified
            if ($employeeId) {
                $attendance = array_filter($attendance, function($record) use ($employeeId) {
                    return $record['employee_id'] == $employeeId;
                });
            }
            
            // Get filter options
            $departments = $this->loadModel('Department')->getActive();
            $employees = $this->employeeModel->getActiveEmployees();
            
            $todayStats = $this->attendanceModel->getTodayStats();
            $lastSyncTime = $this->attendanceModel->getLastSyncTime();
            
            $this->loadView('attendance/index', [
                'pageTitle' => 'Attendance Management',
                'attendance' => $attendance,
                'departments' => $departments,
                'employees' => $employees,
                'todayStats' => $todayStats,
                'lastSyncTime' => $lastSyncTime,
                'filters' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'department_id' => $departmentId,
                    'employee_id' => $employeeId
                ]
            ]);
        }
    }
    
    public function sync() {
        $this->checkRole(['super_admin', 'admin']);
        
        try {
            $zktecoService = $this->getZktecoService();
            
            if (!$zktecoService->isConfigured()) {
                throw new Exception('ZKTeco device not configured. Please check system settings.');
            }
            
            // Get attendance data from ZKTeco device
            $attendanceData = $zktecoService->getAttendanceData();
            
            if (empty($attendanceData)) {
                Session::flash('warning', 'No new attendance data found on the device.');
                $this->redirect('attendance');
            }
            
            // Sync data to database
            $synced = $this->attendanceModel->syncFromZkTeco($attendanceData);
            
            $this->logActivity("Synced {$synced} attendance records from ZKTeco device");
            
            Session::flash('success', "Successfully synced {$synced} attendance records.");
            
        } catch (Exception $e) {
            Session::flash('error', 'Sync failed: ' . $e->getMessage());
        }
        
        $this->redirect('attendance');
    }
    
    public function manual() {
        $this->checkRole(['super_admin', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleManualEntry();
            return;
        }
        
        $employees = $this->employeeModel->getActiveEmployees();
        $date = $_GET['date'] ?? date('Y-m-d');
        
        // Get existing attendance for the date
        $existingAttendance = [];
        $attendanceRecords = $this->attendanceModel->getAllWithDetails($date, $date);
        
        foreach ($attendanceRecords as $record) {
            $existingAttendance[$record['employee_id']] = $record;
        }
        
        $this->loadView('attendance/manual', [
            'pageTitle' => 'Manual Attendance Entry',
            'employees' => $employees,
            'date' => $date,
            'existingAttendance' => $existingAttendance
        ]);
    }
    
    private function handleManualEntry() {
        $this->validateCSRF();
        
        try {
            $date = $_POST['date'] ?? date('Y-m-d');
            $attendanceData = $_POST['attendance'] ?? [];
            
            if (empty($attendanceData)) {
                throw new Exception('No attendance data provided');
            }
            
            $updated = 0;
            
            foreach ($attendanceData as $employeeId => $data) {
                if (empty($data['status'])) {
                    continue;
                }
                
                $checkIn = !empty($data['check_in']) ? $data['check_in'] : null;
                $checkOut = !empty($data['check_out']) ? $data['check_out'] : null;
                $status = $data['status'];
                $remarks = $data['remarks'] ?? null;
                
                // Validate time format
                if ($checkIn && !$this->isValidTime($checkIn)) {
                    throw new Exception("Invalid check-in time format for employee ID: {$employeeId}");
                }
                
                if ($checkOut && !$this->isValidTime($checkOut)) {
                    throw new Exception("Invalid check-out time format for employee ID: {$employeeId}");
                }
                
                $this->attendanceModel->markAttendance($employeeId, $date, $checkIn, $checkOut, $status);
                
                // Update remarks if provided
                if ($remarks) {
                    $existing = $this->attendanceModel->findByEmployeeAndDate($employeeId, $date);
                    if ($existing) {
                        $this->attendanceModel->update($existing['id'], ['remarks' => $remarks]);
                    }
                }
                
                $updated++;
            }
            
            $this->logActivity("Manual attendance entry for {$date}: {$updated} records updated");
            
            Session::flash('success', "Updated attendance for {$updated} employees.");
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to update attendance: ' . $e->getMessage());
        }
        
        $this->redirect('attendance/manual');
    }
    
    public function checkin() {
        $user = Auth::user();
        $employee = $this->employeeModel->findByUserId($user['id']);
        
        if (!$employee) {
            $this->json(['error' => 'Employee record not found'], 404);
        }
        
        try {
            $existing = $this->attendanceModel->findByEmployeeAndDate($employee['id'], date('Y-m-d'));
            
            if ($existing && $existing['check_in']) {
                $this->json(['error' => 'Already checked in today'], 400);
            }
            
            $this->attendanceModel->checkIn($employee['id']);
            
            $this->logActivity('Checked in');
            
            $this->json(['success' => true, 'message' => 'Checked in successfully', 'time' => date('H:i:s')]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function checkout() {
        $user = Auth::user();
        $employee = $this->employeeModel->findByUserId($user['id']);
        
        if (!$employee) {
            $this->json(['error' => 'Employee record not found'], 404);
        }
        
        try {
            $existing = $this->attendanceModel->findByEmployeeAndDate($employee['id'], date('Y-m-d'));
            
            if (!$existing || !$existing['check_in']) {
                $this->json(['error' => 'Must check in first'], 400);
            }
            
            if ($existing['check_out']) {
                $this->json(['error' => 'Already checked out today'], 400);
            }
            
            $this->attendanceModel->checkOut($employee['id']);
            
            $this->logActivity('Checked out');
            
            $this->json(['success' => true, 'message' => 'Checked out successfully', 'time' => date('H:i:s')]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function export() {
        $this->checkRole(['super_admin', 'admin']);
        
        try {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-t');
            $departmentId = $_GET['department_id'] ?? null;
            
            $attendance = $this->attendanceModel->getAllWithDetails($startDate, $endDate, $departmentId);
            
            $filename = 'attendance_report_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // Write header
            fputcsv($output, [
                'Employee Name', 'Employee ID', 'Department', 'Date', 
                'Check In', 'Check Out', 'Total Hours', 'Overtime Hours', 
                'Status', 'Remarks'
            ]);
            
            // Write data
            foreach ($attendance as $record) {
                fputcsv($output, [
                    $record['employee_name'],
                    $record['employee_id'],
                    $record['department_name'] ?? '',
                    $record['attendance_date'],
                    $record['check_in'] ?? '',
                    $record['check_out'] ?? '',
                    $record['total_hours'] ?? '',
                    $record['overtime_hours'] ?? '0',
                    ucfirst($record['status']),
                    $record['remarks'] ?? ''
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            Session::flash('error', 'Export failed: ' . $e->getMessage());
            $this->redirect('attendance');
        }
    }
    
    public function report() {
        $this->checkRole(['super_admin', 'admin']);
        
        // Get filter parameters
        $reportType = $_GET['report_type'] ?? 'summary';
        $departmentId = $_GET['department_id'] ?? '';
        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
        $endDate = $_GET['end_date'] ?? date('Y-m-d'); // Today
        
        // Initialize data arrays
        $reportData = [];
        $reportStats = [];
        $departments = [];
        
        try {
            // Get departments for filter dropdown
            $departments = $this->db->query("
                SELECT id, name 
                FROM departments 
                WHERE is_active = 1 
                ORDER BY name
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Build the base query based on report type
            if ($reportType === 'summary') {
                $reportData = $this->generateSummaryReport($startDate, $endDate, $departmentId);
                $reportStats = $this->calculateSummaryStats($reportData);
            } elseif ($reportType === 'detailed') {
                $reportData = $this->generateDetailedReport($startDate, $endDate, $departmentId);
                $reportStats = $this->calculateDetailedStats($reportData);
            } elseif ($reportType === 'monthly') {
                $reportData = $this->generateMonthlyReport($startDate, $endDate, $departmentId);
                $reportStats = $this->calculateMonthlyStats($reportData);
            } elseif ($reportType === 'department') {
                $reportData = $this->generateDepartmentReport($startDate, $endDate, $departmentId);
                $reportStats = $this->calculateDepartmentStats($reportData);
            }
            
        } catch (Exception $e) {
            Session::flash('error', 'Failed to generate report: ' . $e->getMessage());
        }
        
        $this->loadView('attendance/report', [
            'pageTitle' => 'Attendance Reports',
            'reportType' => $reportType,
            'selectedDepartment' => $departmentId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'departments' => $departments,
            'reportData' => $reportData,
            'reportStats' => $reportStats
        ]);
    }
    
    private function generateSummaryReport($startDate, $endDate, $departmentId) {
        $departmentFilter = $departmentId ? "AND e.department_id = :department_id" : "";
        
        $sql = "
            SELECT 
                e.id as employee_id,
                CONCAT(u.name) as employee_name,
                e.employee_id,
                d.name as department_name,
                COUNT(CASE WHEN a.status = 'present' OR a.status = 'late' THEN 1 END) as present_days,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_days,
                COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late_days,
                COALESCE(SUM(a.total_hours), 0) as total_hours,
                COUNT(a.id) as total_days,
                CASE 
                    WHEN COUNT(a.id) > 0 THEN 
                        (COUNT(CASE WHEN a.status = 'present' OR a.status = 'late' THEN 1 END) * 100.0 / COUNT(a.id))
                    ELSE 0 
                END as attendance_percentage
            FROM employees e
            JOIN users u ON e.user_id = u.id
            LEFT JOIN departments d ON e.department_id = d.id
            LEFT JOIN attendance a ON e.id = a.employee_id 
                AND a.attendance_date BETWEEN :start_date AND :end_date
            WHERE e.status = 'active' $departmentFilter
            GROUP BY e.id, u.name, e.employee_id, d.name
            ORDER BY u.name
        ";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($departmentId) {
            $params['department_id'] = $departmentId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function generateDetailedReport($startDate, $endDate, $departmentId) {
        $departmentFilter = $departmentId ? "AND e.department_id = :department_id" : "";
        
        $sql = "
            SELECT 
                CONCAT(u.name) as employee_name,
                e.employee_id,
                a.attendance_date,
                a.check_in,
                a.check_out,
                a.total_hours,
                a.status,
                a.remarks
            FROM attendance a
            JOIN employees e ON a.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            WHERE a.attendance_date BETWEEN :start_date AND :end_date
            AND e.status = 'active' $departmentFilter
            ORDER BY a.attendance_date DESC, u.name
        ";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($departmentId) {
            $params['department_id'] = $departmentId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function generateMonthlyReport($startDate, $endDate, $departmentId) {
        // Similar to summary but grouped by month
        return $this->generateSummaryReport($startDate, $endDate, $departmentId);
    }
    
    private function generateDepartmentReport($startDate, $endDate, $departmentId) {
        $departmentFilter = $departmentId ? "AND d.id = :department_id" : "";
        
        $sql = "
            SELECT 
                d.name as department_name,
                COUNT(DISTINCT e.id) as total_employees,
                COUNT(CASE WHEN a.status = 'present' OR a.status = 'late' THEN 1 END) as present_count,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent_count,
                COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late_count,
                COALESCE(AVG(a.total_hours), 0) as avg_hours_per_day,
                CASE 
                    WHEN COUNT(a.id) > 0 THEN 
                        (COUNT(CASE WHEN a.status = 'present' OR a.status = 'late' THEN 1 END) * 100.0 / COUNT(a.id))
                    ELSE 0 
                END as department_attendance_percentage
            FROM departments d
            LEFT JOIN employees e ON d.id = e.department_id AND e.status = 'active'
            LEFT JOIN attendance a ON e.id = a.employee_id 
                AND a.attendance_date BETWEEN :start_date AND :end_date
            WHERE d.is_active = 1 $departmentFilter
            GROUP BY d.id, d.name
            ORDER BY d.name
        ";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($departmentId) {
            $params['department_id'] = $departmentId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function calculateSummaryStats($reportData) {
        if (empty($reportData)) {
            return [
                'total_employees' => 0,
                'avg_attendance' => 0,
                'total_hours' => 0,
                'late_count' => 0
            ];
        }
        
        $totalEmployees = count($reportData);
        $totalHours = array_sum(array_column($reportData, 'total_hours'));
        $totalLate = array_sum(array_column($reportData, 'late_days'));
        $avgAttendance = array_sum(array_column($reportData, 'attendance_percentage')) / $totalEmployees;
        
        return [
            'total_employees' => $totalEmployees,
            'avg_attendance' => $avgAttendance,
            'total_hours' => $totalHours,
            'late_count' => $totalLate
        ];
    }
    
    private function calculateDetailedStats($reportData) {
        if (empty($reportData)) {
            return [
                'total_employees' => 0,
                'avg_attendance' => 0,
                'total_hours' => 0,
                'late_count' => 0
            ];
        }
        
        $totalRecords = count($reportData);
        $presentRecords = count(array_filter($reportData, fn($r) => in_array($r['status'], ['present', 'late'])));
        $lateRecords = count(array_filter($reportData, fn($r) => $r['status'] === 'late'));
        $totalHours = array_sum(array_filter(array_column($reportData, 'total_hours')));
        
        return [
            'total_employees' => count(array_unique(array_column($reportData, 'employee_name'))),
            'avg_attendance' => $totalRecords > 0 ? ($presentRecords * 100 / $totalRecords) : 0,
            'total_hours' => $totalHours,
            'late_count' => $lateRecords
        ];
    }
    
    private function calculateMonthlyStats($reportData) {
        return $this->calculateSummaryStats($reportData);
    }
    
    private function calculateDepartmentStats($reportData) {
        if (empty($reportData)) {
            return [
                'total_employees' => 0,
                'avg_attendance' => 0,
                'total_hours' => 0,
                'late_count' => 0
            ];
        }
        
        $totalEmployees = array_sum(array_column($reportData, 'total_employees'));
        $totalLate = array_sum(array_column($reportData, 'late_count'));
        $avgAttendance = array_sum(array_column($reportData, 'department_attendance_percentage')) / count($reportData);
        $totalHours = array_sum(array_column($reportData, 'avg_hours_per_day')) * $totalEmployees;
        
        return [
            'total_employees' => $totalEmployees,
            'avg_attendance' => $avgAttendance,
            'total_hours' => $totalHours,
            'late_count' => $totalLate
        ];
    }
    
    private function getZktecoService() {
        if (!$this->zktecoService) {
            require_once APP_PATH . '/services/ZktecoService.php';
            $this->zktecoService = new ZktecoService();
        }
        return $this->zktecoService;
    }
    
    private function isValidTime($time) {
        return (bool) preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
    }
    
    private function logActivity($action) {
        try {
            $this->db = Database::getInstance();
            $this->db->query(
                "INSERT INTO activity_logs (user_id, action, model, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
                [
                    Auth::id(),
                    $action,
                    'Attendance',
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]
            );
        } catch (Exception $e) {
            error_log('Activity log error: ' . $e->getMessage());
        }
    }
}
