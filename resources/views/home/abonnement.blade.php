@extends('layouts.app')

@section('title', 'Abonnements Premium')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Hero Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-crown text-warning me-2"></i>
                    Abonnements Premium
                </h1>
                <p class="lead text-muted mb-4">
                    Accédez au téléchargement illimité de documents et profitez de tous nos contenus exclusifs
                </p>
                <div class="alert alert-info d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nouveau !</strong> Paiement sécurisé avec Mobile Money (Orange Money, MTN Money, Moov Money, Wave)
                </div>
            </div>

            <!-- Current Subscription Status -->
            @if(Auth::check())
                @if(Auth::user()->is_premium && Auth::user()->subscription_expires_at > now())
                    <div class="alert alert-success text-center mb-5">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Abonnement Actif !</strong> 
                        Votre abonnement {{ Auth::user()->subscription_type }} expire le {{ Auth::user()->subscription_expires_at->format('d/m/Y') }}
                    </div>
                @else
                    <div class="alert alert-warning text-center mb-5">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Abonnement Requis</strong> 
                        Souscrivez à un abonnement pour télécharger les documents premium
                    </div>
                @endif
            @else
                <div class="alert alert-info text-center mb-5">
                    <i class="fas fa-user-plus me-2"></i>
                    <strong>Connectez-vous</strong> ou créez un compte pour souscrire à un abonnement
                    <div class="mt-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">S'inscrire</a>
                    </div>
                </div>
            @endif

            <!-- Benefits Section -->
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-download me-2"></i>
                                Accès Premium
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Téléchargement illimité de documents</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Accès aux articles exclusifs</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Contenu premium en temps réel</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Archives complètes</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Paiement Facile
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Orange Money</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> MTN Money</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Moov Money</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Wave Money</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                @auth
                    @if(!Auth::user()->is_premium || Auth::user()->subscription_expires_at <= now())
                        <a href="{{ route('subscription.choose') }}" class="btn btn-primary btn-lg px-5 py-3 me-3">
                            <i class="fas fa-credit-card me-2"></i>
                            Choisir un Abonnement
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-5 py-3 me-3">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Se connecter pour s'abonner
                    </a>
                @endauth
                
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-5 py-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à l'accueil
                </a>
            </div>

            <!-- Features Grid -->
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Paiement Sécurisé</h5>
                    <p class="text-muted">Transactions protégées par CinetPay, leader des paiements en Afrique de l'Ouest</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-clock text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Activation Immédiate</h5>
                    <p class="text-muted">Votre abonnement est activé instantanément après le paiement</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Support 24/7</h5>
                    <p class="text-muted">Notre équipe est là pour vous accompagner à tout moment</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-icon {
    transition: transform 0.3s ease;
}
.feature-icon:hover {
    transform: translateY(-5px);
}
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
