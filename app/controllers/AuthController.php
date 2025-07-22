<?php
/**
 * Authentication Controller
 */
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        // Don't call parent constructor for auth routes
        $this->userModel = $this->loadModel('User');
    }
    
    public function login() {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        }
        
        $systemSettings = $this->getSystemSettings();
        $this->loadView('auth/login', [
            'pageTitle' => 'Login - ' . $systemSettings['app_name'],
            'systemSettings' => $systemSettings
        ]);
    }
    
    private function handleLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email and password are required.');
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        if (Auth::attempt($email, $password)) {
            if ($remember) {
                // Set remember token (implementation needed)
            }
            
            // Redirect based on role
            $user = Auth::user();
            $this->logActivity('User logged in');
            
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        } else {
            Session::flash('error', 'Invalid email or password.');
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
    
    public function logout() {
        if (Auth::check()) {
            $this->logActivity('User logged out');
            Auth::logout();
        }
        $this->redirect('login');
    }
    
    public function googleAuth() {
        $settings = $this->getSystemSettings();
        $clientId = $settings['google_client_id'] ?? '';
        
        if (empty($clientId)) {
            Session::flash('error', 'Google OAuth not configured.');
            $this->redirect('login');
        }
        
        // Redirect to Google OAuth
        $params = [
            'client_id' => $clientId,
            'redirect_uri' => APP_URL . '/auth/google/callback',
            'scope' => 'email profile',
            'response_type' => 'code',
            'state' => CSRF::generate()
        ];
        
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $url);
        exit;
    }
    
    public function googleCallback() {
        $code = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';
        
        if (empty($code) || $state !== Session::get('csrf_token')) {
            Session::flash('error', 'Invalid Google OAuth response.');
            $this->redirect('login');
        }
        
        $settings = $this->getSystemSettings();
        $clientId = $settings['google_client_id'] ?? '';
        $clientSecret = $settings['google_client_secret'] ?? '';
        
        if (empty($clientId) || empty($clientSecret)) {
            Session::flash('error', 'Google OAuth not configured properly.');
            $this->redirect('login');
        }
        
        try {
            // Exchange code for token
            $tokenData = $this->getGoogleToken($code, $clientId, $clientSecret);
            
            // Get user info from Google
            $userInfo = $this->getGoogleUserInfo($tokenData['access_token']);
            
            // Find or create user
            $user = $this->userModel->findByGoogleId($userInfo['id']);
            
            if (!$user) {
                $user = $this->userModel->findByEmail($userInfo['email']);
                
                if ($user) {
                    // Link Google account to existing user
                    $this->userModel->update($user['id'], ['google_id' => $userInfo['id']]);
                } else {
                    // Create new user (only for super_admin/admin roles)
                    $userData = [
                        'name' => $userInfo['name'],
                        'email' => $userInfo['email'],
                        'google_id' => $userInfo['id'],
                        'role' => 'admin', // Default role for Google login
                        'avatar' => $userInfo['picture'] ?? null,
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $userId = $this->userModel->createUser($userData);
                    $user = $this->userModel->find($userId);
                }
            }
            
            if ($user && in_array($user['role'], ['super_admin', 'admin'])) {
                Auth::login($user);
                $this->logActivity('User logged in via Google OAuth');
                $this->redirect('dashboard');
            } else {
                Session::flash('error', 'Google OAuth is only available for administrators.');
                $this->redirect('login');
            }
            
        } catch (Exception $e) {
            Session::flash('error', 'Google OAuth failed: ' . $e->getMessage());
            $this->redirect('login');
        }
    }
    
    private function getGoogleToken($code, $clientId, $clientSecret) {
        $data = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => APP_URL . '/auth/google/callback'
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents('https://oauth2.googleapis.com/token', false, $context);
        
        if ($result === FALSE) {
            throw new Exception('Failed to get Google token');
        }
        
        return json_decode($result, true);
    }
    
    private function getGoogleUserInfo($accessToken) {
        $options = [
            'http' => [
                'header' => "Authorization: Bearer {$accessToken}\r\n"
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo', false, $context);
        
        if ($result === FALSE) {
            throw new Exception('Failed to get Google user info');
        }
        
        return json_decode($result, true);
    }
    
    private function logActivity($action) {
        try {
            $this->db = Database::getInstance();
            $this->db->query(
                "INSERT INTO activity_logs (user_id, action, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, NOW())",
                [
                    Auth::id(),
                    $action,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null
                ]
            );
        } catch (Exception $e) {
            // Log error but don't break the flow
            error_log('Activity log error: ' . $e->getMessage());
        }
    }
}
