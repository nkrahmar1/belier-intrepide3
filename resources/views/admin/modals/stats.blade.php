{{-- Vue partielle pour le modal Statistiques --}}
<div class="space-y-6 p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                📊 Statistiques globales
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Aperçu de l'activité de votre plateforme
            </p>
        </div>
    </div>
    
    {{-- Cartes statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total utilisateurs --}}
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg p-5 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90 font-medium">Total Utilisateurs</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <span class="text-4xl">👥</span>
            </div>
        </div>
        
        {{-- Total articles --}}
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg p-5 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90 font-medium">Total Articles</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_articles'] ?? 0) }}</p>
                </div>
                <span class="text-4xl">📰</span>
            </div>
        </div>
        
        {{-- Total commandes --}}
        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg p-5 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90 font-medium">Total Commandes</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_orders'] ?? 0) }}</p>
                </div>
                <span class="text-4xl">🛒</span>
            </div>
        </div>
        
        {{-- Total produits --}}
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg p-5 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90 font-medium">Total Produits</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_products'] ?? 0) }}</p>
                </div>
                <span class="text-4xl">📦</span>
            </div>
        </div>
    </div>
    
    {{-- Graphiques avec Chart.js --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- Graphique des abonnements --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 border border-gray-200 dark:border-gray-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Évolution des abonnements
            </h4>
            <canvas id="subscriptionsChart" height="200"></canvas>
        </div>
        
        {{-- Graphique des revenus --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 border border-gray-200 dark:border-gray-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Statistiques mensuelles
            </h4>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>
    
    {{-- Statistiques détaillées --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 border border-gray-200 dark:border-gray-700">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Détails des statistiques
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Articles publiés</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($stats['published_articles'] ?? 0) }}
                </p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Abonnements actifs</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($stats['active_subscriptions'] ?? 0) }}
                </p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Messages non lus</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($stats['unread_messages'] ?? 0) }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Script pour Chart.js --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des abonnements
    const subscriptionsCtx = document.getElementById('subscriptionsChart');
    if (subscriptionsCtx) {
        new Chart(subscriptionsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Abonnements',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Graphique des revenus
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Revenus',
                    data: [1200, 1900, 1500, 2500, 2200, 3000],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
