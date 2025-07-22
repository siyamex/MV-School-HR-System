<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Apply for Leave</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Submit your leave request for approval.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo APP_URL; ?>/leaves" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Leaves
                    </a>
                </div>
            </div>
        </div>

        <!-- Leave Balance Summary -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-green-500"></i>
                        Leave Balance Summary
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($leaveTypes as $leaveType): 
                            $balance = $leaveBalances[$leaveType['id']] ?? ['allowed' => 0, 'used' => 0, 'remaining' => 0];
                            $usedPercentage = $balance['allowed'] > 0 ? ($balance['used'] / $balance['allowed']) * 100 : 0;
                        ?>
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm">
                                        <?php echo htmlspecialchars($leaveType['name']); ?>
                                    </h4>
                                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        <?php echo $balance['remaining']; ?> left
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-500 rounded-full h-2 mb-2">
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300" style="width: <?php echo min($usedPercentage, 100); ?>%"></div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span><?php echo $balance['used']; ?> used</span>
                                    <span><?php echo $balance['allowed']; ?> total</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Application Form -->
        <form action="<?php echo APP_URL; ?>/leaves/store" method="POST" class="space-y-8">
            <?php echo CSRF::generateToken(); ?>
            
            <!-- Leave Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-calendar-alt mr-3 text-blue-500"></i>
                        Leave Details
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Leave Type -->
                        <div>
                            <label for="leave_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Leave Type <span class="text-red-500">*</span>
                            </label>
                            <select id="leave_type_id" name="leave_type_id" required onchange="updateBalance()"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select Leave Type</option>
                                <?php foreach ($leaveTypes as $leaveType): ?>
                                    <option value="<?php echo $leaveType['id']; ?>" data-allowed="<?php echo $leaveBalances[$leaveType['id']]['allowed'] ?? 0; ?>" data-remaining="<?php echo $leaveBalances[$leaveType['id']]['remaining'] ?? 0; ?>">
                                        <?php echo htmlspecialchars($leaveType['name']); ?> (<?php echo ($leaveBalances[$leaveType['id']]['remaining'] ?? 0); ?> days remaining)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="balance_info" class="mt-2 text-sm text-gray-500 dark:text-gray-400" style="display: none;">
                                <span id="remaining_days"></span> days remaining
                            </div>
                        </div>

                        <!-- Days Requested -->
                        <div>
                            <label for="days_requested" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Days Requested <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="days_requested" name="days_requested" min="1" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="Enter number of days">
                            <div id="days_warning" class="mt-1 text-sm text-red-500" style="display: none;">
                                Warning: This exceeds your available balance!
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" required onchange="calculateDays()"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="end_date" name="end_date" required onchange="calculateDays()"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="mt-6">
                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                  placeholder="Please provide detailed reason for your leave request..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Employee Information (Read Only) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-user mr-3 text-purple-500"></i>
                        Employee Information
                    </h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Employee Name
                            </label>
                            <div class="px-4 py-3 bg-gray-100 dark:bg-gray-600 rounded-lg text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($employee['name'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Employee ID
                            </label>
                            <div class="px-4 py-3 bg-gray-100 dark:bg-gray-600 rounded-lg text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($employee['employee_id'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Department
                            </label>
                            <div class="px-4 py-3 bg-gray-100 dark:bg-gray-600 rounded-lg text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($employee['department_name'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Designation
                            </label>
                            <div class="px-4 py-3 bg-gray-100 dark:bg-gray-600 rounded-lg text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($employee['designation'] ?? 'N/A'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="<?php echo APP_URL; ?>/leaves" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Leave Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Leave balance and date calculation functions
function updateBalance() {
    const select = document.getElementById('leave_type_id');
    const balanceInfo = document.getElementById('balance_info');
    const remainingDays = document.getElementById('remaining_days');
    const daysInput = document.getElementById('days_requested');
    
    if (select.value) {
        const option = select.options[select.selectedIndex];
        const remaining = option.getAttribute('data-remaining');
        remainingDays.textContent = remaining;
        balanceInfo.style.display = 'block';
        
        // Update max attribute for days input
        daysInput.setAttribute('max', remaining);
        
        // Validate current input
        validateDaysInput();
    } else {
        balanceInfo.style.display = 'none';
        daysInput.removeAttribute('max');
    }
}

function validateDaysInput() {
    const daysInput = document.getElementById('days_requested');
    const warning = document.getElementById('days_warning');
    const select = document.getElementById('leave_type_id');
    
    if (select.value && daysInput.value) {
        const option = select.options[select.selectedIndex];
        const remaining = parseInt(option.getAttribute('data-remaining'));
        const requested = parseInt(daysInput.value);
        
        if (requested > remaining) {
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
    }
}

function calculateDays() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const daysInput = document.getElementById('days_requested');
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end >= start) {
            // Calculate business days (excluding weekends)
            let count = 0;
            let current = new Date(start);
            
            while (current <= end) {
                const day = current.getDay();
                if (day !== 0 && day !== 6) { // Not Sunday (0) or Saturday (6)
                    count++;
                }
                current.setDate(current.getDate() + 1);
            }
            
            daysInput.value = count;
            validateDaysInput();
        } else {
            // End date is before start date
            document.getElementById('end_date').value = '';
            alert('End date must be after start date');
        }
    }
}

// Event listeners
document.getElementById('days_requested').addEventListener('input', validateDaysInput);
document.getElementById('start_date').addEventListener('change', function() {
    // Update minimum end date to start date
    document.getElementById('end_date').setAttribute('min', this.value);
    calculateDays();
});

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const daysInput = document.getElementById('days_requested');
    const select = document.getElementById('leave_type_id');
    
    if (select.value && daysInput.value) {
        const option = select.options[select.selectedIndex];
        const remaining = parseInt(option.getAttribute('data-remaining'));
        const requested = parseInt(daysInput.value);
        
        if (requested > remaining) {
            e.preventDefault();
            alert('Cannot request more days than your available balance!');
            return false;
        }
    }
    
    // Validate dates
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (endDate < startDate) {
        e.preventDefault();
        alert('End date must be after start date!');
        return false;
    }
});
</script>
