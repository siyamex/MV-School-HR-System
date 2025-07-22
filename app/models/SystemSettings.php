<?php
/**
 * System Settings Model
 */
class SystemSettings extends Model {
    protected $table = 'system_settings';

    public function getAllSettings() {
        $sql = "SELECT * FROM {$this->table} ORDER BY setting_key";
        return $this->db->fetchAll($sql);
    }

    public function getSetting($key) {
        $sql = "SELECT setting_value FROM {$this->table} WHERE setting_key = ?";
        $result = $this->db->fetch($sql, [$key]);
        return $result ? $result['setting_value'] : null;
    }

    public function updateSetting($key, $value) {
        $sql = "UPDATE {$this->table} SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?";
        return $this->db->query($sql, [$value, $key]);
    }

    public function createSetting($key, $value) {
        $sql = "INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?)";
        return $this->db->query($sql, [$key, $value]);
    }

    public function deleteSetting($key) {
        $sql = "DELETE FROM {$this->table} WHERE setting_key = ?";
        return $this->db->query($sql, [$key]);
    }

    public function getSettingsAsArray() {
        $settings = $this->getAllSettings();
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settingsArray;
    }
}
