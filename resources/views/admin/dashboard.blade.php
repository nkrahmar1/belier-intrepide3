@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="dashboardData()" x-init="init()">
    
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Dashboard</h1>
        <p class="mt-1 text-gray-500 text-sm dark:text-gray-400">
            Bienvenue sur votre tableau de bord administrateur
        </p>
    </div>

    <!-- Stats Cards - TailAdmin Style -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">
        
        <!-- Articles Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 3a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H7zm2 3a1 1 0 000 2h6a1 1 0 100-2H9zm0 4a1 1 0 000 2h6a1 1 0 100-2H9zm0 4a1 1 0 000 2h3a1 1 0 100-2H9z"/>
                </svg>
            </div>

            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Articles</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90" x-text="stats.articles_total || {{ $articlesCount }}"></h4>
                </div>

                <span class="flex items-center gap-1 rounded-full bg-green-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247V10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125V3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z" fill=""/>
                    </svg>
                    +<span x-text="stats.articles_today || {{ $stats['articles_today'] }}"></span>
                </span>
            </div>
        </div>

        <!-- Users Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.8937 18.2112C13.9797 18.1175 14.017 18.0034 13.9838 17.8611C13.7971 17.0608 13.4132 16.0881 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208ZM15.3042 11.4955C14.4702 11.4955 13.7006 11.2193 13.0821 10.7533C13.3742 10.3314 13.6054 9.86419 13.7632 9.36432C14.1597 9.75463 14.7039 9.99545 15.3042 9.99545C16.5176 9.99545 17.5012 9.01185 17.5012 7.79851C17.5012 6.58517 16.5176 5.60156 15.3042 5.60156C14.7039 5.60156 14.1597 5.84239 13.7632 6.23271C13.6054 5.73284 13.3741 5.26561 13.082 4.84371C13.7006 4.37777 14.4702 4.10156 15.3042 4.10156C17.346 4.10156 19.0012 5.75674 19.0012 7.79851C19.0012 9.84027 17.346 11.4955 15.3042 11.4955ZM19.9248 19.8987H16.3901C16.7014 19.4736 16.9159 18.969 16.9827 18.3987H19.9248C20.1341 18.3987 20.2991 18.3141 20.3936 18.2112C20.4796 18.1175 20.5169 18.0034 20.4837 17.861C20.2969 17.0607 19.913 16.088 19.1382 15.3208C18.4047 14.5945 17.261 13.9921 15.4231 13.9566C15.2232 13.6945 14.9995 13.437 14.7491 13.1891C14.5144 12.9566 14.262 12.7384 13.9916 12.5362C14.3853 12.4831 14.8044 12.4549 15.2503 12.4549C17.5447 12.4549 19.1291 13.2008 20.1936 14.2549C21.2395 15.2906 21.7206 16.5607 21.9444 17.5202C22.2657 18.8971 21.107 19.8987 19.9248 19.8987Z" fill=""/>
                </svg>
            </div>

            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Utilisateurs</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90" x-text="stats.users_total || {{ $usersCount }}"></h4>
                </div>

                <span class="flex items-center gap-1 rounded-full bg-green-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247V10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125V3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z" fill=""/>
                    </svg>
                    +<span x-text="stats.users_today || {{ $stats['users_today'] }}"></span>
                </span>
            </div>
        </div>

        <!-- Subscriptions Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H6a1 1 0 01-1-1V4zm0 6v10a1 1 0 001 1h12a1 1 0 001-1V10H5zm7 4a1 1 0 100 2h3a1 1 0 100-2h-3z"/>
                </svg>
            </div>

            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Abonnements</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90" x-text="stats.active_subscriptions || {{ $stats['active_subscriptions'] }}"></h4>
                </div>

                <span class="flex items-center gap-1 rounded-full bg-green-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247V10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125V3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z" fill=""/>
                    </svg>
                    Actifs
                </span>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.665 3.75621C11.8762 3.65064 12.1247 3.65064 12.3358 3.75621L18.7807 6.97856L12.3358 10.2009C12.1247 10.3065 11.8762 10.3065 11.665 10.2009L5.22014 6.97856L11.665 3.75621ZM4.29297 8.19203V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0366V11.6513C11.1631 11.6205 11.0777 11.5843 10.9942 11.5426L4.29297 8.19203ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19202L13.0066 11.5426C12.9229 11.5844 12.8372 11.6208 12.75 11.6516V20.037ZM13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z" fill=""/>
                </svg>
            </div>

            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Commandes</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90" x-text="stats.orders_total || {{ $ordersCount ?? 0 }}"></h4>
                </div>

                <span class="flex items-center gap-1 rounded-full bg-amber-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-amber-600 dark:bg-amber-500/15 dark:text-amber-500">
                    En attente
                </span>
            </div>
        </div>
    </div>

    <!-- Statistics Chart -->
    <div class="mt-6">
        <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
            <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
                <div class="w-full">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Statistiques</h3>
                    <p class="mt-1 text-gray-500 text-sm dark:text-gray-400">
                        Évolution des articles et utilisateurs
                    </p>
                </div>

                <div class="relative">
                    <div class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                        <button
                            @click="chartPeriod = 'monthly'"
                            :class="[
                                chartPeriod === 'monthly'
                                    ? 'shadow-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                    : 'text-gray-500 dark:text-gray-400',
                                'px-3 py-2 font-medium rounded-md text-sm hover:text-gray-900 hover:shadow-xs dark:hover:bg-gray-800 dark:hover:text-white',
                            ]"
                        >
                            Mensuel
                        </button>
                        <button
                            @click="chartPeriod = 'quarterly'"
                            :class="[
                                chartPeriod === 'quarterly'
                                    ? 'shadow-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                    : 'text-gray-500 dark:text-gray-400',
                                'px-3 py-2 font-medium rounded-md text-sm hover:text-gray-900 hover:shadow-xs dark:hover:bg-gray-800 dark:hover:text-white',
                            ]"
                        >
                            Trimestriel
                        </button>
                        <button
                            @click="chartPeriod = 'annually'"
                            :class="[
                                chartPeriod === 'annually'
                                    ? 'shadow-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                    : 'text-gray-500 dark:text-gray-400',
                                'px-3 py-2 font-medium rounded-md text-sm hover:text-gray-900 hover:shadow-xs dark:hover:bg-gray-800 dark:hover:text-white',
                            ]"
                        >
                            Annuel
                        </button>
                    </div>
                </div>
            </div>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <div id="statisticsChart" class="-ml-4 min-w-[1000px] xl:min-w-full pl-2">
                    <canvas id="articlesChart" height="310"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Articles Table - TailAdmin Style -->
    <div class="mt-6">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
            <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Articles Récents</h3>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.articles.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-xs hover:bg-brand-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvel Article
                    </a>

                    <a href="{{ route('admin.articles.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 transition-colors">
                        Voir tout
                    </a>
                </div>
            </div>

            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <th class="py-3 text-left">
                                <p class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase">Article</p>
                            </th>
                            <th class="py-3 text-left">
                                <p class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase">Catégorie</p>
                            </th>
                            <th class="py-3 text-left">
                                <p class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase">Statut</p>
                            </th>
                            <th class="py-3 text-left">
                                <p class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase">Date</p>
                            </th>
                            <th class="py-3 text-center">
                                <p class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase">Actions</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <td class="py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if($article->image)
                                    <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                                        <img src="{{ Storage::url($article->image) }}" alt="{{ $article->titre }}" class="w-full h-full object-cover">
                                    </div>
                                    @else
                                    <div class="h-[50px] w-[50px] overflow-hidden rounded-md bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm dark:text-white/90">
                                            {{ Str::limit($article->titre, 40) }}
                                        </p>
                                        <span class="text-gray-500 text-xs dark:text-gray-400">
                                            {{ Str::words(strip_tags($article->contenu ?? ''), 5, '...') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-gray-500 text-sm dark:text-gray-400">{{ $article->category->nom ?? 'Sans catégorie' }}</p>
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <span
                                    :class="{
                                        'rounded-full px-2 py-0.5 text-xs font-medium': true,
                                        'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500': {{ $article->is_published ? 'true' : 'false' }},
                                        'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-500': {{ $article->is_published ? 'false' : 'true' }},
                                    }"
                                >
                                    {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-gray-500 text-sm dark:text-gray-400">{{ $article->created_at->format('d/m/Y') }}</p>
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.articles.edit', ['article' => $article->id]) }}"
                                        class="p-2 rounded-lg text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button @click="deleteArticle({{ $article->id }})"
                                        class="p-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400 text-sm">Aucun article trouvé</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($articles->hasPages())
            <div class="mt-4 border-t border-gray-100 dark:border-gray-800 pt-4">
                {{ $articles->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function dashboardData() {
    return {
        stats: {
            articles_total: {{ $articlesCount }},
            articles_today: {{ $stats['articles_today'] }},
            users_total: {{ $usersCount }},
            users_today: {{ $stats['users_today'] }},
            active_subscriptions: {{ $stats['active_subscriptions'] }},
            orders_total: {{ $ordersCount ?? 0 }},
        },
        chartPeriod: 'monthly',
        chart: null,

        init() {
            this.initChart();
        },

        initChart() {
            const ctx = document.getElementById('articlesChart');
            if (!ctx) return;

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    datasets: [
                        {
                            label: 'Articles',
                            data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 40, 45],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: 'Utilisateurs',
                            data: [5, 10, 8, 15, 12, 18, 20, 25, 22, 28, 30, 35],
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32, 201, 151, 0.1)',
                            fill: true,
                            tension: 0.4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        },

        deleteArticle(articleId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

            fetch(`/admin/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur lors de la suppression');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        },
    };
}
</script>
@endpush
