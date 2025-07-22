<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | <?php echo APP_NAME; ?></title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <!-- Animated 500 Icon -->
                <div class="mx-auto w-32 h-32 gradient-bg rounded-full flex items-center justify-center mb-8 floating-animation shadow-2xl">
                    <i class="fas fa-exclamation-triangle text-4xl text-white"></i>
                </div>
                
                <!-- Error Message -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            Internal Server Error
                        </h1>
                        <div class="w-24 h-1 bg-gradient-to-r from-pink-500 to-red-600 mx-auto mb-6"></div>
                    </div>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                        Something went wrong on our server. Our team has been notified and is working to fix the issue.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="<?php echo APP_URL; ?>/" 
                           class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-pink-500 to-red-600 hover:from-pink-600 hover:to-red-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        
                        <button onclick="location.reload()" 
                                class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-redo mr-2"></i>
                            Try Again
                        </button>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Error Code: 500 | <?php echo APP_NAME; ?>
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
    </script>
</body>
</html>
