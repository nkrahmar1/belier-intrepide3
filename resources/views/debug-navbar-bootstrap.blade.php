<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            Belier Intrépide
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Liens des catégories -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/afrique') }}">Afrique</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/culture-et-media') }}">Culture et Médias</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/economie') }}">Économie</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/pdci-rda') }}">PDCI-RDA</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/politique') }}">Politique</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/categorie/sport') }}">Sport</a></li>
            </ul>

            <!-- Section utilisateur -->
            <div class="btn-group me-3">
                @guest
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false" id="accountDropdownGuest">
                    Mon compte
                </button>
                <ul class="dropdown-menu" aria-labelledby="accountDropdownGuest">
                    <li><a class="dropdown-item" href="{{ route('login') }}">Se Connecter</a></li>
                    <li><a class="dropdown-item" href="{{ route('register') }}">S'inscrire</a></li>
                </ul>
                @endguest

                @auth
                <!-- Initiales -->
                <span class="navbar-text me-2 text-primary fw-bold">
                    {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                </span>

                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false" id="accountDropdownAuth">
                    Compte
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdownAuth">
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Mon Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
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

            <!-- Panier -->
            <div class="dropdown">
                <button type="button" class="cart-icon dropdown-toggle btn btn-link p-0" data-bs-toggle="dropdown"
                    aria-expanded="false" id="cartDropdown">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge active">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="cartDropdown">
                    <li><span class="dropdown-item-text">Votre panier</span></li>
                    <li><a class="dropdown-item" href="#">Article test</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
.nav-link {
    font-weight: 500;
    color: #333 !important;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #0d6efd !important;
}

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
</style>
