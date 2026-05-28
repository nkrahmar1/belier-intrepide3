@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">🏛️ Politique</h1>
        <p class="text-gray-600 mt-2">Actualités politiques et analyses</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($article->image)
                    <img src="{{ Storage::url($article->image) }}" alt="{{ $article->titre }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $article->titre }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($article->contenu, 150) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Par {{ $article->user->name }}</span>
                        <div class="flex space-x-2">
                            <a href="{{ route('articles.show', $article->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Lire la suite
                            </a>
                            @if($article->document_path)
                                @auth
                                    @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
                                        <a href="{{ route('articles.download', $article) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                            <i class="fas fa-download mr-1"></i>
                                            PDF
                                        </a>
                                    @else
                                        <a href="{{ route('home.abonnement') }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                            <i class="fas fa-crown mr-1"></i>
                                            Abonnement
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                        <i class="fas fa-sign-in-alt mr-1"></i>
                                        Connexion
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $article->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($articles->count() == 0)
        <div class="text-center py-8">
            <p class="text-gray-500">Aucun article politique disponible pour le moment.</p>
        </div>
    @endif

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</div>
@endsection
