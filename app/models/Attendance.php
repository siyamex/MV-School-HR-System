<?php
/**
 * Attendance Model
 */
class Attendance extends Model {
    protected $table = 'attendance';
    
    public function getAllWithDetails($startDate = null, $endDate = null, $departmentId = null) {
        $sql = "SELECT a.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name
                FROM {$this->table} a
                JOIN employees e ON a.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        
        if ($startDate) {
            $sql .= " AND a.attendance_date >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND a.attendance_date <= ?";
            $params[] = $endDate;
        }
        
        if ($departmentId) {
            $sql .= " AND e.department_id = ?";
            $params[] = $departmentId;
        }
        
        $sql .= " ORDER BY a.attendance_date DESC, u.name";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findByEmployeeAndDate($employeeId, $date) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE employee_id = ? AND attendance_date = ?",
            [$employeeId, $date]
        );
    }
    
    public function getByEmployeeAndMonth($employeeId, $month) {
        // Month format: YYYY-MM
        $sql = "SELECT * FROM {$this->table} 
                WHERE employee_id = ? 
                AND attendance_date >= ? 
                AND attendance_date <= ?
                ORDER BY attendance_date";
        
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of month
        
        return $this->db->fetchAll($sql, [$employeeId, $startDate, $endDate]);
    }
    
    public function getByEmployeeAndDateRange($employeeId, $startDate, $endDate) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE employee_id = ? 
                AND attendance_date >= ? 
                AND attendance_date <= ?
                ORDER BY attendance_date";
        
        return $this->db->fetchAll($sql, [$employeeId, $startDate, $endDate]);
    }
    
    public function markAttendance($employeeId, $date, $checkIn = null, $checkOut = null, $status = 'present') {
        $existing = $this->findByEmployeeAndDate($employeeId, $date);
        
        $data = [
            'employee_id' => $employeeId,
            'attendance_date' => $date,
            'status' => $status
        ];
        
        if ($checkIn) {
            $data['check_in'] = $checkIn;
        }
        
        if ($checkOut) {
            $data['check_out'] = $checkOut;
            
            // Calculate total hours if both check_in and check_out exist
            if ($checkIn || ($existing && $existing['check_in'])) {
                $checkInTime = $checkIn ?: $existing['check_in'];
                $totalMinutes = $this->calculateTotalMinutes($checkInTime, $checkOut);
                $data['total_hours'] = round($totalMinutes / 60, 2);
            }
        }
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->create($data);
        }
    }
    
    public function checkIn($employeeId, $time = null) {
        $date = date('Y-m-d');
        $time = $time ?: date('H:i:s');
        
        return $this->markAttendance($employeeId, $date, $time, null, 'present');
    }
    
    public function checkOut($employeeId, $time = null) {
        $date = date('Y-m-d');
        $time = $time ?: date('H:i:s');
        
        $existing = $this->findByEmployeeAndDate($employeeId, $date);
        if (!$existing) {
            throw new Exception('No check-in record found for today');
        }
        
        return $this->markAttendance($employeeId, $date, null, $time);
    }
    
    public function getTodayStats() {
        $today = date('Y-m-d');
        
        $stats = [];
        $stats['present'] = $this->count(['attendance_date' => $today, 'status' => 'present']);
        $stats['absent'] = $this->count(['attendance_date' => $today, 'status' => 'absent']);
        $stats['late'] = $this->count(['attendance_date' => $today, 'status' => 'late']);
        $stats['half_day'] = $this->count(['attendance_date' => $today, 'status' => 'half_day']);
        $stats['total'] = $stats['present'] + $stats['absent'] + $stats['late'] + $stats['half_day'];
        
        // Get employees who haven't marked attendance
        $totalEmployees = $this->db->fetch(
            "SELECT COUNT(*) as count FROM employees e 
             JOIN users u ON e.user_id = u.id 
             WHERE e.status = 'active' AND u.is_active = 1"
        )['count'];
        
        $stats['not_marked'] = $totalEmployees - $stats['total'];
        
        return $stats;
    }
    
    public function getLastSyncTime() {
        $result = $this->db->fetch(
            "SELECT MAX(created_at) as last_sync FROM {$this->table} WHERE sync_source = 'zkteco'"
        );
        
        return $result['last_sync'];
    }
    
    public function syncFromZkTeco($attendanceData) {
        $this->db->beginTransaction();
        
        try {
            $synced = 0;
            
            foreach ($attendanceData as $record) {
                $employeeId = $record['employee_id'];
                $date = $record['date'];
                $time = $record['time'];
                $type = $record['type']; // 'in' or 'out'
                
                $existing = $this->findByEmployeeAndDate($employeeId, $date);
                
                if ($type === 'in') {
                    if (!$existing || !$existing['check_in']) {
                        $this->markAttendance($employeeId, $date, $time, null, 'present');
                        $synced++;
                    }
                } else if ($type === 'out') {
                    if ($existing && $existing['check_in'] && !$existing['check_out']) {
                        $this->markAttendance($employeeId, $date, null, $time);
                        $synced++;
                    }
                }
            }
            
            $this->db->commit();
            return $synced;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function getMonthlyReport($employeeId, $month, $year) {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'present' THEN 1 END) as present_days,
                    COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent_days,
                    COUNT(CASE WHEN status = 'late' THEN 1 END) as late_days,
                    COUNT(CASE WHEN status = 'half_day' THEN 1 END) as half_days,
                    COALESCE(SUM(total_hours), 0) as total_hours,
                    COALESCE(SUM(overtime_hours), 0) as overtime_hours
                FROM {$this->table}
                WHERE employee_id = ? 
                AND MONTH(attendance_date) = ?
                AND YEAR(attendance_date) = ?";
        
        return $this->db->fetch($sql, [$employeeId, $month, $year]);
    }
    
    private function calculateTotalMinutes($checkIn, $checkOut) {
        $checkInTime = new DateTime($checkIn);
        $checkOutTime = new DateTime($checkOut);
        
        $diff = $checkOutTime->diff($checkInTime);
        return ($diff->h * 60) + $diff->i;
    }
    
    public function generateDailyReport($date) {
        $sql = "SELECT 
                    u.name as employee_name,
                    e.employee_id,
                    d.name as department_name,
                    a.check_in,
                    a.check_out,
                    a.total_hours,
                    a.status,
                    a.remarks
                FROM employees e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN {$this->table} a ON e.id = a.employee_id AND a.attendance_date = ?
                WHERE e.status = 'active' AND u.is_active = 1
                ORDER BY d.name, u.name";
        
        return $this->db->fetchAll($sql, [$date]);
    }
}
