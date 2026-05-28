@extends('layouts.admin')
@section('title', 'Dashboard Administrateur')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .metric-card { transition: all .2s ease; }
    .metric-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,0,0,.08); }
</style>
@endpush

@section('content')
<div x-data="dashboardData()" x-init="init()" class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Dashboard Admin Pro</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Suivi des performances, abonnements et publications de la page d'accueil.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Nouvel article
            </a>
            <button @click="refreshStats()" :disabled="loading" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-60">
                <span x-text="loading ? 'Actualisation...' : 'Actualiser'"></span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Articles</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="stats.articles_total"></p>
            <p class="mt-1 text-xs text-green-600">+<span x-text="stats.articles_today"></span> aujourd'hui</p>
        </div>
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Publié</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="stats.articles_published"></p>
            <p class="mt-1 text-xs text-gray-500"><span x-text="stats.articles_draft"></span> brouillons</p>
        </div>
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Abonnements actifs</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="stats.active_subscriptions"></p>
            <p class="mt-1 text-xs text-gray-500">Total: <span x-text="stats.subscriptions_total"></span></p>
        </div>
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Revenu abonnement</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="formatCurrency(stats.revenue_total)"></p>
            <p class="mt-1 text-xs text-gray-500">Mois: <span x-text="formatCurrency(stats.revenue_month)"></span></p>
        </div>
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Articles homepage</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="stats.featured_homepage"></p>
            <p class="mt-1 text-xs text-gray-500">Mise en avant active</p>
        </div>
        <div class="metric-card rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase text-gray-500">Messages non lus</p>
            <p class="mt-2 text-2xl font-bold text-gray-900" x-text="stats.messages_unread"></p>
            <p class="mt-1 text-xs text-gray-500">Surveillance support</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 xl:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900">Évolution des contenus et revenus</h3>
            <p class="mb-4 text-sm text-gray-500">Basé sur les 12 derniers mois.</p>
            <div class="h-[340px]">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h3 class="text-lg font-semibold text-gray-900">Derniers abonnements</h3>
            <p class="mb-4 text-sm text-gray-500">Dernières transactions enregistrées.</p>
            <div class="space-y-3">
                @forelse($recentSubscriptions as $subscription)
                    @if($subscription && $subscription->user)
                        <div class="rounded-xl border border-gray-100 p-3">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ trim(($subscription->user->firstname ?? '') . ' ' . ($subscription->user->lastname ?? '')) ?: 'Utilisateur' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $subscription->plan_name ?? $subscription->plan ?? 'Plan standard' }}</p>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $subscription->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                                <span class="text-sm font-semibold text-emerald-600">{{ number_format((float) ($subscription->amount ?? $subscription->price ?? 0), 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-gray-500">Aucun abonnement récent.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Publication homepage</h3>
                <p class="text-sm text-gray-500">Active/désactive les articles visibles sur la page d'accueil.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs uppercase text-gray-500">
                        <th class="py-3">Article</th>
                        <th class="py-3">Catégorie</th>
                        <th class="py-3">Publication</th>
                        <th class="py-3">Homepage</th>
                        <th class="py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homepageArticles as $article)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($article->titre, 60) }}</td>
                            <td class="py-3 text-gray-500">{{ $article->category->nom ?? 'Sans catégorie' }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-2 py-1 text-xs {{ $article->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="rounded-full px-2 py-1 text-xs {{ ($article->is_featured || $article->featured_on_homepage) ? 'bg-brand-100 text-brand-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ($article->is_featured || $article->featured_on_homepage) ? 'Visible accueil' : 'Non affiché' }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <button
                                    @click="toggleHomepage({{ $article->id }}, @json(($article->is_featured || $article->featured_on_homepage)))"                                    class="rounded-lg px-3 py-1.5 text-xs font-semibold {{ ($article->is_featured || $article->featured_on_homepage) ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                >
                                    {{ ($article->is_featured || $article->featured_on_homepage) ? 'Retirer accueil' : 'Mettre en accueil' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-gray-500">Aucun article publié disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Articles récents</h3>
            <a href="{{ route('admin.articles.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Voir tous les articles
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-left text-xs uppercase text-gray-500">
                        <th class="py-3">Titre</th>
                        <th class="py-3">Catégorie</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Statut</th>
                        <th class="py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($article->titre, 60) }}</td>
                            <td class="py-3 text-gray-500">{{ $article->category->nom ?? 'Sans catégorie' }}</td>
                            <td class="py-3 text-gray-500">{{ $article->created_at->format('d/m/Y') }}</td>
                            <td class="py-3">
                                <span class="rounded-full px-2 py-1 text-xs {{ $article->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.articles.edit', ['article' => $article->id]) }}" class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">Modifier</a>
                                        <button @click="togglePublish({{ $article->id }})"
                                             class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-100">
                                                  Publier / Dépublier
                                        </button>
                                        <button @click="deleteArticle({{ $article->id }})"
                                              class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">
                                                  Supprimer
                                        </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-gray-500">Aucun article trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($articles, 'hasPages') && $articles->hasPages())
            <div class="mt-4">{{ $articles->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function dashboardData() {
    return {
        stats: {
            articles_total: @json($articlesCount),
            articles_today: @json($stats['articles_today'] ?? 0),
            articles_published: @json($stats['articles_published'] ?? 0),
            articles_draft: @json($stats['articles_draft'] ?? 0),
            active_subscriptions: @json($stats['active_subscriptions'] ?? 0),
            subscriptions_total: @json($stats['subscriptions_total'] ?? 0),
            revenue_total: @json($stats['revenue_total'] ?? 0),
            revenue_month: @json($stats['revenue_month'] ?? 0),
            featured_homepage: @json($stats['featured_homepage'] ?? 0),
            messages_unread: @json($stats['messages_unread'] ?? 0),
        },

        chartData: @json($chartData),
        chart: null,
        loading: false,

        csrf: document.querySelector('meta[name="csrf-token"]').content,

        init() {
            this.initPerformanceChart();
        },

        // =========================
        // SAFE FETCH WRAPPER
        // =========================
        async request(url, options = {}) {
            try {
                const response = await fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf
                    },
                    ...options
                });

                if (!response.ok) {
                    throw new Error("Erreur serveur (" + response.status + ")");
                }

                return await response.json();
            } catch (error) {
                console.error(error);
                alert(error.message);
                return null;
            }
        },

        // =========================
        // CHART FIX PROPER LIFECYCLE
        // =========================
        initPerformanceChart() {
            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;

            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }

            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.chartData.labels,
                    datasets: [
                        {
                            type: 'line',
                            label: 'Articles publiés',
                            data: this.chartData.articles,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,.2)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Revenus abonnements',
                            data: this.chartData.revenue,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,.3)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        },

        // =========================
        // FORMAT CURRENCY
        // =========================
        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'XOF',
                maximumFractionDigits: 0
            }).format(value || 0);
        },

        // =========================
        // REFRESH STATS (NO RELOAD)
        // =========================
        async refreshStats() {
            this.loading = true;

            const data = await this.request("{{ route('api.admin.stats') }}");

            this.loading = false;

            if (!data || !data.success) return;

            this.stats = {
                ...this.stats,
                ...data.stats
            };

            this.chartData = {
                labels: (data.charts?.articlesPerMonth || []).map(i => i.month),
                articles: (data.charts?.articlesPerMonth || []).map(i => i.count),
                revenue: (data.charts?.revenuePerMonth || []).map(i => i.amount),
            };

            this.initPerformanceChart();
        },

        // =========================
        // HOMEPAGE TOGGLE (FIXED)
        // =========================
        async toggleHomepage(articleId, currentValue) {
            const url = `{{ url('/admin/articles') }}/${articleId}/toggle-homepage`;

            const data = await this.request(url, {
                method: 'PATCH',
                body: JSON.stringify({ featured: !currentValue })
            });

            if (data?.success) {
                window.location.reload(); // tu peux remplacer plus tard par update DOM
            }
        },

        // =========================
        // PUBLISH TOGGLE
        // =========================
        async togglePublish(articleId) {
            const data = await this.request(`/admin/articles/${articleId}/toggle-publish`, {
                method: 'POST'
            });

            if (data?.success) {
                window.location.reload();
            }
        },

        // =========================
        // DELETE ARTICLE
        // =========================
        async deleteArticle(articleId) {
            if (!confirm("Confirmer suppression ?")) return;

            const data = await this.request(`/admin/articles/${articleId}`, {
                method: 'DELETE'
            });

            if (data?.success) {
                window.location.reload();
            }
        }
    };
}
</script>
@endpush
