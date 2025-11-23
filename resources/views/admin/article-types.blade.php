@extends('layouts.admin')

@section('title', 'Gestion des Types d\'Articles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt me-2"></i>Gestion des Types d'Articles</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#articleTypeInfoModal">
                    <i class="fas fa-info-circle me-2"></i>Guide des Types
                </button>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center border-success">
                        <div class="card-body">
                            <i class="fas fa-unlock-alt text-success fa-2x mb-2"></i>
                            <h4 class="text-success">{{ $stats['gratuits'] }}</h4>
                            <small class="text-muted">Articles Gratuits</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-warning">
                        <div class="card-body">
                            <i class="fas fa-star text-warning fa-2x mb-2"></i>
                            <h4 class="text-warning">{{ $stats['premium'] }}</h4>
                            <small class="text-muted">Articles Premium</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <i class="fas fa-download text-info fa-2x mb-2"></i>
                            <h4 class="text-info">{{ $stats['downloads'] }}</h4>
                            <small class="text-muted">Total Téléchargements</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center border-secondary">
                        <div class="card-body">
                            <i class="fas fa-hdd text-secondary fa-2x mb-2"></i>
                            <h4 class="text-secondary">{{ $stats['storage'] }}</h4>
                            <small class="text-muted">Espace de Stockage</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center border-dark">
                        <div class="card-body">
                            <i class="fas fa-money-bill text-dark fa-2x mb-2"></i>
                            <h4 class="text-dark">{{ $stats['revenue'] }} FCFA</h4>
                            <small class="text-muted">Revenus Potentiels</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.articles.types') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="article_type" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="breve" {{ request('article_type') == 'breve' ? 'selected' : '' }}>Brève</option>
                                    <option value="communique" {{ request('article_type') == 'communique' ? 'selected' : '' }}>Communiqué</option>
                                    <option value="analyse" {{ request('article_type') == 'analyse' ? 'selected' : '' }}>Analyse</option>
                                    <option value="enquete" {{ request('article_type') == 'enquete' ? 'selected' : '' }}>Enquête</option>
                                    <option value="interview" {{ request('article_type') == 'interview' ? 'selected' : '' }}>Interview</option>
                                    <option value="tutoriel" {{ request('article_type') == 'tutoriel' ? 'selected' : '' }}>Tutoriel</option>
                                    <option value="explicatif" {{ request('article_type') == 'explicatif' ? 'selected' : '' }}>Explicatif</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="content_quality" class="form-select">
                                    <option value="">Toutes qualités</option>
                                    <option value="court" {{ request('content_quality') == 'court' ? 'selected' : '' }}>Court</option>
                                    <option value="moyen" {{ request('content_quality') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                    <option value="profond" {{ request('content_quality') == 'profond' ? 'selected' : '' }}>Profond</option>
                                    <option value="exclusif" {{ request('content_quality') == 'exclusif' ? 'selected' : '' }}>Exclusif</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="is_premium" class="form-select">
                                    <option value="">Tous accès</option>
                                    <option value="0" {{ request('is_premium') === '0' ? 'selected' : '' }}>Gratuit</option>
                                    <option value="1" {{ request('is_premium') === '1' ? 'selected' : '' }}>Premium</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Filtrer
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.articles.types') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des articles -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Type</th>
                                    <th>Qualité</th>
                                    <th>Accès</th>
                                    <th>Prix unitaire</th>
                                    <th>Limite gratuite</th>
                                    <th>Téléchargements</th>
                                    <th>Taille</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                    @php
                                        $classification = $article->getArticleClassification();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ Str::limit($article->titre, 40) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $article->category->nom ?? 'Sans catégorie' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $article->article_type == 'breve' || $article->article_type == 'communique' ? 'success' :
                                                ($article->article_type == 'analyse' || $article->article_type == 'enquete' ? 'warning' :
                                                ($article->article_type == 'interview' ? 'danger' : 'info'))
                                            }}">
                                                {{ $classification['name'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $classification['quality'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($article->isPremiumType())
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-star me-1"></i>Premium
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-unlock me-1"></i>Gratuit
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($article->unit_price)
                                                <strong>{{ number_format($article->unit_price, 0) }} FCFA</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($article->free_download_limit > 0)
                                                <span class="badge bg-info">{{ $article->free_download_limit }}</span>
                                            @else
                                                <span class="text-muted">Illimité</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $article->download_count }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $article->getStorageUsage() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" 
                                                        onclick="editArticleType({{ $article->id }})"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editArticleTypeModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ route('article.show', $article->id) }}" 
                                                   class="btn btn-outline-info" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Guide des Types -->
<div class="modal fade" id="articleTypeInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Guide des Types d'Articles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Qualité</th>
                                <th>Accès</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-success">
                                <td><strong>Brèves, Faits divers, Communiqués</strong></td>
                                <td>Court</td>
                                <td>Gratuit</td>
                                <td>Informations courtes et factuelles</td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>Analyses politiques, économie locale, Enquêtes</strong></td>
                                <td>Profond</td>
                                <td>Premium</td>
                                <td>Contenu approfondi et analysé</td>
                            </tr>
                            <tr class="table-danger">
                                <td><strong>Interviews exclusives</strong></td>
                                <td>+++</td>
                                <td>Premium</td>
                                <td>Contenu exclusif de haute valeur</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Articles "Comment faire" / "Comprendre"</strong></td>
                                <td>Moyen</td>
                                <td>Gratuit / Premium selon valeur</td>
                                <td>Guides pratiques et explicatifs</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Édition Type Article -->
<div class="modal fade" id="editArticleTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editArticleTypeForm">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le Type d'Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_article_id" name="article_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Type d'article</label>
                        <select id="edit_article_type" name="article_type" class="form-select" required>
                            <option value="breve">Brève</option>
                            <option value="communique">Communiqué</option>
                            <option value="analyse">Analyse</option>
                            <option value="enquete">Enquête</option>
                            <option value="interview">Interview</option>
                            <option value="tutoriel">Tutoriel</option>
                            <option value="explicatif">Explicatif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qualité du contenu</label>
                        <select id="edit_content_quality" name="content_quality" class="form-select" required>
                            <option value="court">Court</option>
                            <option value="moyen">Moyen</option>
                            <option value="profond">Profond</option>
                            <option value="exclusif">Exclusif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="edit_is_premium" name="is_premium" class="form-check-input">
                            <label class="form-check-label">Article Premium</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prix unitaire (FCFA)</label>
                        <input type="number" id="edit_unit_price" name="unit_price" class="form-control" min="0" step="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Limite de téléchargement gratuit</label>
                        <input type="number" id="edit_free_download_limit" name="free_download_limit" class="form-control" min="0">
                        <small class="text-muted">0 = illimité</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editArticleType(articleId) {
    // Charger les données de l'article via AJAX
    fetch(`/admin/articles/${articleId}/edit-type`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_article_id').value = data.id;
            document.getElementById('edit_article_type').value = data.article_type || 'breve';
            document.getElementById('edit_content_quality').value = data.content_quality || 'court';
            document.getElementById('edit_is_premium').checked = data.is_premium;
            document.getElementById('edit_unit_price').value = data.unit_price || '';
            document.getElementById('edit_free_download_limit').value = data.free_download_limit || 0;
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Impossible de charger les données de l\'article.');
        });
}

document.getElementById('editArticleTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const articleId = formData.get('article_id');
    
    fetch(`/admin/articles/${articleId}/update-type`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue.');
    });
});
</script>
@endsection