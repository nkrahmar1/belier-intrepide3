























































<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .navbar {
        background-color: #04672a !important; /* Bleu foncé élégant */
        transition: background-color 0.3s ease-in-out;
    }

    .navbar-brand {
        color: #fff !important;
        font-weight: bold;
        font-size: 1.3rem;
        transition: color 0.3s ease;
    }

    .navbar-brand:hover {
        color: #ffcc00 !important;
    }

    .nav-link {
        color: #e6e6e6 !important;
        position: relative;
        transition: color 0.3s ease;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 0;
        background-color: #ffcc00;
        transition: width 0.3s ease-in-out;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .nav-link:hover {
        color: #ffcc00 !important;
    }

    .btn-light {
        background-color: #f8f9fa;
        border: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
        transform: scale(1.05);
    }

    .dropdown-menu {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Styles pour le panier */
    .cart-icon {
        position: relative;
        color: #fff !important;
        font-size: 1.5rem;
        text-decoration: none !important;
        transition: color 0.3s ease, transform 0.2s ease;
        margin-right: 15px;
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
    }

    .cart-icon:hover {
        color: #ffcc00 !important;
        transform: scale(1.1);
    }

    .cart-icon:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background-color: #dc3545;
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 50%;
        min-width: 20px;
        text-align: center;
        animation: pulse 2s infinite;
    }
/* stylle de mon icon */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .cart-dropdown {
        min-width: 300px;
        max-height: 400px;
        overflow-y: auto;
    }

    .cart-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-empty {
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }

    .cart-total {
        background-color: #f8f9fa;
        padding: 15px;
        font-weight: bold;
        border-top: 2px solid #04672a;
    }

    @keyframes pulse {
  0%, 100% {
      transform: scale(1);
  }
  50% {
      transform: scale(1.1);
  }
}

.cart-badge.pulse {
  animation: pulse 0.5s ease;
}
.cart-badge {
    display: inline-block;
    width: 20px;
    height: 20px;
    font-size: 0.75rem;
    font-weight: bold;
    line-height: 20px;
    border-radius: 50%;
    background-color: #dc3545;
    color: white;
    text-align: center;
    vertical-align: top;
    margin-left: 5px;

    visibility: hidden;  /* Invisible mais garde sa place */
    opacity: 0;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

    .cart-badge.active {
    visibility: visible;
    opacity: 1;
}

    /* Style pour le bouton toggler sur mobile */
    .navbar-toggler {
        border: 2px solid #fff !important;
        padding: 8px 12px;
    }

    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
    }

    /* S'assurer que les liens sont visibles sur desktop */
    @media (min-width: 992px) {
        .navbar-collapse {
            display: flex !important;
        }
    }

    /* ===== OVERRIDE: Forcer visibilité des liens (pc + mobile) ===== */
    .navbar {
        position: sticky !important;
        top: 0;
        z-index: 999999 !important; /* au-dessus de tout */
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        background-color: #04672a !important; /* assure fond pour visibilité */
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    /* Forcer les liens à être visibles, même si d'autres styles les masquent */
    .navbar .nav-link,
    .navbar .navbar-brand {
        color: #ffffff !important;
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
        pointer-events: auto !important;
        text-decoration: none !important;
    }

    /* Quand le collapse est ouvert, forcer l'affichage en flex (mobile) */
    .navbar-collapse.collapse.show {
        display: flex !important;
        flex-basis: 100% !important;
        flex-wrap: wrap !important;
    }

    /* Si un autre style cache accidentellement .navbar-collapse, on le remet visible */
    .navbar-collapse {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* S'assurer que le collapse lui-même a un fond sur mobile (évite effet 'transparent over content') */
    .navbar-collapse.collapse {
        background-color: rgba(4,103,42,0.95) !important;
    }



</style>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @php
          $cat = Request::route('categorie');
          $activeRoute = Request::route()->getName();
        @endphp

        <li class="nav-item">
          <a class="nav-link {{ $activeRoute == 'app_home' ? 'active' : '' }}" href="{{ route('app_home') }}">Accueil</a>
        </li>

        @foreach(['Afrique', 'Sport', 'Culture et média', 'Societé', 'Economie', 'Politique', 'PDCI-RDA', 'Dossiers'] as $categorie)
        <li class="nav-item">
          <a class="nav-link {{ $activeRoute == 'app_category' && $cat == $categorie ? 'active' : '' }}"
             href="{{ route('app_category', ['categorie' => $categorie]) }}">{{ $categorie }}</a>
        </li>
        @endforeach

        <li class="nav-item">
          <a class="nav-link {{ $activeRoute == 'app_about' ? 'active' : '' }}" href="{{ route('app_about') }}">A propos</a>
        </li>
      </ul>
    </div>

        <div class="d-flex align-items-center">

        <!-- Bouton Mon compte -->
        <div class="btn-group me-3">
            @guest
            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="accountDropdownGuest">
                Mon compte
            </button>
            <ul class="dropdown-menu" aria-labelledby="accountDropdownGuest">
                <li><a class="dropdown-item" href="{{ route('login') }}">Se Connecter</a></li>
                <li><a class="dropdown-item" href="{{ route('register') }}">S'inscrire</a></li>
            </ul>
            @endguest

            @auth
            <span class="navbar-text me-2 text-primary fw-bold">
                {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
            </span>
            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="accountDropdownAuth">
                Mon compte
            </button>
            <ul class="dropdown-menu" aria-labelledby="accountDropdownAuth">
                <li>
                <form id="logout-form" action="{{ route('app_logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2"
                            onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">
                    <i class="bi bi-box-arrow-right"></i> Se déconnecter
                    </button>
                </form>
                </li>
            </ul>
            @endauth
        </div>

        <!-- Icône Panier avec dropdown (juste après Mon compte) -->
        <div class="dropdown">
            <button type="button" class="cart-icon dropdown-toggle btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false" id="cartDropdown">
            <i class="bi bi-cart3"></i>
            @php
                $cartCount = 0;
                $downloadCount = 0;
                if (auth()->check()) {
                    $cart = session('cart', []);
                    foreach ($cart as $item) {
                        if(($item['type'] ?? '') === 'download') {
                            $downloadCount += $item['quantity'] ?? 0;
                        } else {
                            $cartCount += $item['quantity'] ?? 0;
                        }
                    }
                }
                $totalItems = $cartCount + $downloadCount;
            @endphp
            <span class="cart-badge {{ $totalItems > 0 ? 'active' : '' }}" title="Achats: {{ $cartCount }} | Téléchargements: {{ $downloadCount }}">
                {{ $totalItems }}
            </span>
            </button>

            <ul class="dropdown-menu cart-dropdown" aria-labelledby="cartDropdown" id="cartDropdownMenu">
            @if($totalItems > 0)
                @auth
                @php
                    $cart = session('cart', []);
                    $total = 0;
                @endphp

                @foreach($cart as $key => $item)
                <li class="cart-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">
                                @if(($item['type'] ?? '') === 'download')
                                    <i class="bi bi-download text-success me-1"></i>
                                @else
                                    <i class="bi bi-bag text-primary me-1"></i>
                                @endif
                                {{ $item['name'] ?? 'Produit' }}
                            </div>
                            <small class="text-muted">
                                @if(($item['type'] ?? '') === 'download')
                                    Téléchargé {{ $item['quantity'] ?? 1 }} fois
                                    @if(isset($item['downloaded_at']))
                                        <br><span class="text-success">{{ date('d/m/Y H:i', strtotime($item['downloaded_at'])) }}</span>
                                    @endif
                                @else
                                    {{ $item['quantity'] ?? 0 }} × {{ $item['price'] ?? 0 }} FCFA
                                @endif
                            </small>
                        </div>
                        <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </li>
                @php
                    if(($item['type'] ?? '') !== 'download') {
                        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                    }
                @endphp
                @endforeach

                <li class="cart-total">
                    @php
                        $downloadCount = 0;
                        $purchaseTotal = 0;
                        foreach($cart as $item) {
                            if(($item['type'] ?? '') === 'download') {
                                $downloadCount += $item['quantity'] ?? 1;
                            } else {
                                $purchaseTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                            }
                        }
                    @endphp

                    @if($downloadCount > 0)
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="bi bi-download text-success"></i> Téléchargements:</span>
                            <span class="badge bg-success">{{ $downloadCount }}</span>
                        </div>
                    @endif

                    @if($purchaseTotal > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-cart text-primary"></i> Total achats:</span>
                            <span>{{ $purchaseTotal }} FCFA</span>
                        </div>
                    @endif

                    <div class="mt-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-eye"></i> Voir le panier complet
                    </a>
                    </div>
                </li>
                @endauth
            @else
                <li class="cart-empty">
                <i class="bi bi-cart-x display-6 text-muted"></i>
                <p class="mb-0">Votre panier est vide</p>
                </li>
            @endif
            </ul>
        </div>

        <!-- Bouton Admin (si utilisateur admin) -->
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light ms-2" style="border-radius: 20px;">
                    <span>🛡️</span> Admin
                </a>
            @endif
        @endauth

    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation de la navbar...');

    // Fonction pour initialiser les dropdowns avec retry
    function initializeDropdowns() {
        // Attendre que Bootstrap soit disponible
        if (typeof bootstrap === 'undefined') {
            console.error('❌ Bootstrap n\'est pas chargé !');
            setTimeout(initializeDropdowns, 500); // Retry dans 500ms
            return;
        }

        // Initialiser tous les dropdowns Bootstrap
        const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        console.log('🔍 Dropdowns trouvés:', dropdownElements.length);

        // Lister tous les dropdowns trouvés pour debug
        dropdownElements.forEach((element, index) => {
            console.log(`📋 Dropdown ${index + 1}:`, {
                id: element.id || 'sans-id',
                text: element.textContent.trim(),
                classes: element.className,
                tag: element.tagName
            });
        });

        dropdownElements.forEach((element, index) => {
            try {
                // Détruire l'instance existante si elle existe
                const existingInstance = bootstrap.Dropdown.getInstance(element);
                if (existingInstance) {
                    existingInstance.dispose();
                    console.log(`🧹 Instance existante supprimée pour dropdown ${index + 1}`);
                }

                // Créer une nouvelle instance
                const dropdown = new bootstrap.Dropdown(element);
                console.log(`✅ Dropdown ${index + 1} initialisé avec succès`);

                // Ajouter des événements pour le debug
                element.addEventListener('show.bs.dropdown', function() {
                    console.log('🔽 Dropdown ouvert:', this.textContent.trim(), this.id);
                });

                element.addEventListener('hide.bs.dropdown', function() {
                    console.log('🔼 Dropdown fermé:', this.textContent.trim(), this.id);
                });

                // Test immédiat du dropdown
                element.addEventListener('click', function(e) {
                    console.log('🖱️ Clic détecté sur dropdown:', this.textContent.trim());
                });

            } catch (error) {
                console.error(`❌ Erreur initialisation dropdown ${index + 1}:`, error, element);
            }
        });

        console.log('✅ Initialisation des dropdowns terminée');
    }

    // Initialiser immédiatement
    initializeDropdowns();

    // Réinitialiser après 2 secondes pour s'assurer que tout fonctionne
    setTimeout(initializeDropdowns, 2000);

    // Fonction pour mettre à jour le badge et contenu du panier
    function updateCart() {
        @if(Route::has('cart.count'))
        fetch("{{ route('cart.count') }}")
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const badge = document.querySelector('.cart-badge');
                const cartDropdown = document.querySelector('#cartDropdownMenu');

                // MAJ du badge
                if (badge) {
                    if (data.count > 0) {
                        badge.style.display = 'inline-block';
                        badge.textContent = data.count;
                        badge.classList.add('active');

                        // Animation pulse
                        badge.classList.remove('pulse');
                        void badge.offsetWidth; // trigger reflow
                        badge.classList.add('pulse');
                    } else {
                        badge.classList.remove('active');
                    }
                }

                // MAJ du contenu du dropdown
                if (cartDropdown && data.html) {
                    cartDropdown.innerHTML = data.html;
                }
            })
            .catch(error => {
                console.warn('⚠️ Cart update failed:', error);
                const cartDropdown = document.querySelector('#cartDropdownMenu');
                if (cartDropdown) {
                    cartDropdown.innerHTML = `<li class="cart-empty">
                        <i class="bi bi-cart-x display-6 text-muted"></i>
                        <p class="mb-0">Impossible de charger le panier.</p>
                    </li>`;
                }
            });
        @else
        console.warn('⚠️ Route cart.count not defined');
        @endif
    }

    // Update initial seulement si la route existe
    @if(Route::has('cart.count'))
    updateCart();
    setInterval(updateCart, 30000); // Toutes les 30s
    @endif

    // Gestion améliorée des formulaires de déconnexion
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            console.log('🚪 Déconnexion en cours...');
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Déconnexion...';
                button.disabled = true;
            }
        });
    }

    // Test des dropdowns au clic
    document.addEventListener('click', function(e) {
        const dropdownToggle = e.target.closest('[data-bs-toggle="dropdown"]');
        if (dropdownToggle) {
            console.log('🖱️ Clic sur dropdown:', dropdownToggle);
        }
    });

    console.log('✅ Navbar initialisée avec succès !');
});

function updateCartBadge(cartCount) {
    const badge = document.querySelector('.cart-badge');
    if (!badge) return;

    if (cartCount > 0) {
        badge.textContent = cartCount;
        badge.classList.add('active');
    } else {
        badge.classList.remove('active');
    }
}

// Fix runtime: forcer la visibilité des liens de la navbar si une classe utilitaire les masque
document.addEventListener('DOMContentLoaded', function() {
    try {
        const nav = document.querySelector('nav.navbar');
        if (nav) {
            // retirer classes utilitaires communes qui masquent la visibilité
            ['invisible','d-none','opacity-0','pe-none'].forEach(cls => {
                if (nav.classList.contains(cls)) nav.classList.remove(cls);
            });

            // forcer styles inline si nécessaire
            const links = nav.querySelectorAll('.nav-link, .navbar-brand');
            links.forEach(a => {
                a.style.visibility = 'visible';
                a.style.opacity = '1';
                a.style.display = 'inline-block';
                a.style.pointerEvents = 'auto';
                a.style.color = '#ffffff';
            });
        }
    } catch (e) {
        console.warn('Navbar visibility fix failed', e);
    }
});
</script>

