@extends('layouts.app')

@section('title', $article->titre)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">{{ $article->titre }}</h1>
        
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-64 object-cover rounded-lg mb-6">
        @endif
        
        <div class="prose max-w-none">
            {!! $article->contenu !!}
        </div>
        
        @if($article->document_path)
            <div class="mt-6">
                @auth
                    @if(auth()->user()->hasActiveSubscription())
                        <a href="{{ route('articles.download', $article) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger le document
                        </a>
                    @else
                        <a href="{{ route('home.abonnement') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded inline-flex items-center">
                            <i class="fas fa-crown mr-2"></i>
                            Abonnement requis
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter pour télécharger
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection