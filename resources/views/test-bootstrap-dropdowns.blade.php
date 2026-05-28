@extends('home.base')

@section('title', 'Test Bootstrap Dropdowns')

@section('content')
<div class="container mt-5">
    <h1>Test Bootstrap Dropdowns</h1>
    
    <div class="row">
        <div class="col-12">
            <h2>État de Bootstrap</h2>
            <p id="bootstrap-status" class="alert alert-info">Vérification en cours...</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Test Dropdown Account</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testAccountDropdown">
                    Mon compte (Test)
                </button>
                <ul class="dropdown-menu" aria-labelledby="testAccountDropdown">
                    <li><a class="dropdown-item" href="#">Connexion</a></li>
                    <li><a class="dropdown-item" href="#">Inscription</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <h3>Test Dropdown Panier</h3>
            <div class="dropdown">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testCartDropdown">
                    <i class="bi bi-cart3"></i> Panier (Test)
                    <span class="badge bg-danger">3</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="testCartDropdown">
                    <li><a class="dropdown-item" href="#">Article 1</a></li>
                    <li><a class="dropdown-item" href="#">Article 2</a></li>
                    <li><a class="dropdown-item" href="#">Article 3</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Voir le panier</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h3>Test Modal</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testModal">
                Ouvrir Modal Test
            </button>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h3>Console d'erreurs</h3>
            <div id="error-console" class="alert alert-warning">
                <strong>Erreurs JavaScript:</strong>
                <ul id="error-list"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Test -->
<div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testModalLabel">Modal Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Cette modal teste si Bootstrap JavaScript fonctionne correctement.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test si Bootstrap est chargé
    const statusElement = document.getElementById('bootstrap-status');
    
    if (typeof bootstrap !== 'undefined') {
        statusElement.textContent = '✅ Bootstrap JavaScript est chargé';
        statusElement.className = 'alert alert-success';
        
        // Test des dropdowns
        try {
            const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            dropdowns.forEach(function(dropdown) {
                new bootstrap.Dropdown(dropdown);
            });
            console.log('✅ Dropdowns initialisés avec succès');
        } catch (error) {
            console.error('❌ Erreur d\'initialisation des dropdowns:', error);
        }
    } else {
        statusElement.textContent = '❌ Bootstrap JavaScript n\'est PAS chargé';
        statusElement.className = 'alert alert-danger';
    }
    
    // Test si jQuery est chargé
    if (typeof $ !== 'undefined') {
        console.log('✅ jQuery est chargé');
    } else {
        console.log('❌ jQuery n\'est pas chargé');
    }
    
    // Capturer les erreurs JavaScript
    window.addEventListener('error', function(e) {
        const errorList = document.getElementById('error-list');
        const li = document.createElement('li');
        li.textContent = e.message + ' (ligne ' + e.lineno + ')';
        errorList.appendChild(li);
    });
    
    // Test manuel des dropdowns
    document.getElementById('testAccountDropdown').addEventListener('click', function() {
        console.log('Click sur dropdown account détecté');
    });
    
    document.getElementById('testCartDropdown').addEventListener('click', function() {
        console.log('Click sur dropdown panier détecté');
    });
});
</script>
@endsection