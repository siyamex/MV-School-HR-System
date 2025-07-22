<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="<?php echo APP_URL; ?>/attendance" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manual Attendance Entry</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Add or edit attendance records manually.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance Details</h3>
        </div>
        
        <div class="p-6">
            <form method="POST" action="<?php echo APP_URL; ?>/attendance/manual/store" id="attendanceForm">
                <?php echo CSRF::generateToken(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee Selection -->
                    <div class="md:col-span-2">
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Employee <span class="text-red-500">*</span>
                        </label>
                        <select id="employee_id" 
                                name="employee_id" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select an employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo $employee['id']; ?>">
                                    <?php echo htmlspecialchars($employee['name']); ?> (<?php echo htmlspecialchars($employee['employee_id']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Attendance Date -->
                    <div>
                        <label for="attendance_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="attendance_date" 
                               name="attendance_date" 
                               max="<?php echo date('Y-m-d'); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required
                                onchange="toggleTimeFields()"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                        </select>
                    </div>
                    
                    <!-- Check In Time -->
                    <div id="check_in_group">
                        <label for="check_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Check In Time
                        </label>
                        <input type="time" 
                               id="check_in" 
                               name="check_in"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Check Out Time -->
                    <div id="check_out_group">
                        <label for="check_out" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Check Out Time
                        </label>
                        <input type="time" 
                               id="check_out" 
                               name="check_out"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Break Time -->
                    <div id="break_time_group">
                        <label for="break_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Break Time (minutes)
                        </label>
                        <input type="number" 
                               id="break_time" 
                               name="break_time"
                               min="0"
                               max="480"
                               value="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Hours Calculation Display -->
                    <div class="md:col-span-2" id="hours_display" style="display: none;">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calculator text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        <strong>Working Hours:</strong> <span id="total_hours">0.0 hours</span>
                                    </p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                        Calculated automatically based on check-in, check-out, and break time.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Remarks -->
                <div class="mt-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Remarks
                    </label>
                    <textarea id="remarks" 
                              name="remarks" 
                              rows="3" 
                              placeholder="Additional notes or comments about this attendance record..."
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                
                <!-- Guidelines -->
                <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Manual Entry Guidelines
                            </h4>
                            <ul class="mt-2 text-sm text-yellow-700 dark:text-yellow-300 list-disc list-inside space-y-1">
                                <li>Manual entries can only be made for past dates, not future dates.</li>
                                <li>For absent status, check-in and check-out times are not required.</li>
                                <li>Break time should be entered in minutes (e.g., 60 for 1 hour).</li>
                                <li>Total working hours will be calculated automatically.</li>
                                <li>Provide clear remarks for manual entries for audit purposes.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="<?php echo APP_URL; ?>/attendance" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const breakTimeInput = document.getElementById('break_time');
    const totalHoursDisplay = document.getElementById('total_hours');
    const hoursDisplayDiv = document.getElementById('hours_display');
    
    function calculateHours() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const breakTime = parseInt(breakTimeInput.value) || 0;
        
        if (checkIn && checkOut) {
            const start = new Date(`2000-01-01T${checkIn}`);
            const end = new Date(`2000-01-01T${checkOut}`);
            
            if (end > start) {
                const diffMs = end - start;
                const totalMinutes = (diffMs / (1000 * 60)) - breakTime;
                const hours = Math.max(0, totalMinutes / 60);
                
                totalHoursDisplay.textContent = `${hours.toFixed(1)} hours`;
                hoursDisplayDiv.style.display = 'block';
            } else {
                hoursDisplayDiv.style.display = 'none';
            }
        } else {
            hoursDisplayDiv.style.display = 'none';
        }
    }
    
    function toggleTimeFields() {
        const status = document.getElementById('status').value;
        const timeGroups = ['check_in_group', 'check_out_group', 'break_time_group'];
        
        if (status === 'absent') {
            timeGroups.forEach(group => {
                document.getElementById(group).style.display = 'none';
            });
            hoursDisplayDiv.style.display = 'none';
            
            // Clear time values for absent status
            checkInInput.value = '';
            checkOutInput.value = '';
            breakTimeInput.value = '0';
        } else {
            timeGroups.forEach(group => {
                document.getElementById(group).style.display = 'block';
            });
        }
    }
    
    // Make toggleTimeFields globally available
    window.toggleTimeFields = toggleTimeFields;
    
    checkInInput.addEventListener('change', calculateHours);
    checkOutInput.addEventListener('change', calculateHours);
    breakTimeInput.addEventListener('input', calculateHours);
    
    // Form validation
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const status = document.getElementById('status').value;
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const attendanceDate = document.getElementById('attendance_date').value;
        const employeeId = document.getElementById('employee_id').value;
        
        if (!employeeId || !attendanceDate || !status) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        // Check if attendance date is not in the future
        const selectedDate = new Date(attendanceDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            e.preventDefault();
            alert('Attendance date cannot be in the future.');
            return;
        }
        
        // Validate times for non-absent status
        if (status !== 'absent') {
            if (!checkIn) {
                e.preventDefault();
                alert('Check-in time is required for non-absent status.');
                return;
            }
            
            if (checkOut && new Date(`2000-01-01T${checkOut}`) <= new Date(`2000-01-01T${checkIn}`)) {
                e.preventDefault();
                alert('Check-out time must be after check-in time.');
                return;
            }
        }
        
        // Confirm submission
        if (!confirm('Are you sure you want to save this attendance record?')) {
            e.preventDefault();
        }
    });
    
    // Set default date to today
    document.getElementById('attendance_date').value = new Date().toISOString().split('T')[0];
});
</script>
