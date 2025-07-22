<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Email Templates</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage system email templates for notifications and communications.</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="createTemplate()" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Template
                    </button>
                    <a href="<?php echo APP_URL; ?>/settings" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Email Templates List -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-envelope mr-3 text-blue-500"></i>
                    Available Templates
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Template Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Last Updated
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="templatesTable">
                        <!-- Default templates from schema -->
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Leave Approved</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">leave_approved</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">Leave Request Approved</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                System Default
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editTemplate('leave_approved')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="previewTemplate('leave_approved')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Leave Rejected</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">leave_rejected</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">Leave Request Rejected</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                System Default
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editTemplate('leave_rejected')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="previewTemplate('leave_rejected')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Overtime Approved</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">overtime_approved</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">Overtime Request Approved</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                System Default
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editTemplate('overtime_approved')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="previewTemplate('overtime_approved')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Overtime Rejected</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">overtime_rejected</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">Overtime Request Rejected</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                System Default
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editTemplate('overtime_rejected')" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="previewTemplate('overtime_rejected')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Template Variables Help -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Available Template Variables</h3>
                    <div class="mt-2 text-sm text-blue-800 dark:text-blue-200">
                        <p class="mb-2">You can use the following variables in your email templates:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium mb-1">Employee Variables:</h4>
                                <ul class="list-disc list-inside space-y-1">
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{employee_name}</code> - Employee's full name</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{employee_id}</code> - Employee ID</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{employee_email}</code> - Employee's email</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium mb-1">Request Variables:</h4>
                                <ul class="list-disc list-inside space-y-1">
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{leave_type}</code> - Type of leave</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{date_from}</code> - Start date</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{date_to}</code> - End date</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{overtime_date}</code> - Overtime date</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{approver_name}</code> - Approver's name</li>
                                    <li><code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{rejection_reason}</code> - Reason for rejection</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Editor Modal -->
<div id="templateModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Edit Email Template
                            </h3>
                            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <form id="templateForm" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="template_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template Name</label>
                                    <input type="text" id="template_name" name="name" required 
                                           class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label for="template_subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                    <input type="text" id="template_subject" name="subject" required 
                                           class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                            
                            <div>
                                <label for="template_body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Body</label>
                                <textarea id="template_body" name="body" rows="10" required 
                                          class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Enter your email template content here..."></textarea>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="template_active" name="is_active" value="1" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Template is active</span>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveTemplate()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Save Template
                </button>
                <button type="button" onclick="closeModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function createTemplate() {
    document.getElementById('modal-title').textContent = 'Create New Template';
    document.getElementById('templateForm').reset();
    document.getElementById('template_active').checked = true;
    document.getElementById('templateModal').classList.remove('hidden');
}

function editTemplate(templateName) {
    document.getElementById('modal-title').textContent = 'Edit Template: ' + templateName;
    // In a real implementation, you would fetch the template data from the server
    // For now, we'll show the modal with placeholder data
    document.getElementById('template_name').value = templateName;
    document.getElementById('templateModal').classList.remove('hidden');
}

function previewTemplate(templateName) {
    alert('Preview functionality for ' + templateName + ' would be implemented here.');
}

function closeModal() {
    document.getElementById('templateModal').classList.add('hidden');
}

function saveTemplate() {
    // In a real implementation, this would send the data to the server
    alert('Template saved successfully! (This would be implemented with actual server communication)');
    closeModal();
}

// Close modal when clicking outside
document.getElementById('templateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
