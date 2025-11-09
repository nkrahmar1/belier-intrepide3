@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="h3">📰 Gestion des Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel Article
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Liste des Articles ({{ $articles->total() }} articles)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Premium</th>
                            <th>Vues</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $article->id }}</span></td>
                                <td>
                                    @if($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}"
                                             alt="Image"
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($article->titre, 30) }}</strong>
                                    @if($article->document_path)
                                        <br><small class="text-info"><i class="fas fa-file"></i> Document joint</small>
                                    @endif
                                </td>
                                <td>
                                    @if($article->category)
                                        <span class="badge bg-info">{{ $article->category->nom }}</span>
                                    @else
                                        <span class="badge bg-secondary">Non classé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->is_published)
                                        <span class="badge bg-success">
                                            <i class="fas fa-eye"></i> Publié
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-eye-slash"></i> Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->is_premium)
                                        <span class="badge bg-gold text-dark">
                                            <i class="fas fa-crown"></i> Premium
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">Gratuit</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-eye"></i> {{ $article->views_count }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $article->created_at ? $article->created_at->format('d/m/Y') : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Bouton de publication/dépublication -->
                                        <form action="{{ route('admin.articles.toggle-publish', ['id' => $article->id]) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @if($article->is_published)
                                                <button type="submit"
                                                        class="btn btn-outline-warning btn-sm"
                                                        title="Dépublier">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            @else
                                                <button type="submit"
                                                        class="btn btn-outline-success btn-sm"
                                                        title="Publier">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Bouton modifier -->
                                        <a href="{{ route('admin.articles.edit', $article) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Bouton Publication Page d'Accueil -->
                                        @if($article->is_published)
                                            @if($article->featured_on_homepage ?? false)
                                                <button onclick="removeFromHomepage({{ $article->id }})"
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Retirer de la page d'accueil">
                                                    <i class="fas fa-home"></i>
                                                    <i class="fas fa-times" style="font-size: 0.6em; margin-left: -2px;"></i>
                                                </button>
                                            @else
                                                <button onclick="publishToHomepage({{ $article->id }})"
                                                        class="btn btn-outline-success btn-sm"
                                                        title="Publier sur la page d'accueil">
                                                    <i class="fas fa-home"></i>
                                                    <i class="fas fa-plus" style="font-size: 0.6em; margin-left: -2px;"></i>
                                                </button>
                                            @endif
                                        @endif

                                        <!-- Bouton voir -->
                                        <a href="{{ route('articles.show', $article->id) }}"
                                           target="_blank"
                                           class="btn btn-outline-info btn-sm"
                                           title="Voir l'article">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>

                                        <!-- Bouton supprimer -->
                                        <form action="{{ route('admin.articles.destroy', $article) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-newspaper fa-3x mb-3"></i>
                                        <h5>Aucun article trouvé</h5>
                                        <p>Commencez par créer votre premier article.</p>
                                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Créer un article
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Total Articles</div>
                            <div class="h5">{{ $articles->total() }}</div>
                        </div>
                        <div>
                            <i class="fas fa-newspaper fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Articles Publiés</div>
                            <div class="h5">{{ $articles->where('is_published', true)->count() }}</div>
                        </div>
                        <div>
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Brouillons</div>
                            <div class="h5">{{ $articles->where('is_published', false)->count() }}</div>
                        </div>
                        <div>
                            <i class="fas fa-eye-slash fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="small text-white-50">Articles Premium</div>
                            <div class="h5">{{ $articles->where('is_premium', true)->count() }}</div>
                        </div>
                        <div>
                            <i class="fas fa-crown fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gold {
    background-color: #ffd700 !important;
}
.homepage-featured {
    box-shadow: 0 0 0 2px #28a745;
}
</style>
@endpush

@push('scripts')
<script>
// Fonctions pour la gestion de la page d'accueil
function publishToHomepage(articleId) {
    if (confirm('Voulez-vous publier cet article sur la page d\'accueil ?')) {
        fetch(`/admin/articles/${articleId}/publish-homepage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher notification de succès
                showNotification(data.message, 'success');
                // Recharger la page pour voir les changements
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Erreur lors de la publication', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
        });
    }
}

function removeFromHomepage(articleId) {
    if (confirm('Voulez-vous retirer cet article de la page d\'accueil ?')) {
        fetch(`/admin/articles/${articleId}/remove-homepage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
        });
    }
}

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    document.body.appendChild(notification);

    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endpush
