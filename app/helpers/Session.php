<?php
/**
 * Session Helper
 */
class Session {
    public static function put($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function forget($key) {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function flush() {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }
    
    public static function flash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
    
    public static function getFlash($key) {
        $message = $_SESSION['flash'][$key] ?? null;
        if ($message) {
            unset($_SESSION['flash'][$key]);
        }
        return $message;
    }
    
    public static function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }
}
