@extends('home.base')

@section('title', $article->titre)

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .article-detail-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .article-header {
        margin-bottom: 30px;
    }

    .article-category-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 15px;
        color: white;
    }

    .article-category-badge.politique {
        background: #dc3545;
    }

    .article-category-badge.economie {
        background: #28a745;
    }

    .article-category-badge.sport {
        background: #007bff;
    }

    .article-category-badge.culture {
        background: #ffc107;
        color: #333;
    }

    .article-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #666;
        font-size: 0.95rem;
        padding-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }

    .article-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .article-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
        margin: 30px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 40px;
    }

    .article-content p {
        margin-bottom: 20px;
    }

    /* Style pour le message d'abonnement */
    .subscription-overlay {
        background: linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.95) 30%, rgba(255,255,255,1) 50%);
        position: relative;
        padding: 40px 20px;
        text-align: center;
        margin-top: -100px;
        border-radius: 12px;
    }

    .subscription-card {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        max-width: 600px;
        margin: 0 auto;
    }

    .subscription-card h3 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .subscription-card p {
        font-size: 1.1rem;
        margin-bottom: 25px;
        opacity: 0.95;
    }

    .subscription-benefits {
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 20px;
        margin: 25px 0;
        text-align: left;
    }

    .subscription-benefits ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .subscription-benefits li {
        padding: 10px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1rem;
    }

    .subscription-benefits li i {
        color: #ffd700;
        font-size: 1.2rem;
    }

    .btn-subscribe {
        background: white;
        color: #dc3545;
        padding: 15px 40px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn-subscribe:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        color: #dc3545;
    }

    .article-content-preview {
        max-height: 300px;
        overflow: hidden;
        position: relative;
    }

    .download-section {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        margin-top: 40px;
    }

    .btn-download {
        background: #28a745;
        color: white;
        padding: 15px 40px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .article-title {
            font-size: 1.8rem;
        }

        .article-image {
            height: 250px;
        }

        .subscription-card {
            padding: 30px 20px;
        }
    }
</style>

<div class="article-detail-container">
    <div class="article-header">
        @if($article->category)
            <div class="article-category-badge {{ strtolower($article->category->nom) }}">
                {{ strtoupper($article->category->nom) }}
            </div>
        @endif

        <h1 class="article-title">{{ $article->titre }}</h1>

        <div class="article-meta">
            <div class="article-meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $article->created_at->format('d M Y') }}</span>
            </div>
            @if($article->user)
                <div class="article-meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $article->user->firstname }} {{ $article->user->lastname }}</span>
                </div>
            @endif
            <div class="article-meta-item">
                <i class="fas fa-clock"></i>
                <span>{{ ceil(str_word_count(strip_tags($article->contenu)) / 200) }} min de lecture</span>
            </div>
        </div>
    </div>

    @if($article->image)
        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="article-image">
    @endif

    @if($needsSubscription)
        <!-- Aperçu limité du contenu -->
        <div class="article-content-preview">
            <div class="article-content">
                {!! Str::limit($article->contenu, 500) !!}
            </div>
        </div>

        <!-- Message d'abonnement -->
        <div class="subscription-overlay">
            <div class="subscription-card">
                <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 20px;"></i>
                <h3>🔒 Contenu Réservé aux Abonnés</h3>
                <p>Cet article fait partie de notre catégorie <strong>Politique</strong> réservée aux abonnés premium.</p>
                
                <div class="subscription-benefits">
                    <h4 style="margin-bottom: 15px; font-size: 1.2rem;">✨ Avantages de l'abonnement :</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Accès illimité à tous les articles Politique</li>
                        <li><i class="fas fa-check-circle"></i> Téléchargement des articles en PDF</li>
                        <li><i class="fas fa-check-circle"></i> Journal quotidien en PDF dans votre boîte mail</li>
                        <li><i class="fas fa-check-circle"></i> Analyses exclusives et dossiers spéciaux</li>
                        <li><i class="fas fa-check-circle"></i> Sans publicité</li>
                    </ul>
                </div>

                <p style="font-size: 1.3rem; margin: 25px 0; font-weight: 700;">
                    <i class="fas fa-tag"></i> Seulement 30 000 F CFA / an
                </p>

                <a href="{{ route('home.abonnement') }}" class="btn-subscribe">
                    <i class="fas fa-star me-2"></i> S'abonner maintenant
                </a>

                @guest
                    <p style="margin-top: 20px; font-size: 0.95rem;">
                        Déjà abonné ? <a href="{{ route('login') }}" style="color: white; text-decoration: underline; font-weight: 600;">Connectez-vous</a>
                    </p>
                @endguest
            </div>
        </div>
    @else
        <!-- Contenu complet pour les abonnés -->
        <div class="article-content">
            {!! $article->contenu !!}
        </div>

        <!-- Section de téléchargement pour les abonnés -->
        @if($isSubscribed && $article->document_path)
            <div class="download-section">
                <h3 style="margin-bottom: 15px;">
                    <i class="fas fa-file-pdf"></i> Télécharger cet article
                </h3>
                <p style="color: #666; margin-bottom: 20px;">
                    En tant qu'abonné, vous pouvez télécharger cet article en PDF
                </p>
                <a href="{{ route('articles.download', $article->id) }}" class="btn-download">
                    <i class="fas fa-download me-2"></i> Télécharger le PDF
                </a>
            </div>
        @endif
    @endif

    <!-- Bouton retour -->
    <div style="margin-top: 40px; text-align: center;">
        <a href="{{ route('home') }}" style="color: #666; text-decoration: none; font-size: 1rem;">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
    </div>
</div>

@endsection
