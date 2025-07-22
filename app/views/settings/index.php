<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">System Settings</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Configure your HR School Management System settings.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo APP_URL; ?>/settings/email-templates" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i>
                        Email Templates
                    </a>
                    <a href="<?php echo APP_URL; ?>/dashboard" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <form action="<?php echo APP_URL; ?>/settings/update" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?php echo CSRF::generateToken(); ?>
            
            <!-- Application Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-cog mr-3 text-blue-500"></i>
                        Application Settings
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Name
                            </label>
                            <input type="text" id="app_name" name="app_name" 
                                   value="<?php echo htmlspecialchars($settings['app_name'] ?? 'HR School Management System'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="theme_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Theme Mode
                            </label>
                            <select id="theme_mode" name="theme_mode"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="light" <?php echo ($settings['theme_mode'] ?? 'light') === 'light' ? 'selected' : ''; ?>>Light</option>
                                <option value="dark" <?php echo ($settings['theme_mode'] ?? 'light') === 'dark' ? 'selected' : ''; ?>>Dark</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="app_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Logo
                            </label>
                            <input type="file" id="app_logo" name="app_logo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG or JPEG. Max size 2MB.</p>
                            <?php if (!empty($settings['app_logo'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo APP_URL . '/public/uploads/' . $settings['app_logo']; ?>" 
                                         alt="Current logo" class="h-12 w-auto">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="app_favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Favicon
                            </label>
                            <input type="file" id="app_favicon" name="app_favicon" accept="image/x-icon,image/vnd.microsoft.icon,image/png"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ICO or PNG. 16x16 or 32x32 pixels.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-envelope mr-3 text-green-500"></i>
                        Email Configuration
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SMTP Host
                            </label>
                            <input type="text" id="smtp_host" name="smtp_host" 
                                   value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="smtp.gmail.com">
                        </div>
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SMTP Port
                            </label>
                            <input type="number" id="smtp_port" name="smtp_port" 
                                   value="<?php echo htmlspecialchars($settings['smtp_port'] ?? '587'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SMTP Username
                            </label>
                            <input type="text" id="smtp_username" name="smtp_username" 
                                   value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SMTP Password
                            </label>
                            <input type="password" id="smtp_password" name="smtp_password" 
                                   value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SMTP Encryption
                            </label>
                            <select id="smtp_encryption" name="smtp_encryption"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                                <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                From Email Address
                            </label>
                            <input type="email" id="mail_from_address" name="mail_from_address" 
                                   value="<?php echo htmlspecialchars($settings['mail_from_address'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="md:col-span-2">
                            <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                From Name
                            </label>
                            <input type="text" id="mail_from_name" name="mail_from_name" 
                                   value="<?php echo htmlspecialchars($settings['mail_from_name'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google OAuth Settings -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fab fa-google mr-3 text-red-500"></i>
                        Google OAuth Settings
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="google_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Google Client ID
                            </label>
                            <input type="text" id="google_client_id" name="google_client_id" 
                                   value="<?php echo htmlspecialchars($settings['google_client_id'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="google_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Google Client Secret
                            </label>
                            <input type="password" id="google_client_secret" name="google_client_secret" 
                                   value="<?php echo htmlspecialchars($settings['google_client_secret'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ZKTeco Integration -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-fingerprint mr-3 text-indigo-500"></i>
                        ZKTeco Biometric Integration
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="zkteco_ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Device IP Address
                            </label>
                            <input type="text" id="zkteco_ip" name="zkteco_ip" 
                                   value="<?php echo htmlspecialchars($settings['zkteco_ip'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="192.168.1.100">
                        </div>
                        <div>
                            <label for="zkteco_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Port
                            </label>
                            <input type="number" id="zkteco_port" name="zkteco_port" 
                                   value="<?php echo htmlspecialchars($settings['zkteco_port'] ?? '4370'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="zkteco_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Device Password
                            </label>
                            <input type="password" id="zkteco_password" name="zkteco_password" 
                                   value="<?php echo htmlspecialchars($settings['zkteco_password'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
