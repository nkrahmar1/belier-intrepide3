@extends('home.base')

@section('title', 'Test Auth Dropdowns')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h2>Test des boutons d'authentification</h2>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>État d'authentification actuel</h5>
                </div>
                <div class="card-body">
                    @auth
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> 
                            <strong>Utilisateur connecté :</strong> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                            <br>
                            <strong>Email :</strong> {{ Auth::user()->email }}
                            <br>
                            <strong>Rôle :</strong> {{ Auth::user()->role ?? 'user' }}
                        </div>
                        
                        <!-- Test du bouton de déconnexion direct -->
                        <form method="POST" action="{{ route('app_logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                <i class="bi bi-box-arrow-right"></i> Test déconnexion directe
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Aucun utilisateur connecté</strong>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                <i class="bi bi-person-plus"></i> S'inscrire
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Test des dropdowns Bootstrap</h5>
                </div>
                <div class="card-body">
                    <!-- Test dropdown simple -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Test Dropdown Simple
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action 1</a></li>
                            <li><a class="dropdown-item" href="#">Action 2</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Autre action</a></li>
                        </ul>
                    </div>

                    <!-- Test dropdown avec bouton de type button/submit -->
                    <div class="dropdown mt-3">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Test Dropdown avec Formulaire
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form class="px-3 py-2">
                                    <button type="button" class="dropdown-item" onclick="alert('Clic sur bouton dans dropdown')">
                                        <i class="bi bi-info-circle"></i> Test bouton
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Reproduction exact de la navbar</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <!-- Reproduction du code navbar -->
                        <div class="btn-group me-3">
                            @guest
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testAccountDropdownGuest">
                                Mon compte (Test)
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="testAccountDropdownGuest">
                                <li><a class="dropdown-item" href="{{ route('login') }}">Se Connecter</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}">S'inscrire</a></li>
                            </ul>
                            @endguest

                            @auth
                            <span class="navbar-text me-2 text-primary fw-bold">
                                {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                            </span>
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="testAccountDropdownAuth">
                                Account (Test)
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="testAccountDropdownAuth">
                                <li>
                                <form id="test-logout-form" action="{{ route('app_logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2"
                                            onclick="return confirm('Test déconnexion - Voulez-vous vraiment vous déconnecter ?')">
                                    <i class="bi bi-box-arrow-right"></i> Se déconnecter (Test)
                                    </button>
                                </form>
                                </li>
                            </ul>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Debug JavaScript</h5>
                </div>
                <div class="card-body">
                    <pre id="debugOutput" style="background: #f8f9fa; padding: 10px; font-size: 12px; height: 300px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const debugOutput = document.getElementById('debugOutput');
    
    function addDebug(message) {
        debugOutput.textContent += new Date().toLocaleTimeString() + ': ' + message + '\n';
        debugOutput.scrollTop = debugOutput.scrollHeight;
    }
    
    addDebug('Page chargée');
    
    // Vérifier Bootstrap
    if (typeof bootstrap !== 'undefined') {
        addDebug('✅ Bootstrap est disponible');
    } else {
        addDebug('❌ Bootstrap n\'est PAS disponible');
    }
    
    // Vérifier les dropdowns
    const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    addDebug('Dropdowns trouvés: ' + dropdowns.length);
    
    dropdowns.forEach((dropdown, index) => {
        addDebug('Dropdown ' + (index + 1) + ': ' + dropdown.textContent.trim());
        
        // Ajouter des event listeners
        dropdown.addEventListener('show.bs.dropdown', function() {
            addDebug('🔽 Ouverture: ' + this.textContent.trim());
        });
        
        dropdown.addEventListener('hide.bs.dropdown', function() {
            addDebug('🔼 Fermeture: ' + this.textContent.trim());
        });
        
        dropdown.addEventListener('click', function() {
            addDebug('🖱️ Clic: ' + this.textContent.trim());
        });
    });
    
    addDebug('Initialisation terminée');
});
</script>
@endsection