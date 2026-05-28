<div>
    <h1 class="text-xl font-bold mb-4">Liste des articles</h1>
    @foreach($articles as $article)
        <div class="mb-2 p-2 bg-white rounded shadow">
            <a href="{{ route('articles.show', $article) }}" class="text-blue-700 hover:underline">{{ $article->titre }}</a>
        </div>
    @endforeach
    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
