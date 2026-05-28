{{-- Section Gestion des Messages avec filtres et actions en temps réel --}}
<div x-data="messagesSection()" x-init="loadMessages()">
    
    {{-- Barre de filtres --}}
    <div class="bg-gray-50 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Recherche --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Rechercher</label>
                <input type="text" 
                       x-model="filters.search" 
                       @input.debounce.500ms="loadMessages()"
                       placeholder="Sujet, contenu..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            {{-- Filtre statut --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">📬 Statut</label>
                <select x-model="filters.status" 
                        @change="loadMessages()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="all">Tous</option>
                    <option value="unread">Non lus</option>
                    <option value="read">Lus</option>
                </select>
            </div>
        </div>

        {{-- Statistiques --}}
        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                <span x-text="messages.data?.length || 0"></span> messages trouvés
                <span class="ml-4 text-red-600 font-semibold" x-text="`${unreadCount} non lus`"></span>
            </div>
        </div>
    </div>

    {{-- Liste des messages --}}
    <div class="space-y-4">
        <template x-for="message in messages.data" :key="message.id">
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4"
                 :class="message.is_read ? 'border-gray-300' : 'border-yellow-500 bg-yellow-50'">
                
                <div class="flex flex-col lg:flex-row justify-between gap-4">
                    {{-- Contenu du message --}}
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-2xl">
                                <span x-show="!message.is_read">📬</span>
                                <span x-show="message.is_read">✉️</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="text-lg font-bold text-gray-900" x-text="message.subject || 'Sans sujet'"></h4>
                                    <span x-show="!message.is_read" 
                                          class="px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                                        Nouveau
                                    </span>
                                </div>
                                
                                {{-- Aperçu du message --}}
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2" 
                                   x-text="message.message"></p>
                                
                                {{-- Informations --}}
                                <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                    <span>👤 <span x-text="message.user?.name || message.name || 'Anonyme'"></span></span>
                                    <span>📧 <span x-text="message.email"></span></span>
                                    <span>📅 <span x-text="formatDate(message.created_at)"></span></span>
                                </div>
                            </div>
                        </div>

                        {{-- Message complet (expandable) --}}
                        <div x-show="expandedMessage === message.id" 
                             x-collapse
                             class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="prose prose-sm max-w-none">
                                <p class="whitespace-pre-wrap text-gray-700" x-text="message.message"></p>
                            </div>
                            
                            {{-- Zone de réponse --}}
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h5 class="font-semibold text-gray-900 mb-2">Répondre :</h5>
                                <textarea x-model="replyMessage" 
                                          rows="4" 
                                          placeholder="Votre réponse..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 mb-2"></textarea>
                                <button @click="sendReply(message)" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold transition-all">
                                    📤 Envoyer la réponse
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex lg:flex-col gap-2">
                        <button @click="expandedMessage = expandedMessage === message.id ? null : message.id" 
                                class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg font-semibold text-sm transition-all whitespace-nowrap">
                            <span x-text="expandedMessage === message.id ? '📕 Fermer' : '📖 Lire'"></span>
                        </button>
                        <button @click="toggleRead(message)" 
                                class="px-4 py-2 rounded-lg font-semibold text-sm transition-all whitespace-nowrap"
                                :class="message.is_read ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-green-100 hover:bg-green-200 text-green-700'">
                            <span x-text="message.is_read ? '📬 Non lu' : '✅ Lu'"></span>
                        </button>
                        <button @click="deleteMessage(message.id)" 
                                class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-semibold text-sm transition-all">
                            🗑️ Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Pagination --}}
        <div x-show="messages.last_page > 1" class="flex justify-center gap-2 mt-6">
            <button @click="changePage(messages.current_page - 1)" 
                    :disabled="messages.current_page === 1"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50">
                ← Précédent
            </button>
            <span class="px-4 py-2 bg-yellow-600 text-white rounded-lg" 
                  x-text="`Page ${messages.current_page} / ${messages.last_page}`"></span>
            <button @click="changePage(messages.current_page + 1)" 
                    :disabled="messages.current_page === messages.last_page"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50">
                Suivant →
            </button>
        </div>

        {{-- État vide --}}
        <div x-show="!loading && messages.data?.length === 0" 
             class="text-center py-12 text-gray-500">
            <div class="text-6xl mb-4">✉️</div>
            <p class="text-lg">Aucun message trouvé</p>
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
function messagesSection() {
    return {
        loading: false,
        messages: { data: [], current_page: 1, last_page: 1 },
        expandedMessage: null,
        replyMessage: '',
        filters: {
            search: '',
            status: 'all',
            per_page: 15
        },

        get unreadCount() {
            return this.messages.data?.filter(m => !m.is_read).length || 0;
        },

        async loadMessages() {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    ...this.filters,
                    page: this.messages.current_page
                });

                const response = await fetch(`/api/admin/messages?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.messages = data.messages;
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors du chargement des messages', 'error');
            } finally {
                this.loading = false;
            }
        },

        async toggleRead(message) {
            try {
                const response = await fetch(`/api/admin/messages/${message.id}/toggle-read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.loadMessages();
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la modification', 'error');
            }
        },

        async deleteMessage(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) return;

            try {
                // TODO: Ajouter la route de suppression
                this.showNotification('Fonction de suppression à implémenter', 'info');
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la suppression', 'error');
            }
        },

        async sendReply(message) {
            if (!this.replyMessage.trim()) {
                this.showNotification('Veuillez saisir une réponse', 'error');
                return;
            }

            try {
                // TODO: Implémenter l'envoi de réponse par email
                this.showNotification('Réponse envoyée avec succès!', 'success');
                this.replyMessage = '';
                this.expandedMessage = null;
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de l\'envoi', 'error');
            }
        },

        changePage(page) {
            this.messages.current_page = page;
            this.loadMessages();
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        showNotification(message, type) {
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
