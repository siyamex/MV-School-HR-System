<?php
/**
 * User Model
 */
class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE email = ?", [$email]);
    }
    
    public function findByGoogleId($googleId) {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE google_id = ?", [$googleId]);
    }
    
    public function createUser($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->create($data);
    }
    
    public function updatePassword($userId, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    public function getActiveUsers($role = null) {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1";
        $params = [];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY name";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getUsersWithEmployeeInfo() {
        $sql = "SELECT u.*, e.employee_id, e.department_id, e.designation, d.name as department_name
                FROM {$this->table} u
                LEFT JOIN employees e ON u.id = e.user_id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE u.is_active = 1
                ORDER BY u.name";
        return $this->db->fetchAll($sql);
    }
    
    public function getSupervisors() {
        return $this->getActiveUsers('supervisor');
    }
    
    public function getAdmins() {
        $sql = "SELECT * FROM {$this->table} WHERE role IN ('super_admin', 'admin') AND is_active = 1 ORDER BY name";
        return $this->db->fetchAll($sql);
    }
}
