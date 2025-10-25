<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100" x-data="spaApp()" @popstate.window="handlePopState">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->

    <!-- Tailwind CSS - Local -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/lib/bootstrap/jquery/jquery.js') }}"></script>

    <style>
        /* Responsive Design Optimizations */

        /* Prevent horizontal scrolling */
        body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* Container fixes pour éviter le débordement */
        main {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Sidebar fixes */
        aside#sidebar {
            max-height: 100vh;
            overflow-y: auto;
        }

        /* Scroll styling for sidebar */
        aside#sidebar::-webkit-scrollbar {
            width: 6px;
        }

        aside#sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        aside#sidebar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 3px;
        }

        aside#sidebar::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Mobile First Approach */
        .sidebar-nav a.active {
            background-color: #3b82f6;
            color: white;
            border-radius: 0.5rem;
        }

        .sidebar-nav a:hover:not(.active) {
            background-color: #f3f4f6;
            border-radius: 0.5rem;
        }

        .sidebar-nav a {
            transition: all 0.2s ease-in-out;
        }

        .mobile-menu-overlay {
            backdrop-filter: blur(4px);
        }

        /* Smooth scrolling for all devices */
        html {
            scroll-behavior: smooth;
        }

        /* Touch optimization for mobile */
        button, a, input, select, textarea {
            touch-action: manipulation;
        }

        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Tablet optimizations */
        @media (min-width: 768px) and (max-width: 1024px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Desktop optimizations */
        @media (min-width: 1024px) {
            .container {
                max-width: 1200px;
                margin: 0 auto;
            }
            
            /* Main content ne déborde pas à droite */
            main {
                padding-right: 1rem;
            }
        }

        /* Ultra wide screen optimizations */
        @media (min-width: 1536px) {
            main {
                max-width: calc(100vw - 16rem - 4rem);
            }
        }

        /* High DPI screen optimizations */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .crisp-edges {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }

        /* Card hover effects for touch devices */
        @media (hover: none) {
            .hover-card:hover {
                transform: none;
            }
        }

        /* Loading states */
        .loading {
            pointer-events: none;
            opacity: 0.6;
        }

        /* Smooth transitions for all interactions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Fix pour empêcher le contenu de déborder sous la sidebar */
        @media (min-width: 1024px) {
            body {
                padding-left: 0 !important;
            }
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Dashboard Manager -->
    <script src="{{ asset('js/dashboard-manager.js') }}"></script>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased bg-gray-50">
    <div class="min-h-full">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-4 left-4 z-50">
            <button id="mobile-menu-btn" type="button" class="bg-white p-2 rounded-lg shadow-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile menu overlay -->
        <div id="mobile-menu-overlay" class="lg:hidden fixed inset-0 z-40 bg-black bg-opacity-50 hidden mobile-menu-overlay"></div>

        <!-- Sidebar - Hidden on mobile, slide-in when menu is open -->
        <aside id="sidebar" class="
            fixed inset-y-0 left-0 z-50
            w-64 bg-white border-r border-gray-200
            flex flex-col py-6 px-4 min-h-screen shadow-lg
            transform -translate-x-full lg:translate-x-0
            transition-transform duration-300 ease-in-out
        ">
            <!-- Close button for mobile sidebar (en haut de la sidebar) -->
            <div class="lg:hidden flex justify-end mb-4">
                <button id="sidebar-close-btn" type="button" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex items-center mb-8">
                <img class="h-10 w-10 rounded-full" src="{{ asset('image/pdci.jpg') }}" alt="Logo">
                <span class="ml-3 text-2xl font-extrabold text-gray-900 tracking-tight">MonAPP</span>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 sidebar-nav overflow-y-auto">
                <a href="{{ url('/') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-green-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200">
                    <span class="text-2xl">🏡</span> <span class="tracking-wide">Accueil du site</span>
                </a>
                <a @click.prevent="navigateTo('{{ route('admin.dashboard') }}', 'Dashboard')" 
                   href="{{ route('admin.dashboard') }}" 
                   :class="currentPage === 'Dashboard' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">🏠</span> <span class="tracking-wide">Dashboard</span>
                </a>
                <a @click.prevent="loadSection('users', 'Utilisateurs')" 
                   href="#users"
                   :class="currentPage === 'Utilisateurs' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">👥</span> <span class="tracking-wide">Utilisateurs</span>
                </a>
                <a @click.prevent="loadSection('orders', 'Commandes')" 
                   href="#orders"
                   :class="currentPage === 'Commandes' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-blue-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">🧾</span> <span class="tracking-wide">Commandes</span>
                </a>
                <a @click.prevent="loadSection('articles', 'Gestion des Articles')" 
                   href="#articles"
                   :class="currentPage === 'Gestion des Articles' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">📰</span> <span class="tracking-wide">Articles</span>
                </a>
                <a @click.prevent="loadSection('products', 'Produits')" 
                   href="#products"
                   :class="currentPage === 'Produits' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">📦</span> <span class="tracking-wide">Produits</span>
                </a>
                <a @click.prevent="loadSection('subscriptions', 'Abonnements')" 
                   href="#subscriptions"
                   :class="currentPage === 'Abonnements' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">💳</span> <span class="tracking-wide">Abonnements</span>
                </a>
                <a @click.prevent="loadSection('stats', 'Statistiques')" 
                   href="#stats"
                   :class="currentPage === 'Statistiques' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">📊</span> <span class="tracking-wide">Statistiques</span>
                </a>
                <a @click.prevent="loadSection('messages', 'Messages')" 
                   href="#messages"
                   :class="currentPage === 'Messages' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">✉️</span> <span class="tracking-wide">Messages</span>
                </a>
                <a @click.prevent="loadSection('settings', 'Paramètres')" 
                   href="#settings"
                   :class="currentPage === 'Paramètres' ? 'ring-2 ring-blue-500 bg-blue-50' : ''"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-sm bg-gradient-to-r from-green-100 via-white to-emerald-50 text-green-700 font-bold border border-green-200 hover:scale-105 hover:shadow-lg transition-all duration-200 cursor-pointer">
                    <span class="text-2xl">⚙️</span> <span class="tracking-wide">Paramètres</span>
                </a>
            </nav>

            <!-- User Profile & Color Selector -->
            <div class="mt-4 pt-4 border-t border-gray-200 space-y-4">
                <div class="flex items-center space-x-3">
                    <img class="h-9 w-9 rounded-full" src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Admin').'&color=7F9CF5&background=EBF4FF' }}" alt="Avatar">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label for="dashboard-color" class="text-xs text-gray-600">Couleur:</label>
                    <input type="color" id="dashboard-color" value="#f3f4f6" class="w-8 h-8 border-0 p-0 bg-transparent cursor-pointer rounded">
                </div>
            </div>
        </aside>

        <!-- Main content area with proper margin for sidebar -->
        <div id="main-content" class="lg:ml-64 min-h-screen transition-all duration-300">
            <!-- Mobile header -->
            <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-lg font-semibold text-gray-900" x-text="currentPage">@yield('title', 'Dashboard')</h1>
                <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Admin').'&color=7F9CF5&background=EBF4FF' }}" alt="Avatar">
            </div>

            <!-- Desktop Header Mosaic Style - Sticky -->
            <header class="hidden lg:block bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Left: Sidebar Toggle + Breadcrumb -->
                    <div class="flex items-center gap-4">
                        <button id="sidebar-toggle-btn" 
                                onclick="toggleSidebar()"
                                class="p-2 rounded-lg hover:bg-gray-100 transition-colors group"
                                title="Réduire/Agrandir la sidebar">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-900" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-bold text-gray-900" x-text="currentPage">
                            @yield('title', 'Dashboard Administrateur')
                        </h1>
                    </div>

                    <!-- Right: Actions + Dark Mode + Profile -->
                    <div class="flex items-center gap-4">
                        <!-- Search Button (Modal Search) -->
                        <button onclick="openSearchModal()" 
                                class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                title="Rechercher (Ctrl+K)">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative"
                                    title="Notifications">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Dropdown Notifications -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <p class="text-sm font-medium text-gray-900">Nouvel utilisateur</p>
                                        <p class="text-xs text-gray-500 mt-1">Un nouvel utilisateur s'est inscrit</p>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                        <p class="text-sm font-medium text-gray-900">Nouvelle commande</p>
                                        <p class="text-xs text-gray-500 mt-1">Commande #1234 reçue</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button id="dark-mode-toggle" 
                                onclick="toggleDarkMode()"
                                class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                title="Activer/Désactiver le mode sombre">
                            <svg id="dark-mode-icon-sun" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg id="dark-mode-icon-moon" class="w-5 h-5 text-gray-600 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <img class="h-8 w-8 rounded-full" 
                                     src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Admin').'&color=7F9CF5&background=EBF4FF' }}" 
                                     alt="Avatar">
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown Profile -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
                                 style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    👤 Mon profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    ⚙️ Paramètres
                                </a>
                                <div class="border-t border-gray-200 my-2"></div>
                                <form method="POST" action="{{ route('app_logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        🚪 Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="min-h-screen p-4 sm:p-6 lg:p-8">
                <!-- SPA Loading Overlay -->
                <div x-show="isLoading" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center"
                     style="display: none;">
                    <div class="text-center">
                        <div class="relative inline-flex">
                            <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl" x-text="loadingIcon"></span>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-700 dark:text-gray-300 font-medium" x-text="'Chargement de ' + currentPage + '...'"></p>
                    </div>
                </div>

                <!-- SPA Content Container -->
                <div x-show="!isLoading"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const sidebarCloseBtn = document.getElementById('sidebar-close-btn');

            // Open mobile menu
            mobileMenuBtn?.addEventListener('click', function() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            // Close mobile menu
            function closeMobileMenu() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            sidebarCloseBtn?.addEventListener('click', closeMobileMenu);
            overlay?.addEventListener('click', closeMobileMenu);

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                    closeAdminModal();
                    closeSearchModal();
                }
            });

            // Close mobile menu when clicking on a sidebar link
            const sidebarLinks = sidebar?.querySelectorAll('a');
            sidebarLinks?.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        setTimeout(closeMobileMenu, 100);
                    }
                });
            });
        });

        // Sidebar Collapse/Expand - Mosaic Style
        let sidebarCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebarCollapsed = !sidebarCollapsed;
            
            if (sidebarCollapsed) {
                // Réduire la sidebar
                sidebar.classList.add('!w-20');
                mainContent.classList.add('lg:!ml-20');
                mainContent.classList.remove('lg:ml-64');
                
                // Cacher les textes, ne garder que les icônes (emoji)
                const sidebarTexts = sidebar.querySelectorAll('.sidebar-nav a span.tracking-wide');
                sidebarTexts.forEach(text => text.classList.add('hidden'));
                
                // Sauvegarder l'état dans localStorage
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                // Agrandir la sidebar
                sidebar.classList.remove('!w-20');
                mainContent.classList.remove('lg:!ml-20');
                mainContent.classList.add('lg:ml-64');
                
                // Réafficher les textes
                const sidebarTexts = sidebar.querySelectorAll('.sidebar-nav a span.tracking-wide');
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                
                // Sauvegarder l'état dans localStorage
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        // Restaurer l'état de la sidebar au chargement
        document.addEventListener('DOMContentLoaded', function() {
            // S'assurer que les textes sont visibles par défaut
            const sidebar = document.getElementById('sidebar');
            const sidebarTexts = sidebar?.querySelectorAll('.sidebar-nav a span.tracking-wide');
            sidebarTexts?.forEach(text => text.classList.remove('hidden'));
            
            // Ensuite restaurer l'état sauvegardé si nécessaire
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                toggleSidebar();
            }
        });

        // Dark Mode Toggle - Mosaic Style
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            
            // Toggle icons
            const sunIcon = document.getElementById('dark-mode-icon-sun');
            const moonIcon = document.getElementById('dark-mode-icon-moon');
            
            if (isDark) {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
                localStorage.setItem('darkMode', 'true');
            } else {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
                localStorage.setItem('darkMode', 'false');
            }
        }

        // Restaurer le mode sombre au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const savedDarkMode = localStorage.getItem('darkMode');
            if (savedDarkMode === 'true') {
                document.documentElement.classList.add('dark');
                document.getElementById('dark-mode-icon-sun')?.classList.add('hidden');
                document.getElementById('dark-mode-icon-moon')?.classList.remove('hidden');
            }
        });

        // Search Modal - Placeholder function
        function openSearchModal() {
            alert('🔍 Fonction de recherche en développement\n\nProchainement : recherche rapide dans tous les modules du dashboard !');
        }

        function closeSearchModal() {
            // À implémenter avec le modal de recherche
        }

        // SPA-like navigation for dashboard links - DISABLED to fix partial loading issues
        // This AJAX navigation was causing pages to load only partially
        // Commenting out the entire function to use normal browser navigation instead
        /*
        document.addEventListener('DOMContentLoaded', function () {
            // Sélecteur pour tous les liens du menu admin (sidebar + mobile)
            const dashboardLinks = document.querySelectorAll('.sidebar-nav a, .mobile-menu-overlay a[href^="/admin"]');
            dashboardLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    // Ne pas intercepter les liens vides ou #
                    if (this.getAttribute('href') === '#' || this.getAttribute('href') === '') return;
                    // Si c'est le lien Dashboard et qu'on est déjà sur la route, forcer un reload complet
                    if (this.classList.contains('active')) {
                        window.location.reload();
                        return;
                    }
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    // Mettre à jour l'URL sans recharger
                    window.history.pushState({}, '', url);
                    // Charger le contenu via AJAX
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => {
                            if (!response.ok) throw new Error('Erreur de chargement');
                            return response.text();
                        })
                        .then(html => {
                            // Extraire le contenu principal de la page
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            let newContent = doc.querySelector('body') || doc.documentElement;

                            // Nettoyer le contenu existant avant d'injecter le nouveau
                            const contentContainer = document.getElementById('dashboard-content') || document.querySelector('main');
                            if (contentContainer) {
                                contentContainer.innerHTML = '';
                                // Injecter seulement le contenu, pas les balises HTML/HEAD
                                contentContainer.innerHTML = newContent.innerHTML;
                            } else {
                                console.error('Container de contenu non trouvé');
                                return;
                            }

                            // Réexécuter les scripts après chargement AJAX
                            const scripts = contentContainer.querySelectorAll('script');
                            scripts.forEach(script => {
                                const newScript = document.createElement('script');
                                if (script.src) {
                                    newScript.src = script.src;
                                } else {
                                    newScript.textContent = script.textContent;
                                }
                                script.parentNode.replaceChild(newScript, script);
                            });

                            // Re-initialiser Bootstrap si nécessaire
                            if (window.bootstrap && window.bootstrap.Tooltip) {
                                const tooltips = contentContainer.querySelectorAll('[data-bs-toggle="tooltip"]');
                                tooltips.forEach(el => new bootstrap.Tooltip(el));
                            }
                        })
                        .catch((error) => {
                            console.error('Erreur de chargement AJAX:', error);
                            const contentContainer = document.getElementById('dashboard-content') || document.querySelector('main');
                            if (contentContainer) {
                                contentContainer.innerHTML = '<div class="p-6 text-red-600">Erreur lors du chargement de la page: ' + error.message + '</div>';
                            } else {
                                console.error('Impossible d\'afficher l\'erreur, container non trouvé');
                            }
                        });
                });
            });

            // Gérer le bouton retour du navigateur
            window.addEventListener('popstate', function () {
                const url = window.location.pathname;
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        let newContent = doc.querySelector('body') || doc.documentElement;

                        const contentContainer = document.getElementById('dashboard-content') || document.querySelector('main');
                        if (contentContainer) {
                            contentContainer.innerHTML = '';
                            contentContainer.innerHTML = newContent.innerHTML;
                        } else {
                            console.error('Container de contenu non trouvé pour popstate');
                        }

                        // Réexécuter les scripts
                        const scripts = contentContainer.querySelectorAll('script');
                        scripts.forEach(script => {
                            const newScript = document.createElement('script');
                            if (script.src) {
                                newScript.src = script.src;
                            } else {
                                newScript.textContent = script.textContent;
                            }
                            script.parentNode.replaceChild(newScript, script);
                        });
                    });
            });
        });
        */
        // End of disabled AJAX navigation function

        // Sélecteur de couleur dynamique (gardé actif)
        document.addEventListener('DOMContentLoaded', function() {
            const colorInput = document.getElementById('dashboard-color');
            if (colorInput) {
                colorInput.addEventListener('input', function() {
                    document.body.style.backgroundColor = this.value;
                    document.documentElement.style.backgroundColor = this.value;
                });
            }
        });

        // SPA Modal System for Admin Dashboard
        function openAdminModal(section) {
            console.log('openAdminModal called with section:', section);

            // Empêcher la navigation par défaut
            event.preventDefault();
            event.stopPropagation();

            const modal = document.getElementById('admin-modal');
            const modalTitle = document.getElementById('admin-modal-title');
            const modalContent = document.getElementById('admin-modal-content');
            const modalIcon = document.getElementById('admin-modal-icon');

            console.log('Modal elements found:', { modal, modalTitle, modalContent, modalIcon });

            if (!modal || !modalTitle || !modalContent || !modalIcon) {
                console.error('Modal elements not found');
                alert('Erreur: Éléments du modal non trouvés. Rechargez la page.');
                return;
            }

            // Configuration des sections
            const sections = {
                'users': {
                    title: '👥 Gestion des Utilisateurs',
                    icon: '👥',
                    url: '/admin/modal/users'
                },
                'orders': {
                    title: '🧾 Gestion des Commandes',
                    icon: '🧾',
                    url: '/admin/modal/orders'
                },
                'articles': {
                    title: '📰 Gestion des Articles',
                    icon: '📰',
                    url: '/admin/modal/articles'
                },
                'products': {
                    title: '📦 Gestion des Produits',
                    icon: '📦',
                    url: '/admin/modal/products'
                },
                'subscriptions': {
                    title: '💳 Gestion des Abonnements',
                    icon: '💳',
                    url: '/admin/modal/subscriptions'
                },
                'stats': {
                    title: '📊 Statistiques',
                    icon: '📊',
                    url: '/admin/modal/stats'
                },
                'messages': {
                    title: '✉️ Messages',
                    icon: '✉️',
                    url: '/admin/modal/messages'
                },
                'settings': {
                    title: '⚙️ Paramètres',
                    icon: '⚙️',
                    url: '/admin/modal/settings'
                }
            };

            const config = sections[section];
            if (!config) {
                console.error('Section not found:', section);
                alert('Erreur: Section non trouvée: ' + section);
                return;
            }

            console.log('Config found:', config);

            // Mettre à jour le titre et l'icône
            modalTitle.textContent = config.title;
            modalIcon.textContent = config.icon;

            // Afficher un loader
            modalContent.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600">Chargement...</span>
                </div>
            `;

            // Afficher le modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            console.log('Modal displayed, fetching content from:', config.url);

            // Charger le contenu via AJAX
            fetch(config.url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Fetch response:', response);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                console.log('HTML received, length:', html.length);

                // Le HTML reçu est déjà le contenu partiel, pas besoin de parsing complexe
                let content = html.trim();

                console.log('Content length after trim:', content.length);

                if (!content) {
                    content = '<div class="text-center py-8 text-gray-500">Contenu non disponible</div>';
                }

                // Injecter le contenu dans le modal avec un wrapper responsive
                modalContent.innerHTML = `
                    <div class="max-h-96 overflow-y-auto">
                        <div class="transform scale-90 origin-top">
                            ${content}
                        </div>
                    </div>
                `;

                // Réexécuter les scripts du contenu chargé
                const scripts = modalContent.querySelectorAll('script');
                scripts.forEach(script => {
                    if (script.src) {
                        // Script externe - créer un nouveau script
                        const newScript = document.createElement('script');
                        newScript.src = script.src;
                        document.head.appendChild(newScript);
                    } else if (script.textContent) {
                        // Script inline - l'exécuter
                        try {
                            eval(script.textContent);
                        } catch (e) {
                            console.warn('Erreur lors de l\'exécution du script:', e);
                        }
                    }
                });

                // Ajuster la taille du modal selon le contenu
                setTimeout(() => {
                    adjustModalSize();
                }, 100);

                console.log('Content loaded successfully');
            })
            .catch(error => {
                console.error('Erreur de chargement:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <div class="text-4xl mb-4">❌</div>
                        <p class="font-semibold">Erreur de chargement</p>
                        <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                        <button onclick="openAdminModal('${section}')"
                                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Réessayer
                        </button>
                    </div>
                `;
            });
        }

        function closeAdminModal() {
            const modal = document.getElementById('admin-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function adjustModalSize() {
            const modal = document.getElementById('admin-modal');
            const content = document.getElementById('admin-modal-content');

            if (modal && content) {
                const contentHeight = content.scrollHeight;
                const maxHeight = window.innerHeight * 0.8;

                if (contentHeight > maxHeight) {
                    content.style.maxHeight = maxHeight + 'px';
                }
            }
        }

        // Fermer le modal avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAdminModal();
            }
        });

        // Initialisation du système modal
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin modal system initialized');

            // Test rapide pour vérifier que les éléments existent
            const modal = document.getElementById('admin-modal');
            console.log('Modal element found:', !!modal);

            // Ajouter des gestionnaires d'événements aux liens du modal
            const modalLinks = document.querySelectorAll('a[onclick*="openAdminModal"]');
            console.log('Modal links found:', modalLinks.length);

            modalLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const onclickAttr = this.getAttribute('onclick');
                    const match = onclickAttr.match(/openAdminModal\('([^']+)'\)/);
                    if (match) {
                        const section = match[1];
                        console.log('Link clicked for section:', section);
                        openAdminModal(section);
                    }
                    return false;
                });
            });
        });

        // Fonction de test pour déboguer
        function testModal() {
            console.log('Testing modal system...');
            openAdminModal('users');
        }

        // Exposer les fonctions globalement pour le débogage
        window.openAdminModal = openAdminModal;
        window.closeAdminModal = closeAdminModal;
        window.testModal = testModal;

    </script>

    <!-- Admin Modal SPA System - Mosaic Design -->
    <div id="admin-modal" 
         class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-y-auto"
         style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.6);"
         onclick="if(event.target.id === 'admin-modal') closeAdminModal()">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-6xl shadow-2xl 
                    transform transition-all duration-300 ease-out
                    animate-modal-enter my-8">
            
            <!-- Modal Header - Gradient moderne -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 
                        text-white p-5 flex items-center justify-between
                        border-b border-white/20">
                <div class="flex items-center gap-3">
                    <span id="admin-modal-icon" class="text-3xl animate-bounce-slow">📊</span>
                    <div>
                        <h3 id="admin-modal-title" class="text-xl font-bold tracking-wide">
                            Chargement...
                        </h3>
                        <p class="text-xs text-white/70 mt-0.5">Dashboard administrateur</p>
                    </div>
                </div>
                <button onclick="closeAdminModal()" 
                        class="group flex items-center justify-center w-10 h-10 
                               rounded-full hover:bg-white/20 
                               transition-all duration-200 ease-in-out
                               transform hover:rotate-90"
                        title="Fermer (ESC)">
                    <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div id="admin-modal-content" 
                 class="overflow-y-auto bg-gray-50 dark:bg-gray-900
                        scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-200
                        dark:scrollbar-track-gray-800"
                 style="max-height: calc(90vh - 120px);">
                <!-- Content will be loaded here -->
                <div class="flex items-center justify-center py-20">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent mb-4"></div>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">Chargement du contenu...</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (optionnel) -->
            <div class="bg-gray-100 dark:bg-gray-800 px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Belier Intrépide</span> • Dashboard Admin
                </div>
                <button onclick="closeAdminModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 
                               hover:text-gray-900 dark:hover:text-white
                               transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Animations Mosaic pour le modal */
        @keyframes modal-enter {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-modal-enter {
            animation: modal-enter 0.3s ease-out;
        }

        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }

        /* Scrollbar personnalisée pour le modal */
        .scrollbar-thin::-webkit-scrollbar {
            width: 8px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Afficher le modal avec animation */
        #admin-modal.show {
            display: flex !important;
        }

        /* Styles Chatbot AI */
        @keyframes bounce-gentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .chatbot-bubble {
            animation: bounce-gentle 3s ease-in-out infinite;
        }

        .chatbot-message {
            animation: fadeInUp 0.3s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-indicator span {
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }
    </style>

    <!-- AI Chatbot Assistant - Floating -->
    <div x-data="chatbotManager()" 
         x-init="init()"
         class="fixed bottom-6 right-6 z-50"
         style="z-index: 9999;">
        
        <!-- Chatbot Window -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="mb-4 w-96 h-[600px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden"
             style="display: none;">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Assistant AI</h3>
                        <p class="text-xs text-white/80">En ligne • Prêt à vous aider</p>
                    </div>
                </div>
                <button @click="toggleChat()" 
                        class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Messages Container -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900"
                 x-ref="messagesContainer"
                 @scroll.debounce="handleScroll()">
                
                <!-- Welcome Message -->
                <div x-show="messages.length === 0" class="text-center py-8">
                    <span class="text-6xl mb-4 block">👋</span>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        Bonjour ! Je suis votre assistant AI
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Je peux vous aider avec :
                    </p>
                    <div class="space-y-2 text-sm text-left max-w-xs mx-auto">
                        <button @click="sendQuickCommand('stats')" 
                                class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                            📊 Afficher les statistiques
                        </button>
                        <button @click="sendQuickCommand('users')" 
                                class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                            👥 Gérer les utilisateurs
                        </button>
                        <button @click="sendQuickCommand('articles')" 
                                class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                            📰 Créer un article
                        </button>
                        <button @click="sendQuickCommand('help')" 
                                class="w-full text-left p-3 bg-white dark:bg-gray-800 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                            ❓ Obtenir de l'aide
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <template x-for="(message, index) in messages" :key="index">
                    <div :class="message.sender === 'user' ? 'flex justify-end' : 'flex justify-start'"
                         class="chatbot-message">
                        <div :class="message.sender === 'user' 
                                      ? 'bg-blue-600 text-white' 
                                      : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700'"
                             class="max-w-[80%] rounded-2xl px-4 py-3 shadow-sm">
                            <p class="text-sm whitespace-pre-wrap" x-html="message.text"></p>
                            <span class="text-xs opacity-70 mt-1 block" x-text="message.time"></span>
                        </div>
                    </div>
                </template>

                <!-- Typing Indicator -->
                <div x-show="isTyping" class="flex justify-start chatbot-message">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 shadow-sm">
                        <div class="typing-indicator flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <form @submit.prevent="sendMessage()" class="flex gap-2">
                    <input type="text" 
                           x-model="inputMessage"
                           :disabled="isTyping"
                           placeholder="Tapez votre message..."
                           class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 
                                  bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                    <button type="submit" 
                            :disabled="!inputMessage.trim() || isTyping"
                            class="bg-blue-600 text-white p-3 rounded-xl hover:bg-blue-700 
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    Propulsé par AI • Réponses intelligentes
                </p>
            </div>
        </div>

        <!-- Floating Button -->
        <button @click="toggleChat()" 
                class="chatbot-bubble w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 
                       rounded-full shadow-2xl flex items-center justify-center
                       hover:scale-110 transition-transform duration-200
                       relative group">
            <span class="text-3xl" x-show="!isOpen">💬</span>
            <svg x-show="isOpen" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            
            <!-- Badge notification -->
            <span x-show="unreadCount > 0" 
                  class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs font-bold 
                         rounded-full flex items-center justify-center"
                  x-text="unreadCount"></span>
            
            <!-- Tooltip -->
            <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 
                        bg-gray-900 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap
                        opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                Assistant AI
            </div>
        </button>
    </div>

    <script>
        // SPA Application Manager avec Alpine.js
        function spaApp() {
            return {
                isLoading: false,
                currentPage: '{{ $title ?? "Dashboard" }}',
                currentUrl: window.location.href,
                loadingIcon: '🏠',
                
                init() {
                    console.log('✅ SPA App initialized');
                    this.updatePageTitle();
                },

                async navigateTo(url, pageName) {
                    // Éviter le rechargement si même page
                    if (this.currentUrl === url) {
                        console.log('⚠️ Déjà sur cette page');
                        return;
                    }

                    console.log('🚀 Navigation SPA vers:', pageName);
                    
                    this.isLoading = true;
                    this.currentPage = pageName;
                    this.currentUrl = url;
                    this.updateLoadingIcon(pageName);

                    try {
                        // Fetch le contenu via AJAX
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                            }
                        });

                        if (!response.ok) throw new Error('Erreur de chargement');

                        const html = await response.text();
                        
                        // Parser le HTML reçu
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Extraire le contenu principal
                        const newContent = doc.querySelector('main')?.innerHTML || 
                                         doc.querySelector('[x-data]')?.innerHTML ||
                                         html;

                        // Simuler un délai réaliste pour l'UX
                        await new Promise(resolve => setTimeout(resolve, 400));

                        // Injecter le nouveau contenu
                        const mainElement = document.querySelector('main');
                        if (mainElement) {
                            mainElement.innerHTML = newContent;
                            
                            // Re-initialiser les scripts Alpine.js dans le nouveau contenu
                            if (window.Alpine) {
                                Alpine.initTree(mainElement);
                            }

                            // Re-initialiser Chart.js si présent
                            if (typeof initCharts === 'function') {
                                setTimeout(() => initCharts(), 100);
                            }
                        }

                        // Mettre à jour l'URL sans rechargement
                        window.history.pushState({ page: pageName, url: url }, pageName, url);
                        
                        // Scroll en haut de page
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        this.updatePageTitle();
                        
                        console.log('✅ Contenu chargé avec succès');

                    } catch (error) {
                        console.error('❌ Erreur navigation SPA:', error);
                        
                        // Fallback: rechargement classique
                        window.location.href = url;
                    } finally {
                        this.isLoading = false;
                    }
                },

                async loadSection(section, title) {
                    console.log('📄 Chargement section:', section);
                    
                    this.isLoading = true;
                    this.currentPage = title;
                    this.updateLoadingIcon(section);

                    // Simuler le chargement de données
                    await new Promise(resolve => setTimeout(resolve, 600));

                    // Contenu dynamique selon la section
                    const content = this.getSectionContent(section, title);
                    
                    const mainElement = document.querySelector('main');
                    if (mainElement) {
                        // Animation de sortie
                        mainElement.style.opacity = '0';
                        mainElement.style.transform = 'translateY(20px)';
                        
                        await new Promise(resolve => setTimeout(resolve, 200));
                        
                        mainElement.innerHTML = content;
                        
                        // Animation d'entrée
                        await new Promise(resolve => setTimeout(resolve, 50));
                        mainElement.style.transition = 'all 0.3s ease-out';
                        mainElement.style.opacity = '1';
                        mainElement.style.transform = 'translateY(0)';
                    }

                    // Mettre à jour l'URL
                    const newUrl = `{{ url('/admin') }}/${section}`;
                    window.history.pushState({ page: title, section: section }, title, newUrl);

                    this.isLoading = false;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                getSectionContent(section, title) {
                    const icons = {
                        users: '👥',
                        orders: '🧾',
                        articles: '📰',
                        products: '📦',
                        subscriptions: '💳',
                        stats: '📊',
                        messages: '✉️',
                        settings: '⚙️'
                    };

                    return `
                        <div class="px-4 sm:px-6 lg:px-8 py-8">
                            <div class="max-w-7xl mx-auto">
                                <!-- Header -->
                                <div class="mb-8">
                                    <div class="flex items-center gap-4 mb-4">
                                        <span class="text-5xl">${icons[section] || '📋'}</span>
                                        <div>
                                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">${title}</h1>
                                            <p class="text-gray-600 dark:text-gray-400 mt-1">Gestion et administration</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content Card -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="p-8">
                                        <div class="text-center py-12">
                                            <span class="text-6xl mb-4 block">${icons[section] || '📋'}</span>
                                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                                ${title}
                                            </h2>
                                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                                Cette section est en cours de développement avec Alpine.js
                                            </p>
                                            <div class="inline-flex items-center gap-2 px-6 py-3 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg">
                                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Navigation SPA active
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow cursor-pointer">
                                        <div class="text-3xl mb-3">➕</div>
                                        <h3 class="font-bold text-lg mb-2">Créer nouveau</h3>
                                        <p class="text-blue-100 text-sm">Ajouter un nouvel élément</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow cursor-pointer">
                                        <div class="text-3xl mb-3">📊</div>
                                        <h3 class="font-bold text-lg mb-2">Statistiques</h3>
                                        <p class="text-emerald-100 text-sm">Voir les analyses</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow cursor-pointer">
                                        <div class="text-3xl mb-3">⚙️</div>
                                        <h3 class="font-bold text-lg mb-2">Paramètres</h3>
                                        <p class="text-purple-100 text-sm">Configurer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                },

                updateLoadingIcon(page) {
                    const icons = {
                        'Dashboard': '🏠',
                        'Utilisateurs': '👥',
                        'Commandes': '🧾',
                        'Articles': '📰',
                        'Gestion des Articles': '📰',
                        'Produits': '📦',
                        'Abonnements': '💳',
                        'Statistiques': '📊',
                        'Messages': '✉️',
                        'Paramètres': '⚙️',
                        'users': '👥',
                        'orders': '🧾',
                        'articles': '📰',
                        'products': '📦',
                        'subscriptions': '💳',
                        'stats': '📊',
                        'messages': '✉️',
                        'settings': '⚙️'
                    };
                    this.loadingIcon = icons[page] || '📄';
                },

                updatePageTitle() {
                    document.title = `${this.currentPage} - Belier Intrépide • Dashboard Admin`;
                },

                handlePopState(event) {
                    if (event.state) {
                        this.currentPage = event.state.page || 'Dashboard';
                        console.log('⬅️ Navigation arrière vers:', this.currentPage);
                    }
                }
            };
        }

        // Chatbot Manager avec Alpine.js
        function chatbotManager() {
            return {
                isOpen: false,
                messages: [],
                inputMessage: '',
                isTyping: false,
                unreadCount: 0,

                init() {
                    // Charger l'historique depuis localStorage
                    const saved = localStorage.getItem('chatbot_messages');
                    if (saved) {
                        this.messages = JSON.parse(saved);
                    }

                    // Message de bienvenue après 2 secondes si premier usage
                    if (this.messages.length === 0) {
                        setTimeout(() => {
                            if (!this.isOpen) {
                                this.unreadCount = 1;
                            }
                        }, 2000);
                    }
                },

                toggleChat() {
                    this.isOpen = !this.isOpen;
                    this.unreadCount = 0;
                    
                    if (this.isOpen) {
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                },

                async sendMessage() {
                    if (!this.inputMessage.trim()) return;

                    const userMessage = {
                        sender: 'user',
                        text: this.inputMessage,
                        time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                    };

                    this.messages.push(userMessage);
                    const question = this.inputMessage;
                    this.inputMessage = '';
                    this.isTyping = true;

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });

                    // Simuler une réponse AI (à remplacer par vraie API)
                    try {
                        const response = await this.getAIResponse(question);
                        
                        setTimeout(() => {
                            const aiMessage = {
                                sender: 'ai',
                                text: response,
                                time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                            };

                            this.messages.push(aiMessage);
                            this.isTyping = false;
                            
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });

                            // Sauvegarder l'historique
                            this.saveMessages();
                        }, 1000 + Math.random() * 1000); // Délai réaliste 1-2s

                    } catch (error) {
                        this.isTyping = false;
                        console.error('Erreur chatbot:', error);
                    }
                },

                async sendQuickCommand(command) {
                    const commands = {
                        'stats': 'Afficher les statistiques du dashboard',
                        'users': 'Montrer la liste des utilisateurs',
                        'articles': 'Comment créer un nouvel article ?',
                        'help': 'Quelles sont tes fonctionnalités ?'
                    };

                    this.inputMessage = commands[command] || command;
                    await this.sendMessage();
                },

                async getAIResponse(question) {
                    // Réponses intelligentes basées sur le contexte du dashboard
                    const responses = {
                        'stats': '📊 Voici un résumé de vos statistiques :\n\n• Utilisateurs totaux : <strong>' + (document.querySelectorAll('[data-stat="users"]')[0]?.textContent || 'N/A') + '</strong>\n• Articles publiés : <strong>En cours de calcul...</strong>\n• Commandes aujourd\'hui : <strong>En cours...</strong>\n\nVoulez-vous voir plus de détails ? Je peux ouvrir le modal statistiques pour vous.',
                        
                        'users': '👥 Pour gérer les utilisateurs :\n\n1. Je peux ouvrir le modal utilisateurs\n2. Vous pouvez ajouter, modifier ou supprimer des users\n3. Gérer les rôles et permissions\n\nSouhaitez-vous que j\'ouvre le panneau utilisateurs ?',
                        
                        'article': '📰 Pour créer un article :\n\n1. Cliquez sur le bouton "Nouvel Article" ✅\n2. Remplissez le titre et le contenu\n3. Ajoutez une image (optionnel)\n4. Choisissez la catégorie\n5. Publiez ou sauvegardez en brouillon\n\nBesoin d\'aide pour une étape spécifique ?',
                        
                        'help': '🤖 Je suis votre assistant AI pour le dashboard admin !\n\nJe peux vous aider à :\n• 📊 Consulter les statistiques\n• 👥 Gérer les utilisateurs\n• 📰 Créer/modifier des articles\n• 💳 Suivre les abonnements\n• ⚙️ Configurer les paramètres\n• 🔍 Trouver rapidement des informations\n\nPosez-moi n\'importe quelle question !',
                        
                        'default': '🤔 Voici ce que je comprends de votre question :\n\n"<em>' + question + '</em>"\n\nJe peux vous aider avec :\n• Les statistiques et données\n• La gestion des utilisateurs\n• La création de contenu\n• Les paramètres du système\n\nPouvez-vous préciser ce dont vous avez besoin ?'
                    };

                    // Détection intelligente des mots-clés
                    const lowerQ = question.toLowerCase();
                    
                    if (lowerQ.includes('stat') || lowerQ.includes('chiffre') || lowerQ.includes('nombre')) {
                        return responses.stats;
                    } else if (lowerQ.includes('user') || lowerQ.includes('utilisateur') || lowerQ.includes('membre')) {
                        return responses.users;
                    } else if (lowerQ.includes('article') || lowerQ.includes('contenu') || lowerQ.includes('publier')) {
                        return responses.article;
                    } else if (lowerQ.includes('aide') || lowerQ.includes('help') || lowerQ.includes('fonction')) {
                        return responses.help;
                    } else {
                        return responses.default;
                    }
                },

                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },

                handleScroll() {
                    // Future: détecter le scroll pour charger plus de messages
                },

                saveMessages() {
                    // Garder seulement les 50 derniers messages
                    const toSave = this.messages.slice(-50);
                    localStorage.setItem('chatbot_messages', JSON.stringify(toSave));
                },

                clearHistory() {
                    this.messages = [];
                    localStorage.removeItem('chatbot_messages');
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
