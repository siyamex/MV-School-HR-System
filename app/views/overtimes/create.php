<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="<?php echo APP_URL; ?>/overtime" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">New Overtime Request</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Submit a new overtime request for approval.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Overtime Request Details</h3>
        </div>
        
        <div class="p-6">
            <form method="POST" action="<?php echo APP_URL; ?>/overtime/store" id="overtimeForm">
                <?php echo CSRF::generateToken(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Overtime Date -->
                    <div>
                        <label for="overtime_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Overtime Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="overtime_date" 
                               name="overtime_date" 
                               min="<?php echo date('Y-m-d'); ?>"
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Department Info (Read-only for reference) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Department
                        </label>
                        <div class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400">
                            <?php 
                            // This would be populated from the controller with employee department info
                            echo "Your Department"; 
                            ?>
                        </div>
                    </div>
                    
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="start_time" 
                               name="start_time" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="end_time" 
                               name="end_time" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <!-- Hours Display -->
                    <div class="md:col-span-2">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        <strong>Total Hours:</strong> <span id="totalHours">0.0 hours</span>
                                    </p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                        Hours will be calculated automatically based on start and end time.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reason -->
                <div class="mt-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Overtime <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" 
                              name="reason" 
                              rows="4" 
                              required
                              placeholder="Please provide a detailed reason for this overtime request..."
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"></textarea>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Provide clear justification for the overtime work required.
                    </p>
                </div>
                
                <!-- Guidelines -->
                <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Overtime Request Guidelines
                            </h4>
                            <ul class="mt-2 text-sm text-yellow-700 dark:text-yellow-300 list-disc list-inside space-y-1">
                                <li>Overtime requests must be submitted at least 24 hours in advance.</li>
                                <li>Maximum 4 hours of overtime per day allowed.</li>
                                <li>Provide detailed justification for overtime work.</li>
                                <li>Approval is subject to supervisor/manager discretion.</li>
                                <li>Overtime rates apply as per company policy.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="<?php echo APP_URL; ?>/overtime" 
                       class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const totalHoursDisplay = document.getElementById('totalHours');
    const submitBtn = document.getElementById('submitBtn');
    
    function calculateHours() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        
        if (startTime && endTime) {
            const start = new Date(`2000-01-01T${startTime}`);
            const end = new Date(`2000-01-01T${endTime}`);
            
            if (end > start) {
                const diffMs = end - start;
                const hours = diffMs / (1000 * 60 * 60);
                totalHoursDisplay.textContent = `${hours.toFixed(1)} hours`;
                
                // Check if exceeds maximum (4 hours)
                if (hours > 4) {
                    totalHoursDisplay.innerHTML = `<span class="text-red-600">${hours.toFixed(1)} hours (Exceeds 4-hour limit)</span>`;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Exceeds Limit';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Request';
                }
            } else {
                totalHoursDisplay.textContent = '0.0 hours';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Invalid Time';
            }
        } else {
            totalHoursDisplay.textContent = '0.0 hours';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Request';
        }
    }
    
    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);
    
    // Form validation
    document.getElementById('overtimeForm').addEventListener('submit', function(e) {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const reason = document.getElementById('reason').value.trim();
        const overtimeDate = document.getElementById('overtime_date').value;
        
        if (!overtimeDate || !startTime || !endTime || !reason) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        if (new Date(`2000-01-01T${endTime}`) <= new Date(`2000-01-01T${startTime}`)) {
            e.preventDefault();
            alert('End time must be after start time.');
            return;
        }
        
        const hours = (new Date(`2000-01-01T${endTime}`) - new Date(`2000-01-01T${startTime}`)) / (1000 * 60 * 60);
        if (hours > 4) {
            e.preventDefault();
            alert('Maximum 4 hours of overtime per day is allowed.');
            return;
        }
        
        if (reason.length < 10) {
            e.preventDefault();
            alert('Please provide a more detailed reason (at least 10 characters).');
            return;
        }
        
        // Check if date is not in the past
        const selectedDate = new Date(overtimeDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            e.preventDefault();
            alert('Overtime date cannot be in the past.');
            return;
        }
        
        // Confirm submission
        if (!confirm('Are you sure you want to submit this overtime request?')) {
            e.preventDefault();
        }
    });
});
</script>
