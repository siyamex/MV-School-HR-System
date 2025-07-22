<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    HR Admin Dashboard
                </h1>
                <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                    Manage HR operations and oversee staff activities.
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
        <!-- Total Staff -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                        <?php echo number_format($stats['total_staff']); ?>
                    </div>
                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Staff</div>
                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">Active employees</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-blue-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-blue-500/5 rounded-full"></div>
        </div>

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

        <!-- Pending Overtime -->
        <div class="relative overflow-hidden bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl p-6 border border-orange-200 dark:border-orange-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-1">
                        <?php echo number_format($stats['pending_overtime']); ?>
                    </div>
                    <div class="text-sm font-medium text-orange-700 dark:text-orange-300">Pending Overtime</div>
                    <div class="text-xs text-orange-600 dark:text-orange-400 mt-1">Requests to review</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-orange-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-orange-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-orange-500/5 rounded-full"></div>
        </div>

        <!-- Staff On Leave Today -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                        <?php 
                        $leaveCount = $stats['staff_on_leave_today'] ?? 0;
                        echo number_format(is_array($leaveCount) ? count($leaveCount) : $leaveCount); 
                        ?>
                    </div>
                    <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">On Leave Today</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Staff members</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-times text-2xl text-emerald-500"></i>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-emerald-500/5 rounded-full"></div>
        </div>
    </div>

    <!-- Quick Actions & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Leave Requests -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-amber-600 dark:text-amber-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Leave Requests</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pending approvals</p>
                            </div>
                        </div>
                        <a href="<?php echo APP_URL; ?>/leaves" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 bg-white dark:bg-gray-800 rounded-lg border border-amber-200 dark:border-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            View All
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (!empty($recentLeaves)): ?>
                        <div class="space-y-4">
                            <?php foreach ($recentLeaves as $leave): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            <?php echo strtoupper(substr($leave['employee_name'] ?? 'N/A', 0, 2)); ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($leave['employee_name'] ?? 'Unknown'); ?></p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo htmlspecialchars($leave['leave_type'] ?? 'N/A'); ?> • 
                                                <?php echo date('M j', strtotime($leave['start_date'] ?? 'now')); ?> - <?php echo date('M j', strtotime($leave['end_date'] ?? 'now')); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-full">
                                            <?php echo ucfirst($leave['status'] ?? 'pending'); ?>
                                        </span>
                                        <a href="<?php echo APP_URL; ?>/leaves/view/<?php echo $leave['id'] ?? '#'; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">
                                            Review
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
                            <p class="text-gray-500 dark:text-gray-400">No pending leave requests</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- System Status & Quick Actions -->
        <div class="space-y-6">
            <!-- Attendance Status -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance Today</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current status</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Present</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Staff checked in</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                            <?php echo $attendanceToday['present'] ?? 0; ?>
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times text-red-600 dark:text-red-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Absent</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Staff not present</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-red-600 dark:text-red-400">
                            <?php echo $attendanceToday['absent'] ?? 0; ?>
                        </span>
                    </div>

                    <a href="<?php echo APP_URL; ?>/attendance" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>
                        View Full Report
                    </a>
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
                            <p class="text-sm text-gray-500 dark:text-gray-400">HR tasks</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3">
                        <a href="<?php echo APP_URL; ?>/employees/create" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/10 dark:to-blue-800/10 border border-blue-200 dark:border-blue-800/30 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-900/20 dark:hover:to-blue-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-user-plus text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Add Employee</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Create new staff</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="<?php echo APP_URL; ?>/salary" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/10 dark:to-emerald-800/10 border border-emerald-200 dark:border-emerald-800/30 hover:from-emerald-100 hover:to-emerald-200 dark:hover:from-emerald-900/20 dark:hover:to-emerald-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-money-bill text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Salary Slips</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Manage payroll</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="<?php echo APP_URL; ?>/overtime" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/10 dark:to-orange-800/10 border border-orange-200 dark:border-orange-800/30 hover:from-orange-100 hover:to-orange-200 dark:hover:from-orange-900/20 dark:hover:to-orange-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-clock text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Overtime</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Review requests</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-orange-500 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Overtime & Summary -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- Recent Overtime Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Overtime</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pending requests</p>
                        </div>
                    </div>
                    <a href="<?php echo APP_URL; ?>/overtime" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-orange-600 dark:text-orange-400 bg-white dark:bg-gray-800 rounded-lg border border-orange-200 dark:border-orange-700 hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($recentOvertime)): ?>
                    <div class="space-y-4">
                        <?php foreach ($recentOvertime as $overtime): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        <?php echo strtoupper(substr($overtime['employee_name'] ?? 'N/A', 0, 2)); ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($overtime['employee_name'] ?? 'Unknown'); ?></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo date('M j, Y', strtotime($overtime['overtime_date'] ?? 'now')); ?> • 
                                            <?php echo ($overtime['hours'] ?? 0); ?>h
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-orange-100 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-full">
                                        <?php echo ucfirst($overtime['status'] ?? 'pending'); ?>
                                    </span>
                                    <a href="<?php echo APP_URL; ?>/overtime/view/<?php echo $overtime['id'] ?? '#'; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">
                                        Review
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
                        <p class="text-gray-500 dark:text-gray-400">No pending overtime requests</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- HR Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-pie text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">HR Summary</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo date('F Y'); ?> overview</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Staff Statistics -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                                <?php echo number_format($stats['total_staff']); ?>
                            </div>
                            <div class="text-xs text-blue-600 dark:text-blue-400">Active Staff</div>
                        </div>
                        <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl">
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                                <?php 
                                $leaveCount = $stats['staff_on_leave_today'] ?? 0;
                                echo number_format(is_array($leaveCount) ? count($leaveCount) : $leaveCount); 
                                ?>
                            </div>
                            <div class="text-xs text-emerald-600 dark:text-emerald-400">On Leave</div>
                        </div>
                    </div>

                    <!-- Pending Actions -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/10 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-alt text-amber-500 text-sm"></i>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Leave Requests</span>
                            </div>
                            <span class="text-amber-600 dark:text-amber-400 font-semibold"><?php echo $stats['pending_leaves']; ?></span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/10 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock text-orange-500 text-sm"></i>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Overtime Requests</span>
                            </div>
                            <span class="text-orange-600 dark:text-orange-400 font-semibold"><?php echo $stats['pending_overtime']; ?></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="<?php echo APP_URL; ?>/reports" class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-alt mr-2"></i>
                            Generate Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-2xl border border-blue-200 dark:border-blue-800/30 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">HR Administration</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Managing human resources efficiently</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">HR Admin Panel</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Full access to HR operations</p>
            </div>
        </div>
    </div>
</div>
