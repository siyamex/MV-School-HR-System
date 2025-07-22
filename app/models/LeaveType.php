<?php
/**
 * LeaveType Model
 */
class LeaveType extends Model {
    protected $table = 'leave_types';
    
    public function getActive() {
        return $this->findAll(['is_active' => 1], 'name');
    }
    
    public function getAllWithUsage() {
        $sql = "SELECT lt.*,
                       COUNT(lr.id) as total_requests,
                       COUNT(CASE WHEN lr.status = 'approved' THEN 1 END) as approved_requests,
                       COUNT(CASE WHEN lr.status = 'pending' THEN 1 END) as pending_requests
                FROM {$this->table} lt
                LEFT JOIN leave_requests lr ON lt.id = lr.leave_type_id
                GROUP BY lt.id, lt.name, lt.days_allowed, lt.is_active, lt.created_at, lt.updated_at
                ORDER BY lt.name";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getUsageStats($id, $year = null) {
        $year = $year ?: date('Y');
        
        $sql = "SELECT 
                    COUNT(*) as total_requests,
                    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_requests,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_requests,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_requests,
                    SUM(CASE WHEN status = 'approved' THEN days_requested ELSE 0 END) as total_days_taken
                FROM leave_requests
                WHERE leave_type_id = ? AND YEAR(start_date) = ?";
        
        return $this->db->fetch($sql, [$id, $year]);
    }
}
