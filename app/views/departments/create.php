<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="<?php echo APP_URL; ?>/departments" 
               class="flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                    Back to Departments
                </a>
                <div class="h-6 border-l border-gray-300 dark:border-gray-600"></div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Department</h1>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (Session::has('error')): ?>
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                <?php echo Session::getFlash('error'); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <?php echo csrf_field(); ?>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Department Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Department Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Enter department name">
                    </div>

                    <!-- Department Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Enter department description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <!-- Department Head -->
                    <div class="md:col-span-2">
                        <label for="head_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Department Head
                        </label>
                        <select id="head_id" 
                                name="head_id"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Department Head (Optional)</option>
                            <?php foreach ($potentialHeads as $head): ?>
                                <option value="<?php echo $head['id']; ?>" 
                                        <?php echo (($_POST['head_id'] ?? '') == $head['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($head['name']); ?> 
                                    (<?php echo ucfirst($head['role']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Only admins and supervisors can be assigned as department heads
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   <?php echo (($_POST['is_active'] ?? '1') == '1') ? 'checked' : ''; ?>
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Active Department
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Only active departments will be available for employee assignment
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="<?php echo APP_URL; ?>/departments" 
                       class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Create Department
                    </button>
                </div>
            </div>
        </form>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Department Management Tips
            </h3>
            <div class="text-sm text-blue-800 dark:text-blue-200 space-y-2">
                <p><strong>Department Name:</strong> Choose a clear, descriptive name for easy identification.</p>
                <p><strong>Department Head:</strong> The head will have supervisory access to manage staff within this department.</p>
                <p><strong>Description:</strong> Add details about the department's purpose and responsibilities.</p>
                <p><strong>Status:</strong> Only active departments will appear in employee assignment dropdowns.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    
    form.addEventListener('submit', function(e) {
        if (!nameInput.value.trim()) {
            e.preventDefault();
            alert('Department name is required');
            nameInput.focus();
        }
    });
    
    // Auto-focus on name field
    nameInput.focus();
});
</script>
