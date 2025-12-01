@extends('layouts.admin')

@section('title', 'Tableau de Bord Amélioré')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-500 rounded-xl shadow-lg p-8 text-white">
        <h1 class="text-3xl font-bold mb-2">🎯 Tableau de Bord Professionnel</h1>
        <p class="text-green-100">Bienvenue dans votre espace d'administration amélioré</p>
    </div>

    <!-- Real-Time Statistics -->
    <section class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📊 Statistiques en Temps Réel</h2>
        @include('components.real-time-stats', [
            'articlesCount' => \App\Models\Article::count(),
            'usersCount' => \App\Models\User::count(),
            'premiumCount' => \App\Models\User::where('subscription_type', 'premium')->count(),
            'messagesCount' => \App\Models\Message::where('read_at', null)->count()
        ])
    </section>

    <!-- Quick Actions -->
    <section class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">⚡ Actions Rapides</h2>
        @include('components.quick-actions-advanced')
    </section>

    <!-- Advanced Filters -->
    <section class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">🔍 Filtres Avancés</h2>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
            @include('components.advanced-filters', [
                'categories' => \App\Models\Category::all(),
                'showLabel' => 'Afficher les filtres'
            ])
        </form>
    </section>

    <!-- Theme Personalization -->
    <section class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">🎨 Personnalisation</h2>
        @include('components.theme-personalizer')
    </section>

    <!-- Recent Activity -->
    <section class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📝 Activité Récente</h2>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">✨ Nouvel Article Créé</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Il y a 2 heures</p>
                        </div>
                        <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm">Succès</span>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">👤 Utilisateur Inscrit</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Il y a 4 heures</p>
                        </div>
                        <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">Utilisateur</span>
                    </div>
                </div>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">📦 Commande Traitée</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Il y a 6 heures</p>
                        </div>
                        <span class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full text-sm">Commande</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Enhanced Dashboard Styles */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideInUp 0.5s ease-out;
    }

    /* Gradient Text */
    .gradient-text-green {
        @apply bg-gradient-to-r from-green-600 to-green-500 bg-clip-text text-transparent;
    }

    .gradient-text-blue {
        @apply bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent;
    }

    /* Smooth Transitions */
    section {
        @apply animate-slide-in;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .grid {
            @apply grid-cols-1;
        }
    }
</style>
@endsection
