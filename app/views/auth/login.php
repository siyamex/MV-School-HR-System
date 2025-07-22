<div class="w-full max-w-md">
    <!-- Logo and Brand -->
    <div class="text-center mb-8">
        <div class="flex justify-center items-center mb-4">
            <?php if (!empty($systemSettings['app_logo'])): ?>
                <img class="h-12 w-auto" src="<?php echo APP_URL; ?>/public/uploads/<?php echo $systemSettings['app_logo']; ?>" alt="Logo">
            <?php else: ?>
                <i class="fas fa-graduation-cap text-4xl text-primary-600 dark:text-primary-400"></i>
            <?php endif; ?>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            <?php echo $systemSettings['app_name'] ?? 'HR School Management'; ?>
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Sign in to your account</p>
    </div>

    <!-- Login Form -->
    <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
        <?php if (Session::hasFlash('error')): ?>
        <div class="mb-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-md">
            <div class="flex">
                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                <span><?php echo Session::getFlash('error'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('success')): ?>
        <div class="mb-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-md">
            <div class="flex">
                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                <span><?php echo Session::getFlash('success'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo APP_URL; ?>/login" class="space-y-6">
            <?php echo CSRF::field(); ?>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        class="input-field pl-10" 
                        placeholder="Enter your email"
                        value="<?php echo $_POST['email'] ?? ''; ?>"
                    >
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="input-field pl-10" 
                        placeholder="Enter your password"
                    >
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Remember me
                    </label>
                </div>
                
                <div class="text-sm">
                    <a href="#" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                        Forgot password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full flex justify-center items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </div>
        </form>

        <!-- Google OAuth -->
        <?php if (!empty($systemSettings['google_client_id'])): ?>
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or continue with</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="<?php echo APP_URL; ?>/auth/google" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Sign in with Google
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Theme Toggle -->
    <div class="mt-8 text-center">
        <button id="theme-toggle" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            <i class="fas fa-moon dark:hidden mr-2"></i>
            <i class="fas fa-sun hidden dark:inline mr-2"></i>
            <span class="dark:hidden">Dark Mode</span>
            <span class="hidden dark:inline">Light Mode</span>
        </button>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $systemSettings['app_name'] ?? 'HR School Management'; ?>. All rights reserved.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    
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
    });
});
</script>
