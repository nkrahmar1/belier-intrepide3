{{-- Section Gestion des Articles avec recherche, filtres et actions en temps réel --}}
<div x-data="articlesSection()" x-init="loadArticles()">
    
    {{-- Barre de recherche et filtres --}}
    <div class="bg-gray-50 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Recherche --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Rechercher</label>
                <input type="text" 
                       x-model="filters.search" 
                       @input.debounce.500ms="loadArticles()"
                       placeholder="Titre, contenu, auteur..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Filtre statut --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">📊 Statut</label>
                <select x-model="filters.status" 
                        @change="loadArticles()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">Tous</option>
                    <option value="published">Publiés</option>
                    <option value="draft">Brouillons</option>
                </select>
            </div>

            {{-- Filtre catégorie --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🏷️ Catégorie</label>
                <select x-model="filters.category" 
                        @change="loadArticles()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">Toutes</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Résultats et pagination --}}
        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                <span x-text="articles.data?.length || 0"></span> articles trouvés
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Par page:</label>
                <select x-model="filters.per_page" 
                        @change="loadArticles()"
                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Bouton Créer un article --}}
    <div class="mb-6">
        <button @click="showCreateForm = true" 
                class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transform hover:scale-105 transition-all shadow-lg">
            <span>➕</span>
            Créer un nouvel article
        </button>
    </div>

    {{-- Formulaire de création/édition (modal inline) --}}
    <div x-show="showCreateForm || showEditForm" 
         x-cloak
         class="bg-white border-2 border-blue-200 rounded-xl p-6 mb-6 shadow-xl">
        <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span x-text="showEditForm ? '✏️ Modifier l\'article' : '➕ Créer un article'"></span>
        </h3>

        <form @submit.prevent="showEditForm ? updateArticle() : createArticle()" class="space-y-4">
            {{-- Titre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" 
                       x-model="formData.titre" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select x-model="formData.category_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Extrait --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Extrait</label>
                <textarea x-model="formData.extrait" 
                          rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Résumé court de l'article..."></textarea>
            </div>

            {{-- Contenu avec TinyMCE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                <textarea x-model="formData.contenu" 
                          id="article-content-editor"
                          rows="10" 
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Contenu de l'article..."></textarea>
            </div>

            {{-- Options --}}
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           x-model="formData.is_premium" 
                           class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">💎 Article Premium</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" 
                           x-model="formData.is_published" 
                           class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">✅ Publier immédiatement</span>
                </label>
            </div>

            {{-- Boutons d'action --}}
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" 
                        :disabled="loading"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center gap-2 transition-all disabled:opacity-50">
                    <span x-show="loading" class="animate-spin">⏳</span>
                    <span x-text="showEditForm ? 'Mettre à jour' : 'Créer l\'article'"></span>
                </button>
                <button type="button" 
                        @click="cancelForm()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition-all">
                    Annuler
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des articles --}}
    <div class="space-y-4">
        <template x-for="article in articles.data" :key="article.id">
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between gap-4">
                    {{-- Info article --}}
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                            <div x-show="article.image" class="flex-shrink-0">
                                <img :src="`/storage/${article.image}`" 
                                     :alt="article.titre" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xl font-bold text-gray-900 mb-2" x-text="article.titre"></h4>
                                <p class="text-sm text-gray-600 mb-2" x-text="article.extrait"></p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full" x-text="article.category?.nom"></span>
                                    <span class="px-3 py-1 rounded-full"
                                          :class="article.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                                          x-text="article.is_published ? '✅ Publié' : '📝 Brouillon'"></span>
                                    <span x-show="article.is_premium" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full">💎 Premium</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>✍️ <span x-text="article.user?.name"></span></span>
                            <span>📅 <span x-text="formatDate(article.created_at)"></span></span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex lg:flex-col gap-2">
                        <button @click="togglePublish(article)" 
                                class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap"
                                :class="article.is_published ? 'bg-orange-100 hover:bg-orange-200 text-orange-700' : 'bg-green-100 hover:bg-green-200 text-green-700'">
                            <span x-text="article.is_published ? '📝 Brouillon' : '✅ Publier'"></span>
                        </button>
                        <button @click="editArticle(article)" 
                                class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-semibold text-sm transition-all">
                            ✏️ Modifier
                        </button>
                        <button @click="deleteArticle(article.id)" 
                                class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold text-sm transition-all">
                            🗑️ Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Pagination --}}
        <div x-show="articles.last_page > 1" class="flex justify-center gap-2 mt-6">
            <button @click="changePage(articles.current_page - 1)" 
                    :disabled="articles.current_page === 1"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50">
                ← Précédent
            </button>
            <span class="px-4 py-2 bg-blue-600 text-white rounded-lg" x-text="`Page ${articles.current_page} / ${articles.last_page}`"></span>
            <button @click="changePage(articles.current_page + 1)" 
                    :disabled="articles.current_page === articles.last_page"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50">
                Suivant →
            </button>
        </div>

        {{-- État vide --}}
        <div x-show="!loading && articles.data?.length === 0" 
             class="text-center py-12 text-gray-500">
            <div class="text-6xl mb-4">📰</div>
            <p class="text-lg">Aucun article trouvé</p>
        </div>

        {{-- Chargement --}}
        <div x-show="loading" class="text-center py-12">
            <div class="animate-spin text-6xl mb-4">⏳</div>
            <p class="text-lg text-gray-600">Chargement...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function articlesSection() {
    return {
        loading: false,
        showCreateForm: false,
        showEditForm: false,
        articles: { data: [], current_page: 1, last_page: 1 },
        filters: {
            search: '',
            status: 'all',
            category: 'all',
            per_page: 10
        },
        formData: {
            id: null,
            titre: '',
            contenu: '',
            extrait: '',
            category_id: '',
            is_premium: false,
            is_published: false
        },

        async loadArticles() {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    ...this.filters,
                    page: this.articles.current_page
                });

                const response = await fetch(`/api/admin/articles?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.articles = data.articles;
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors du chargement des articles', 'error');
            } finally {
                this.loading = false;
            }
        },

        async createArticle() {
            this.loading = true;
            try {
                const response = await fetch('/admin/dashboard/quick-article', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification('Article créé avec succès!', 'success');
                    this.cancelForm();
                    this.loadArticles();
                } else {
                    this.showNotification(data.message || 'Erreur lors de la création', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la création', 'error');
            } finally {
                this.loading = false;
            }
        },

        async togglePublish(article) {
            try {
                const response = await fetch(`/api/admin/articles/${article.id}/toggle-publish`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.loadArticles();
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la modification', 'error');
            }
        },

        async deleteArticle(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

            try {
                const response = await fetch(`/api/admin/articles/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification('Article supprimé avec succès', 'success');
                    this.loadArticles();
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la suppression', 'error');
            }
        },

        editArticle(article) {
            this.formData = { ...article };
            this.showEditForm = true;
            this.showCreateForm = false;
        },

        async updateArticle() {
            // TODO: Implémenter la mise à jour
            this.showNotification('Mise à jour en cours...', 'success');
        },

        cancelForm() {
            this.showCreateForm = false;
            this.showEditForm = false;
            this.formData = {
                id: null,
                titre: '',
                contenu: '',
                extrait: '',
                category_id: '',
                is_premium: false,
                is_published: false
            };
        },

        changePage(page) {
            this.articles.current_page = page;
            this.loadArticles();
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        showNotification(message, type) {
            // Utiliser la fonction du parent si disponible
            if (window.showNotification) {
                window.showNotification(message, type);
            } else {
                alert(message);
            }
        }
    };
}
</script>
@endpush
