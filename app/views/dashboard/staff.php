<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    My Dashboard
                </h1>
                <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                    Welcome back, <?php echo htmlspecialchars(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')); ?>!
                </p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <?php echo date('l, F j, Y'); ?>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    Last login: <?php echo date('M j, H:i'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Pending Leaves -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-2xl p-6 border border-amber-200 dark:border-amber-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400 mb-1">
                        <?php echo number_format($stats['pending_leaves']); ?>
                    </div>
                    <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Pending Leaves</div>
                    <div class="text-xs text-amber-600 dark:text-amber-400 mt-1">Awaiting approval</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-amber-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl text-amber-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-amber-500/5 rounded-full"></div>
        </div>

        <!-- Approved Leaves This Year -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                        <?php echo number_format($stats['approved_leaves_this_year']); ?>
                    </div>
                    <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Leaves Taken</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">This year</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-2xl text-emerald-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-emerald-500/5 rounded-full"></div>
        </div>

        <!-- Attendance This Month -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                        <?php echo number_format($stats['attendance_this_month']); ?>
                    </div>
                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Days Present</div>
                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">This month</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-blue-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-blue-500/5 rounded-full"></div>
        </div>

        <!-- Working Days This Month -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200 dark:border-purple-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                        <?php echo number_format($stats['working_days_this_month']); ?>
                    </div>
                    <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Working Days</div>
                    <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">This month</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-purple-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-2xl text-purple-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-purple-500/5 rounded-full"></div>
        </div>
    </div>

    <!-- Personal Information & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Employee Profile -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Profile</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Personal information</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-semibold mx-auto mb-4">
                        <?php echo strtoupper(substr($employee['first_name'] ?? 'N', 0, 1) . substr($employee['last_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        <?php echo htmlspecialchars(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')); ?>
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($employee['position'] ?? 'Staff'); ?></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">ID: <?php echo htmlspecialchars($employee['employee_id'] ?? 'N/A'); ?></p>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Email:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['email'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Phone:</span>
                            <span class="text-gray-900 dark:text-white"><?php echo htmlspecialchars($employee['phone'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Join Date:</span>
                            <span class="text-gray-900 dark:text-white">
                                <?php echo $employee['hire_date'] ? date('M j, Y', strtotime($employee['hire_date'])) : 'N/A'; ?>
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="<?php echo APP_URL; ?>/profile" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Balance -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Leave Balance</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo date('Y'); ?> entitlements</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($leaveTypes)): ?>
                    <div class="space-y-4">
                        <?php foreach ($leaveTypes as $leaveType): 
                            $balance = $leaveBalances[$leaveType['id']] ?? ['allowed' => 0, 'used' => 0, 'remaining' => 0];
                            $usedPercentage = $balance['allowed'] > 0 ? ($balance['used'] / $balance['allowed']) * 100 : 0;
                        ?>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($leaveType['name']); ?>
                                    </h4>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                        <?php echo $balance['remaining']; ?> days left
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mb-2">
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo min($usedPercentage, 100); ?>%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Used: <?php echo $balance['used']; ?> days</span>
                                    <span>Total: <?php echo $balance['allowed']; ?> days</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-6">
                        <i class="fas fa-calendar text-gray-400 dark:text-gray-500 text-2xl mb-2"></i>
                        <p class="text-gray-500 dark:text-gray-400">No leave types configured</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bolt text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Personal tasks</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-3">
                    <a href="<?php echo APP_URL; ?>/leaves/create" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/10 dark:to-amber-800/10 border border-amber-200 dark:border-amber-800/30 hover:from-amber-100 hover:to-amber-200 dark:hover:from-amber-900/20 dark:hover:to-amber-800/20 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                            <i class="fas fa-calendar-plus text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Request Leave</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Submit request</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-amber-500 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="<?php echo APP_URL; ?>/overtime/create" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/10 dark:to-orange-800/10 border border-orange-200 dark:border-orange-800/30 hover:from-orange-100 hover:to-orange-200 dark:hover:from-orange-900/20 dark:hover:to-orange-800/20 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Request Overtime</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Submit request</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-orange-500 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="<?php echo APP_URL; ?>/attendance" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/10 dark:to-blue-800/10 border border-blue-200 dark:border-blue-800/30 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900/20 dark:hover:to-blue-800/20 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                            <i class="fas fa-user-check text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">My Attendance</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">View records</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <?php if ($currentSalarySlip): ?>
                        <a href="<?php echo APP_URL; ?>/salary/view/<?php echo $currentSalarySlip['id']; ?>" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/10 dark:to-emerald-800/10 border border-emerald-200 dark:border-emerald-800/30 hover:from-emerald-100 hover:to-emerald-200 dark:hover:from-emerald-900/20 dark:hover:to-emerald-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-money-bill text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Salary Slip</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo date('F Y'); ?></p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- My Recent Activities -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- My Recent Leaves -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Leave Requests</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Recent submissions</p>
                        </div>
                    </div>
                    <a href="<?php echo APP_URL; ?>/leaves" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 bg-white dark:bg-gray-800 rounded-lg border border-amber-200 dark:border-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($myLeaves)): ?>
                    <div class="space-y-4">
                        <?php foreach ($myLeaves as $leave): 
                            $statusColors = [
                                'pending' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                                'approved' => 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
                                'rejected' => 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400'
                            ];
                            $statusColor = $statusColors[$leave['status']] ?? $statusColors['pending'];
                        ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white text-sm">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($leave['leave_type'] ?? 'N/A'); ?></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo date('M j', strtotime($leave['start_date'] ?? 'now')); ?> - <?php echo date('M j', strtotime($leave['end_date'] ?? 'now')); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium <?php echo $statusColor; ?> rounded-full">
                                        <?php echo ucfirst($leave['status'] ?? 'pending'); ?>
                                    </span>
                                    <a href="<?php echo APP_URL; ?>/leaves/view/<?php echo $leave['id'] ?? '#'; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">No leave requests yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My Recent Overtime -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Overtime</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Recent requests</p>
                        </div>
                    </div>
                    <a href="<?php echo APP_URL; ?>/overtime" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-orange-600 dark:text-orange-400 bg-white dark:bg-gray-800 rounded-lg border border-orange-200 dark:border-orange-700 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($myOvertime)): ?>
                    <div class="space-y-4">
                        <?php foreach ($myOvertime as $overtime): 
                            $statusColors = [
                                'pending' => 'bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                                'approved' => 'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
                                'rejected' => 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400'
                            ];
                            $statusColor = $statusColors[$overtime['status']] ?? $statusColors['pending'];
                        ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-sm">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?php echo date('M j, Y', strtotime($overtime['overtime_date'] ?? 'now')); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo ($overtime['hours'] ?? 0); ?> hours
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium <?php echo $statusColor; ?> rounded-full">
                                        <?php echo ucfirst($overtime['status'] ?? 'pending'); ?>
                                    </span>
                                    <a href="<?php echo APP_URL; ?>/overtime/view/<?php echo $overtime['id'] ?? '#'; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">No overtime requests yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-2xl border border-blue-200 dark:border-blue-800/30 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-user-clock text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Employee Self-Service</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your personal HR activities</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Staff Portal</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Personal dashboard access</p>
            </div>
        </div>
    </div>
</div>
