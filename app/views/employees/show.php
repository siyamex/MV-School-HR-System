<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Employee Details</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">View employee information and details.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo APP_URL; ?>/employees/edit/<?php echo $employee['id']; ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Employee
                    </a>
                    <a href="<?php echo APP_URL; ?>/employees" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Employee Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8 text-center">
                        <?php if (!empty($employee['profile_photo'])): ?>
                            <img class="h-24 w-24 rounded-full mx-auto mb-4 border-4 border-white shadow-lg" 
                                 src="<?php echo APP_URL . '/uploads/profiles/' . $employee['profile_photo']; ?>" 
                                 alt="<?php echo htmlspecialchars($employee['name']); ?>">
                        <?php else: ?>
                            <div class="h-24 w-24 rounded-full mx-auto mb-4 bg-white/20 flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-lg">
                                <?php 
                                $name = $employee['name'] ?? 'Unknown';
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
                        <h2 class="text-xl font-bold text-white mb-1"><?php echo htmlspecialchars($employee['name'] ?? 'Unknown'); ?></h2>
                        <p class="text-blue-100"><?php echo htmlspecialchars($employee['designation'] ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['employee_id']); ?></span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['email']); ?></span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['department_name'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $employee['status'] === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'; ?>">
                                    <?php echo ucfirst($employee['status']); ?>
                                </span>
                            </div>
                            <?php if (!empty($employee['hire_date'])): ?>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</span>
                                <span class="text-sm text-gray-900 dark:text-white"><?php echo date('M d, Y', strtotime($employee['hire_date'])); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Employee Information</h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contact Information -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-address-book mr-2 text-blue-500"></i>
                                    Contact Information
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['phone'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['address'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                                    Emergency Contact
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['emergency_contact'] ?? 'N/A'); ?></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['emergency_phone'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Details -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-briefcase mr-2 text-green-500"></i>
                                    Employment Details
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Designation</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['designation'] ?? 'N/A'); ?></p>
                                    </div>
                                    <?php if (!empty($employee['salary'])): ?>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Salary</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1">$<?php echo number_format($employee['salary'], 2); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Financial Information -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-university mr-2 text-purple-500"></i>
                                    Financial Information
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Bank Account</label>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1"><?php echo htmlspecialchars($employee['bank_account'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timestamps -->
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <div>
                                    <strong>Created:</strong> <?php echo date('M d, Y \a\t g:i A', strtotime($employee['created_at'])); ?>
                                </div>
                                <div>
                                    <strong>Last Updated:</strong> <?php echo date('M d, Y \a\t g:i A', strtotime($employee['updated_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
