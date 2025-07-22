<!DOCTYPE html>
<html lang="en" class="<?php echo $systemSettings['theme_mode'] ?? 'light'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'HR School Management'; ?></title>
    
    <!-- Favicon -->
    <?php if (!empty($systemSettings['app_favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/public/uploads/<?php echo $systemSettings['app_favicon']; ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/public/assets/favicon.ico">
    <?php endif; ?>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/app.css">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-blue-100 dark:from-gray-900 dark:to-gray-800 opacity-50"></div>
        
        <!-- Content -->
        <div class="relative w-full flex items-center justify-center">
            <?php echo $content; ?>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo APP_URL; ?>/public/assets/js/app.js"></script>
    
    <!-- Theme initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme from localStorage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
