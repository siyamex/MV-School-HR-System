<?php
/**
 * CSRF Protection Helper
 */
class CSRF {
    private static $tokenName = 'csrf_token';
    
    public static function generate() {
        if (!Session::has(self::$tokenName)) {
            Session::put(self::$tokenName, bin2hex(random_bytes(32)));
        }
        return Session::get(self::$tokenName);
    }
    
    public static function generateToken() {
        $token = self::generate();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . $token . '">';
    }
    
    public static function field() {
        $token = self::generate();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . $token . '">';
    }
    
    public static function validate() {
        $token = $_POST[self::$tokenName] ?? $_GET[self::$tokenName] ?? null;
        return $token && hash_equals(Session::get(self::$tokenName), $token);
    }
    
    public static function token() {
        return self::generate();
    }
}
