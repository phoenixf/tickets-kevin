<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistema de Tickets') ?> - Sistema de Tickets</title>

    <!-- Dark Mode Script - Must be in head to prevent FOUC -->
    <script>
        // Immediately check theme preference before page renders
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Default to dark mode if no preference is set
            if (theme === 'dark' || (!theme && prefersDark) || !theme) {
                document.documentElement.classList.add('dark');
                // Set default to dark if no preference exists
                if (!theme) {
                    localStorage.setItem('theme', 'dark');
                }
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: '#1a1a1a',
                            100: '#2d2d2d',
                            200: '#404040',
                            300: '#525252',
                            400: '#666666',
                            500: '#808080',
                            600: '#999999',
                            700: '#b3b3b3',
                            800: '#cccccc',
                            900: '#e6e6e6',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed w-full z-30 top-0 transition-colors duration-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <!-- Logo -->
                    <a href="/dashboard" class="flex items-center ml-2 md:mr-24">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap ml-2 text-gray-800 dark:text-white">Sistema de Tickets</span>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ml-3 gap-3">
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" type="button" class="flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm px-3 py-2 transition-colors duration-200" aria-label="Alternar tema">
                            <!-- Sun Icon (visible in dark mode) -->
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon Icon (visible in light mode) -->
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <span id="theme-toggle-text" class="hidden sm:inline text-sm font-medium">Light Mode</span>
                        </button>

                        <!-- User Menu -->
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 mr-3"><?= esc($user->nome ?? '') ?></span>
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold">
                                <?= strtoupper(substr($user->nome ?? 'U', 0, 2)) ?>
                            </div>
                            <form action="<?= url_to('logout') ?>" method="post" class="ml-4">
                                <?= csrf_field() ?>
                                <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200" title="Sair">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 w-64 h-full pt-16 font-normal duration-75 transition-width" aria-label="Sidebar">
            <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 pt-0 transition-colors duration-200">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex-1 px-3 space-y-1 bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-200">
                        <ul class="space-y-2 pb-2">
                            <li>
                                <a href="/dashboard" class="text-base text-gray-900 dark:text-gray-100 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors duration-200">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="ml-3">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="/tickets" class="text-base text-gray-900 dark:text-gray-100 font-normal rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center p-2 group transition-colors duration-200">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 flex-shrink-0 group-hover:text-gray-900 dark:group-hover:text-white transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span class="ml-3 flex-1 whitespace-nowrap">Tickets</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div id="main-content" class="h-full w-full bg-gray-50 dark:bg-gray-900 relative overflow-y-auto lg:ml-64 transition-colors duration-200">
            <main>
                <div class="pt-6 px-4">
                    <!-- Mensagens Flash -->
                    <?php if (session('success')) : ?>
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-600 text-green-700 dark:text-green-200 rounded transition-colors duration-200">
                            <p class="font-medium"><?= esc(session('success')) ?></p>
                        </div>
                    <?php endif ?>

                    <?php if (session('error')) : ?>
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-200 rounded transition-colors duration-200">
                            <p class="font-medium"><?= esc(session('error')) ?></p>
                        </div>
                    <?php endif ?>

                    <?php if (session('errors')) : ?>
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-200 rounded transition-colors duration-200">
                            <p class="font-medium mb-2">Erros de validação:</p>
                            <ul class="list-disc list-inside">
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <!-- Content -->
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>

    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <!-- Dark Mode Toggle Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleText = document.getElementById('theme-toggle-text');

        // Show the correct icon and text based on current theme
        function updateIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
                themeToggleText.textContent = 'Light Mode';
            } else {
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleText.textContent = 'Dark Mode';
            }
        }

        // Initialize icon on page load
        updateIcon();

        // Toggle theme when button is clicked
        themeToggleBtn.addEventListener('click', function() {
            // Toggle dark class
            document.documentElement.classList.toggle('dark');

            // Save preference
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }

            // Update icon and text
            updateIcon();
        });
    </script>
</body>
</html>
