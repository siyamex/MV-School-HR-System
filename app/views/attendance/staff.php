<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Attendance</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">View your attendance records and statistics.</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="requestCorrection()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Request Correction
                </button>
            </div>
        </div>
    </div>

    <!-- Personal Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Days Present</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $stats['present_days'] ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-times text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Days Absent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $stats['absent_days'] ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Days</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $stats['late_days'] ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hours</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo number_format($stats['total_hours'] ?? 0, 1); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 mb-8 text-white">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <?php if (!empty($employee['profile_photo'])): ?>
                    <img class="h-16 w-16 rounded-full object-cover border-4 border-white/20" 
                         src="<?php echo APP_URL . '/uploads/profiles/' . $employee['profile_photo']; ?>" alt="">
                <?php else: ?>
                    <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xl border-4 border-white/20">
                        <?php 
                        $employeeName = $employee['name'] ?? 'Unknown';
                        $nameParts = explode(' ', $employeeName);
                        if (count($nameParts) >= 2) {
                            echo strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                        } else {
                            echo strtoupper(substr($employeeName, 0, 2));
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($employee['name'] ?? 'Unknown'); ?></h2>
                <p class="text-blue-100"><?php echo htmlspecialchars($employee['employee_id'] ?? ''); ?> • <?php echo htmlspecialchars($employee['department_name'] ?? ''); ?></p>
                <p class="text-blue-100"><?php echo htmlspecialchars($employee['designation'] ?? ''); ?></p>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-4">
            <form method="GET" class="flex items-center gap-4">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Range:</label>
                
                <input type="date" 
                       name="start_date" 
                       value="<?php echo htmlspecialchars($startDate); ?>"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                
                <span class="text-gray-500 dark:text-gray-400">to</span>
                
                <input type="date" 
                       name="end_date" 
                       value="<?php echo htmlspecialchars($endDate); ?>"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                
                <a href="<?php echo APP_URL; ?>/attendance" 
                   class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
            </form>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Attendance Records</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Check In
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Check Out
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Break Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Hours
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Remarks
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (empty($attendance)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Attendance Records</h3>
                                    <p class="text-gray-500 dark:text-gray-400">No attendance records found for the selected date range.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($attendance as $record): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo date('M d, Y', strtotime($record['attendance_date'])); ?>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo date('l', strtotime($record['attendance_date'])); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['break_time'] ? $record['break_time'] . ' min' : '-'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo $record['total_hours'] ? number_format($record['total_hours'], 1) . 'h' : '-'; ?>
                                    </div>
                                    <?php if ($record['overtime_hours'] > 0): ?>
                                        <div class="text-sm text-blue-600 dark:text-blue-400">
                                            +<?php echo number_format($record['overtime_hours'], 1); ?>h OT
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColors = [
                                        'present' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        'absent' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        'late' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'half_day' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
                                    ];
                                    $statusColor = $statusColors[$record['status']] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusColor; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $record['status'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">
                                        <?php echo $record['remarks'] ? htmlspecialchars($record['remarks']) : '-'; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function requestCorrection() {
    // This could open a modal or redirect to a correction request form
    alert('Attendance correction request feature coming soon!');
}
</script>
