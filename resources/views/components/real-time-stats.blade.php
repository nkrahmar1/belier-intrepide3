<!-- Real-Time Statistics Component -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" x-data="realtimeStats()">
    <!-- Articles Stat Card -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-green-900 dark:text-green-100">Articles</h3>
            <div class="bg-green-200 dark:bg-green-700 rounded-full p-3">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.3-1.54c-.31-.37-.86-.37-1.17 0-.31.37-.31.97 0 1.34l2 2.36c.31.37.86.37 1.17 0 .39-.46 1.35-1.65 2.89-3.65.3-.37.29-.97-.02-1.34-.31-.36-.86-.37-1.17 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-600 dark:text-green-300 mb-2">
            <span x-text="stats.articles || '0'">{{ $articlesCount ?? 0 }}</span>
        </p>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-green-700 dark:text-green-200">📈 Articles créés</span>
            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 14c1.66 0 3-1.34 3-3 0-1.66-1.34-3-3-3-1.66 0-3 1.34-3 3 0 1.66 1.34 3 3 3zm13.71-9.71L12 8.29 6.41 2.7c-.39-.39-1.02-.39-1.41 0L.29 7.6c-.39.39-.39 1.02 0 1.41l5.59 5.59 5.59-5.59 5.59 5.59c.39.39 1.02.39 1.41 0l5.29-5.29c.39-.39.39-1.02 0-1.41z"/>
            </svg>
        </div>
    </div>

    <!-- Users Stat Card -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100">Utilisateurs</h3>
            <div class="bg-blue-200 dark:bg-blue-700 rounded-full p-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-blue-600 dark:text-blue-300 mb-2">
            <span x-text="stats.users || '0'">{{ $usersCount ?? 0 }}</span>
        </p>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-blue-700 dark:text-blue-200">👥 Utilisateurs actifs</span>
            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 14c1.66 0 3-1.34 3-3 0-1.66-1.34-3-3-3-1.66 0-3 1.34-3 3 0 1.66 1.34 3 3 3zm13.71-9.71L12 8.29 6.41 2.7c-.39-.39-1.02-.39-1.41 0L.29 7.6c-.39.39-.39 1.02 0 1.41l5.59 5.59 5.59-5.59 5.59 5.59c.39.39 1.02.39 1.41 0l5.29-5.29c.39-.39.39-1.02 0-1.41z"/>
            </svg>
        </div>
    </div>

    <!-- Premium Users Stat Card -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-100">Premium</h3>
            <div class="bg-purple-200 dark:bg-purple-700 rounded-full p-3">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-purple-600 dark:text-purple-300 mb-2">
            <span x-text="stats.premium || '0'">{{ $premiumCount ?? 0 }}</span>
        </p>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-purple-700 dark:text-purple-200">⭐ Utilisateurs premium</span>
            <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 14c1.66 0 3-1.34 3-3 0-1.66-1.34-3-3-3-1.66 0-3 1.34-3 3 0 1.66 1.34 3 3 3zm13.71-9.71L12 8.29 6.41 2.7c-.39-.39-1.02-.39-1.41 0L.29 7.6c-.39.39-.39 1.02 0 1.41l5.59 5.59 5.59-5.59 5.59 5.59c.39.39 1.02.39 1.41 0l5.29-5.29c.39-.39.39-1.02 0-1.41z"/>
            </svg>
        </div>
    </div>

    <!-- Messages Stat Card -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-all transform hover:scale-105">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-orange-900 dark:text-orange-100">Messages</h3>
            <div class="bg-orange-200 dark:bg-orange-700 rounded-full p-3">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-orange-600 dark:text-orange-300 mb-2">
            <span x-text="stats.messages || '0'">{{ $messagesCount ?? 0 }}</span>
        </p>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-orange-700 dark:text-orange-200">💬 Messages non lus</span>
            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 14c1.66 0 3-1.34 3-3 0-1.66-1.34-3-3-3-1.66 0-3 1.34-3 3 0 1.66 1.34 3 3 3zm13.71-9.71L12 8.29 6.41 2.7c-.39-.39-1.02-.39-1.41 0L.29 7.6c-.39.39-.39 1.02 0 1.41l5.59 5.59 5.59-5.59 5.59 5.59c.39.39 1.02.39 1.41 0l5.29-5.29c.39-.39.39-1.02 0-1.41z"/>
            </svg>
        </div>
    </div>
</div>

<script>
function realtimeStats() {
    return {
        stats: {
            articles: {{ $articlesCount ?? 0 }},
            users: {{ $usersCount ?? 0 }},
            premium: {{ $premiumCount ?? 0 }},
            messages: {{ $messagesCount ?? 0 }}
        },
        refreshStats() {
            // This could be updated with AJAX call to refresh stats
            console.log('Stats refreshed');
        }
    };
}
</script>
