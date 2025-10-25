<div class="p-6 md:p-10 bg-gradient-to-br from-blue-50 via-white to-indigo-100 min-h-screen">
    <h1 class="text-3xl md:text-4xl font-extrabold text-indigo-700 mb-8 flex items-center gap-3">
        <span class="inline-block bg-indigo-100 text-indigo-600 rounded-full p-2 shadow-md">📰</span>
        Articles
    </h1>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition group border border-indigo-100">
            <h2 class="text-xl font-bold text-indigo-700 mb-4 flex items-center gap-2">
                <span class="inline-block bg-indigo-50 text-indigo-500 rounded-full p-1">Liste des articles</span>
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
                    <li class="py-3 text-gray-400 italic">Aucun article</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
