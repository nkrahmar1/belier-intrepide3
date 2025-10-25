@extends('layouts.app')

@section('title', 'Articles')

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container py-3 py-md-4">
    <!-- Header avec statistiques responsive -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12 col-md-8 text-center text-md-start mb-3 mb-md-0">
            <h1 class="fw-bold text-dark" style="font-size: clamp(1.8rem, 5vw, 3rem);">📰 Nos Articles</h1>
            <p class="text-muted" style="font-size: clamp(1rem, 2.5vw, 1.2rem);">Découvrez nos dernières publications et analyses</p>
        </div>
        <div class="col-12 col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center text-md-end py-3">
                    <h5 class="card-title mb-1" style="font-size: clamp(1.2rem, 3vw, 1.5rem);">{{ method_exists($articles, 'total') ? $articles->total() : $articles->count() }}</h5>
                    <p class="card-text mb-0" style="font-size: clamp(0.8rem, 2vw, 1rem);">Articles disponibles</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres par catégorie responsive -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <h6 class="card-title mb-3" style="font-size: clamp(0.9rem, 2.5vw, 1.1rem);">Filtrer par catégorie :</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-sm-start">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary btn-sm px-3 py-2" style="font-size: clamp(0.8rem, 2vw, 0.9rem);">
                            Toutes les catégories
                        </a>
                        <!-- Catégories statiques pour éviter les requêtes supplémentaires -->
                        <a href="{{ route('articles.index', ['category' => 'politique']) }}" class="btn btn-outline-secondary btn-sm">
                            Politique
                        </a>
                        <a href="{{ route('articles.index', ['category' => 'economie']) }}" class="btn btn-outline-secondary btn-sm">
                            Économie
                        </a>
                        <a href="{{ route('articles.index', ['category' => 'sport']) }}" class="btn btn-outline-secondary btn-sm">
                            Sport
                        </a>
                        <a href="{{ route('articles.index', ['category' => 'culture']) }}" class="btn btn-outline-secondary btn-sm">
                            Culture
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="row">
        @forelse($articles as $article)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Image de l'article -->
                    @if($article->image)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $article->image) }}"
                                 class="card-img-top"
                                 alt="{{ $article->titre }}"
                                 style="height: 200px; object-fit: cover;">

                            <!-- Badges -->
                            <div class="position-absolute top-0 end-0 p-2">
                                @if($article->is_premium)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-crown"></i> Premium
                                    </span>
                                @endif
                                @if($article->document_path)
                                    <span class="badge bg-info">
                                        <i class="fas fa-file-download"></i> Document
                                    </span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                             style="height: 200px;">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <!-- Catégorie -->
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $article->category_nom ?? 'Non classé' }}</span>
                        </div>

                        <!-- Titre -->
                        <h5 class="card-title">{{ $article->titre_limit ?? $article->titre }}</h5>

                        <!-- Extrait -->
                        <p class="card-text flex-grow-1">
                            {{ $article->extrait_limit ?? ($article->extrait ?: 'Aucun extrait disponible') }}
                        </p>

                        <!-- Métadonnées -->
                        <div class="small text-muted mb-3">
                            <div class="d-flex justify-content-between">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    {{ $article->date_formatted }}
                                </span>
                                <span>
                                    <i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }} vues
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('articles.show', $article->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-book-open"></i> Lire l'article
                            </a>

                            @if($article->document_path)
                                @auth
                                    @if(auth()->user()->hasActiveSubscription())
                                        <a href="{{ route('articles.download', $article) }}"
                                           class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    @else
                                        <a href="{{ route('subscription.choose') }}" class="btn btn-warning btn-sm" title="Abonnement requis">
                                            <i class="fas fa-crown"></i> Abonnement requis
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> Se connecter
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-5x text-muted mb-3"></i>
                    <h3 class="text-muted">Aucun article disponible</h3>
                    <p class="text-muted">Les articles publiés apparaîtront ici prochainement.</p>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer le premier article
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $articles->links() }}
        </div>
    @endif

    <!-- Appel à l'action pour l'abonnement -->
    @guest
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white text-center">
                    <div class="card-body">
                        <h4>🔓 Accédez à tous nos contenus</h4>
                        <p class="mb-3">Connectez-vous pour accéder aux articles et documents premium</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-light">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light">
                                <i class="fas fa-user-plus"></i> Créer un compte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush
            <p class="text-gray-600">Découvrez nos derniers articles et publications</p>
        </div>

        <!-- Vérification si des articles existent -->
        @if($articles->count() > 0)
            <!-- Grille des articles -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($articles as $article)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Image de l'article -->
                        @if($article->image)
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                <img src="{{ asset('storage/' . $article->image) }}"
                                     alt="{{ $article->titre }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Contenu de la carte -->
                        <div class="p-6">
                            <!-- Catégorie -->
                            @if($article->category)
                                <div class="mb-2">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $article->category->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Titre -->
                            <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $article->titre }}
                            </h2>

                            <!-- Extrait du contenu -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($article->contenu), 120) }}
                            </p>

                            <!-- Meta informations -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                                @if($article->file_name)
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        PDF
                                    </span>
                                @endif
                            </div>

                            <!-- Boutons d'action -->
                            <div class="flex gap-2">
                                <a href="{{ route('articles.show', $article->id) }}"
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200">
                                    Lire l'article
                                </a>

                                @if($article->file_name)
                                    @auth
                                        <a href="{{ route('articles.download', $article->id) }}"
                                           class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="bg-gray-400 text-white py-2 px-4 rounded-md text-sm font-medium cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </span>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $articles->links() }}
            </div>
        @else
            <!-- Aucun article -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article</h3>
                <p class="mt-1 text-sm text-gray-500">Aucun article publié pour le moment.</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
// Nettoyer les URLs VS Code automatiquement
function cleanVSCodeURL() {
    let url = window.location.href;
    if (url.includes('vscodeBrowserReqId') || (url.includes('id=') && url.match(/id=[0-9a-f-]{36}/i))) {
        let cleanURL = url.split('?')[0];
        window.history.replaceState({}, document.title, cleanURL);
    }
}

// Appliquer le nettoyage au chargement
document.addEventListener('DOMContentLoaded', function() {
    cleanVSCodeURL();
});
</script>
@endpush

@endsection
