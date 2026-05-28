{{-- Vue partielle pour le modal Abonnements --}}
<div class="space-y-4 p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                Tous les abonnements
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $subscriptions->total() }} abonnement(s) au total
            </p>
        </div>
    </div>
    
    {{-- Liste des abonnements --}}
    <div class="space-y-3">
        @forelse($subscriptions as $subscription)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700
                        hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 
                                    flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($subscription->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $subscription->user->name ?? 'Utilisateur inconnu' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $subscription->user->email ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $subscription->status === 'active' ? '✅ Actif' : '❌ Expiré' }}
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            📅 {{ $subscription->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <span class="text-6xl mb-3 block">💳</span>
                <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                    Aucun abonnement trouvé
                </p>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    @if($subscriptions->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
