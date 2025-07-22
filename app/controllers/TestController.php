<?php
/**
 * Test Controller - for debugging only
 */
class TestController {
    
    public function index() {
        echo "<h1>Test Controller Works!</h1>";
        echo "<p>Authentication status: " . (Auth::check() ? 'Logged in' : 'Not logged in') . "</p>";
        if (Auth::check()) {
            $user = Auth::user();
            echo "<p>User role: " . ($user['role'] ?? 'not set') . "</p>";
        }
        echo "<p>Current URL: " . ($_GET['url'] ?? 'not set') . "</p>";
        echo "<p>Session data:</p>";
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
        exit;
    }
}
