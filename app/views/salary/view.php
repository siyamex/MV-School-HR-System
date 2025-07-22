<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Salary Slip Details</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    <?php 
                    $monthName = date('F', mktime(0, 0, 0, $salarySlip['month'], 1));
                    echo $monthName . ' ' . $salarySlip['year'] . ' - ' . htmlspecialchars($salarySlip['employee_name']); 
                    ?>
                </p>
            </div>
            <div class="flex space-x-3">
                <?php if ($salarySlip['file_path']): ?>
                    <a href="<?php echo APP_URL; ?>/salary-slips/download/<?php echo $salarySlip['id']; ?>" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download PDF
                    </a>
                <?php endif; ?>
                <a href="<?php echo APP_URL; ?>/salary-slips" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Salary Slip Details -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-8 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">HR School Management System</h2>
                    <p class="text-blue-100 mt-1">Salary Slip</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-100">Period</p>
                    <p class="text-xl font-semibold">
                        <?php echo $monthName . ' ' . $salarySlip['year']; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Employee Information -->
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee Name:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($salarySlip['employee_name']); ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee ID:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($salarySlip['employee_id']); ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Department:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($salarySlip['department_name'] ?? 'N/A'); ?>
                        </span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Designation:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($salarySlip['designation'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo $salarySlip['hire_date'] ? date('M d, Y', strtotime($salarySlip['hire_date'])) : 'N/A'; ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Generated:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            <?php echo date('M d, Y', strtotime($salarySlip['generated_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Earnings</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Basic Salary</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        $<?php echo number_format($salarySlip['basic_salary'], 2); ?>
                    </span>
                </div>
                <?php if ($salarySlip['allowances'] > 0): ?>
                    <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-gray-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allowances</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            $<?php echo number_format($salarySlip['allowances'], 2); ?>
                        </span>
                    </div>
                <?php endif; ?>
                <?php if ($salarySlip['overtime_pay'] > 0): ?>
                    <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-gray-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overtime Pay</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            $<?php echo number_format($salarySlip['overtime_pay'], 2); ?>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-200 dark:border-gray-600">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Gross Salary</span>
                    <span class="text-base font-bold text-green-600 dark:text-green-400">
                        $<?php echo number_format($salarySlip['gross_salary'], 2); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Deductions</h3>
            <div class="space-y-3">
                <?php if ($salarySlip['deductions'] > 0): ?>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Other Deductions</span>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                            -$<?php echo number_format($salarySlip['deductions'], 2); ?>
                        </span>
                    </div>
                <?php endif; ?>
                <?php if ($salarySlip['tax'] > 0): ?>
                    <div class="flex justify-between items-center py-2 <?php echo $salarySlip['deductions'] > 0 ? 'border-t border-gray-100 dark:border-gray-600' : ''; ?>">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tax</span>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                            -$<?php echo number_format($salarySlip['tax'], 2); ?>
                        </span>
                    </div>
                <?php endif; ?>
                <?php if ($salarySlip['deductions'] == 0 && $salarySlip['tax'] == 0): ?>
                    <div class="text-sm text-gray-500 dark:text-gray-400 py-2">
                        No deductions applied
                    </div>
                <?php endif; ?>
                <div class="flex justify-between items-center py-3 border-t-2 border-gray-200 dark:border-gray-600">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Total Deductions</span>
                    <span class="text-base font-bold text-red-600 dark:text-red-400">
                        -$<?php echo number_format($salarySlip['deductions'] + $salarySlip['tax'], 2); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Net Salary -->
        <div class="px-6 py-6 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Net Salary</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Amount to be paid</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        $<?php echo number_format($salarySlip['net_salary'], 2); ?>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <?php echo date('M d, Y', strtotime($salarySlip['generated_at'])); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- PDF Attachment -->
        <?php if ($salarySlip['file_path']): ?>
            <div class="px-6 py-4 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-red-500 text-lg mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                PDF Salary Slip Available
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Official salary slip document
                            </p>
                        </div>
                    </div>
                    <a href="<?php echo APP_URL; ?>/salary-slips/download/<?php echo $salarySlip['id']; ?>" 
                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                        <i class="fas fa-download mr-1"></i>
                        Download
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</div>

<script>
function printSalarySlip() {
    window.print();
}

// Add print button functionality
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.flex.items-center.justify-between');
    if (header) {
        const printButton = document.createElement('button');
        printButton.innerHTML = '<i class="fas fa-print mr-2"></i>Print';
        printButton.className = 'inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors mr-3';
        printButton.onclick = printSalarySlip;
        
        const buttonContainer = header.lastElementChild;
        buttonContainer.insertBefore(printButton, buttonContainer.firstChild);
    }
});
</script>
