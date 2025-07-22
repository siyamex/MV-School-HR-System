<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your personal information and account settings.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo APP_URL; ?>/dashboard" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8 text-center">
                        <?php if (!empty($user['avatar'])): ?>
                            <img class="h-24 w-24 rounded-full mx-auto mb-4 border-4 border-white shadow-lg" 
                                 src="<?php echo APP_URL . '/public/uploads/' . $user['avatar']; ?>" 
                                 alt="<?php echo htmlspecialchars($user['name']); ?>">
                        <?php else: ?>
                            <div class="h-24 w-24 rounded-full mx-auto mb-4 bg-white/20 flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-lg">
                                <?php 
                                $name = $user['name'] ?? 'Unknown';
                                $nameParts = explode(' ', $name);
                                $initials = '';
                                if (count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($name, 0, 2));
                                }
                                echo $initials;
                                ?>
                            </div>
                        <?php endif; ?>
                        <h2 class="text-xl font-bold text-white mb-1"><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p class="text-blue-100"><?php echo ucfirst($user['role']); ?></p>
                        <?php if ($employee): ?>
                            <p class="text-blue-100 text-sm">ID: <?php echo htmlspecialchars($employee['employee_id']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                            <?php if ($employee): ?>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['department_name'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Designation</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['designation'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo ($employee['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'; ?>">
                                    <?php echo ucfirst($employee['status'] ?? 'active'); ?>
                                </span>
                            </div>
                            <?php if (!empty($employee['hire_date'])): ?>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo date('M d, Y', strtotime($employee['hire_date'])); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-user mr-3 text-blue-500"></i>
                                Personal Information
                            </h3>
                        </div>
                        
                        <form action="<?php echo APP_URL; ?>/profile/update" method="POST" enctype="multipart/form-data" class="px-6 py-6">
                            <?php echo CSRF::generateToken(); ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($user['name']); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required
                                           value="<?php echo htmlspecialchars($user['email']); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <?php if ($employee): ?>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" id="phone" name="phone"
                                           value="<?php echo htmlspecialchars($employee['phone'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label for="bank_account" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Bank Account
                                    </label>
                                    <input type="text" id="bank_account" name="bank_account"
                                           value="<?php echo htmlspecialchars($employee['bank_account'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Address
                                    </label>
                                    <textarea id="address" name="address" rows="3"
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                              placeholder="Enter full address"><?php echo htmlspecialchars($employee['address'] ?? ''); ?></textarea>
                                </div>
                                <div>
                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Emergency Contact Name
                                    </label>
                                    <input type="text" id="emergency_contact" name="emergency_contact"
                                           value="<?php echo htmlspecialchars($employee['emergency_contact'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label for="emergency_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Emergency Contact Phone
                                    </label>
                                    <input type="tel" id="emergency_phone" name="emergency_phone"
                                           value="<?php echo htmlspecialchars($employee['emergency_phone'] ?? ''); ?>"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Photo Upload -->
                            <div class="mt-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Profile Photos</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Avatar (Account Photo)
                                        </label>
                                        <input type="file" id="avatar" name="avatar" accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG or JPEG. Max size 2MB.</p>
                                    </div>
                                    <?php if ($employee): ?>
                                    <div>
                                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Profile Photo (Employee)
                                        </label>
                                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG or JPEG. Max size 2MB.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-lock mr-3 text-yellow-500"></i>
                                Change Password
                            </h3>
                        </div>
                        
                        <form action="<?php echo APP_URL; ?>/profile/change-password" method="POST" class="px-6 py-6">
                            <?php echo CSRF::generateToken(); ?>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Current Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="current_password" name="current_password" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        New Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="new_password" name="new_password" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Must be at least 8 characters long.</p>
                                </div>
                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Confirm New Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="confirm_password" name="confirm_password" required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-key mr-2"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
