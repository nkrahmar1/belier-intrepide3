@extends('layouts.admin')
@section('title', 'Dashboard Professionnel - Admin')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
</style>
@endpush

@section('content')
<!-- Dashboard content (sans conteneur externe - déjà dans le layout) -->
<div class="w-full" x-data="professionalDashboard()" x-init="init()">

        <!-- Header Actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Administrateur</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Gérez votre plateforme en temps réel</p>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button @click="refreshStats()" :disabled="loading"
                    class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                    <svg class="fill-current shrink-0" :class="{ 'animate-spin': loading }" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 14c-3.3 0-6-2.7-6-6s2.7-6 6-6 6 2.7 6 6-2.7 6-6 6z"/>
                        <path d="M8 4c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1z"/>
                    </svg>
                    <span class="ml-2" x-text="loading ? 'Actualisation...' : 'Actualiser'"></span>
                </button>

                <button @click="showCreateArticleModal()"
                    class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                    <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="ml-2">Créer Article</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-12 gap-6 mb-8">

            <!-- Total Articles -->
            <div class="col-span-full sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-sm rounded-xl card-hover">
                <div class="px-5 py-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase">Articles Totaux</h3>
                        <svg class="w-8 h-8 fill-current text-blue-500" viewBox="0 0 32 32">
                            <path d="M19 11h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2zm0 4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2zm3-10h-2V3a1 1 0 0 0-2 0v2h-4V3a1 1 0 0 0-2 0v2h-2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3zm1 17a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V7h4v1a1 1 0 0 0 2 0V7h2a1 1 0 0 1 1 1v14z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline">
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100" x-text="stats.articles_total || {{ $articlesCount }}"></div>
                        <div class="ml-2 text-sm font-semibold text-green-600 dark:text-green-400" x-text="'+' + (stats.articles_today || {{ $stats['articles_today'] }}) + ' aujourd\'hui'"></div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span x-text="stats.articles_published || {{ $stats['articles_published'] }}"></span> publiés •
                        <span x-text="stats.articles_draft || {{ $stats['articles_draft'] }}"></span> brouillons
                    </div>
                </div>
            </div>

            <!-- Total Utilisateurs -->
            <div class="col-span-full sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-sm rounded-xl card-hover">
                <div class="px-5 py-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase">Utilisateurs</h3>
                        <svg class="w-8 h-8 fill-current text-emerald-500" viewBox="0 0 32 32">
                            <path d="M16 15.503A5.041 5.041 0 1 0 16 5.42a5.041 5.041 0 0 0 0 10.083zm0 2.215c-6.703 0-11 3.699-11 5.5v3.363h22v-3.363c0-1.801-4.297-5.5-11-5.5z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline">
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100" x-text="stats.users_total || {{ $usersCount }}"></div>
                        <div class="ml-2 text-sm font-semibold text-green-600 dark:text-green-400" x-text="'+' + (stats.users_today || {{ $stats['users_today'] }}) + ' aujourd\'hui'"></div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span x-text="stats.active_users || {{ $stats['active_users'] }}"></span> actifs •
                        <span x-text="stats.premium_users || {{ $stats['premium_users'] }}"></span> premium
                    </div>
                </div>
            </div>

            <!-- Abonnements Actifs -->
            <div class="col-span-full sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-sm rounded-xl card-hover">
                <div class="px-5 py-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase">Abonnements</h3>
                        <svg class="w-8 h-8 fill-current text-amber-500" viewBox="0 0 32 32">
                            <path d="M21 14a.75.75 0 0 1-.75-.75 1.5 1.5 0 0 0-1.5-1.5.75.75 0 1 1 0-1.5 1.5 1.5 0 0 0 1.5-1.5.75.75 0 1 1 1.5 0 1.5 1.5 0 0 0 1.5 1.5.75.75 0 1 1 0 1.5 1.5 1.5 0 0 0-1.5 1.5.75.75 0 0 1-.75.75zm-14 8a.75.75 0 0 1-.75-.75A1.5 1.5 0 0 0 4.75 19.75a.75.75 0 0 1 0-1.5A1.5 1.5 0 0 0 6.25 16.75a.75.75 0 0 1 1.5 0A1.5 1.5 0 0 0 9.25 18.25a.75.75 0 0 1 0 1.5A1.5 1.5 0 0 0 7.75 21.25a.75.75 0 0 1-.75.75zm16-8a.75.75 0 0 1-.75-.75 1.5 1.5 0 0 0-1.5-1.5.75.75 0 0 1 0-1.5 1.5 1.5 0 0 0 1.5-1.5.75.75 0 0 1 1.5 0 1.5 1.5 0 0 0 1.5 1.5.75.75 0 0 1 0 1.5 1.5 1.5 0 0 0-1.5 1.5.75.75 0 0 1-.75.75z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline">
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100" x-text="stats.active_subscriptions || {{ $stats['active_subscriptions'] }}"></div>
                        <div class="ml-2 text-sm font-medium text-amber-600 dark:text-amber-400">actifs</div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Revenus: <span class="font-semibold text-green-600" x-text="formatCurrency(stats.revenue_total || {{ $stats['revenue_total'] ?? 0 }})"></span>
                    </div>
                </div>
            </div>

            <!-- Commandes (si disponible) -->
            <div class="col-span-full sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-sm rounded-xl card-hover">
                <div class="px-5 py-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase">Commandes</h3>
                        <svg class="w-8 h-8 fill-current text-purple-500" viewBox="0 0 32 32">
                            <path d="M13 15l11-7L11.504 2.672a1 1 0 0 0-1.019.003L1 9l12 7z"/>
                            <path d="M13 17L1 10v14a1 1 0 0 0 .504.868L11 31V17z"/>
                            <path d="M15 17v14l9.496-6.132A1 1 0 0 0 25 24V10l-12 7z"/>
                        </svg>
                    </div>
                    <div class="flex items-baseline">
                        <div class="text-3xl font-bold text-gray-800 dark:text-gray-100" x-text="stats.orders_total || {{ $ordersCount ?? 0 }}"></div>
                        <div class="ml-2 text-sm font-semibold text-green-600 dark:text-green-400" x-text="'+' + (stats.orders_today || 0) + ' aujourd\'hui'"></div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        En attente: <span x-text="stats.pending_orders || 0"></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-12 gap-6 mb-8">

            <!-- Articles par mois (Chart.js) -->
            <div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <div class="px-5 pt-5">
                    <header class="flex justify-between items-start mb-2">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Articles publiés / mois</h2>
                        <div class="text-sm font-medium text-green-600 dark:text-green-400 px-2 py-1 bg-green-100 dark:bg-green-500/20 rounded">
                            +<span x-text="stats.articles_this_month || 0"></span> ce mois
                        </div>
                    </header>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-4">
                        12 derniers mois
                    </div>
                </div>
                <!-- Chart built with Chart.js -->
                <div class="px-5 pb-5">
                    <canvas id="articlesChart" width="595" height="248"></canvas>
                </div>
            </div>

            <!-- Revenus par mois (Chart.js) -->
            <div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
                <div class="px-5 pt-5">
                    <header class="flex justify-between items-start mb-2">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Revenus mensuels</h2>
                        <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400 px-2 py-1 bg-emerald-100 dark:bg-emerald-500/20 rounded">
                            <span x-text="formatCurrency(stats.revenue_this_month || 0)"></span>
                        </div>
                    </header>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase mb-4">
                        Abonnements actifs
                    </div>
                </div>
                <!-- Chart built with Chart.js -->
                <div class="px-5 pb-5">
                    <canvas id="revenueChart" width="595" height="248"></canvas>
                </div>
            </div>

        </div>

        <!-- Articles Table -->
        <div class="col-span-full bg-white dark:bg-gray-800 shadow-sm rounded-xl">
            <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Articles Récents</h2>
            </header>
            <div class="p-3">

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <!-- Table header -->
                        <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
                            <tr>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Article</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Catégorie</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Statut</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-left">Date</div>
                                </th>
                                <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-center">Actions</div>
                                </th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($articles as $article)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($article->image)
                                        <img class="w-10 h-10 rounded-lg object-cover mr-3" src="{{ Storage::url($article->image) }}" alt="{{ $article->titre }}">
                                        @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ Str::limit($article->titre, 40) }}</div>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400">
                                        {{ $article->category->nom ?? 'Sans catégorie' }}
                                    </span>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    @if($article->is_published)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Publié
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        Brouillon
                                    </span>
                                    @endif
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-gray-600 dark:text-gray-400">{{ $article->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="togglePublish({{ $article->id ?? 'null' }}, {{ $article->is_published ? 'true' : 'false' }})"
                                            class="text-sm px-3 py-1.5 rounded-lg {{ $article->is_published ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' : 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-500/30' }} transition-colors">
                                            {{ $article->is_published ? 'Dépublier' : 'Publier' }}
                                        </button>
                                        <a href="{{ route('admin.articles.edit', ['article' => $article->id]) }}"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                            title="Modifier l'article">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button @click="deleteArticle({{ $article->id ?? 'null' }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                            title="Supprimer l'article">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-2 first:pl-5 last:pr-5 py-8 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">Aucun article trouvé</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $articles->links() }}
                </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Modal Création Article (réutilisé de l'ancien dashboard) -->
    <div x-show="showModal" x-cloak
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        @click.self="showModal = false">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
            @click.stop>

            <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Créer un nouvel article</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="createArticle()" class="p-6 space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Titre *</label>
                    <input type="text" x-model="formData.titre" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catégorie *</label>
                    <select x-model="formData.category_id" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Extrait</label>
                    <textarea x-model="formData.extrait" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contenu *</label>
                    <textarea x-model="formData.contenu" rows="6" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="formData.is_premium" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Article Premium</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="formData.is_published" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Publier maintenant</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" :disabled="submitting"
                        class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-lg font-semibold disabled:opacity-50 transition-all">
                        <span x-text="submitting ? 'Création...' : 'Créer l\'article'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                        class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Annuler
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<!-- Fin du conteneur principal dashboard -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function professionalDashboard() {
    return {
        loading: false,
        showModal: false,
        submitting: false,
        stats: {},
        formData: {
            titre: '',
            contenu: '',
            extrait: '',
            category_id: '',
            is_premium: false,
            is_published: false
        },
        articlesChart: null,
        revenueChart: null,

        init() {
            this.initCharts();
            console.log('✅ Dashboard professionnel initialisé');
        },

        initCharts() {
            const chartData = @json($chartData);

            // Destroy existing charts
            if (this.articlesChart !== null) {
                this.articlesChart.destroy();
                this.articlesChart = null;
            }
            if (this.revenueChart !== null) {
                this.revenueChart.destroy();
                this.revenueChart = null;
            }

            // Articles Chart
            const articlesCtx = document.getElementById('articlesChart');
            if (articlesCtx) {
                this.articlesChart = new Chart(articlesCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Articles publiés',
                            data: chartData.articles,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgb(16, 185, 129)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(16, 185, 129, 0.5)',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#9ca3af' },
                                grid: { color: 'rgba(156, 163, 175, 0.1)' }
                            },
                            x: {
                                ticks: { color: '#9ca3af' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                this.revenueChart = new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Revenus (€)',
                            data: chartData.revenue,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(59, 130, 246, 0.5)',
                                borderWidth: 1,
                                callbacks: {
                                    label: (context) => {
                                        return 'Revenus: ' + context.parsed.y.toFixed(2) + ' €';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#9ca3af',
                                    callback: (value) => value.toFixed(0) + ' €'
                                },
                                grid: { color: 'rgba(156, 163, 175, 0.1)' }
                            },
                            x: {
                                ticks: { color: '#9ca3af' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        },

        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(value);
        },

        refreshStats() {
            this.loading = true;
            fetch('/api/admin/stats')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.stats = data.stats;
                        console.log('✅ Statistiques actualisées');
                    }
                })
                .catch(error => console.error('❌ Erreur:', error))
                .finally(() => {
                    this.loading = false;
                });
        },

        showCreateArticleModal() {
            this.showModal = true;
            this.formData = {
                titre: '',
                contenu: '',
                extrait: '',
                category_id: '',
                is_premium: false,
                is_published: false
            };
        },

        createArticle() {
            this.submitting = true;

            fetch('/admin/articles/quick-create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Article créé avec succès !');
                    this.showModal = false;
                    window.location.reload();
                } else {
                    alert('❌ Erreur: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('❌ Erreur:', error);
                alert('❌ Erreur lors de la création');
            })
            .finally(() => {
                this.submitting = false;
            });
        },

        togglePublish(articleId, isCurrentlyPublished) {
            if (!confirm(isCurrentlyPublished ? 'Dépublier cet article ?' : 'Publier cet article ?')) return;

            fetch(`/admin/articles/${articleId}/toggle-publish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('❌ Erreur: ' + data.message);
                }
            })
            .catch(error => console.error('❌ Erreur:', error));
        },

        deleteArticle(articleId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

            fetch(`/admin/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('❌ Erreur: ' + data.message);
                }
            })
            .catch(error => console.error('❌ Erreur:', error));
        }
    }
}
</script>
@endpush

@endsection
