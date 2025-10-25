@extends('home.base')

@section('title', 'Test Dropdowns Account & Panier')

@section('content')
<div class="container mt-5">
    <h1>Test Dropdowns Account & Panier</h1>

    <div class="alert alert-info">
        <strong>Instructions de test :</strong>
        <ol>
            <li>Testez les dropdowns ci-dessous SANS l'inspecteur ouvert</li>
            <li>Ouvrez l'inspecteur (F12) et testez à nouveau</li>
            <li>Fermez l'inspecteur et testez encore</li>
            <li>Les dropdowns doivent fonctionner dans tous les cas</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Test Dropdown Account (Connecté)</h3>
            <div class="btn-group">
                <span class="navbar-text me-2 text-primary fw-bold">AB</span>
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testAccountDropdownAuth">
                    Account
                </button>
                <ul class="dropdown-menu" aria-labelledby="testAccountDropdownAuth">
                    <li>
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i> Se déconnecter
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Test Dropdown Account (Invité)</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testAccountDropdownGuest">
                    Mon compte
                </button>
                <ul class="dropdown-menu" aria-labelledby="testAccountDropdownGuest">
                    <li><a class="dropdown-item" href="#">Se Connecter</a></li>
                    <li><a class="dropdown-item" href="#">S'inscrire</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Test Dropdown Panier (Avec articles)</h3>
            <div class="dropdown">
                <button type="button" class="cart-icon dropdown-toggle btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false" id="testCartDropdownFull">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge active" title="Achats: 2 | Téléchargements: 1">3</span>
                </button>
                <ul class="dropdown-menu cart-dropdown" aria-labelledby="testCartDropdownFull">
                    <li><h6 class="dropdown-header">Articles dans votre panier</h6></li>
                    <li><a class="dropdown-item" href="#">Article 1 - 15€</a></li>
                    <li><a class="dropdown-item" href="#">Article 2 - 20€</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Voir le panier complet</a></li>
                    <li><a class="dropdown-item" href="#">Procéder au paiement</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Test Dropdown Panier (Vide)</h3>
            <div class="dropdown">
                <button type="button" class="cart-icon dropdown-toggle btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false" id="testCartDropdownEmpty">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge" title="Achats: 0 | Téléchargements: 0">0</span>
                </button>
                <ul class="dropdown-menu cart-dropdown" aria-labelledby="testCartDropdownEmpty">
                    <li><span class="dropdown-item-text">Votre panier est vide</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Découvrir nos articles</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h3>Console de Debug</h3>
            <div class="alert alert-dark">
                <strong>Ouvrez la console (F12) pour voir les logs de debug</strong>
                <br>
                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="window.checkBootstrap()">
                    Vérifier l'état Bootstrap
                </button>
                <button type="button" class="btn btn-sm btn-warning mt-2" onclick="window.fixBootstrap()">
                    Forcer réinitialisation
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.cart-icon {
    position: relative;
    font-size: 1.2rem;
    color: #333;
    text-decoration: none;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 0.75rem;
    min-width: 18px;
    text-align: center;
    line-height: 1.2;
}

.cart-badge:not(.active) {
    background-color: #6c757d;
}

.cart-dropdown {
    min-width: 250px;
    max-width: 300px;
}
</style>
@endsection

@section('scripts')
<script>
// Script de test spécifique
document.addEventListener('DOMContentLoaded', function() {
    console.log('🧪 Page de test des dropdowns chargée');

    // Ajouter des listeners spécifiques pour les tests
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(element) {
        element.addEventListener('click', function(e) {
            console.log('🖱️ Test click sur:', this.id);
        });

        element.addEventListener('shown.bs.dropdown', function() {
            console.log('✅ Test dropdown ouvert:', this.id);
        });

        element.addEventListener('hidden.bs.dropdown', function() {
            console.log('❌ Test dropdown fermé:', this.id);
        });
    });
});
</script>
@endsection
