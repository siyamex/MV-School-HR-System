<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Bulk Upload Salary Slips</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Upload salary slips for multiple employees using CSV file.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo APP_URL; ?>/salary-slips/download-sample" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Download Sample
                </a>
                <a href="<?php echo APP_URL; ?>/salary-slips" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Salary Slips
                </a>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">Instructions for Bulk Upload</h3>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                    <li class="flex items-start">
                        <span class="inline-block w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Download the sample CSV file to see the required format
                    </li>
                    <li class="flex items-start">
                        <span class="inline-block w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Required columns: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded text-xs">employee_id</code>, <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded text-xs">basic_salary</code>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-block w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Optional columns: allowances, overtime_pay, deductions, tax, remarks
                    </li>
                    <li class="flex items-start">
                        <span class="inline-block w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Use employee_id (not employee name) to identify employees
                    </li>
                    <li class="flex items-start">
                        <span class="inline-block w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                        Existing records for the same month/year will be updated
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload CSV File</h3>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            <?php echo CSRF::token(); ?>
            
            <!-- Period Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

            <!-- File Upload -->
            <div>
                <label for="bulk_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    CSV File <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="bulk_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-file-csv text-3xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">CSV files only (MAX. 5MB)</p>
                        </div>
                        <input id="bulk_file" name="bulk_file" type="file" accept=".csv" class="hidden" required>
                    </label>
                </div>
            </div>

            <!-- CSV Format Example -->
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Expected CSV Format:</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-600">
                                <th class="px-2 py-1 text-left font-medium">employee_id</th>
                                <th class="px-2 py-1 text-left font-medium">basic_salary</th>
                                <th class="px-2 py-1 text-left font-medium">allowances</th>
                                <th class="px-2 py-1 text-left font-medium">overtime_pay</th>
                                <th class="px-2 py-1 text-left font-medium">deductions</th>
                                <th class="px-2 py-1 text-left font-medium">tax</th>
                                <th class="px-2 py-1 text-left font-medium">remarks</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-400">
                            <tr>
                                <td class="px-2 py-1">EMP001</td>
                                <td class="px-2 py-1">5000.00</td>
                                <td class="px-2 py-1">500.00</td>
                                <td class="px-2 py-1">200.00</td>
                                <td class="px-2 py-1">100.00</td>
                                <td class="px-2 py-1">150.00</td>
                                <td class="px-2 py-1">July salary</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1">EMP002</td>
                                <td class="px-2 py-1">6000.00</td>
                                <td class="px-2 py-1">600.00</td>
                                <td class="px-2 py-1">0.00</td>
                                <td class="px-2 py-1">120.00</td>
                                <td class="px-2 py-1">180.00</td>
                                <td class="px-2 py-1">July salary</td>
                            </tr>
                        </tbody>
                    </table>
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
                    <i class="fas fa-upload mr-2"></i>
                    Upload CSV File
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('bulk_file');
    const fileLabel = fileInput.parentElement.querySelector('div');
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // MB
            
            fileLabel.innerHTML = `
                <i class="fas fa-file-csv text-3xl text-green-400 mb-3"></i>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">${fileName}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Size: ${fileSize} MB - Click to change</p>
            `;
        }
    });
});
</script>
