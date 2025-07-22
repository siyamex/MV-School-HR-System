<?php
/**
 * SystemSetting Model
 */
class SystemSetting extends Model {
    protected $table = 'system_settings';
    
    public function getSetting($key, $default = null) {
        $setting = $this->db->fetch("SELECT setting_value FROM {$this->table} WHERE setting_key = ?", [$key]);
        return $setting ? $setting['setting_value'] : $default;
    }
    
    public function setSetting($key, $value) {
        $exists = $this->db->fetch("SELECT id FROM {$this->table} WHERE setting_key = ?", [$key]);
        
        if ($exists) {
            return $this->db->query(
                "UPDATE {$this->table} SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?",
                [$value, $key]
            );
        } else {
            return $this->db->query(
                "INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?)",
                [$key, $value]
            );
        }
    }
    
    public function getAllSettings() {
        $settings = $this->db->fetchAll("SELECT setting_key, setting_value FROM {$this->table}");
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        return $result;
    }
    
    public function updateSettings($settings) {
        $this->db->beginTransaction();
        
        try {
            foreach ($settings as $key => $value) {
                $this->setSetting($key, $value);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function getAppName() {
        return $this->getSetting('app_name', 'HR School Management');
    }
    
    public function getAppLogo() {
        return $this->getSetting('app_logo');
    }
    
    public function getSmtpSettings() {
        return [
            'host' => $this->getSetting('smtp_host'),
            'port' => $this->getSetting('smtp_port', 587),
            'username' => $this->getSetting('smtp_username'),
            'password' => $this->getSetting('smtp_password'),
            'encryption' => $this->getSetting('smtp_encryption', 'tls'),
            'from_address' => $this->getSetting('mail_from_address'),
            'from_name' => $this->getSetting('mail_from_name')
        ];
    }
    
    public function getGoogleAuthSettings() {
        return [
            'client_id' => $this->getSetting('google_client_id'),
            'client_secret' => $this->getSetting('google_client_secret')
        ];
    }
    
    public function getZktecoSettings() {
        return [
            'ip' => $this->getSetting('zkteco_ip'),
            'port' => $this->getSetting('zkteco_port', 4370),
            'password' => $this->getSetting('zkteco_password')
        ];
    }
}
