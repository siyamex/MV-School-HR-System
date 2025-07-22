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
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and Brand -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <?php if (!empty($systemSettings['app_logo'])): ?>
                            <img class="h-8 w-auto" src="<?php echo APP_URL; ?>/public/uploads/<?php echo $systemSettings['app_logo']; ?>" alt="Logo">
                        <?php else: ?>
                            <i class="fas fa-graduation-cap text-2xl text-primary-600"></i>
                        <?php endif; ?>
                        <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-white">
                            <?php echo $systemSettings['app_name'] ?? 'HR School Management'; ?>
                        </span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <!-- Main Navigation -->
                    <div class="flex space-x-4">
                        <a href="<?php echo APP_URL; ?>/dashboard" class="nav-link">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        
                        <?php if (Auth::isAdmin()): ?>
                        <a href="<?php echo APP_URL; ?>/employees" class="nav-link">
                            <i class="fas fa-users mr-2"></i>Employees
                        </a>
                        <?php endif; ?>
                        
                        <?php if (Auth::isSupervisor()): ?>
                        <a href="<?php echo APP_URL; ?>/leaves" class="nav-link">
                            <i class="fas fa-calendar-alt mr-2"></i>Leaves
                        </a>
                        <a href="<?php echo APP_URL; ?>/overtime" class="nav-link">
                            <i class="fas fa-clock mr-2"></i>Overtime
                        </a>
                        <?php endif; ?>
                        
                        <?php if (Auth::isAdmin()): ?>
                        <a href="<?php echo APP_URL; ?>/attendance" class="nav-link">
                            <i class="fas fa-calendar-check mr-2"></i>Attendance
                        </a>
                        <a href="<?php echo APP_URL; ?>/salary-slips" class="nav-link">
                            <i class="fas fa-money-check-alt mr-2"></i>Salary
                        </a>
                        <?php endif; ?>
                        
                        <?php if (Auth::isSuperAdmin()): ?>
                        <a href="<?php echo APP_URL; ?>/settings" class="nav-link">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <?php $user = Auth::user(); ?>
                            <img class="h-8 w-8 rounded-full" src="<?php echo !empty($user['avatar']) ? APP_URL . '/public/uploads/' . $user['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=3b82f6&color=fff'; ?>" alt="Avatar">
                            <span class="ml-2 text-gray-700 dark:text-gray-300"><?php echo $user['name']; ?></span>
                            <i class="ml-1 fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="<?php echo APP_URL; ?>/profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="<?php echo APP_URL; ?>/logout" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="<?php echo APP_URL; ?>/dashboard" class="mobile-nav-link">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                
                <?php if (Auth::isAdmin()): ?>
                <a href="<?php echo APP_URL; ?>/employees" class="mobile-nav-link">
                    <i class="fas fa-users mr-2"></i>Employees
                </a>
                <?php endif; ?>
                
                <?php if (Auth::isSupervisor()): ?>
                <a href="<?php echo APP_URL; ?>/leaves" class="mobile-nav-link">
                    <i class="fas fa-calendar-alt mr-2"></i>Leaves
                </a>
                <a href="<?php echo APP_URL; ?>/overtime" class="mobile-nav-link">
                    <i class="fas fa-clock mr-2"></i>Overtime
                </a>
                <?php endif; ?>
                
                <?php if (Auth::isAdmin()): ?>
                <a href="<?php echo APP_URL; ?>/attendance" class="mobile-nav-link">
                    <i class="fas fa-calendar-check mr-2"></i>Attendance
                </a>
                <a href="<?php echo APP_URL; ?>/salary-slips" class="mobile-nav-link">
                    <i class="fas fa-money-check-alt mr-2"></i>Salary
                </a>
                <?php endif; ?>
                
                <?php if (Auth::isSuperAdmin()): ?>
                <a href="<?php echo APP_URL; ?>/settings" class="mobile-nav-link">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages -->
        <?php if (Session::hasFlash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>
            <?php echo Session::getFlash('success'); ?>
        </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo Session::getFlash('error'); ?>
        </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('warning')): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <?php echo Session::getFlash('warning'); ?>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <div class="py-6">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo APP_URL; ?>/public/assets/js/app.js"></script>
    
    <!-- Theme Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            // Theme toggle
            themeToggle.addEventListener('click', function() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                
                // Update system setting via AJAX
                fetch('<?php echo APP_URL; ?>/api/update-theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo CSRF::token(); ?>'
                    },
                    body: JSON.stringify({ theme: localStorage.getItem('theme') })
                });
            });
            
            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Initialize theme from localStorage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
