<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="<?php echo APP_URL; ?>/departments" 
               class="flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Departments
            </a>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <?php echo htmlspecialchars($department['name']); ?>
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Department Details and Employee List
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo APP_URL; ?>/departments/edit/<?php echo $department['id']; ?>"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Department
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (Session::has('success')): ?>
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            <?php echo Session::getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (Session::has('error')): ?>
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            <?php echo Session::getFlash('error'); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Department Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-building text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Department Info</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Basic details</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($department['name']); ?>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($department['description'] ?: 'No description'); ?>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department Head</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                <?php if ($department['head_name']): ?>
                                    <span class="flex items-center">
                                        <i class="fas fa-user-tie text-green-600 dark:text-green-400 mr-2"></i>
                                        <?php echo htmlspecialchars($department['head_name']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="flex items-center text-yellow-600 dark:text-yellow-400">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        No head assigned
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <p class="mt-1">
                                <?php if ($department['is_active']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Employees</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                <span class="flex items-center">
                                    <i class="fas fa-users text-blue-600 dark:text-blue-400 mr-2"></i>
                                    <?php echo number_format($employeeCount); ?> employees
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <?php echo date('M d, Y', strtotime($department['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employees List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-users text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Department Employees</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo count($employees); ?> employee(s) in this department
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($employees)): ?>
                        <div class="text-center py-12">
                            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Employees</h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                This department doesn't have any employees yet.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Employee</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Employee ID</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Designation</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Status</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php foreach ($employees as $employee): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900 dark:text-white">
                                                            <?php echo htmlspecialchars($employee['name'] ?? 'Unknown'); ?>
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            <?php echo htmlspecialchars($employee['email'] ?? ''); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="text-sm font-mono text-gray-900 dark:text-white">
                                                    <?php echo htmlspecialchars($employee['employee_id']); ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="text-sm text-gray-900 dark:text-white">
                                                    <?php echo htmlspecialchars($employee['designation'] ?? 'Not assigned'); ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <?php if ($employee['status'] === 'active'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Active
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <?php echo ucfirst($employee['status']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-4 px-4">
                                                <a href="<?php echo APP_URL; ?>/employees/view/<?php echo $employee['id']; ?>"
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                   title="View Employee">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
