<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Super Admin Dashboard
                </h1>
                <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">
                    Welcome back! Here's what's happening in your organization today.
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

        <!-- Departments -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-2xl p-6 border border-emerald-200 dark:border-emerald-800/50 hover:shadow-lg transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                        <?php echo number_format($stats['departments']); ?>
                    </div>
                    <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Departments</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Active divisions</div>
                </div>
                <div class="flex-shrink-0 w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-2xl text-emerald-500"></i>
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
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Leave Requests</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Latest employee leave applications</p>
                            </div>
                        </div>
                        <a href="<?php echo APP_URL; ?>/leaves" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <span>View All</span>
                            <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <?php if (!empty($recentLeaves)): ?>
                        <div class="space-y-4">
                            <?php foreach ($recentLeaves as $index => $leave): ?>
                            <div class="flex items-center p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    <?php echo strtoupper(substr($leave['employee_name'], 0, 2)); ?>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo htmlspecialchars($leave['employee_name']); ?>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    <?php echo htmlspecialchars($leave['leave_type_name']); ?>
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <?php echo date('M d', strtotime($leave['start_date'])); ?> - <?php echo date('M d', strtotime($leave['end_date'])); ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/20 text-amber-800 dark:text-amber-400 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                            <div class="flex space-x-1">
                                                <button class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/30 transition-colors flex items-center justify-center" title="Approve">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                                <button class="w-8 h-8 bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/30 transition-colors flex items-center justify-center" title="Reject">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-check text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No pending leave requests</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">All leave requests have been processed</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- System Status & Quick Actions -->
        <div class="space-y-6">
            <!-- System Status -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-server text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Status</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Monitor system health</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Attendance Sync -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sync-alt text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Attendance Sync</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <?php if ($lastAttendanceSync): ?>
                                        Last: <?php echo date('M d, H:i', strtotime($lastAttendanceSync)); ?>
                                    <?php else: ?>
                                        Never synced
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <a href="<?php echo APP_URL; ?>/attendance/sync" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 rounded-lg border border-blue-200 dark:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            <i class="fas fa-sync mr-1"></i>
                            Sync
                        </a>
                    </div>

                    <!-- Today's Attendance -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Today's Attendance</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo $attendanceToday['present'] ?? 0; ?> present, 
                                    <?php echo $attendanceToday['absent'] ?? 0; ?> absent
                                </p>
                            </div>
                        </div>
                        <a href="<?php echo APP_URL; ?>/attendance" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 dark:hover:text-emerald-300 text-xs font-medium">View</a>
                    </div>
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
                            <p class="text-sm text-gray-500 dark:text-gray-400">Common tasks</p>
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
                                <p class="text-xs text-gray-500 dark:text-gray-400">Create new staff member</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="<?php echo APP_URL; ?>/departments/create" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/10 dark:to-emerald-800/10 border border-emerald-200 dark:border-emerald-800/30 hover:from-emerald-100 hover:to-emerald-200 dark:hover:from-emerald-900/20 dark:hover:to-emerald-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-building text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">New Department</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Create department</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                        </a>

                        <a href="<?php echo APP_URL; ?>/settings" class="flex items-center p-3 rounded-xl bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/10 dark:to-purple-800/10 border border-purple-200 dark:border-purple-800/30 hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-900/20 dark:hover:to-purple-800/20 transition-all duration-200 group">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white group-hover:scale-105 transition-transform">
                                <i class="fas fa-cog text-sm"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Settings</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">System configuration</p>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 ml-auto group-hover:text-purple-500 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Overview Chart Placeholder -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Overview</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo date('F Y'); ?> statistics</p>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-3xl text-indigo-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Analytics Dashboard</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                        Advanced analytics and reporting will be available here.<br>
                        Track attendance trends, leave patterns, and workforce insights.
                    </p>
                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                        <i class="fas fa-chart-pie mr-2"></i>
                        View Detailed Analytics
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent System Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-rose-600 dark:text-rose-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">System events and logs</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Sample Activity Items -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-blue-600 dark:text-blue-400 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">System initialized</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Super admin account created • <?php echo date('M j, Y \a\t H:i'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 dark:bg-emerald-900/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-database text-emerald-600 dark:text-emerald-400 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">Database configured</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                All tables created successfully • <?php echo date('M j, Y \a\t H:i'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-cogs text-purple-600 dark:text-purple-400 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                <span class="font-medium">System ready</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                HR Management System is operational • <?php echo date('M j, Y \a\t H:i'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Activity logs will show user actions and system events once the system is actively used.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-2xl border border-blue-200 dark:border-blue-800/30 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-school text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo APP_NAME; ?></h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Complete HR Management Solution for Educational Institutions</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Version 1.0</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Built with PHP MVC & TailwindCSS</p>
            </div>
        </div>
    </div>
</div>
