{{-- Vue partielle pour le modal Articles --}}
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="space-y-4 p-4" x-data="articlesManager()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                Tous les articles
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $articles->total() }} article(s) au total
            </p>
        </div>
        <button @click="openCreateModal()" 
                class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 
                       text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2 
                       transform hover:scale-105 transition-all duration-200 shadow-lg">
            <span class="text-lg">➕</span>
            <span>Nouvel article</span>
        </button>
    </div>
    
    {{-- Grid des articles --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl 
                        transform hover:-translate-y-1 transition-all duration-300 
                        border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                {{-- Image --}}
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" 
                         alt="{{ $article->titre }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-green-400 to-emerald-500 
                                flex items-center justify-center">
                        <span class="text-6xl">📰</span>
                    </div>
                @endif
                
                <div class="p-4">
                    {{-- Titre --}}
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 text-lg">
                        {{ $article->titre }}
                    </h4>
                    
                    {{-- Catégorie et Statut --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <span class="mr-1">📁</span>
                            {{ $article->category->nom ?? 'Sans catégorie' }}
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $article->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                            {{ $article->is_published ? '✅ Publié' : '⏳ Brouillon' }}
                        </span>
                    </div>
                    
                    {{-- Auteur et Date --}}
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                        <span class="flex items-center gap-1">
                            <span>👤</span>
                            {{ $article->user->name ?? 'Inconnu' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <span>📅</span>
                            {{ $article->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button onclick="togglePublishArticle({{ $article->id }}, {{ $article->is_published ? 'true' : 'false' }})"
                                class="flex-1 {{ $article->is_published ? 'bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' }} 
                                       px-3 py-2 rounded-lg text-sm font-semibold 
                                       hover:opacity-80 transition-all 
                                       flex items-center justify-center gap-1">
                            <span>{{ $article->is_published ? '📝' : '✅' }}</span>
                            <span>{{ $article->is_published ? 'Dépublier' : 'Publier' }}</span>
                        </button>
                        <a href="{{ route('admin.articles.edit', ['article' => $article->id]) }}"
                           class="flex-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 
                                  px-3 py-2 rounded-lg text-sm font-semibold 
                                  hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors 
                                  flex items-center justify-center gap-1">
                            <span>✏️</span>
                            <span>Modifier</span>
                        </a>
                        <button onclick="deleteArticleItem({{ $article->id }})"
                                class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 
                                       px-3 py-2 rounded-lg text-sm font-semibold 
                                       hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors 
                                       flex items-center justify-center gap-1">
                            <span>🗑️</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                    <span class="text-6xl mb-3">📭</span>
                    <p class="text-lg font-medium">Aucun article trouvé</p>
                    <p class="text-sm mt-1">Créez votre premier article</p>
                </div>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    @if($articles->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

    {{-- MODAL DE CRÉATION/ÉDITION D'ARTICLE --}}
    <div x-show="showModal" 
         x-cloak
         @keydown.escape.window="closeModal()"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"
             @click="closeModal()"></div>

        {{-- Modal Content --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click.stop 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                {{-- Header --}}
                <div class="sticky top-0 bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between z-10">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">📝</span>
                        <div>
                            <h3 class="text-xl font-bold text-white">Créer un nouvel article</h3>
                            <p class="text-green-100 text-sm">Remplissez les informations ci-dessous</p>
                        </div>
                    </div>
                    <button @click="closeModal()" 
                            class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <form @submit.prevent="submitArticle()" class="overflow-y-auto max-h-[calc(90vh-200px)]">
                    <div class="p-6 space-y-6">
                        
                        {{-- Titre --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                📌 Titre de l'article *
                            </label>
                            <input type="text" 
                                   x-model="formData.titre"
                                   required
                                   placeholder="Ex: Le Bélier Intrépide annonce une nouvelle politique..."
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white
                                          transition-all">
                            <p class="text-xs text-gray-500 mt-1">Minimum 3 caractères</p>
                        </div>

                        {{-- Catégorie --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                📁 Catégorie *
                            </label>
                            <select x-model="formData.category_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                                           focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                <option value="">-- Choisir une catégorie --</option>
                                <option value="1">🏛️ POLITIQUE</option>
                                <option value="2">💰 Économie</option>
                                <option value="6">🌍 Afrique</option>
                                <option value="7">⚽ Sport</option>
                                <option value="8">🎭 Culture et média</option>
                                <option value="9">👥 Société</option>
                                <option value="10">🐘 PDCI-RDA</option>
                                <option value="11">📂 Dossiers</option>
                            </select>
                        </div>

                        {{-- Extrait --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                ✨ Résumé court (extrait)
                            </label>
                            <textarea x-model="formData.extrait"
                                      rows="2"
                                      maxlength="500"
                                      placeholder="Résumé court pour les aperçus (max 500 caractères)..."
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                                             focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"></textarea>
                            <p class="text-xs text-gray-500 mt-1" x-text="(formData.extrait?.length || 0) + '/500 caractères'"></p>
                        </div>

                        {{-- Contenu --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                📄 Contenu de l'article *
                            </label>
                            <textarea x-model="formData.contenu"
                                      required
                                      rows="8"
                                      placeholder="Rédigez le contenu complet de votre article ici..."
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                                             focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white 
                                             font-mono text-sm"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 10 caractères</p>
                        </div>

                        {{-- Upload Image --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                🖼️ Image de couverture
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 flex flex-col items-center px-4 py-6 bg-white dark:bg-gray-700 
                                              border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg 
                                              cursor-pointer hover:border-green-500 transition-colors">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-semibold text-green-600">Cliquez pour choisir</span> ou glissez une image
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WEBP (max 2MB)</span>
                                    <input type="file" 
                                           @change="handleImageUpload($event)"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="hidden">
                                </label>
                                
                                {{-- Aperçu image --}}
                                <div x-show="imagePreview" class="flex-shrink-0">
                                    <img :src="imagePreview" 
                                         alt="Aperçu" 
                                         class="w-32 h-32 object-cover rounded-lg border-2 border-green-500">
                                    <button @click="removeImage()" 
                                            type="button"
                                            class="mt-2 text-xs text-red-600 hover:text-red-800">
                                        ❌ Supprimer
                                    </button>
                                </div>
                            </div>
                            <p x-show="imageName" 
                               class="text-sm text-green-600 dark:text-green-400 mt-2"
                               x-text="'✅ ' + imageName"></p>
                        </div>

                        {{-- Upload Document --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                📎 Document téléchargeable (optionnel)
                            </label>
                            <label class="flex flex-col items-center px-4 py-6 bg-white dark:bg-gray-700 
                                          border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg 
                                          cursor-pointer hover:border-blue-500 transition-colors">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-blue-600">Cliquez pour choisir</span> un document
                                </span>
                                <span class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, TXT, ZIP (max 10MB)</span>
                                <input type="file" 
                                       @change="handleDocumentUpload($event)"
                                       accept=".pdf,.doc,.docx,.txt,.zip"
                                       class="hidden">
                            </label>
                            <p x-show="documentName" 
                               class="text-sm text-blue-600 dark:text-blue-400 mt-2 flex items-center justify-between">
                                <span x-text="'📄 ' + documentName"></span>
                                <button @click="removeDocument()" 
                                        type="button"
                                        class="text-xs text-red-600 hover:text-red-800">
                                    ❌ Supprimer
                                </button>
                            </p>
                        </div>

                        {{-- Options --}}
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center gap-3 p-4 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <input type="checkbox" 
                                       x-model="formData.is_premium"
                                       class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white">💎 Article Premium</span>
                                    <p class="text-xs text-gray-500">Réservé aux abonnés</p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 border border-gray-300 dark:border-gray-600 rounded-lg 
                                          cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <input type="checkbox" 
                                       x-model="formData.is_published"
                                       class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white">✅ Publier immédiatement</span>
                                    <p class="text-xs text-gray-500">Sinon, restera en brouillon</p>
                                </div>
                            </label>
                        </div>

                        {{-- Messages d'erreur --}}
                        <div x-show="errorMessage" 
                             class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 
                                    rounded-lg p-4 flex items-start gap-3">
                            <span class="text-2xl">❌</span>
                            <div class="flex-1">
                                <p class="font-semibold text-red-800 dark:text-red-400">Erreur</p>
                                <p class="text-sm text-red-700 dark:text-red-300" x-text="errorMessage"></p>
                            </div>
                        </div>

                    </div>

                    {{-- Footer avec boutons --}}
                    <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900 px-6 py-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                @click="closeModal()"
                                :disabled="submitting"
                                class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                                       text-gray-700 dark:text-gray-300 font-semibold
                                       hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            Annuler
                        </button>
                        <button type="submit"
                                :disabled="submitting"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 
                                       hover:from-green-700 hover:to-emerald-700 text-white rounded-lg 
                                       font-semibold flex items-center gap-2 transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="submitting" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Publication...' : '✅ Publier l\'article'"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
// Composant Alpine.js pour gérer les articles
function articlesManager() {
    return {
        showModal: false,
        submitting: false,
        errorMessage: '',
        imagePreview: null,
        imageName: '',
        documentName: '',
        formData: {
            titre: '',
            contenu: '',
            extrait: '',
            category_id: '',
            is_premium: false,
            is_published: false,
            image: null,
            document: null
        },

        openCreateModal() {
            this.resetForm();
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
            document.body.style.overflow = '';
        },

        resetForm() {
            this.formData = {
                titre: '',
                contenu: '',
                extrait: '',
                category_id: '',
                is_premium: false,
                is_published: false,
                image: null,
                document: null
            };
            this.imagePreview = null;
            this.imageName = '';
            this.documentName = '';
            this.errorMessage = '';
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validation taille (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                this.errorMessage = 'L\'image ne doit pas dépasser 2MB';
                return;
            }

            // Validation type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                this.errorMessage = 'Format d\'image non supporté (utilisez JPG, PNG, GIF ou WEBP)';
                return;
            }

            this.formData.image = file;
            this.imageName = file.name;
            this.errorMessage = '';

            // Créer aperçu
            const reader = new FileReader();
            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeImage() {
            this.formData.image = null;
            this.imageName = '';
            this.imagePreview = null;
        },

        handleDocumentUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validation taille (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                this.errorMessage = 'Le document ne doit pas dépasser 10MB';
                return;
            }

            // Validation type
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.txt', '.zip'];
            const fileName = file.name.toLowerCase();
            const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
            
            if (!isValidExtension) {
                this.errorMessage = 'Format de document non supporté (utilisez PDF, DOC, DOCX, TXT ou ZIP)';
                return;
            }

            this.formData.document = file;
            this.documentName = file.name;
            this.errorMessage = '';
        },

        removeDocument() {
            this.formData.document = null;
            this.documentName = '';
        },

        async submitArticle() {
            this.errorMessage = '';

            // Validation basique
            if (!this.formData.titre || this.formData.titre.length < 3) {
                this.errorMessage = 'Le titre doit contenir au moins 3 caractères';
                return;
            }

            if (!this.formData.contenu || this.formData.contenu.length < 10) {
                this.errorMessage = 'Le contenu doit contenir au moins 10 caractères';
                return;
            }

            if (!this.formData.category_id) {
                this.errorMessage = 'Veuillez sélectionner une catégorie';
                return;
            }

            this.submitting = true;

            try {
                // Créer FormData pour envoyer les fichiers
                const formData = new FormData();
                formData.append('titre', this.formData.titre);
                formData.append('contenu', this.formData.contenu);
                formData.append('extrait', this.formData.extrait || '');
                formData.append('category_id', this.formData.category_id);
                formData.append('is_premium', this.formData.is_premium ? '1' : '0');
                formData.append('is_published', this.formData.is_published ? '1' : '0');

                if (this.formData.image) {
                    formData.append('image', this.formData.image);
                }

                if (this.formData.document) {
                    formData.append('document', this.formData.document);
                }

                // Envoyer la requête
                const response = await fetch('/admin/articles/quick-create', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Succès !
                    this.closeModal();
                    
                    // Afficher message de succès
                    alert('✅ Article créé avec succès !');
                    
                    // Recharger la section articles via SPA
                    const spaData = Alpine.$data(document.querySelector('[x-data*="spaApp"]'));
                    if (spaData && typeof spaData.loadSection === 'function') {
                        spaData.loadSection('articles', 'Gestion des Articles');
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Erreur du serveur
                    this.errorMessage = data.message || 'Une erreur est survenue';
                    
                    // Afficher erreurs de validation
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        this.errorMessage = errorMessages.join(', ');
                    }
                }

            } catch (error) {
                console.error('❌ Erreur:', error);
                this.errorMessage = 'Erreur de connexion au serveur';
            } finally {
                this.submitting = false;
            }
        }
    };
}

// Fonctions globales pour les actions sur les articles existants
function togglePublishArticle(articleId, isCurrentlyPublished) {
    if (!confirm(isCurrentlyPublished ? 'Dépublier cet article ?' : 'Publier cet article ?')) return;

    fetch(`/admin/articles/${articleId}/toggle-publish`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Recharger la section articles via SPA
            const spaData = Alpine.$data(document.querySelector('[x-data*="spaApp"]'));
            if (spaData && typeof spaData.loadSection === 'function') {
                spaData.loadSection('articles', 'Gestion des Articles');
            } else {
                window.location.reload();
            }
        } else {
            alert('❌ Erreur: ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('❌ Erreur:', error);
        alert('❌ Erreur lors de la publication');
    });
}

function deleteArticleItem(articleId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

    fetch(`/admin/articles/${articleId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Recharger la section articles via SPA
            const spaData = Alpine.$data(document.querySelector('[x-data*="spaApp"]'));
            if (spaData && typeof spaData.loadSection === 'function') {
                spaData.loadSection('articles', 'Gestion des Articles');
            } else {
                window.location.reload();
            }
        } else {
            alert('❌ Erreur: ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('❌ Erreur:', error);
        alert('❌ Erreur lors de la suppression');
    });
}
</script>
