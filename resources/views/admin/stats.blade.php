@extends('layouts.admin')

@section('title', 'Tableau de Bord - Statistiques')

@push('styles')
<style>
    .stats-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .progress-bar {
        transition: width 1.5s ease-in-out;
    }
    .metric-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .alert-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .alert-critical {
        background: #fecaca;
        color: #991b1b;
    }
    .alert-warning {
        background: #fde047;
        color: #713f12;
    }
    .alert-success {
        background: #bbf7d0;
        color: #14532d;
    }
    .trend-up {
        color: #16a34a;
    }
    .trend-down {
        color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-100 p-6">
    <!-- Header avec animation -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 mb-4">
            📊 Tableau de Bord
        </h1>
        <p class="text-gray-600 text-lg">Vue d'ensemble de votre plateforme en temps réel</p>
    </div>

    <!-- Alertes & Indicateurs Critiques -->
    @php
        $drafts = $stats['articles_draft'] ?? 0;
        $unreadMessages = $stats['messages_unread'] ?? 0;
        $newUsersToday = $stats['users_today'] ?? 0;
    @endphp
    
    @if($drafts > 0 || $unreadMessages > 0)
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        @if($drafts > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-yellow-800">🖊️ Articles en Brouillon</h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ $drafts }}</p>
                </div>
                <a href="#" class="text-yellow-600 hover:text-yellow-800 text-2xl">→</a>
            </div>
        </div>
        @endif

        @if($unreadMessages > 0)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-blue-800">💬 Messages Non Lus</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ $unreadMessages }}</p>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-2xl">→</a>
            </div>
        </div>
        @endif

        @if($newUsersToday > 0)
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-green-800">👥 Nouveaux Utilisateurs Aujourd'hui</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $newUsersToday }}</p>
                </div>
                <a href="#" class="text-green-600 hover:text-green-800 text-2xl">→</a>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Métriques principales avec données réelles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Utilisateurs -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
                <span class="text-green-500 text-sm font-semibold flex items-center gap-1">
                    <span class="trend-up">↑</span>
                    {{ $newUsersToday }} aujourd'hui
                </span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Utilisateurs Total</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($usersCount) }}</p>
            <p class="text-xs text-gray-500 mt-2">Actifs: {{ $stats['users_active'] ?? 0 }} | Premium: {{ $stats['users_premium'] ?? 0 }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full progress-bar" style="width: {{ min(($usersCount / 1000) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Articles -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📰</span>
                </div>
                <span class="text-green-500 text-sm font-semibold flex items-center gap-1">
                    <span class="trend-up">↑</span>
                    {{ $stats['articles_today'] ?? 0 }} ajd
                </span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Articles Total</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($articlesCount) }}</p>
            <p class="text-xs text-gray-500 mt-2">Publiés: {{ $stats['articles_published'] ?? 0 }} | Brouillons: {{ $stats['articles_draft'] ?? 0 }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full progress-bar" style="width: {{ min(($articlesCount / 500) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Revenus -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
                <span class="text-green-500 text-sm font-semibold">Actif</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Revenus Totaux</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalRevenue, 2) }}€</p>
            <p class="text-xs text-gray-500 mt-2">{{ $subscriptionsCount }} abonnements actifs</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full progress-bar" style="width: {{ min(($totalRevenue / 50000) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Abonnements -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💳</span>
                </div>
                <span class="text-green-500 text-sm font-semibold flex items-center gap-1">
                    <span class="trend-up">↑</span>
                    {{ $stats['active_subscriptions'] ?? 0 }}%
                </span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Abonnements Actifs</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($subscriptionsCount) }}</p>
            <p class="text-xs text-gray-500 mt-2">Total: {{ $stats['subscriptions_total'] ?? 0 }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full progress-bar" style="width: {{ min(($subscriptionsCount / 1000) * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique Articles & Revenus mensuels -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-3"></span>
                Articles par Mois (12 derniers mois)
            </h3>
            <canvas id="articlesChart" height="80"></canvas>
        </div>

        <!-- Graphique Revenus mensuels -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-full mr-3"></span>
                Revenus par Mois (12 derniers mois)
            </h3>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    <!-- Activité Récente & Top Articles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Activité Récente -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full mr-3"></span>
                Activité Récente
            </h3>
            <div class="space-y-4">
                <!-- Articles récents -->
                @php
                    $recentArticles = App\Models\Article::latest()->take(3)->get();
                @endphp
                @forelse($recentArticles as $article)
                    <div class="flex items-start p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <span class="text-lg mr-3">📝</span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ Str::limit($article->titre, 40) }}</p>
                            <p class="text-xs text-gray-500">{{ $article->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded">{{ $article->is_published ? '✓ Publié' : '⊙ Brouillon' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
                @endforelse

                <!-- Utilisateurs récents -->
                @php
                    $recentUsers = App\Models\User::latest()->take(2)->get();
                @endphp
                @forelse($recentUsers as $user)
                    <div class="flex items-start p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <span class="text-lg mr-3">👤</span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $user->firstname }} {{ $user->lastname }}</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        @if($user->is_premium)
                            <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded">Premium</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucun nouvel utilisateur</p>
                @endforelse
            </div>
        </div>

        <!-- Top articles -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-full mr-3"></span>
                Articles les Plus Récents
            </h3>
            <div class="space-y-4">
                @php
                    $topArticles = App\Models\Article::published()->latest()->take(5)->get();
                    $colors = ['from-blue-500 to-purple-500', 'from-green-500 to-blue-500', 'from-yellow-500 to-red-500', 'from-purple-500 to-pink-500', 'from-indigo-500 to-blue-500'];
                @endphp
                @forelse($topArticles as $index => $article)
                    <div class="flex items-center p-3 bg-gradient-to-r {{ $colors[$index] ?? 'from-gray-400 to-gray-600' }} rounded-xl text-white hover:shadow-lg transition">
                        <span class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold mr-4">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold truncate">{{ Str::limit($article->titre, 35) }}</h4>
                            <p class="text-sm opacity-90">{{ $article->category->nom ?? 'Non catégorisé' }} • {{ $article->views_count ?? 0 }} vues</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucun article disponible</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Métriques secondaires avancées -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-red-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-pink-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🧾</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['articles_draft'] ?? 0 }}</p>
            <p class="text-xs text-gray-600">Brouillons</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-indigo-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">⭐</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['articles_premium'] ?? 0 }}</p>
            <p class="text-xs text-gray-600">Premium</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-green-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">✉️</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['messages_unread'] ?? 0 }}</p>
            <p class="text-xs text-gray-600">Messages</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-yellow-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">📂</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $categoriesCount }}</p>
            <p class="text-xs text-gray-600">Catégories</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-cyan-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">👁️</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['users_active'] ?? 0 }}</p>
            <p class="text-xs text-gray-600">Actifs</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover hover:bg-rose-50 transition">
            <div class="w-8 h-8 bg-gradient-to-r from-rose-400 to-pink-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">💎</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['users_premium'] ?? 0 }}</p>
            <p class="text-xs text-gray-600">Premium</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique Articles par mois (12 mois de données réelles)
    const articlesCtx = document.getElementById('articlesChart')?.getContext('2d');
    if (articlesCtx) {
        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        const articleData = @json($chartData['articles'] ?? array_fill(0, 12, 0));
        const articlesPerMonth = articleData.slice(0, 12).map(item => item.count || 0);
        
        new Chart(articlesCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Articles Publiés',
                    data: articlesPerMonth,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { 
                        display: true, 
                        labels: { color: 'rgb(107, 114, 128)', font: { size: 12 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'rgb(107, 114, 128)' },
                        grid: { color: 'rgba(200, 200, 200, 0.1)' }
                    },
                    x: { 
                        ticks: { color: 'rgb(107, 114, 128)' }, 
                        grid: { color: 'rgba(200, 200, 200, 0.1)' } 
                    }
                }
            }
        });
    }

    // Graphique Revenus par mois (12 mois de données réelles)
    const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revenueCtx) {
        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        const revenueData = @json($chartData['revenue'] ?? array_fill(0, 12, 0));
        const revenuePerMonth = revenueData.slice(0, 12).map(item => item.amount || 0);
        
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenus (€)',
                    data: revenuePerMonth,
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 2,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(34, 197, 94, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { 
                        display: true, 
                        labels: { color: 'rgb(107, 114, 128)', font: { size: 12 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'rgb(107, 114, 128)' },
                        grid: { color: 'rgba(200, 200, 200, 0.1)' }
                    },
                    x: { 
                        ticks: { color: 'rgb(107, 114, 128)' }, 
                        grid: { color: 'rgba(200, 200, 200, 0.1)' } 
                    }
                }
            }
        });
    }

    // Animation des barres de progression
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            bar.style.width = bar.style.width;
        });
    }, 500);
});
</script>
@endpush
@endsection
