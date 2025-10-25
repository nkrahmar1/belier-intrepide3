<!-- Articles récents avec actions directes -->
@if(isset($recentArticles) && $recentArticles->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Statut</th>
                    <th>Vues</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentArticles as $article)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}"
                                     class="rounded me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ Str::limit($article->titre, 40) }}</h6>
                                <small class="text-muted">
                                    @if($article->category)
                                        {{ $article->category->nom }}
                                    @else
                                        Sans catégorie
                                    @endif
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($article->is_published)
                            <span class="badge bg-success">
                                <i class="fas fa-eye"></i> Publié
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-edit"></i> Brouillon
                            </span>
                        @endif

                        @if($article->is_premium)
                            <span class="badge bg-gold text-dark ms-1">
                                <i class="fas fa-crown"></i> Premium
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <!-- Bouton Publication/Dépublication -->
                            @if($article->id)
                            <button class="btn btn-outline-{{ $article->is_published ? 'warning' : 'success' }}"
                                    onclick="togglePublish({{ $article->id }})"
                                    title="{{ $article->is_published ? 'Dépublier' : 'Publier' }}">
                                <i class="fas fa-{{ $article->is_published ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                            @endif

                            <!-- Bouton Édition -->
                            <a href="{{ route('admin.articles.edit', $article) }}"
                               class="btn btn-outline-primary"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Bouton Téléchargement -->
                            @if($article->document_path)
                                <a href="{{ route('admin.articles.download', $article) }}"
                                   class="btn btn-outline-info"
                                   title="Télécharger document">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif

                            <!-- Menu actions -->
                            <div class="btn-group" role="group">
                                <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('articles.show', $article->id) }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Voir
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="duplicateArticle({{ $article->id }})">
                                        <i class="fas fa-copy"></i> Dupliquer
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteArticle({{ $article->id }})">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Aucun article créé</h5>
        <p class="text-muted mb-4">Commencez par créer votre premier article pour voir les statistiques</p>
        <button class="btn btn-primary btn-lg" onclick="showCreateArticleModal()">
            <i class="fas fa-plus"></i> Créer mon premier article
        </button>

        <!-- Bouton pour créer des données de test -->
        <div class="mt-3">
            <button class="btn btn-outline-secondary" onclick="createTestData()">
                <i class="fas fa-database"></i> Créer des données de test
            </button>
        </div>
    </div>

    <script>
    function createTestData() {
        if(confirm('Voulez-vous créer des données de test (articles, catégories) ?')) {
            window.open('{{ route("create-test-data") }}', '_blank');
            setTimeout(() => {
                location.reload();
            }, 3000);
        }
    }
    </script>
@endif

<script>
function togglePublish(articleId) {
    // Vérifier que l'ID de l'article est valide
    if (!articleId || articleId === 'undefined' || articleId === '') {
        console.error('ID article invalide:', articleId);
        showNotification('Erreur: ID article manquant', 'error');
        return;
    }

    $.ajax({
        url: `/admin/articles/${articleId}/toggle-publish`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#recentArticles').load('{{ route("admin.dashboard") }} #recentArticles > *');
            showNotification('Statut mis à jour!', 'success');
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX:', xhr.responseText);
            showNotification('Erreur lors de la mise à jour: ' + error, 'error');
        }
    });
}

function duplicateArticle(articleId) {
    if(confirm('Voulez-vous dupliquer cet article?')) {
        $.ajax({
            url: `/admin/articles/${articleId}/duplicate`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#recentArticles').load('{{ route("admin.dashboard") }} #recentArticles > *');
                showNotification('Article dupliqué avec succès!', 'success');
            },
            error: function() {
                showNotification('Erreur lors de la duplication', 'error');
            }
        });
    }
}

function deleteArticle(articleId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cet article? Cette action est irréversible.')) {
        $.ajax({
            url: `/admin/articles/${articleId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#recentArticles').load('{{ route("admin.dashboard") }} #recentArticles > *');
                showNotification('Article supprimé avec succès!', 'success');
            },
            error: function() {
                showNotification('Erreur lors de la suppression', 'error');
            }
        });
    }
}
</script>
