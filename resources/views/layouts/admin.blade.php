<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="tailAdminApp()" x-init="init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#28a745">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/lib/bootstrap/jquery/jquery.js') }}"></script>

    <style>
        /* TailAdmin Style Overrides avec palette verte */
        :root {
            --color-brand-50: #f0fdf4;
            --color-brand-100: #dcfce7;
            --color-brand-200: #bbf7d0;
            --color-brand-300: #86efac;
            --color-brand-400: #4ade80;
            --color-brand-500: #28a745;
            --color-brand-600: #20c997;
            --color-brand-700: #15803d;
            --color-brand-800: #166534;
            --color-brand-900: #14532d;
        }

        body {
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar customization */
        .sidebar-expanded {
            width: 290px;
        }

        .sidebar-collapsed {
            width: 90px;
        }

        @media (max-width: 1024px) {
            .sidebar-mobile-open {
                transform: translateX(0);
            }
            
            .sidebar-mobile-closed {
                transform: translateX(-100%);
            }
        }

        /* Menu item styles */
        .menu-item {
            @apply flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 transition-all duration-200;
        }

        .menu-item-active {
            @apply bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400;
        }

        .menu-item-inactive:hover {
            @apply bg-gray-100 dark:bg-gray-800;
        }

        .menu-item-icon-active {
            @apply text-green-600 dark:text-green-400;
        }

        .menu-item-icon-inactive {
            @apply text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300;
        }

        /* Smooth transitions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* No scrollbar utility */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Green brand colors */
        .text-brand-500 {
            color: #28a745;
        }

        .text-brand-600 {
            color: #20c997;
        }

        .bg-brand-500 {
            background-color: #28a745;
        }

        .bg-brand-600 {
            background-color: #20c997;
        }

        .border-brand-500 {
            border-color: #28a745;
        }

        .hover\:text-brand-600:hover {
            color: #20c997;
        }

        .hover\:bg-brand-600:hover {
            background-color: #20c997;
        }

        /* Card shadows */
        .shadow-theme-xs {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-theme-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen xl:flex">
        <!-- Sidebar -->
        <aside
            :class="[
                'fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-[99999] border-r border-gray-200',
                {
                    'lg:w-[290px]': sidebarExpanded || sidebarHovered,
                    'lg:w-[90px]': !sidebarExpanded && !sidebarHovered,
                    'translate-x-0 w-[290px]': mobileSidebarOpen,
                    '-translate-x-full': !mobileSidebarOpen,
                    'lg:translate-x-0': true,
                },
            ]"
            @mouseenter="!sidebarExpanded && (sidebarHovered = true)"
            @mouseleave="sidebarHovered = false"
        >
            <!-- Logo -->
            <div
                :class="[
                    'py-8 flex',
                    !sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start',
                ]"
            >
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                        <img class="h-10" src="{{ asset('image/pdci.jpg') }}" alt="Logo">
                    </template>
                    <template x-if="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)">
                        <img class="h-8" src="{{ asset('image/pdci.jpg') }}" alt="Logo">
                    </template>
                    <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Belier Admin</span>
                    </template>
                </a>
            </div>

            <!-- Navigation -->
            <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar custom-scrollbar">
                <nav class="mb-6">
                    <div class="flex flex-col gap-4">
                        <!-- Menu Group -->
                        <div>
                            <h2
                                :class="[
                                    'mb-4 text-xs uppercase flex leading-[20px] text-gray-400',
                                    !sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start',
                                ]"
                            >
                                <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                    MENU
                                </template>
                                <template x-if="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)">
                                    •••
                                </template>
                            </h2>
                            <ul class="flex flex-col gap-4">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                       :class="[
                                           'menu-item group',
                                           isActive('{{ route('admin.dashboard') }}') ? 'menu-item-active' : 'menu-item-inactive',
                                       ]"
                                    >
                                        <span
                                            :class="[
                                                isActive('{{ route('admin.dashboard') }}') ? 'menu-item-icon-active' : 'menu-item-icon-inactive',
                                            ]"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Dashboard</span>
                                        </template>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ url('/') }}"
                                       class="menu-item menu-item-inactive group"
                                    >
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Site Public</span>
                                        </template>
                                    </a>
                                </li>

                                <li x-data="{ subOpen: false }">
                                    <button
                                        @click="subOpen = !subOpen"
                                        :class="[
                                            'menu-item group w-full',
                                            subOpen ? 'menu-item-active' : 'menu-item-inactive',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                subOpen ? 'menu-item-icon-active' : 'menu-item-icon-inactive',
                                            ]"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text flex-1 text-left">Articles</span>
                                        </template>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <svg
                                                :class="[
                                                    'ml-auto w-5 h-5 transition-transform duration-200',
                                                    { 'rotate-180 text-brand-500': subOpen },
                                                ]"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </template>
                                    </button>
                                    <div
                                        x-show="subOpen && (sidebarExpanded || sidebarHovered || mobileSidebarOpen)"
                                        x-transition
                                    >
                                        <ul class="mt-2 space-y-1 ml-9">
                                            <li>
                                                <a href="{{ route('admin.articles.index') }}" class="block py-2 px-3 text-sm text-gray-700 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                    Tous les articles
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.articles.create') }}" class="block py-2 px-3 text-sm text-gray-700 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                    Créer un article
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>

                                <li>
                                    <a href="#users" class="menu-item menu-item-inactive group">
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Utilisateurs</span>
                                        </template>
                                    </a>
                                </li>

                                <li>
                                    <a href="#products" class="menu-item menu-item-inactive group">
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Produits</span>
                                        </template>
                                    </a>
                                </li>

                                <li>
                                    <a href="#orders" class="menu-item menu-item-inactive group">
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Commandes</span>
                                        </template>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Others Group -->
                        <div>
                            <h2
                                :class="[
                                    'mb-4 text-xs uppercase flex leading-[20px] text-gray-400',
                                    !sidebarExpanded && !sidebarHovered ? 'lg:justify-center' : 'justify-start',
                                ]"
                            >
                                <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                    AUTRES
                                </template>
                                <template x-if="!(sidebarExpanded || sidebarHovered || mobileSidebarOpen)">
                                    •••
                                </template>
                            </h2>
                            <ul class="flex flex-col gap-4">
                                <li>
                                    <a href="#stats" class="menu-item menu-item-inactive group">
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Statistiques</span>
                                        </template>
                                    </a>
                                </li>

                                <li>
                                    <a href="#settings" class="menu-item menu-item-inactive group">
                                        <span class="menu-item-icon-inactive">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <template x-if="sidebarExpanded || sidebarHovered || mobileSidebarOpen">
                                            <span class="menu-item-text">Paramètres</span>
                                        </template>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Backdrop for mobile -->
        <div
            x-show="mobileSidebarOpen"
            @click="mobileSidebarOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 z-[99998] lg:hidden"
            style="display: none;"
        ></div>

        <!-- Main Content -->
        <div
            class="flex-1 transition-all duration-300 ease-in-out"
            :class="[sidebarExpanded || sidebarHovered ? 'lg:ml-[290px]' : 'lg:ml-[90px]']"
        >
            <!-- Header -->
            <header class="sticky top-0 flex w-full bg-white border-gray-200 z-[9999] dark:border-gray-800 dark:bg-gray-900 lg:border-b">
                <div class="flex flex-col items-center justify-between grow lg:flex-row lg:px-6">
                    <div class="flex items-center justify-between w-full gap-2 px-3 py-3 border-b border-gray-200 dark:border-gray-800 sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4">
                        <!-- Toggle Button -->
                        <button
                            @click="handleToggle"
                            class="flex items-center justify-center w-10 h-10 text-gray-500 border-gray-200 rounded-lg z-[99999] dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11 lg:border hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        >
                            <svg x-show="!mobileSidebarOpen" width="16" height="12" viewBox="0 0 16 12" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="currentColor"/>
                            </svg>
                            <svg x-show="mobileSidebarOpen" class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" style="display: none;">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z" fill=""/>
                            </svg>
                        </button>

                        <!-- Mobile Logo -->
                        <div class="lg:hidden flex items-center gap-2">
                            <img class="h-8" src="{{ asset('image/pdci.jpg') }}" alt="Logo">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Belier</span>
                        </div>

                        <!-- Search Bar (placeholder) -->
                        <div class="hidden lg:block flex-1 max-w-md">
                            <div class="relative">
                                <input type="text" placeholder="Rechercher..." class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Header Right -->
                    <div class="hidden lg:flex items-center gap-4 px-6">
                        <!-- Dark Mode Toggle -->
                        <button @click="toggleDarkMode" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <button class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Admin').'&color=28a745&background=f0fdf4' }}" alt="Avatar">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50"
                                style="display: none;"
                            >
                                <a href="#profile" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    👤 Mon profil
                                </a>
                                <a href="#settings" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    ⚙️ Paramètres
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                                <form method="POST" action="{{ route('app_logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        🚪 Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 mx-auto max-w-screen-2xl md:p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="p-4 mx-auto max-w-screen-2xl md:p-6 pt-0">
                <p class="text-sm text-center text-gray-500 dark:text-gray-400">
                    Conçu avec ❤️ par 
                    <a href="#" class="text-brand-500 hover:text-brand-600 transition-colors duration-200 font-medium">
                        Belier Intrépide
                    </a>
                    • Powered by TailAdmin
                </p>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function tailAdminApp() {
            return {
                sidebarExpanded: localStorage.getItem('sidebarExpanded') === 'true',
                sidebarHovered: false,
                mobileSidebarOpen: false,
                darkMode: localStorage.getItem('darkMode') === 'true',

                init() {
                    // Apply dark mode on load
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }

                    // Close mobile sidebar on route change
                    window.addEventListener('popstate', () => {
                        this.mobileSidebarOpen = false;
                    });
                },

                handleToggle() {
                    if (window.innerWidth >= 1024) {
                        this.sidebarExpanded = !this.sidebarExpanded;
                        localStorage.setItem('sidebarExpanded', this.sidebarExpanded);
                    } else {
                        this.mobileSidebarOpen = !this.mobileSidebarOpen;
                    }
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    localStorage.setItem('darkMode', this.darkMode);
                },

                isActive(url) {
                    return window.location.href === url || window.location.pathname === url;
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
