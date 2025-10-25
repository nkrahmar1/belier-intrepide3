@extends('home.base')

@section('title', 'Choisir un abonnement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-crown text-warning"></i>
                    Choisissez votre abonnement
                </h1>
                <p class="lead text-muted">Accédez au téléchargement illimité de documents avec nos formules d'abonnement</p>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($plans as $id => $plan)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-lg border-0 {{ $id == 2 ? 'border-primary' : '' }} position-relative">
                @if($id == 2)
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="fas fa-star"></i> Populaire
                        </span>
                    </div>
                @endif
                
                <div class="card-header text-center bg-white border-0 pt-4">
                    <h3 class="card-title text-dark fw-bold">{{ $plan['name'] }}</h3>
                    <div class="display-4 fw-bold text-primary">
                        {{ number_format($plan['price']) }} 
                        <small class="text-muted fs-6">FCFA</small>
                    </div>
                    <small class="text-muted">par mois</small>
                </div>

                <div class="card-body">
                    <p class="text-muted text-center mb-4">{{ $plan['description'] }}</p>
                    
                    <ul class="list-unstyled">
                        @if($id == 1)
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Téléchargement de 10 documents/mois</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Accès aux articles gratuits</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Support par email</li>
                            <li class="mb-2"><i class="fas fa-times text-muted me-2"></i> <span class="text-muted">Accès prioritaire</span></li>
                        @elseif($id == 2)
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Téléchargement illimité</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Accès à tous les articles</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Support prioritaire</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Notifications en temps réel</li>
                        @else
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Tout du plan Premium</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> API d'accès</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Statistiques avancées</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Support dédié 24/7</li>
                        @endif
                    </ul>
                </div>

                <div class="card-footer bg-white border-0 text-center pb-4">
                    <a href="{{ route('subscription.payment', $id) }}" 
                       class="btn {{ $id == 2 ? 'btn-primary' : 'btn-outline-primary' }} btn-lg w-100 rounded-pill">
                        <i class="fas fa-credit-card me-2"></i>
                        Choisir ce plan
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Garantie satisfait ou remboursé</strong> - Annulez votre abonnement à tout moment
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .card {
        margin-bottom: 2rem;
    }
}
</style>
@endsection