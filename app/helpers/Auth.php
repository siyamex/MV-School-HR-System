<?php
/**
 * Authentication Helper
 */
class Auth {
    public static function attempt($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            return true;
        }
        
        return false;
    }
    
    public static function login($user) {
        Session::put('user_id', $user['id']);
        Session::put('user_role', $user['role']);
        Session::put('user_name', $user['name']);
        Session::put('user_email', $user['email']);
    }
    
    public static function logout() {
        Session::flush();
    }
    
    public static function check() {
        return Session::has('user_id');
    }
    
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        return [
            'id' => Session::get('user_id'),
            'role' => Session::get('user_role'),
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email')
        ];
    }
    
    public static function id() {
        return Session::get('user_id');
    }
    
    public static function hasRole($roles) {
        if (!self::check()) {
            return false;
        }
        
        $userRole = Session::get('user_role');
        
        if (is_string($roles)) {
            return $userRole === $roles;
        }
        
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        
        return false;
    }
    
    public static function isSuperAdmin() {
        return self::hasRole('super_admin');
    }
    
    public static function isAdmin() {
        return self::hasRole(['super_admin', 'admin']);
    }
    
    public static function isSupervisor() {
        return self::hasRole(['super_admin', 'admin', 'supervisor']);
    }
    
    public static function isStaff() {
        return self::hasRole('staff');
    }
}
