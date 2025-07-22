<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $error_code ?? 'Error'; ?> - <?php echo $error_title ?? 'Something went wrong'; ?> | <?php echo APP_NAME; ?></title>
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
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <!-- Animated Error Icon -->
                <div class="mx-auto w-32 h-32 gradient-bg rounded-full flex items-center justify-center mb-8 floating-animation shadow-2xl">
                    <?php if (isset($error_code)): ?>
                        <span class="text-4xl font-bold text-white"><?php echo htmlspecialchars($error_code); ?></span>
                    <?php else: ?>
                        <i class="fas fa-exclamation-circle text-4xl text-white"></i>
                    <?php endif; ?>
                </div>
                
                <!-- Error Message -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            <?php echo htmlspecialchars($error_title ?? 'Something went wrong'); ?>
                        </h1>
                        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-600 mx-auto mb-6"></div>
                    </div>
                    
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                        <?php echo htmlspecialchars($error_message ?? 'An unexpected error occurred. Please try again later.'); ?>
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

                <!-- Footer Info -->
                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?php echo APP_NAME; ?> | Powered by PHP MVC
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
