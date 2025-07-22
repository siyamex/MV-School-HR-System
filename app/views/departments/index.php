<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Department Management</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage departments and assign department heads
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="<?php echo APP_URL; ?>/departments/create"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Department
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

        <!-- Departments Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Department</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Description</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Department Head</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Employees</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php if (empty($departments)): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-building text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">No departments found</p>
                                        <p class="text-sm">Create your first department to get started</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($departments as $dept): ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-building text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        <?php echo htmlspecialchars($dept['name']); ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        ID: <?php echo $dept['id']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                <?php echo htmlspecialchars($dept['description'] ?: 'No description'); ?>
                                            </p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php if ($dept['head_name']): ?>
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-2">
                                                        <i class="fas fa-user text-green-600 dark:text-green-400 text-xs"></i>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        <?php echo htmlspecialchars($dept['head_name']); ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    No head assigned
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <?php echo number_format($dept['employee_count']); ?> employees
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <?php if ($dept['is_active']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Active
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-3">
                                                <a href="<?php echo APP_URL; ?>/departments/view/<?php echo $dept['id']; ?>"
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                   title="View Department">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/departments/edit/<?php echo $dept['id']; ?>"
                                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                   title="Edit Department">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($dept['employee_count'] == 0): ?>
                                                    <form method="POST" action="<?php echo APP_URL; ?>/departments/delete/<?php echo $dept['id']; ?>"
                                                          class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this department?');">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                title="Delete Department">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-gray-400" title="Cannot delete department with employees">
                                                        <i class="fas fa-trash"></i>
                                                    </span>
                                                <?php endif; ?>
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

        <!-- Quick Stats -->
        <?php if (!empty($departments)): ?>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                <?php echo count($departments); ?>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Departments</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                <?php echo count(array_filter($departments, function($d) { return $d['is_active']; })); ?>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Active Departments</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                <?php echo count(array_filter($departments, function($d) { return !empty($d['head_name']); })); ?>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">With Heads</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                <?php echo array_sum(array_column($departments, 'employee_count')); ?>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Employees</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
</div>
