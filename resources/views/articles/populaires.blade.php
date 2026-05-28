@extends('layouts.app')

@section('title', 'Articles Populaires')

@section('content')
<div class="container">
    <h1 class="mb-4">Articles les Plus Populaires</h1>

    <div class="row">
        @forelse($articles as $index => $article)
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="row g-0">
                        <div class="col-md-4">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}"
                                     class="img-fluid rounded-start h-100 w-100 object-fit-cover"
                                     alt="{{ $article->titre }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="popular-number me-3">{{ $index + 1 }}</div>
                                    <h5 class="card-title mb-0">{{ $article->titre }}</h5>
                                </div>
                                <p class="card-text">{{ Str::limit($article->extrait, 200) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <small>{{ $article->created_at->format('d/m/Y') }}</small>
                                        <span class="mx-2">•</span>
                                        <small>{{ $article->views_count }} vues</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('articles.show', $article->id) }}"
                                           class="btn btn-primary">Lire l'article</a>
                                        @if($article->document_path)
                                            @auth
                                                @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
                                                    <a href="{{ route('articles.download', $article) }}"
                                                       class="btn btn-success">
                                                        <i class="fas fa-download"></i> PDF
                                                    </a>
                                                @else
                                                    <a href="{{ route('home.abonnement') }}"
                                                       class="btn btn-warning">
                                                        <i class="fas fa-crown"></i> Abonnement
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('login') }}"
                                                   class="btn btn-outline-secondary">
                                                    <i class="fas fa-sign-in-alt"></i> Connexion
                                                </a>
                                            @endauth
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucun article disponible pour le moment.
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.popular-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(45deg, #f1c40f, #f39c12);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.object-fit-cover {
    object-fit: cover;
}
</style>
@endsection
