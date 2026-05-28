@extends('home.base')

@section('title', 'Mon Panier')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-primary fw-bold">
        <i class="bi bi-cart3"></i> Mon Panier
    </h1>

    @php
        $cart = session('cart', []);
        $downloadCount = 0;
        $purchaseCount = 0;
        $purchaseTotal = 0;
    @endphp

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3">Votre panier est vide</h3>
            <p class="text-muted">Commencez par télécharger des articles ou faire des achats</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-house"></i> Retour à l'accueil
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-list"></i> Articles dans votre panier</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cart as $key => $item)
                            @php
                                $isDownload = ($item['type'] ?? '') === 'download';
                                if($isDownload) {
                                    $downloadCount += $item['quantity'] ?? 1;
                                } else {
                                    $purchaseCount += $item['quantity'] ?? 0;
                                    $purchaseTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                                }
                            @endphp

                            <div class="row align-items-center border-bottom py-3">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        @if($isDownload)
                                            <i class="bi bi-download text-success me-2"></i>
                                        @else
                                            <i class="bi bi-bag text-primary me-2"></i>
                                        @endif
                                        {{ $item['title'] ?? $item['name'] ?? 'Article' }}
                                    </h6>
                                    @if($isDownload)
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Téléchargé
                                            @if(isset($item['downloaded_at']))
                                                le {{ date('d/m/Y à H:i', strtotime($item['downloaded_at'])) }}
                                            @endif
                                        </small>
                                    @else
                                        <small class="text-muted">Article acheté</small>
                                    @endif
                                </div>

                                <div class="col-md-3 text-center">
                                    @if($isDownload)
                                        <span class="badge bg-success">{{ $item['quantity'] ?? 1 }} téléchargement(s)</span>
                                    @else
                                        <span>Quantité: {{ $item['quantity'] ?? 0 }}</span>
                                    @endif
                                </div>

                                <div class="col-md-2 text-center">
                                    @if($isDownload)
                                        <span class="text-success fw-bold">Gratuit</span>
                                    @else
                                        <span class="fw-bold">{{ $item['price'] ?? 0 }} FCFA</span>
                                    @endif
                                </div>

                                <div class="col-md-1 text-center">
                                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-calculator"></i> Résumé</h5>
                    </div>
                    <div class="card-body">
                        @if($downloadCount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-download text-success"></i> Téléchargements:</span>
                                <span class="badge bg-success">{{ $downloadCount }}</span>
                            </div>
                        @endif

                        @if($purchaseCount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-bag text-primary"></i> Achats:</span>
                                <span>{{ $purchaseCount }} article(s)</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total à payer:</span>
                                <span>{{ $purchaseTotal }} FCFA</span>
                            </div>

                            @if($purchaseTotal > 0)
                                <div class="mt-3">
                                    <button class="btn btn-primary w-100">
                                        <i class="bi bi-credit-card"></i> Procéder au paiement
                                    </button>
                                </div>
                            @endif
                        @endif

                        @if($downloadCount > 0 && $purchaseCount == 0)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i>
                                Tous vos téléchargements sont gratuits grâce à votre abonnement !
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.border-bottom:last-child {
    border-bottom: none !important;
}
</style>
@endsection
