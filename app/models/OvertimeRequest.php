<?php
/**
 * OvertimeRequest Model
 */
class OvertimeRequest extends Model {
    protected $table = 'overtime_requests';
    
    public function getAllWithDetails() {
        $sql = "SELECT ot.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name,
                       approver.name as approver_name
                FROM {$this->table} ot
                JOIN employees e ON ot.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users approver ON ot.approved_by = approver.id
                ORDER BY ot.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithDetails($id) {
        $sql = "SELECT ot.*, 
                       u.name as employee_name, 
                       u.email as employee_email,
                       e.employee_id,
                       e.department_id,
                       d.name as department_name,
                       approver.name as approver_name
                FROM {$this->table} ot
                JOIN employees e ON ot.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users approver ON ot.approved_by = approver.id
                WHERE ot.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getByEmployee($employeeId) {
        $sql = "SELECT ot.*, 
                       approver.name as approver_name
                FROM {$this->table} ot
                LEFT JOIN users approver ON ot.approved_by = approver.id
                WHERE ot.employee_id = ?
                ORDER BY ot.created_at DESC";
        return $this->db->fetchAll($sql, [$employeeId]);
    }
    
    public function getByStatus($status) {
        $sql = "SELECT ot.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name
                FROM {$this->table} ot
                JOIN employees e ON ot.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE ot.status = ?
                ORDER BY ot.created_at DESC";
        return $this->db->fetchAll($sql, [$status]);
    }
    
    public function getByDepartmentAndStatus($departmentId, $status = null) {
        $sql = "SELECT ot.*, 
                       u.name as employee_name, 
                       e.employee_id
                FROM {$this->table} ot
                JOIN employees e ON ot.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                WHERE e.department_id = ?";
        
        $params = [$departmentId];
        
        if ($status) {
            $sql .= " AND ot.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY ot.created_at DESC";
        
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
    
    public function checkOverlapping($employeeId, $date, $startTime, $endTime, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE employee_id = ? 
                AND overtime_date = ?
                AND status = 'approved'
                AND (
                    (start_time <= ? AND end_time > ?) OR
                    (start_time < ? AND end_time >= ?) OR
                    (start_time >= ? AND end_time <= ?)
                )";
        
        $params = [$employeeId, $date, $startTime, $startTime, $endTime, $endTime, $startTime, $endTime];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function getMonthlyOvertimeHours($employeeId, $month, $year) {
        $sql = "SELECT COALESCE(SUM(hours_requested), 0) as total_hours
                FROM {$this->table}
                WHERE employee_id = ? 
                AND status = 'approved'
                AND MONTH(overtime_date) = ?
                AND YEAR(overtime_date) = ?";
        
        $result = $this->db->fetch($sql, [$employeeId, $month, $year]);
        return $result['total_hours'];
    }
    
    public function getStats() {
        $stats = [];
        $stats['pending'] = $this->count(['status' => 'pending']);
        $stats['approved'] = $this->count(['status' => 'approved']);
        $stats['rejected'] = $this->count(['status' => 'rejected']);
        $stats['total'] = $stats['pending'] + $stats['approved'] + $stats['rejected'];
        return $stats;
    }
}
