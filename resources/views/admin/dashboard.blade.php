@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div x-data="dashboardData()" x-init="init()" x-cloak>
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="glass-card" style="padding: 20px;">
            <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px;">Total Articles</div>
            <div style="font-size: 28px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;" x-text="stats.articles_total || '0'"></div>
            <div style="font-size: 12px; color: #22c55e;">
                <span x-text="stats.articles_published || '0'"></span> publiés
            </div>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px;">Abonnements Actifs</div>
            <div style="font-size: 28px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;" x-text="stats.active_subscriptions || '0'"></div>
            <div style="font-size: 12px; color: #22c55e;"><span x-text="stats.revenue_total || '$0'"></span> revenus</div>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px;">À la Une</div>
            <div style="font-size: 28px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;" x-text="stats.featured_homepage || '0'"></div>
            <div style="font-size: 12px; color: #22c55e;">Articles en vedette</div>
        </div>

        <div class="glass-card" style="padding: 20px;">
            <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 8px;">Messages</div>
            <div style="font-size: 28px; font-weight: 700; color: #f1f5f9; margin-bottom: 6px;" x-text="stats.messages_unread || '0'"></div>
            <div style="font-size: 12px; color: #fb923c;">Non lus</div>
        </div>
    </div>

    <!-- Action Bar -->
    <div style="display: flex; gap: 12px; margin-bottom: 32px;">
        <button @click="openQuickCreate()" class="btn btn-primary">+ Nouvel Article</button>
        <button @click="refreshStats()" class="btn btn-secondary">Rafraîchir</button>
    </div>

    <!-- Main Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 32px;">
        <!-- Performance Chart -->
        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 12px;">Tendance Articles</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- Stats Distribution -->
        <div class="glass-card" style="padding: 20px;">
            <h3 style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 12px;">Distribution</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Articles -->
    <div class="glass-card" style="padding: 20px; margin-bottom: 32px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 12px;">Articles Récents</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Titre</th>
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Catégorie</th>
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Statut</th>
                        <th style="text-align: center; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Actions</th>
                    </tr>
                </thead>
                <tbody x-ref="articlesBody">
                    <template x-for="article in recentArticles" :key="article.id">
                        <tr style="border-bottom: 1px solid rgba(148,163,184,0.05); transition: all 0.2s;" @mouseenter="$el.style.background='rgba(6,182,212,0.05)'" @mouseleave="$el.style.background='transparent'">
                            <td style="padding: 12px; color: #cbd5e1;"><strong x-text="article.titre.substring(0, 30) + (article.titre.length > 30 ? '...' : '')"></strong></td>
                            <td style="padding: 12px; color: #cbd5e1;" x-text="article.category?.name || 'N/A'"></td>
                            <td style="padding: 12px;">
                                <span :class="article.is_published ? 'badge-success' : 'badge-warning'" style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                    <span x-show="article.is_published">Publié</span>
                                    <span x-show="!article.is_published">Brouillon</span>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <button @click="togglePublish(article.id, article.is_published)" style="background: rgba(6,182,212,0.1); color: #06b6d4; border: none; padding: 6px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; transition: all 0.2s;" @mouseover="$el.style.background='rgba(6,182,212,0.2)'" @mouseout="$el.style.background='rgba(6,182,212,0.1)'">
                                    <span x-show="!article.is_published">Publier</span>
                                    <span x-show="article.is_published">Dépublier</span>
                                </button>
                                <button @click="toggleHomepage(article.id, article.is_featured)" style="background: rgba(251,146,60,0.1); color: #fb923c; border: none; padding: 6px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; margin-left: 6px; transition: all 0.2s;" @mouseover="$el.style.background='rgba(251,146,60,0.2)'" @mouseout="$el.style.background='rgba(251,146,60,0.1)'">
                                    <span x-show="!article.is_featured">En Vedette</span>
                                    <span x-show="article.is_featured">Retirer</span>
                                </button>
                                <button @click="deleteArticle(article.id)" style="background: rgba(239,68,68,0.1); color: #ef4444; border: none; padding: 6px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; margin-left: 6px; transition: all 0.2s;" @mouseover="$el.style.background='rgba(239,68,68,0.2)'" @mouseout="$el.style.background='rgba(239,68,68,0.1)'">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="glass-card" style="padding: 20px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 12px;">Abonnements Récents</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Client</th>
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Plan</th>
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Montant</th>
                        <th style="text-align: left; padding: 12px; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 11px;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="sub in subscriptions" :key="sub.id">
                        <tr style="border-bottom: 1px solid rgba(148,163,184,0.05);">
                            <td style="padding: 12px; color: #cbd5e1;" x-text="sub.user?.name || 'N/A'"></td>
                            <td style="padding: 12px; color: #cbd5e1;" x-text="sub.plan_name || 'N/A'"></td>
                            <td style="padding: 12px; color: #cbd5e1;" x-text="'$' + (sub.amount || '0')"></td>
                            <td style="padding: 12px;">
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: rgba(34,197,94,0.1); color: #22c55e;">Actif</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Create Modal -->
    <div class="modal-overlay" :class="{ 'active': quickCreateOpen }" @click="closeQuickCreate()">
        <div class="modal" @click.stop>
            <div class="modal-header">
                <h2>Ajouter un Nouvel Article</h2>
                <button @click="closeQuickCreate()" class="modal-close">&times;</button>
            </div>
            <form @submit="submitQuickCreate" style="display: contents;">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="titre">Titre *</label>
                        <input type="text" id="titre" name="titre" required placeholder="Titre de l'article">
                    </div>

                    <div class="form-group">
                        <label for="category_id">Catégorie *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Sélectionner une catégorie --</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu *</label>
                        <textarea id="contenu" name="contenu" required placeholder="Contenu de l'article" style="resize: vertical; min-height: 100px;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="extrait">Extrait</label>
                        <textarea id="extrait" name="extrait" placeholder="Extrait (résumé court)" style="resize: vertical; min-height: 60px;"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_premium" value="1" style="margin-right: 6px;">
                                Contenu Premium
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_published" value="1" style="margin-right: 6px;">
                                Publier immédiatement
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="closeQuickCreate()" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer l'Article</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    function dashboardData() {
        return {
            quickCreateOpen: false,
            stats: {
                articles_total: 0,
                articles_published: 0,
                active_subscriptions: 0,
                revenue_total: 0,
                featured_homepage: 0,
                messages_unread: 0
            },
            recentArticles: [],
            subscriptions: [],
            categories: [],
            performanceChart: null,
            distributionChart: null,

            init() {
                this.loadCategories();
                this.refreshStats();
            },

            async loadCategories() {
                try {
                    const response = await fetch('/api/categories');
                    this.categories = await response.json();
                } catch (e) {
                    console.error('Failed to load categories:', e);
                }
            },

            async refreshStats() {
                try {
                    const response = await fetch('/api/admin/stats', {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await response.json();
                    this.stats = data.stats || {};
                    this.recentArticles = data.articles || [];
                    this.subscriptions = data.subscriptions || [];
                    
                    this.$nextTick(() => {
                        this.initPerformanceChart(data.chartData);
                        this.initDistributionChart();
                    });
                } catch (e) {
                    this.showToast('Erreur lors du chargement des statistiques', 'error');
                }
            },

            openQuickCreate() {
                this.quickCreateOpen = true;
            },

            closeQuickCreate() {
                this.quickCreateOpen = false;
                document.querySelectorAll('.modal-body input, .modal-body textarea').forEach(el => el.value = '');
                document.querySelectorAll('.modal-body input[type="checkbox"]').forEach(el => el.checked = false);
            },

            async submitQuickCreate(e) {
                e.preventDefault();
                const form = e.target.closest('form') || e.target;
                const formData = new FormData(form);
                
                try {
                    const response = await fetch('/admin/articles/quick-create', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: formData
                    });
                    
                    if (response.ok) {
                        this.showToast('Article créé avec succès!', 'success');
                        this.closeQuickCreate();
                        this.refreshStats();
                    } else {
                        const error = await response.json();
                        this.showToast(error.message || 'Erreur lors de la création', 'error');
                    }
                } catch (e) {
                    this.showToast('Erreur réseau', 'error');
                }
            },

            async togglePublish(articleId, currentValue) {
                try {
                    const response = await fetch(`/admin/articles/${articleId}/toggle-publish`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    if (response.ok) {
                        const article = this.recentArticles.find(a => a.id === articleId);
                        if (article) {
                            article.is_published = !article.is_published;
                            if (article.is_published) {
                                article.published_at = new Date().toISOString();
                            }
                            this.showToast(article.is_published ? 'Article publié' : 'Article dépublié', 'success');
                        }
                        this.refreshStats();
                    }
                } catch (e) {
                    this.showToast('Erreur lors de la mise à jour', 'error');
                }
            },

            async toggleHomepage(articleId, currentValue) {
                try {
                    const response = await fetch(`/admin/articles/${articleId}/toggle-homepage`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    if (response.ok) {
                        const article = this.recentArticles.find(a => a.id === articleId);
                        if (article) {
                            article.is_featured = !article.is_featured;
                            this.showToast(article.is_featured ? 'Ajouté à la vedette' : 'Retiré de la vedette', 'success');
                        }
                        this.refreshStats();
                    }
                } catch (e) {
                    this.showToast('Erreur lors de la mise à jour', 'error');
                }
            },

            async deleteArticle(articleId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet article?')) return;
                
                try {
                    const response = await fetch(`/admin/articles/${articleId}/delete`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    if (response.ok) {
                        this.showToast('Article supprimé', 'success');
                        this.recentArticles = this.recentArticles.filter(a => a.id !== articleId);
                        this.refreshStats();
                    }
                } catch (e) {
                    this.showToast('Erreur lors de la suppression', 'error');
                }
            },

            initPerformanceChart(chartData) {
                const ctx = document.getElementById('performanceChart');
                if (!ctx) return;
                
                if (this.performanceChart) {
                    this.performanceChart.destroy();
                }

                const data = chartData?.articlesPerMonth || {};
                this.performanceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(data),
                        datasets: [{
                            label: 'Articles',
                            data: Object.values(data),
                            borderColor: '#06b6d4',
                            backgroundColor: 'rgba(6, 182, 212, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderWidth: 0,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                ticks: { color: '#94a3b8' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#94a3b8' }
                            }
                        }
                    }
                });
            },

            initDistributionChart() {
                const ctx = document.getElementById('distributionChart');
                if (!ctx) return;
                
                if (this.distributionChart) {
                    this.distributionChart.destroy();
                }

                this.distributionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Publiés', 'Brouillons', 'À la une'],
                        datasets: [{
                            data: [
                                this.stats.articles_published,
                                this.stats.articles_total - this.stats.articles_published,
                                this.stats.featured_homepage
                            ],
                            backgroundColor: [
                                '#22c55e',
                                '#fb923c',
                                '#06b6d4'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: '#cbd5e1' }
                            }
                        }
                    }
                });
            },

            showToast(message, type = 'info') {
                const container = document.getElementById('dashboard-toasts');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.textContent = message;
                container.appendChild(toast);
                setTimeout(() => toast.remove(), 4500);
            }
        };
    }
</script>
@endsection
