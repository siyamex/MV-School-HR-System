<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Salary Slip</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Create or update employee salary slip records.</p>
            </div>
            <a href="<?php echo APP_URL; ?>/salary-slips" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Salary Slips
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Salary Slip Details</h3>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            <?php echo CSRF::token(); ?>
            
            <!-- Employee and Period Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Employee <span class="text-red-500">*</span>
                    </label>
                    <select name="employee_id" id="employee_id" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Select Employee</option>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?php echo $employee['id']; ?>" 
                                        data-salary="<?php echo $employee['salary'] ?? 0; ?>">
                                    <?php echo htmlspecialchars($employee['name'] . ' (' . $employee['employee_id'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Month <span class="text-red-500">*</span>
                    </label>
                    <select name="month" id="month" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <?php 
                        $months = [
                            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                        ];
                        $currentMonth = date('m');
                        foreach ($months as $num => $name): 
                        ?>
                            <option value="<?php echo $num; ?>" <?php echo ($currentMonth == $num) ? 'selected' : ''; ?>>
                                <?php echo $name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Year <span class="text-red-500">*</span>
                    </label>
                    <select name="year" id="year" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <?php 
                        $currentYear = date('Y');
                        for ($year = $currentYear; $year >= $currentYear - 2; $year--): 
                        ?>
                            <option value="<?php echo $year; ?>" <?php echo ($currentYear == $year) ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Salary Breakdown</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Basic Salary <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" name="basic_salary" id="basic_salary" step="0.01" required
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label for="allowances" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Allowances
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" name="allowances" id="allowances" step="0.01" value="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label for="overtime_pay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Overtime Pay
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" name="overtime_pay" id="overtime_pay" step="0.01" value="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Gross Salary
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="text" id="gross_salary" readonly
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-600 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deductions -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Deductions</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Other Deductions
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" name="deductions" id="deductions" step="0.01" value="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label for="tax" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tax
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="number" name="tax" id="tax" step="0.01" value="0"
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Net Salary
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">$</span>
                            </div>
                            <input type="text" id="net_salary" readonly
                                   class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-600 dark:text-white font-bold text-green-600 dark:text-green-400">
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Upload PDF</h4>
                
                <div class="flex items-center justify-center w-full">
                    <label for="salary_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF files only (MAX. 10MB)</p>
                        </div>
                        <input id="salary_file" name="salary_file" type="file" accept=".pdf" class="hidden">
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="<?php echo APP_URL; ?>/salary-slips" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Salary Slip
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeSelect = document.getElementById('employee_id');
    const basicSalaryInput = document.getElementById('basic_salary');
    const allowancesInput = document.getElementById('allowances');
    const overtimePayInput = document.getElementById('overtime_pay');
    const deductionsInput = document.getElementById('deductions');
    const taxInput = document.getElementById('tax');
    const grossSalaryInput = document.getElementById('gross_salary');
    const netSalaryInput = document.getElementById('net_salary');
    
    // Auto-fill basic salary when employee is selected
    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const salary = selectedOption.getAttribute('data-salary') || 0;
        basicSalaryInput.value = salary;
        calculateSalaries();
    });
    
    // Calculate salaries when inputs change
    [basicSalaryInput, allowancesInput, overtimePayInput, deductionsInput, taxInput].forEach(input => {
        input.addEventListener('input', calculateSalaries);
    });
    
    function calculateSalaries() {
        const basic = parseFloat(basicSalaryInput.value) || 0;
        const allowances = parseFloat(allowancesInput.value) || 0;
        const overtime = parseFloat(overtimePayInput.value) || 0;
        const deductions = parseFloat(deductionsInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        
        const gross = basic + allowances + overtime;
        const net = gross - deductions - tax;
        
        grossSalaryInput.value = gross.toFixed(2);
        netSalaryInput.value = net.toFixed(2);
    }
    
    // File upload preview
    const fileInput = document.getElementById('salary_file');
    const fileLabel = fileInput.parentElement.querySelector('div');
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            fileLabel.innerHTML = `
                <i class="fas fa-file-pdf text-3xl text-red-400 mb-3"></i>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">${fileName}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Click to change file</p>
            `;
        }
    });
    
    // Initial calculation
    calculateSalaries();
});
</script>
