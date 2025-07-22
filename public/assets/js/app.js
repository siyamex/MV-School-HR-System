/**
 * HR School Management System
 * Main JavaScript Application
 */

// Global application object
window.HRSchool = {
    // Configuration
    config: {
        baseUrl: document.querySelector('meta[name="base-url"]')?.content || '',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    
    // Utilities
    utils: {
        // Show loading state
        showLoading: function(element, text = 'Loading...') {
            const spinner = '<i class="fas fa-spinner fa-spin mr-2"></i>';
            if (element) {
                element.innerHTML = spinner + text;
                element.disabled = true;
            }
        },
        
        // Hide loading state
        hideLoading: function(element, originalText = 'Submit') {
            if (element) {
                element.innerHTML = originalText;
                element.disabled = false;
            }
        },
        
        // Show notification
        notify: function(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type}`;
            
            const icon = this.getNotificationIcon(type);
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="${icon} mr-2"></i>
                    <span>${message}</span>
                    <button class="ml-auto text-current opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after duration
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        },
        
        // Get notification icon
        getNotificationIcon: function(type) {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || icons.info;
        },
        
        // Format date
        formatDate: function(date, format = 'YYYY-MM-DD') {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            return format
                .replace('YYYY', year)
                .replace('MM', month)
                .replace('DD', day);
        },
        
        // Format currency
        formatCurrency: function(amount, currency = 'IDR') {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },
        
        // Debounce function
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    },
    
    // AJAX utilities
    ajax: {
        // Send GET request
        get: async function(url, params = {}) {
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = url + (queryString ? '?' + queryString : '');
            
            try {
                const response = await fetch(fullUrl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': HRSchool.config.csrfToken
                    }
                });
                
                return await this.handleResponse(response);
            } catch (error) {
                throw new Error('Network error: ' + error.message);
            }
        },
        
        // Send POST request
        post: async function(url, data = {}) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': HRSchool.config.csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                return await this.handleResponse(response);
            } catch (error) {
                throw new Error('Network error: ' + error.message);
            }
        },
        
        // Send form data
        postForm: async function(url, formData) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': HRSchool.config.csrfToken
                    },
                    body: formData
                });
                
                return await this.handleResponse(response);
            } catch (error) {
                throw new Error('Network error: ' + error.message);
            }
        },
        
        // Handle response
        handleResponse: async function(response) {
            if (!response.ok) {
                const error = await response.text();
                throw new Error(`HTTP ${response.status}: ${error}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        }
    },
    
    // Form utilities
    forms: {
        // Validate form
        validate: function(form) {
            let isValid = true;
            const errors = [];
            
            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    errors.push(`${this.getFieldLabel(field)} is required`);
                    this.markFieldAsInvalid(field);
                } else {
                    this.markFieldAsValid(field);
                }
            });
            
            // Email validation
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !this.isValidEmail(field.value)) {
                    isValid = false;
                    errors.push(`${this.getFieldLabel(field)} must be a valid email`);
                    this.markFieldAsInvalid(field);
                }
            });
            
            return { isValid, errors };
        },
        
        // Get field label
        getFieldLabel: function(field) {
            const label = form.querySelector(`label[for="${field.id}"]`);
            return label ? label.textContent.trim() : field.name || field.id || 'Field';
        },
        
        // Mark field as invalid
        markFieldAsInvalid: function(field) {
            field.classList.add('border-red-500', 'focus:ring-red-500');
            field.classList.remove('border-gray-300', 'focus:ring-primary-500');
        },
        
        // Mark field as valid
        markFieldAsValid: function(field) {
            field.classList.remove('border-red-500', 'focus:ring-red-500');
            field.classList.add('border-gray-300', 'focus:ring-primary-500');
        },
        
        // Email validation
        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        
        // Serialize form data
        serialize: function(form) {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }
            
            return data;
        }
    },
    
    // Data table functionality
    dataTable: {
        init: function(tableId, options = {}) {
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const wrapper = table.closest('.datatable-wrapper');
            const searchInput = wrapper.querySelector('.datatable-search input');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', HRSchool.utils.debounce(function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                }, 300));
            }
            
            // Sort functionality
            const headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.innerHTML += ' <i class="fas fa-sort text-gray-400 ml-1"></i>';
                
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    const type = header.dataset.type || 'string';
                    this.sortTable(tbody, column, type);
                });
            });
        },
        
        sortTable: function(tbody, column, type) {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const sortedRows = rows.sort((a, b) => {
                const aVal = a.querySelector(`[data-${column}]`)?.dataset[column] || 
                           a.cells[parseInt(column)]?.textContent.trim() || '';
                const bVal = b.querySelector(`[data-${column}]`)?.dataset[column] || 
                           b.cells[parseInt(column)]?.textContent.trim() || '';
                
                if (type === 'number') {
                    return parseFloat(aVal) - parseFloat(bVal);
                } else if (type === 'date') {
                    return new Date(aVal) - new Date(bVal);
                } else {
                    return aVal.localeCompare(bVal);
                }
            });
            
            sortedRows.forEach(row => tbody.appendChild(row));
        }
    },
    
    // File upload functionality
    fileUpload: {
        init: function(uploadAreaId, options = {}) {
            const uploadArea = document.getElementById(uploadAreaId);
            const fileInput = uploadArea.querySelector('input[type="file"]');
            
            if (!uploadArea || !fileInput) return;
            
            // Drag and drop events
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                fileInput.files = files;
                this.handleFiles(files, options);
            });
            
            // File input change
            fileInput.addEventListener('change', (e) => {
                this.handleFiles(e.target.files, options);
            });
        },
        
        handleFiles: function(files, options) {
            Array.from(files).forEach(file => {
                if (options.validate && !this.validateFile(file, options)) {
                    return;
                }
                
                if (options.preview) {
                    this.showPreview(file);
                }
            });
        },
        
        validateFile: function(file, options) {
            // Size validation
            if (options.maxSize && file.size > options.maxSize) {
                HRSchool.utils.notify(`File ${file.name} is too large. Maximum size is ${this.formatBytes(options.maxSize)}.`, 'error');
                return false;
            }
            
            // Type validation
            if (options.allowedTypes && !options.allowedTypes.includes(file.type)) {
                HRSchool.utils.notify(`File ${file.name} has invalid type. Allowed types: ${options.allowedTypes.join(', ')}.`, 'error');
                return false;
            }
            
            return true;
        },
        
        showPreview: function(file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show image preview
                    console.log('Image preview loaded:', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        },
        
        formatBytes: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    },
    
    // Modal functionality
    modal: {
        open: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        },
        
        close: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    },
    
    // Initialize application
    init: function() {
        console.log('HR School Management System loaded');
        
        // Initialize data tables
        document.querySelectorAll('.data-table').forEach(table => {
            this.dataTable.init(table.id);
        });
        
        // Initialize file uploads
        document.querySelectorAll('.file-upload-area').forEach(area => {
            this.fileUpload.init(area.id);
        });
        
        // Initialize tooltips
        this.initTooltips();
        
        // Auto-hide alerts
        this.initAlerts();
    },
    
    // Initialize tooltips
    initTooltips: function() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute z-50 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg';
                tooltip.textContent = this.dataset.tooltip;
                document.body.appendChild(tooltip);
                
                // Position tooltip
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
                
                this._tooltip = tooltip;
            });
            
            element.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this._tooltip = null;
                }
            });
        });
    },
    
    // Initialize alerts
    initAlerts: function() {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    HRSchool.init();
});

// Export for use in other scripts
window.$ = HRSchool;
