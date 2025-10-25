<div class="p-6 md:p-10 bg-gradient-to-br from-blue-50 via-white to-indigo-100 min-h-screen">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/chart-admin.js"></script>
    <script>
        // Palette dynamique pour le camembert
        const pieColors = ['#6366f1', '#f472b6', '#34d399', '#fbbf24', '#f87171', '#60a5fa', '#a78bfa', '#facc15'];
        window.usersPieData = {
            labels: @json($pieLabels ?? []),
            datasets: [{
                data: @json($pieData ?? []),
                backgroundColor: pieColors.slice(0, (@json($pieData ?? []).length)),
                borderWidth: 2
            }]
        };
        window.usersBarData = {
            labels: @json($barLabels ?? []),
            datasets: [{
                label: 'Utilisateurs connectés',
                data: @json($barData ?? []),
                backgroundColor: '#6366f1',
                borderRadius: 8
            }]
        };
        // Message si aucune donnée
        if ((window.usersPieData.datasets[0].data.length === 0 || window.usersPieData.datasets[0].data.every(v => v === 0)) && document.getElementById('usersPieChart')) {
            document.getElementById('usersPieChart').insertAdjacentHTML('afterend', '<div class="text-gray-400 text-center mt-2">Aucune donnée à afficher pour le camembert.</div>');
        }
        if ((window.usersBarData.datasets[0].data.length === 0 || window.usersBarData.datasets[0].data.every(v => v === 0)) && document.getElementById('usersBarChart')) {
            document.getElementById('usersBarChart').insertAdjacentHTML('afterend', '<div class="text-gray-400 text-center mt-2">Aucune donnée à afficher pour le graphique en barres.</div>');
        }
    </script>
    <h1 class="text-3xl md:text-4xl font-extrabold text-indigo-700 mb-8 flex items-center gap-3">
        <span class="inline-block bg-indigo-100 text-indigo-600 rounded-full p-2 shadow-md">🏠</span>
        Dashboard
    </h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-indigo-100 flex flex-col justify-center items-center hover:shadow-2xl transition group w-full">
            <h3 class="text-lg font-bold text-indigo-700 mb-4 flex items-center gap-2"><span class="text-2xl">�</span> Répartition Utilisateurs / Abonnés</h3>
            <canvas id="usersPieChart" width="250" height="250"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-blue-100 flex flex-col justify-center items-center hover:shadow-2xl transition group w-full">
            <h3 class="text-lg font-bold text-blue-700 mb-4 flex items-center gap-2"><span class="text-2xl">📈</span> Utilisateurs connectés (7 jours)</h3>
            <canvas id="usersBarChart" width="350" height="250"></canvas>
        </div>
    </div>
    <div class="max-w-3xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition group border border-indigo-100">
            <h2 class="text-xl font-bold text-indigo-700 mb-4 flex items-center gap-2">
                <span class="inline-block bg-indigo-50 text-indigo-500 rounded-full p-1">📰</span>
                Articles récents
            </h2>
            <ul class="divide-y divide-indigo-50">
                @forelse($articles as $article)
                    <li class="py-3 flex items-center justify-between group-hover:bg-indigo-50 transition rounded-lg px-2">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-800">{{ $article->titre ?? $article->title }}</span>
                            <span class="text-xs text-gray-400">{{ $article->created_at->format('d/m/Y') }}</span>
                        </div>
                        <a href="{{ route('articles.show', $article->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-semibold transition">Voir</a>
                    </li>
                @empty
                    <li class="py-3 text-gray-400 italic">Aucun article récent</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
