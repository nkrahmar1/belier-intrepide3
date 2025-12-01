<!-- Quick Actions Advanced Component -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">⚡ Actions Rapides Avancées</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">Accédez rapidement aux fonctionnalités principales</p>
    </div>

    <div x-data="quickActionsManager()" class="space-y-6">
        <!-- Primary Actions -->
        <div>
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Actions Principales</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Create Article -->
                <a href="{{ route('admin.articles.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-green-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-green-200 dark:bg-green-700 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Nouvel Article</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Créer un article</p>
                    </div>
                </a>

                <!-- Create User -->
                <a href="{{ route('admin.users.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-200 dark:bg-blue-700 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm9-1c.42 0 .9.01 1.41.07 1.48 1.78 2.59 3.5 2.59 5.93v2h6v-2c0-2.62-6.05-4-8-4z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Nouvel Utilisateur</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Ajouter un utilisateur</p>
                    </div>
                </a>

                <!-- Create Product -->
                <a href="{{ route('admin.products.create') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-purple-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-purple-200 dark:bg-purple-700 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Nouveau Produit</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Ajouter un produit</p>
                    </div>
                </a>

                <!-- Quick Report -->
                <a href="{{ route('admin.reports.index') }}" class="group relative overflow-hidden rounded-lg p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 hover:shadow-lg transition-all transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-orange-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-orange-200 dark:bg-orange-700 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2V17zm4 0h-2V7h2V17zm4 0h-2v-4h2V17z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-900 dark:text-white">Rapports</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Voir les statistiques</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Secondary Actions -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Actions Secondaires</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('admin.articles.index') }}" class="p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-center group">
                    <p class="text-2xl mb-1">📄</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-green-600">Tous les Articles</p>
                </a>
                <a href="{{ route('admin.users.index') }}" class="p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-center group">
                    <p class="text-2xl mb-1">👥</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600">Tous les Utilisateurs</p>
                </a>
                <a href="{{ route('admin.products.index') }}" class="p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-center group">
                    <p class="text-2xl mb-1">📦</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-purple-600">Tous les Produits</p>
                </a>
                <a href="{{ route('admin.settings') }}" class="p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-center group">
                    <p class="text-2xl mb-1">⚙️</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-600">Paramètres</p>
                </a>
            </div>
        </div>

        <!-- Favorite Actions -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-semibold text-gray-900 dark:text-white">⭐ Favoris</h4>
                <button 
                    @click="editFavorites = !editFavorites"
                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    {{ editFavorites ? 'Fait' : 'Modifier' }}
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <template x-for="(action, index) in favorites" :key="index">
                    <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                        <span x-text="action.icon" class="text-lg"></span>
                        <a :href="action.url" class="flex-1 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <span x-text="action.label"></span>
                        </a>
                        <button 
                            x-show="editFavorites"
                            @click="removeFavorite(index)"
                            class="text-red-600 hover:text-red-700 transition-colors"
                        >
                            ✕
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Save Preferences -->
        <div class="flex gap-2 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button 
                @click="savePreferences()" 
                class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-lg font-medium transition-all"
            >
                💾 Enregistrer Préférences
            </button>
        </div>
    </div>
</div>

<script>
function quickActionsManager() {
    return {
        editFavorites: false,
        favorites: [
            { label: 'Articles', icon: '📄', url: '{{ route("admin.articles.index") }}' },
            { label: 'Utilisateurs', icon: '👥', url: '{{ route("admin.users.index") }}' },
            { label: 'Produits', icon: '📦', url: '{{ route("admin.products.index") }}' },
            { label: 'Commandes', icon: '📋', url: '{{ route("admin.orders.index") }}' },
        ],

        removeFavorite(index) {
            this.favorites.splice(index, 1);
        },

        addFavorite(label, icon, url) {
            this.favorites.push({ label, icon, url });
        },

        savePreferences() {
            localStorage.setItem('favoriteActions', JSON.stringify(this.favorites));
            alert('✅ Préférences enregistrées!');
        },

        loadPreferences() {
            const saved = localStorage.getItem('favoriteActions');
            if (saved) {
                this.favorites = JSON.parse(saved);
            }
        },

        init() {
            this.loadPreferences();
        }
    };
}
</script>
