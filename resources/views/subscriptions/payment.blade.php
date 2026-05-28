@extends('home.base')

@section('title', 'Mode de paiement')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- En-tête -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary mb-3">
                    <i class="fas fa-credit-card text-success"></i>
                    Finaliser votre abonnement
                </h1>
                <p class="lead text-muted">Sélectionnez votre mode de paiement pour activer votre abonnement</p>
            </div>

            <!-- Récapitulatif du plan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Récapitulatif de votre commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="fw-bold">Plan {{ $plan['name'] }}</h6>
                            <p class="text-muted mb-0">Durée : {{ $plan['duration'] }} mois</p>
                            <small class="text-success">
                                <i class="fas fa-shield-alt me-1"></i>
                                Paiement sécurisé par CinetPay
                            </small>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="h4 fw-bold text-primary mb-0">
                                {{ number_format($plan['price']) }} FCFA
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Choisissez votre mode de paiement
                    </h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm" method="POST" action="{{ route('subscription.process-payment') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan['id'] }}">
                        
                        <!-- Options de paiement CinetPay -->
                        <div class="row g-3">
                            <!-- Orange Money -->
                            <div class="col-12 col-md-6">
                                <div class="payment-option" data-method="ORANGE_MONEY_CI">
                                    <div class="card h-100 border-2 payment-card">
                                        <div class="card-body text-center">
                                            <div class="payment-icon mb-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c5/Orange_logo.svg" 
                                                     alt="Orange Money" class="img-fluid" style="height: 40px;">
                                            </div>
                                            <h6 class="fw-bold">Orange Money</h6>
                                            <small class="text-muted">Paiement via Orange Money</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MTN Money -->
                            <div class="col-12 col-md-6">
                                <div class="payment-option" data-method="MTN_MONEY_CI">
                                    <div class="card h-100 border-2 payment-card">
                                        <div class="card-body text-center">
                                            <div class="payment-icon mb-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/MTN_Logo.svg/512px-MTN_Logo.svg.png" 
                                                     alt="MTN Money" class="img-fluid" style="height: 40px;">
                                            </div>
                                            <h6 class="fw-bold">MTN Money</h6>
                                            <small class="text-muted">Paiement via MTN Money</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Moov Money -->
                            <div class="col-12 col-md-6">
                                <div class="payment-option" data-method="MOOV_MONEY_CI">
                                    <div class="card h-100 border-2 payment-card">
                                        <div class="card-body text-center">
                                            <div class="payment-icon mb-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Moov_Africa_logo.svg/512px-Moov_Africa_logo.svg.png" 
                                                     alt="Moov Money" class="img-fluid" style="height: 40px;">
                                            </div>
                                            <h6 class="fw-bold">Moov Money</h6>
                                            <small class="text-muted">Paiement via Moov Money</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Wave -->
                            <div class="col-12 col-md-6">
                                <div class="payment-option" data-method="WAVE_CI">
                                    <div class="card h-100 border-2 payment-card">
                                        <div class="card-body text-center">
                                            <div class="payment-icon mb-3">
                                                <img src="https://wave.com/assets/img/logo-blue.png" 
                                                     alt="Wave" class="img-fluid" style="height: 40px;">
                                            </div>
                                            <h6 class="fw-bold">Wave</h6>
                                            <small class="text-muted">Paiement via Wave</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Champ caché pour la méthode sélectionnée -->
                        <input type="hidden" name="payment_method" id="selectedMethod">

                        <!-- Champ numéro de téléphone -->
                        <div class="mt-4" id="phoneNumberSection" style="display: none;">
                            <label for="phone_number" class="form-label">
                                <i class="fas fa-phone me-2"></i>
                                Numéro de téléphone
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+225</span>
                                <input type="tel" class="form-control" name="phone_number" id="phone_number" 
                                       placeholder="01 02 03 04 05" pattern="[0-9]{10}" maxlength="10">
                            </div>
                            <small class="form-text text-muted">
                                Entrez votre numéro sans le +225 (ex: 0102030405)
                            </small>
                        </div>

                        <!-- Bouton de paiement -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" id="payButton" class="btn btn-success btn-lg" disabled>
                                <i class="fas fa-lock me-2"></i>
                                Payer {{ number_format($plan['price']) }} FCFA
                            </button>
                            <a href="{{ route('subscription.choose') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Retour aux plans
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations de sécurité -->
            <div class="alert alert-info mt-4">
                <i class="fas fa-shield-alt me-2"></i>
                <strong>Paiement sécurisé :</strong> Vos informations de paiement sont protégées par le cryptage SSL et traitées par CinetPay.
            </div>
        </div>
    </div>
</div>

<style>
.payment-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border-color: #dee2e6 !important;
}

.payment-card:hover {
    border-color: #0d6efd !important;
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
    transform: translateY(-2px);
}

.payment-option.selected .payment-card {
    border-color: #198754 !important;
    background-color: #f8fff9;
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2);
}

.payment-option.selected .payment-card::after {
    content: '✓';
    position: absolute;
    top: 10px;
    right: 15px;
    color: #198754;
    font-weight: bold;
    font-size: 1.2rem;
}

.payment-card {
    position: relative;
}

@media (max-width: 768px) {
    .payment-option {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const selectedMethodInput = document.getElementById('selectedMethod');
    const phoneNumberSection = document.getElementById('phoneNumberSection');
    const phoneNumberInput = document.getElementById('phone_number');
    const payButton = document.getElementById('payButton');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Retirer la sélection de tous les autres
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Ajouter la sélection à l'option cliquée
            this.classList.add('selected');
            
            // Définir la méthode sélectionnée
            const method = this.dataset.method;
            selectedMethodInput.value = method;
            
            // Afficher le champ numéro de téléphone
            phoneNumberSection.style.display = 'block';
            phoneNumberInput.required = true;
            
            // Vérifier si on peut activer le bouton
            checkFormValidity();
        });
    });

    phoneNumberInput.addEventListener('input', function() {
        // Formater le numéro (retirer les espaces et caractères non numériques)
        let value = this.value.replace(/\D/g, '');
        
        // Limiter à 10 chiffres
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        this.value = value;
        checkFormValidity();
    });

    function checkFormValidity() {
        const methodSelected = selectedMethodInput.value !== '';
        const phoneValid = phoneNumberInput.value.length === 10;
        
        payButton.disabled = !(methodSelected && phoneValid);
    }

    // Validation du formulaire
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        if (!selectedMethodInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un mode de paiement');
            return;
        }
        
        if (phoneNumberInput.value.length !== 10) {
            e.preventDefault();
            alert('Veuillez entrer un numéro de téléphone valide (10 chiffres)');
            return;
        }
        
        // Afficher un loader pendant le traitement
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement en cours...';
        payButton.disabled = true;
    });
});
</script>
@endsection