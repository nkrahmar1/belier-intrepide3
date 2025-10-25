{{-- Vue partielle pour le modal Messages --}}
<div class="space-y-4 p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                💬 Tous les messages
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $messages->total() }} message(s) au total
            </p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                🔍 Filtrer
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                ✅ Marquer tous comme lus
            </button>
        </div>
    </div>
    
    {{-- Liste des messages --}}
    <div class="space-y-3">
        @forelse($messages as $message)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700
                        hover:shadow-md transition-shadow {{ $message->is_read ? '' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' }}">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-start gap-3">
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 
                                        flex items-center justify-center text-white font-bold shrink-0">
                                {{ strtoupper(substr($message->sender_name ?? 'U', 0, 1)) }}
                            </div>
                            
                            {{-- Contenu --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $message->sender_name ?? 'Utilisateur inconnu' }}
                                    </p>
                                    @if(!$message->is_read)
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full dark:bg-blue-900 dark:text-blue-200">
                                            Nouveau
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    {{ $message->sender_email ?? 'N/A' }}
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                                    {{ $message->subject ?? 'Sans objet' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ Str::limit($message->message ?? '', 150) }}
                                </p>
                            </div>
                        </div>
                        
                        {{-- Date et actions --}}
                        <div class="flex flex-col items-end gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                📅 {{ $message->created_at->diffForHumans() }}
                            </span>
                            <div class="flex gap-2">
                                <button class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition"
                                        title="Voir">
                                    👁️
                                </button>
                                <button class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition"
                                        title="Répondre">
                                    ↩️
                                </button>
                                <button class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition"
                                        title="Supprimer">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <span class="text-6xl mb-3 block">📭</span>
                <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                    Aucun message trouvé
                </p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">
                    Tous vos messages apparaîtront ici
                </p>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    @if($messages->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $messages->links() }}
        </div>
    @endif
</div>
