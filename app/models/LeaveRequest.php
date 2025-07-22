<?php
/**
 * LeaveRequest Model
 */
class LeaveRequest extends Model {
    protected $table = 'leave_requests';
    
    public function getAllWithDetails() {
        $sql = "SELECT lr.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name,
                       lt.name as leave_type_name,
                       approver.name as approver_name
                FROM {$this->table} lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users approver ON lr.approved_by = approver.id
                ORDER BY lr.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithDetails($id) {
        $sql = "SELECT lr.*, 
                       u.name as employee_name, 
                       u.email as employee_email,
                       e.employee_id,
                       e.department_id,
                       d.name as department_name,
                       lt.name as leave_type_name,
                       approver.name as approver_name
                FROM {$this->table} lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users approver ON lr.approved_by = approver.id
                WHERE lr.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getByEmployee($employeeId) {
        $sql = "SELECT lr.*, 
                       lt.name as leave_type_name,
                       approver.name as approver_name
                FROM {$this->table} lr
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN users approver ON lr.approved_by = approver.id
                WHERE lr.employee_id = ?
                ORDER BY lr.created_at DESC";
        return $this->db->fetchAll($sql, [$employeeId]);
    }
    
    public function getByStatus($status) {
        $sql = "SELECT lr.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name,
                       lt.name as leave_type_name
                FROM {$this->table} lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE lr.status = ?
                ORDER BY lr.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }
    
    public function getByDepartmentAndStatus($departmentId, $status = null) {
        $sql = "SELECT lr.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       lt.name as leave_type_name
                FROM {$this->table} lr
                JOIN employees e ON lr.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                WHERE e.department_id = ?";
        
        $params = [$departmentId];
        
        if ($status) {
            $sql .= " AND lr.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function approve($id, $approverId) {
        return $this->update($id, [
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function reject($id, $approverId, $reason = null) {
        return $this->update($id, [
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ]);
    }
    
    public function checkOverlapping($employeeId, $startDate, $endDate, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE employee_id = ? 
                AND status = 'approved'
                AND (
                    (start_date <= ? AND end_date >= ?) OR
                    (start_date <= ? AND end_date >= ?) OR
                    (start_date >= ? AND end_date <= ?)
                )";
        
        $params = [$employeeId, $startDate, $startDate, $endDate, $endDate, $startDate, $endDate];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function getLeaveBalance($employeeId, $leaveTypeId, $year = null) {
        if (!$year) {
            $year = date('Y');
        }
        
        $sql = "SELECT COALESCE(SUM(days_requested), 0) as used_days
                FROM {$this->table}
                WHERE employee_id = ? 
                AND leave_type_id = ? 
                AND status = 'approved'
                AND YEAR(start_date) = ?";
        
        $result = $this->db->fetch($sql, [$employeeId, $leaveTypeId, $year]);
        
        // Get allowed days for leave type
        $leaveType = $this->db->fetch("SELECT days_allowed FROM leave_types WHERE id = ?", [$leaveTypeId]);
        $allowedDays = $leaveType ? $leaveType['days_allowed'] : 0;
        
        return [
            'allowed' => $allowedDays,
            'used' => $result['used_days'],
            'remaining' => max(0, $allowedDays - $result['used_days'])
        ];
    }
    
    public function getStats() {
        $stats = [];
        $stats['pending'] = $this->count(['status' => 'pending']);
        $stats['approved'] = $this->count(['status' => 'approved']);
        $stats['rejected'] = $this->count(['status' => 'rejected']);
        $stats['total'] = $stats['pending'] + $stats['approved'] + $stats['rejected'];
        return $stats;
    }
    
    public function getStaffOnLeaveToday($departmentId = null) {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as count FROM {$this->table} lr
                JOIN employees e ON lr.employee_id = e.id
                WHERE lr.status = 'approved' 
                AND ? BETWEEN lr.start_date AND lr.end_date";
        
        $params = [$today];
        
        if ($departmentId) {
            $sql .= " AND e.department_id = ?";
            $params[] = $departmentId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }
}
