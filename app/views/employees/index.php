<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Employee Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your organization's employees and their information</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Import/Export Buttons -->
                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export Excel
                </button>
                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>
                    Import Excel
                </button>
                <a href="<?php echo APP_URL; ?>/employees/create" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Employee
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Employees</label>
                    <div class="relative">
                        <input type="text" id="employee-search" placeholder="Search by name, email, employee ID..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                    <select id="department-filter" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Departments</option>
                        <?php if (!empty($departments)): ?>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>"><?php echo htmlspecialchars($department['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select id="status-filter" class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        <?php echo isset($stats['total']) ? $stats['total'] : 0; ?>
                    </div>
                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Employees</div>
                </div>
                <i class="fas fa-users text-2xl text-blue-500/50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-xl p-6 border border-emerald-200 dark:border-emerald-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        <?php echo isset($stats['active']) ? $stats['active'] : 0; ?>
                    </div>
                    <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Active</div>
                </div>
                <i class="fas fa-user-check text-2xl text-emerald-500/50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-xl p-6 border border-amber-200 dark:border-amber-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                        <?php echo isset($stats['inactive']) ? $stats['inactive'] : 0; ?>
                    </div>
                    <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Inactive</div>
                </div>
                <i class="fas fa-user-clock text-2xl text-amber-500/50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-6 border border-red-200 dark:border-red-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        <?php echo isset($stats['terminated']) ? $stats['terminated'] : 0; ?>
                    </div>
                    <div class="text-sm font-medium text-red-700 dark:text-red-300">Terminated</div>
                </div>
                <i class="fas fa-user-times text-2xl text-red-500/50"></i>
            </div>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Employee Directory</h3>
        </div>
        
        <div class="overflow-x-auto">
            <?php if (!empty($employees)): ?>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Join Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($employees as $employee): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <?php if (!empty($employee['profile_picture'])): ?>
                                                <img class="h-12 w-12 rounded-full object-cover" src="<?php echo APP_URL . '/uploads/profiles/' . $employee['profile_picture']; ?>" alt="">
                                            <?php else: ?>
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                                    <?php 
                                                    $nameParts = explode(' ', $employee['employee_name']);
                                                    $initials = '';
                                                    if (count($nameParts) >= 2) {
                                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                                    } else {
                                                        $initials = strtoupper(substr($employee['employee_name'], 0, 2));
                                                    }
                                                    echo $initials;
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                <?php echo htmlspecialchars($employee['employee_name']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <?php echo htmlspecialchars($employee['email']); ?>
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                ID: <?php echo htmlspecialchars($employee['employee_id']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">
                                        <?php echo htmlspecialchars($employee['position'] ?? 'N/A'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($employee['department_name'] ?? 'N/A'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo $employee['hire_date'] ? date('M j, Y', strtotime($employee['hire_date'])) : 'N/A'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClasses = [
                                        'active' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        'inactive' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'terminated' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                                    ];
                                    $statusClass = $statusClasses[$employee['status']] ?? $statusClasses['inactive'];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($employee['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="<?php echo APP_URL; ?>/employees/view/<?php echo $employee['id']; ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/employees/edit/<?php echo $employee['id']; ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteEmployee(<?php echo $employee['id']; ?>)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No employees found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first employee to the system.</p>
                    <a href="<?php echo APP_URL; ?>/employees/create" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Employee
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-4">Delete Employee</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to delete this employee? This action cannot be undone.</p>
            </div>
            <div class="items-center px-4 py-3 flex justify-center space-x-4">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none">
                    Cancel
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteEmployeeId = null;

function deleteEmployee(id) {
    deleteEmployeeId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteEmployeeId = null;
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteEmployeeId) {
        window.location.href = `<?php echo APP_URL; ?>/employees/delete/${deleteEmployeeId}`;
    }
});

// Search and Filter functionality
document.getElementById('employee-search').addEventListener('input', function() {
    // Implement search functionality
});

document.getElementById('department-filter').addEventListener('change', function() {
    // Implement filter functionality
});

document.getElementById('status-filter').addEventListener('change', function() {
    // Implement filter functionality
});
</script>
