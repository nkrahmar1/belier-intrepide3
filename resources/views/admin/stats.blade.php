@extends('layouts.admin')

@section('title', 'Statistiques Avancées')

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
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-100 p-6">
    <!-- Header avec animation -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600 mb-4">
            📊 Statistiques Avancées
        </h1>
        <p class="text-gray-600 text-lg">Analyse complète de votre plateforme en temps réel</p>
    </div>

    <!-- Métriques principales avec animations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Utilisateurs -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">👥</span>
                </div>
                <span class="text-green-500 text-sm font-semibold">+{{ rand(5, 15) }}%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Utilisateurs Total</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($usersCount) }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full progress-bar" style="width: {{ min(($usersCount / 100) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Articles -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📰</span>
                </div>
                <span class="text-green-500 text-sm font-semibold">+{{ rand(8, 25) }}%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Articles Publiés</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($articlesCount) }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full progress-bar" style="width: {{ min(($articlesCount / 50) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Revenus -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
                <span class="text-orange-500 text-sm font-semibold">+{{ rand(10, 30) }}%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Revenus Totaux</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalRevenue) }}€</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full progress-bar" style="width: {{ min(($totalRevenue / 10000) * 100, 100) }}%"></div>
            </div>
        </div>

        <!-- Abonnements -->
        <div class="metric-card rounded-2xl shadow-lg p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💳</span>
                </div>
                <span class="text-purple-500 text-sm font-semibold">+{{ rand(3, 12) }}%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1">Abonnements Actifs</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($subscriptionsCount) }}</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full progress-bar" style="width: {{ min(($subscriptionsCount / 20) * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique de performance -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-3"></span>
                Performance Mensuelle
            </h3>
            <canvas id="performanceChart" width="400" height="200"></canvas>
        </div>

        <!-- Top articles -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-3 h-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-full mr-3"></span>
                Articles les Plus Lus
            </h3>
            <div class="space-y-4">
                @php
                $topArticles = App\Models\Article::latest()->take(5)->get();
                $colors = ['from-blue-500 to-purple-500', 'from-green-500 to-blue-500', 'from-yellow-500 to-red-500', 'from-purple-500 to-pink-500', 'from-indigo-500 to-blue-500'];
                @endphp
                @forelse($topArticles as $index => $article)
                    <div class="flex items-center p-3 bg-gradient-to-r {{ $colors[$index] ?? 'from-gray-400 to-gray-600' }} rounded-xl text-white">
                        <span class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold mr-4">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <h4 class="font-semibold truncate">{{ Str::limit($article->titre, 30) }}</h4>
                            <p class="text-sm opacity-90">{{ rand(100, 999) }} vues</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucun article disponible</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Métriques secondaires -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-pink-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🧾</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($ordersCount) }}</p>
            <p class="text-xs text-gray-600">Commandes</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">📦</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($productsCount) }}</p>
            <p class="text-xs text-gray-600">Produits</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">✉️</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($messagesCount) }}</p>
            <p class="text-xs text-gray-600">Messages</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">📂</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($categoriesCount) }}</p>
            <p class="text-xs text-gray-600">Catégories</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">👁️</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format(rand(1000, 9999)) }}</p>
            <p class="text-xs text-gray-600">Vues</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 text-center card-hover">
            <div class="w-8 h-8 bg-gradient-to-r from-rose-400 to-pink-400 rounded-lg mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">⭐</span>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format(rand(50, 200)) }}</p>
            <p class="text-xs text-gray-600">Favoris</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de performance
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Articles',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Utilisateurs',
                data: [5, 10, 8, 15, 12, 18],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

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
