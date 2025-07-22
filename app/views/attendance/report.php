<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Reports</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Generate and view detailed attendance reports.</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportReport()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Export PDF
                </button>
                <button onclick="exportExcel()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Report Filters</h3>
        </div>
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                        <select name="report_type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="summary" <?php echo ($reportType ?? '') === 'summary' ? 'selected' : ''; ?>>Summary Report</option>
                            <option value="detailed" <?php echo ($reportType ?? '') === 'detailed' ? 'selected' : ''; ?>>Detailed Report</option>
                            <option value="monthly" <?php echo ($reportType ?? '') === 'monthly' ? 'selected' : ''; ?>>Monthly Report</option>
                            <option value="department" <?php echo ($reportType ?? '') === 'department' ? 'selected' : ''; ?>>Department Report</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <select name="department_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">All Departments</option>
                            <?php if (!empty($departments)): ?>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['id']; ?>" <?php echo ($selectedDepartment ?? '') == $department['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($department['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" 
                               name="start_date" 
                               value="<?php echo htmlspecialchars($startDate ?? ''); ?>"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" 
                               name="end_date" 
                               value="<?php echo htmlspecialchars($endDate ?? ''); ?>"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Generate Report
                    </button>
                    <a href="<?php echo APP_URL; ?>/attendance/report" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Summary Stats -->
    <?php if (!empty($reportData)): ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Employees</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $reportStats['total_employees'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Attendance</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo number_format($reportStats['avg_attendance'] ?? 0, 1); ?>%</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hours</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo number_format($reportStats['total_hours'] ?? 0, 1); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Instances</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo $reportStats['late_count'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Report Data -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <?php 
                $reportTypes = [
                    'summary' => 'Summary Report',
                    'detailed' => 'Detailed Report',
                    'monthly' => 'Monthly Report',
                    'department' => 'Department Report'
                ];
                echo $reportTypes[$reportType ?? 'summary'] ?? 'Attendance Report';
                ?>
            </h3>
        </div>
        
        <?php if (!empty($reportData)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <?php if (($reportType ?? 'summary') === 'summary'): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Present Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Absent Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Late Days</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Hours</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attendance %</th>
                            <?php else: ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check In</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Check Out</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($reportData as $record): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <?php if (($reportType ?? 'summary') === 'summary'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo htmlspecialchars($record['employee_name'] ?? ''); ?>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo htmlspecialchars($record['employee_id'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($record['department_name'] ?? ''); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['present_days'] ?? 0; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['absent_days'] ?? 0; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['late_days'] ?? 0; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo number_format($record['total_hours'] ?? 0, 1); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $percentage = $record['attendance_percentage'] ?? 0; ?>
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">
                                                <?php echo number_format($percentage, 1); ?>%
                                            </span>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo min(100, $percentage); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo htmlspecialchars($record['employee_name'] ?? ''); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo date('M d, Y', strtotime($record['attendance_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['check_in'] ? date('H:i', strtotime($record['check_in'])) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['check_out'] ? date('H:i', strtotime($record['check_out'])) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <?php echo $record['total_hours'] ? number_format($record['total_hours'], 1) . 'h' : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $statusColors = [
                                            'present' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                            'absent' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                            'late' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'half_day' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
                                        ];
                                        $statusColor = $statusColors[$record['status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusColor; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $record['status'])); ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <i class="fas fa-chart-bar text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Report Data</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Select your criteria and generate a report to view attendance data.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function exportReport() {
    // Get current URL parameters for the report
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.location.href = `<?php echo APP_URL; ?>/attendance/report/export?${params.toString()}`;
}

function exportExcel() {
    // Get current URL parameters for the report
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = `<?php echo APP_URL; ?>/attendance/report/export?${params.toString()}`;
}

// Set default dates
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    if (!startDateInput.value) {
        const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
        startDateInput.value = firstDay.toISOString().split('T')[0];
    }
    
    if (!endDateInput.value) {
        endDateInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
