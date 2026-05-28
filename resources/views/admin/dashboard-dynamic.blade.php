@extends('layouts.admin')
@section('title', 'Dashboard Administrateur - Dynamique')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
<style>
    [x-cloak] { display: none !important; }
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50"
     x-data="dashboardData()"
     x-init="init()">

    <!-- Container principal -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="text-4xl">🎯</span>
                    Dashboard Administrateur
                </h1>
                <p class="text-gray-600 mt-2">Gestion en temps réel de votre plateforme</p>
            </div>
            <div class="flex gap-3">
                <button @click="openModal('articles')"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transform hover:scale-105 transition-all shadow-lg">
                    <span>➕</span>
                    <span class="hidden sm:inline">Nouvel Article</span>
                </button>
                <button @click="loadStats()"
                        :disabled="loading"
                        class="bg-white border-2 border-gray-200 hover:border-blue-300 text-gray-700 hover:text-blue-600 px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transform hover:scale-105 transition-all shadow-md">
                    <span :class="{ 'animate-spin': loading }">🔄</span>
                    <span class="hidden sm:inline">Actualiser</span>
                </button>
            </div>
        </div>

        <!-- Statistiques principales - Grid responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" x-cloak>
            <!-- Card Articles -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-card border border-blue-100 cursor-pointer"
                 @click="openModal('articles')">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">📰</span>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Articles</h3>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-2" x-text="stats.articles_total || 0"></p>
                        <div class="flex items-center gap-1">
                            <span class="text-emerald-500 text-sm font-semibold" x-text="`+${stats.articles_today || 0}`"></span>
                            <span class="text-gray-500 text-xs">aujourd'hui</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <span class="text-blue-600 text-2xl">📊</span>
                    </div>
                </div>
                <div class="mt-4 bg-blue-50 rounded-lg p-2">
                    <div class="flex justify-between text-xs text-blue-600 font-medium">
                        <span x-text="`Publiés: ${stats.articles_published || 0}`"></span>
                        <span x-text="`Brouillons: ${stats.articles_draft || 0}`"></span>
                    </div>
                </div>
            </div>

            <!-- Card Messages -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-card border border-yellow-100 cursor-pointer"
                 @click="openModal('messages')">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">✉️</span>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Messages</h3>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-2" x-text="stats.messages_total || 0"></p>
                        <div class="flex items-center gap-1">
                            <span class="text-red-500 text-sm font-semibold" x-text="`${stats.messages_unread || 0} non lus`"></span>
                        </div>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <span class="text-yellow-600 text-2xl">📬</span>
                    </div>
                </div>
                <div class="mt-4 bg-yellow-50 rounded-lg p-2">
                    <div class="text-xs text-yellow-600 font-medium text-center" x-text="`${stats.messages_today || 0} aujourd'hui`"></div>
                </div>
            </div>

            <!-- Card Abonnements -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-card border border-green-100 cursor-pointer"
                 @click="openModal('subscriptions')">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">💳</span>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Abonnés</h3>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-2" x-text="stats.subscriptions_active || 0"></p>
                        <div class="flex items-center gap-1">
                            <span class="text-emerald-500 text-sm font-semibold">actifs</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <span class="text-green-600 text-2xl">✅</span>
                    </div>
                </div>
                <div class="mt-4 bg-green-50 rounded-lg p-2">
                    <div class="text-xs text-green-600 font-medium text-center" x-text="`Total: ${stats.subscriptions_total || 0}`"></div>
                </div>
            </div>

            <!-- Card Revenus -->
            <div class="bg-white rounded-2xl shadow-xl p-6 hover-card border border-purple-100">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">💰</span>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase">Revenus</h3>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-2" x-text="`${(stats.subscriptions_revenue || 0).toFixed(0)}€`"></p>
                        <div class="flex items-center gap-1">
                            <span class="text-emerald-500 text-sm font-semibold">total</span>
                        </div>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <span class="text-purple-600 text-2xl">📈</span>
                    </div>
                </div>
                <div class="mt-4 bg-purple-50 rounded-lg p-2">
                    <div class="text-xs text-purple-600 font-medium text-center">Abonnements actifs</div>
                </div>
            </div>
        </div>

        <!-- Graphiques interactifs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Graphique Articles par mois -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>📊</span>
                    Articles publiés par mois
                </h3>
                <canvas id="articlesChart" height="300"></canvas>
            </div>

            <!-- Graphique Revenus -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>💰</span>
                    Évolution des revenus
                </h3>
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>

        <!-- Sections rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button @click="openModal('articles')"
                    class="bg-white hover:bg-blue-50 rounded-xl p-6 shadow-lg border-2 border-transparent hover:border-blue-300 transition-all transform hover:scale-105">
                <div class="text-4xl mb-3">📰</div>
                <h4 class="font-bold text-gray-900 mb-2">Gérer les Articles</h4>
                <p class="text-sm text-gray-600">Créer, modifier, publier</p>
            </button>

            <button @click="openModal('messages')"
                    class="bg-white hover:bg-yellow-50 rounded-xl p-6 shadow-lg border-2 border-transparent hover:border-yellow-300 transition-all transform hover:scale-105">
                <div class="text-4xl mb-3">✉️</div>
                <h4 class="font-bold text-gray-900 mb-2">Messages</h4>
                <p class="text-sm text-gray-600">Lire et répondre</p>
            </button>

            <button @click="openModal('subscriptions')"
                    class="bg-white hover:bg-green-50 rounded-xl p-6 shadow-lg border-2 border-transparent hover:border-green-300 transition-all transform hover:scale-105">
                <div class="text-4xl mb-3">💳</div>
                <h4 class="font-bold text-gray-900 mb-2">Abonnements</h4>
                <p class="text-sm text-gray-600">Gérer les abonnés</p>
            </button>

            <button @click="openModal('settings')"
                    class="bg-white hover:bg-purple-50 rounded-xl p-6 shadow-lg border-2 border-transparent hover:border-purple-300 transition-all transform hover:scale-105">
                <div class="text-4xl mb-3">⚙️</div>
                <h4 class="font-bold text-gray-900 mb-2">Paramètres</h4>
                <p class="text-sm text-gray-600">Configuration</p>
            </button>
        </div>

    </div>

    <!-- Modal dynamique avec votre système -->
    @include('admin.partials.dashboard-modals')

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function dashboardData() {
    return {
        loading: false,
        currentModal: null,
        stats: {
            articles_total: 0,
            articles_published: 0,
            articles_draft: 0,
            articles_today: 0,
            messages_total: 0,
            messages_unread: 0,
            messages_today: 0,
            subscriptions_active: 0,
            subscriptions_total: 0,
            subscriptions_revenue: 0,
        },
        charts: {
            articlesPerMonth: [],
            revenuePerMonth: []
        },
        articlesChart: null,
        revenueChart: null,

        init() {
            this.loadStats();
        },

        async loadStats() {
            this.loading = true;
            try {
                const response = await fetch('/api/admin/stats', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Erreur lors du chargement des statistiques');

                const data = await response.json();

                if (data.success) {
                    this.stats = data.stats;
                    this.charts = data.charts;

                    // Mettre à jour les graphiques
                    this.$nextTick(() => {
                        this.updateCharts();
                    });
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors du chargement des statistiques', 'error');
            } finally {
                this.loading = false;
            }
        },

        updateCharts() {
            // Graphique Articles
            const articlesCtx = document.getElementById('articlesChart');
            if (articlesCtx) {
                if (this.articlesChart) this.articlesChart.destroy();

                this.articlesChart = new Chart(articlesCtx, {
                    type: 'line',
                    data: {
                        labels: this.charts.articlesPerMonth.map(d => d.month),
                        datasets: [{
                            label: 'Articles publiés',
                            data: this.charts.articlesPerMonth.map(d => d.count),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Graphique Revenus
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                if (this.revenueChart) this.revenueChart.destroy();

                this.revenueChart = new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: this.charts.revenuePerMonth.map(d => d.month),
                        datasets: [{
                            label: 'Revenus (€)',
                            data: this.charts.revenuePerMonth.map(d => d.amount),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        },

        openModal(section) {
            // Utiliser votre système de modal existant
            if (typeof openAdminModal === 'function') {
                openAdminModal(section, null);
            } else {
                console.error('Fonction openAdminModal non trouvée');
            }
        },

        showNotification(message, type = 'success') {
            // Système de notification simple
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white transform transition-all duration-300`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    };
}
</script>
@endpush
