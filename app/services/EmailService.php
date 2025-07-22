<?php
/**
 * Email Service
 */
class EmailService {
    private $settings;
    
    public function __construct() {
        $systemSettings = new SystemSetting();
        $this->settings = $systemSettings->getSmtpSettings();
    }
    
    public function send($to, $subject, $body) {
        // Basic implementation - you can enhance this with PHPMailer or similar
        if (empty($this->settings['host'])) {
            // If SMTP not configured, just log the email
            error_log("EMAIL: To: {$to}, Subject: {$subject}, Body: {$body}");
            return true;
        }
        
        try {
            // Basic mail() function - replace with PHPMailer for production
            $headers = "From: " . ($this->settings['from_name'] ?? 'HR System') . " <" . ($this->settings['from_address'] ?? 'noreply@hrschool.com') . ">\r\n";
            $headers .= "Reply-To: " . ($this->settings['from_address'] ?? 'noreply@hrschool.com') . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            return mail($to, $subject, nl2br($body), $headers);
            
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
    }
    
    public function isConfigured() {
        return !empty($this->settings['host']) && !empty($this->settings['from_address']);
    }
}
