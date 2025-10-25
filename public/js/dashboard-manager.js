/**
 * Dashboard Manager avec Alpine.js
 * Gère l'interactivité du dashboard administrateur
 */

function dashboardManager() {
    return {
        // État
        loading: false,
        stats: {
            articles_total: 0,
            articles_published: 0,
            articles_draft: 0,
            articles_today: 0,
            articles_premium: 0,
            users_total: 0,
            users_today: 0,
            subscriptions_active: 0,
            subscriptions_total: 0,
            messages_unread: 0,
        },

        // Filtres pour articles
        filters: {
            search: '',
            category: 'all',
            status: 'all',
        },

        // Articles
        articles: [],
        filteredArticles: [],

        // Notifications
        notifications: [],

        /**
         * Initialisation
         */
        init() {
            console.log('🎯 Dashboard Manager initialisé avec Alpine.js');
            this.loadStats();
            this.initializeArticles();

            // Actualiser les stats toutes les 60 secondes
            setInterval(() => this.loadStats(), 60000);
        },

        /**
         * Charger les statistiques depuis l'API
         */
        async loadStats() {
            try {
                const response = await fetch('/api/admin/stats', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des statistiques');
                }

                const data = await response.json();

                if (data.success) {
                    this.stats = data.stats;
                    console.log('✅ Statistiques chargées', this.stats);
                }
            } catch (error) {
                console.error('❌ Erreur stats:', error);
            }
        },

        /**
         * Actualiser les statistiques (bouton)
         */
        async refreshStats() {
            this.loading = true;
            await this.loadStats();
            this.showNotification('Statistiques actualisées !', 'success');
            this.loading = false;
        },

        /**
         * Initialiser les articles depuis le DOM
         */
        initializeArticles() {
            const articlesContainer = document.getElementById('publishedArticles');
            if (articlesContainer) {
                const articleElements = articlesContainer.querySelectorAll('[data-article-id]');
                this.articles = Array.from(articleElements).map(el => ({
                    id: el.dataset.articleId,
                    title: el.dataset.articleTitle || '',
                    category: el.dataset.articleCategory || '',
                    status: el.dataset.articleStatus || 'published',
                    element: el
                }));
                this.applyFilters();
            }
        },

        /**
         * Appliquer les filtres sur les articles
         */
        applyFilters() {
            this.filteredArticles = this.articles.filter(article => {
                // Filtre recherche
                if (this.filters.search) {
                    const searchLower = this.filters.search.toLowerCase();
                    if (!article.title.toLowerCase().includes(searchLower)) {
                        return false;
                    }
                }

                // Filtre catégorie
                if (this.filters.category !== 'all' && article.category !== this.filters.category) {
                    return false;
                }

                // Filtre statut
                if (this.filters.status !== 'all' && article.status !== this.filters.status) {
                    return false;
                }

                return true;
            });

            // Mise à jour du DOM
            this.updateArticlesDisplay();
        },

        /**
         * Mettre à jour l'affichage des articles
         */
        updateArticlesDisplay() {
            this.articles.forEach(article => {
                const isVisible = this.filteredArticles.includes(article);
                if (article.element) {
                    article.element.style.display = isVisible ? '' : 'none';
                }
            });

            // Afficher message si aucun résultat
            const container = document.getElementById('publishedArticles');
            if (container) {
                let emptyMessage = container.querySelector('.empty-message');

                if (this.filteredArticles.length === 0) {
                    if (!emptyMessage) {
                        emptyMessage = document.createElement('div');
                        emptyMessage.className = 'empty-message text-center py-12 text-gray-500';
                        emptyMessage.innerHTML = `
                            <span class="text-6xl block mb-4">🔍</span>
                            <p class="text-lg font-medium">Aucun article trouvé</p>
                            <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                        `;
                        container.appendChild(emptyMessage);
                    }
                } else if (emptyMessage) {
                    emptyMessage.remove();
                }
            }
        },

        /**
         * Recherche d'articles (avec debounce)
         */
        searchArticles() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.applyFilters();
            }, 300);
        },

        /**
         * Ouvrir une section
         */
        openSection(section) {
            console.log(`📂 Ouverture de la section: ${section}`);

            // Scroll vers la section appropriée ou ouvrir un modal
            const sectionMap = {
                'articles': () => {
                    const articlesSection = document.querySelector('[data-section="articles"]');
                    if (articlesSection) {
                        articlesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },
                'users': () => {
                    window.location.href = '/admin/users';
                },
                'subscriptions': () => {
                    // TODO: Ouvrir modal abonnements
                    this.showNotification('Section Abonnements à venir', 'info');
                }
            };

            if (sectionMap[section]) {
                sectionMap[section]();
            }
        },

        /**
         * Afficher le modal de création d'article
         */
        showCreateArticleModal() {
            const modal = document.getElementById('createArticleModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        },

        /**
         * Basculer un article vers la page d'accueil
         */
        async toggleHomepage(articleId, isCurrentlyFeatured) {
            try {
                const response = await fetch(`/api/admin/articles/${articleId}/toggle-featured`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showNotification(
                        isCurrentlyFeatured ? 'Article retiré de la page d\'accueil' : 'Article ajouté à la page d\'accueil',
                        'success'
                    );

                    // Recharger la page après un court délai
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(data.message || 'Erreur lors de la modification', 'error');
                }
            } catch (error) {
                console.error('❌ Erreur toggle homepage:', error);
                this.showNotification('Erreur lors de la modification', 'error');
            }
        },

        /**
         * Afficher une notification toast
         */
        showNotification(message, type = 'success') {
            const id = Date.now();
            const notification = {
                id,
                message,
                type,
                show: true
            };

            this.notifications.push(notification);

            // Créer l'élément de notification
            const notifEl = document.createElement('div');
            notifEl.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' :
                'bg-blue-500'
            } text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3`;

            notifEl.innerHTML = `
                <span class="text-2xl">
                    ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️'}
                </span>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <span class="text-xl">×</span>
                </button>
            `;

            document.body.appendChild(notifEl);

            // Animation d'entrée
            setTimeout(() => {
                notifEl.style.opacity = '1';
                notifEl.style.transform = 'translateX(0)';
            }, 10);

            // Auto-suppression après 5 secondes
            setTimeout(() => {
                notifEl.style.opacity = '0';
                notifEl.style.transform = 'translateX(100%)';
                setTimeout(() => notifEl.remove(), 300);
            }, 5000);
        },

        /**
         * Formatter une date
         */
        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        /**
         * Formatter un nombre
         */
        formatNumber(num) {
            return new Intl.NumberFormat('fr-FR').format(num);
        }
    };
}

// Fonction globale pour compatibilité avec l'ancien code
function refreshDashboard() {
    const dashboardEl = document.querySelector('[x-data*="dashboardManager"]');
    if (dashboardEl && dashboardEl.__x) {
        dashboardEl.__x.$data.refreshStats();
    }
}

// Fonctions de compatibilité pour les modals existants
function showCreateArticleModal() {
    const dashboardEl = document.querySelector('[x-data*="dashboardManager"]');
    if (dashboardEl && dashboardEl.__x) {
        dashboardEl.__x.$data.showCreateArticleModal();
    } else {
        // Fallback vers l'ancien système
        const modal = document.getElementById('createArticleModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
}

function addToHomepage(articleId) {
    const dashboardEl = document.querySelector('[x-data*="dashboardManager"]');
    if (dashboardEl && dashboardEl.__x) {
        dashboardEl.__x.$data.toggleHomepage(articleId, false);
    }
}

function removeFromHomepage(articleId) {
    const dashboardEl = document.querySelector('[x-data*="dashboardManager"]');
    if (dashboardEl && dashboardEl.__x) {
        dashboardEl.__x.$data.toggleHomepage(articleId, true);
    }
}

console.log('✅ Dashboard Manager Alpine.js chargé');
