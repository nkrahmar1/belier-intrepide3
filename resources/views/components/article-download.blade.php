{{-- Composant de téléchargement d'article avec gestion des permissions --}}
<div class="article-download-section" data-article-id="{{ $article->id }}">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Informations sur l'article -->
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <!-- Badge du type d'article -->
                        @php
                            $classification = $article->getArticleClassification();
                            $badgeClass = match($article->article_type) {
                                'breve', 'communique' => 'bg-success',
                                'tutoriel', 'explicatif' => 'bg-info',
                                'analyse', 'enquete' => 'bg-warning text-dark',
                                'interview' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} me-2">
                            <i class="fas fa-file-alt me-1"></i>
                            {{ $classification['name'] }}
                        </span>
                        
                        <!-- Niveau d'accès -->
                        <span class="badge {{ $article->isPremiumType() ? 'bg-gold text-dark' : 'bg-light text-dark' }} me-2">
                            <i class="fas {{ $article->isPremiumType() ? 'fa-star' : 'fa-unlock' }} me-1"></i>
                            {{ $classification['access'] }}
                        </span>

                        <!-- Qualité du contenu -->
                        <small class="text-muted">
                            <i class="fas fa-chart-bar me-1"></i>
                            Qualité: {{ $classification['quality'] }}
                        </small>
                    </div>
                </div>
                
                <div class="col-md-4 text-end">
                    <!-- Taille du fichier -->
                    @if($article->file_size || $article->storage_size)
                        <small class="text-muted d-block">
                            <i class="fas fa-file-download me-1"></i>
                            {{ $article->getStorageUsage() }}
                        </small>
                    @endif
                    
                    <!-- Nombre de téléchargements -->
                    <small class="text-muted">
                        <i class="fas fa-download me-1"></i>
                        {{ $article->download_count }} téléchargements
                    </small>
                </div>
            </div>

            <!-- Zone de téléchargement -->
            <div id="download-area-{{ $article->id }}">
                <!-- Le contenu sera chargé via JavaScript -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Vérification des permissions...</span>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            @if($article->free_download_limit > 0)
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Limite de téléchargement gratuit:</strong> {{ $article->free_download_limit }} par utilisateur
                    </small>
                </div>
            @endif

            @if($article->unit_price > 0)
                <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded">
                    <small class="text-dark">
                        <i class="fas fa-tag me-1"></i>
                        <strong>Achat unitaire disponible:</strong> {{ number_format($article->unit_price, 0) }} FCFA
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.badge.bg-gold {
    background: linear-gradient(135deg, #ffd700, #ffed4e) !important;
}

.article-download-section .card {
    transition: transform 0.2s ease;
}

.article-download-section .card:hover {
    transform: translateY(-2px);
}

.download-btn {
    transition: all 0.3s ease;
}

.download-btn:hover {
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const articleId = {{ $article->id }};
    const downloadArea = document.getElementById(`download-area-${articleId}`);
    
    // Vérifier les permissions de téléchargement
    fetch(`/article/${articleId}/download-check`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                downloadArea.innerHTML = generateDownloadButton(data, articleId);
            } else {
                downloadArea.innerHTML = generateErrorMessage(data);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            downloadArea.innerHTML = generateErrorMessage({
                message: 'Impossible de vérifier les permissions de téléchargement.'
            });
        });
});

function generateDownloadButton(data, articleId) {
    if (data.can_download) {
        const reasonText = {
            'free': 'Téléchargement gratuit',
            'premium_subscription': 'Inclus dans votre abonnement',
            'purchase_required': 'Achat unitaire'
        };
        
        const iconClass = {
            'free': 'fa-download text-success',
            'premium_subscription': 'fa-crown text-warning',
            'purchase_required': 'fa-shopping-cart text-info'
        };
        
        return `
            <div class="d-grid">
                <button onclick="downloadArticle(${articleId})" 
                        class="btn btn-primary download-btn">
                    <i class="fas ${iconClass[data.reason] || 'fa-download'} me-2"></i>
                    ${reasonText[data.reason] || 'Télécharger'}
                </button>
                <small class="text-muted mt-2">
                    ${reasonText[data.reason]} - ${data.file_size}
                </small>
            </div>
        `;
    } else {
        return generateAccessDeniedButton(data, articleId);
    }
}

function generateAccessDeniedButton(data, articleId) {
    const messages = {
        'login_required': {
            text: 'Connexion requise',
            button: 'Se connecter',
            action: 'login',
            class: 'btn-outline-primary'
        },
        'subscription_required': {
            text: 'Abonnement premium requis',
            button: 'Voir les formules',
            action: 'subscription',
            class: 'btn-outline-warning'
        },
        'limit_exceeded': {
            text: 'Limite de téléchargement atteinte',
            button: 'Passer au premium',
            action: 'subscription',
            class: 'btn-outline-danger'
        },
        'purchase_required': {
            text: `Achat unitaire: ${data.price || 0} FCFA`,
            button: 'Acheter',
            action: 'purchase',
            class: 'btn-outline-success'
        }
    };
    
    const config = messages[data.reason] || {
        text: 'Accès non autorisé',
        button: 'Plus d\'infos',
        action: 'info',
        class: 'btn-outline-secondary'
    };
    
    return `
        <div class="text-center">
            <p class="mb-3">
                <i class="fas fa-lock me-2 text-muted"></i>
                ${config.text}
            </p>
            <button onclick="handleAccessAction('${config.action}', ${articleId}, ${data.price || 0})" 
                    class="btn ${config.class}">
                <i class="fas fa-external-link-alt me-2"></i>
                ${config.button}
            </button>
        </div>
    `;
}

function generateErrorMessage(data) {
    return `
        <div class="text-center text-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${data.message || 'Une erreur est survenue.'}
        </div>
    `;
}

function downloadArticle(articleId) {
    // Afficher un indicateur de chargement
    const downloadArea = document.getElementById(`download-area-${articleId}`);
    const originalContent = downloadArea.innerHTML;
    
    downloadArea.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary me-2" role="status"></div>
            Préparation du téléchargement...
        </div>
    `;
    
    // Créer un lien de téléchargement
    const link = document.createElement('a');
    link.href = `/article/${articleId}/download`;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Restaurer le contenu après 2 secondes
    setTimeout(() => {
        downloadArea.innerHTML = originalContent;
    }, 2000);
}

function handleAccessAction(action, articleId, price = 0) {
    switch (action) {
        case 'login':
            window.location.href = '/login';
            break;
        case 'subscription':
            window.location.href = '/subscription/choose';
            break;
        case 'purchase':
            // Implémenter l'achat unitaire
            if (confirm(`Acheter cet article pour ${price} FCFA ?`)) {
                alert('Fonctionnalité d\'achat unitaire à implémenter');
            }
            break;
        default:
            alert('Plus d\'informations sur les accès premium disponibles dans votre espace personnel.');
    }
}
</script>