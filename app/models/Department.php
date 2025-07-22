<?php
/**
 * Department Model
 */
class Department extends Model {
    protected $table = 'departments';
    
    public function getActive() {
        return $this->findAll(['is_active' => 1], 'name');
    }
    
    public function getAllWithHead() {
        $sql = "SELECT d.*, u.name as head_name
                FROM {$this->table} d
                LEFT JOIN users u ON d.head_id = u.id
                ORDER BY d.name";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithHead($id) {
        $sql = "SELECT d.*, u.name as head_name
                FROM {$this->table} d
                LEFT JOIN users u ON d.head_id = u.id
                WHERE d.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getEmployeeCount($id) {
        return $this->db->fetch(
            "SELECT COUNT(e.id) as count FROM employees e 
             JOIN users u ON e.user_id = u.id 
             WHERE e.department_id = ? AND e.status = 'active' AND u.is_active = 1",
            [$id]
        )['count'];
    }
    
    public function getDepartmentStats() {
        $sql = "SELECT d.name, 
                       COUNT(e.id) as total_employees,
                       SUM(CASE WHEN e.status = 'active' THEN 1 ELSE 0 END) as active_employees
                FROM {$this->table} d
                LEFT JOIN employees e ON d.id = e.department_id
                WHERE d.is_active = 1
                GROUP BY d.id, d.name
                ORDER BY d.name";
        return $this->db->fetchAll($sql);
    }
}
