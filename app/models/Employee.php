<?php
/**
 * Employee Model
 */
class Employee extends Model {
    protected $table = 'employees';
    
    public function getAllWithUserInfo() {
        $sql = "SELECT e.*, u.name, u.email, u.is_active, d.name as department_name, d.id as department_id
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                ORDER BY u.name";
        return $this->db->fetchAll($sql);
    }
    
    public function findWithUserInfo($id) {
        $sql = "SELECT e.*, u.name, u.email, u.role, u.is_active, d.name as department_name
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findByUserId($userId) {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE user_id = ?", [$userId]);
    }
    
    public function findByEmployeeId($employeeId) {
        $sql = "SELECT e.*, u.name, u.email, u.role, d.name as department_name
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.employee_id = ?";
        return $this->db->fetch($sql, [$employeeId]);
    }
    
    public function getByDepartment($departmentId) {
        $sql = "SELECT e.*, u.name, u.email, u.is_active
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                WHERE e.department_id = ? AND u.is_active = 1
                ORDER BY u.name";
        return $this->db->fetchAll($sql, [$departmentId]);
    }
    
    public function getActiveEmployees() {
        $sql = "SELECT e.*, u.name, u.email, d.name as department_name
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE e.status = 'active' AND u.is_active = 1
                ORDER BY u.name";
        return $this->db->fetchAll($sql);
    }
    
    // Alias for getAllActive used by SalaryController
    public function getAllActive() {
        return $this->getActiveEmployees();
    }
    
    public function generateEmployeeId() {
        $year = date('Y');
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE employee_id LIKE ?";
        $count = $this->db->fetch($sql, [$year . '%']);
        $nextNumber = str_pad($count['count'] + 1, 4, '0', STR_PAD_LEFT);
        return $year . $nextNumber;
    }
    
    public function createEmployee($userData, $employeeData) {
        $this->db->beginTransaction();
        
        try {
            // Create user first
            $userModel = new User();
            $userId = $userModel->createUser($userData);
            
            // Generate employee ID if not provided
            if (!isset($employeeData['employee_id'])) {
                $employeeData['employee_id'] = $this->generateEmployeeId();
            }
            
            // Create employee record
            $employeeData['user_id'] = $userId;
            $employeeId = $this->create($employeeData);
            
            $this->db->commit();
            return $employeeId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function updateEmployee($id, $userData, $employeeData) {
        error_log("Employee Model: updateEmployee called for ID: $id");
        error_log("Employee Model: userData: " . print_r($userData, true));
        error_log("Employee Model: employeeData: " . print_r($employeeData, true));
        
        $this->db->beginTransaction();
        
        try {
            $employee = $this->find($id);
            if (!$employee) {
                error_log("Employee Model: Employee not found for ID: $id");
                throw new Exception('Employee not found');
            }
            error_log("Employee Model: Employee found: " . print_r($employee, true));
            
            // Update user
            error_log("Employee Model: Updating user with ID: " . $employee['user_id']);
            $userModel = new User();
            $userModel->update($employee['user_id'], $userData);
            error_log("Employee Model: User updated successfully");
            
            // Update employee
            error_log("Employee Model: Updating employee with ID: $id");
            $this->update($id, $employeeData);
            error_log("Employee Model: Employee updated successfully");
            
            $this->db->commit();
            error_log("Employee Model: Transaction committed successfully");
            return true;
            
        } catch (Exception $e) {
            error_log("Employee Model: Error in updateEmployee: " . $e->getMessage());
            error_log("Employee Model: Rolling back transaction");
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function deleteEmployee($id) {
        $this->db->beginTransaction();
        
        try {
            $employee = $this->find($id);
            if (!$employee) {
                throw new Exception('Employee not found');
            }
            
            // Delete employee (user will be cascaded)
            $this->delete($id);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function getStats() {
        $stats = [];
        $stats['total'] = $this->count(['status' => 'active']);
        $stats['by_department'] = $this->db->fetchAll("
            SELECT d.name, COUNT(e.id) as count
            FROM departments d
            LEFT JOIN employees e ON d.id = e.department_id AND e.status = 'active'
            GROUP BY d.id, d.name
            ORDER BY d.name
        ");
        return $stats;
    }
    
    public function getAllWithDepartments() {
        $sql = "SELECT e.*, u.name as employee_name, u.email, d.name as department_name
                FROM {$this->table} e
                LEFT JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                ORDER BY u.name, e.employee_id";
        return $this->db->fetchAll($sql);
    }
    
    public function getManagers() {
        $sql = "SELECT e.id, u.name as employee_name, e.designation
                FROM {$this->table} e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE e.status = 'active' 
                AND (e.designation LIKE '%manager%' OR e.designation LIKE '%supervisor%' OR e.designation LIKE '%head%')
                ORDER BY u.name";
        return $this->db->fetchAll($sql);
    }
}
