<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <!-- Animated 404 Icon -->
                <div class="mx-auto w-32 h-32 gradient-bg rounded-full flex items-center justify-center mb-8 floating-animation shadow-2xl">
                    <span class="text-4xl font-bold text-white">404</span>
                </div>
                
                <!-- Error Message -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            Oops! Page Not Found
                        </h1>
                        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mb-6"></div>
                    </div>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                        The page you're looking for doesn't exist or has been moved to a different location.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="<?php echo APP_URL; ?>/" 
                           class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        
                        <button onclick="history.back()" 
                                class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Go Back
                        </button>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-12 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 max-w-lg mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center justify-center">
                        <i class="fas fa-life-ring mr-2 text-blue-500"></i>
                        Need Help?
                    </h3>
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center justify-center space-x-6">
                            <a href="<?php echo APP_URL; ?>/employees" class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                                <i class="fas fa-users mr-1"></i>
                                Employees
                            </a>
                            <a href="<?php echo APP_URL; ?>/leaves" class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Leaves
                            </a>
                            <a href="<?php echo APP_URL; ?>/attendance" class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                                <i class="fas fa-clock mr-1"></i>
                                Attendance
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-school mr-1"></i>
                        <?php echo APP_NAME; ?> | 
                        <span class="pulse-animation">⚡</span>
                        Powered by PHP MVC
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto dark mode detection
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to buttons
            const buttons = document.querySelectorAll('a, button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 600ms linear';
                    ripple.style.left = (e.clientX - button.offsetLeft) + 'px';
                    ripple.style.top = (e.clientY - button.offsetTop) + 'px';
                    
                    button.style.position = 'relative';
                    button.style.overflow = 'hidden';
                    button.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
