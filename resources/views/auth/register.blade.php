@extends('home.base')

@section('title', 'register')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-11 col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
            <h1 class="text-center text-muted mb-3 mt-3 mt-md-5" style="font-size: clamp(1.5rem, 4vw, 2rem);">S'inscrire</h1>
            <p class="text-center text-muted mb-4 mb-md-5" style="font-size: clamp(0.9rem, 2.5vw, 1rem);">Créer un compte si vous n'en avez pas un</p>

            {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Message d'erreur général --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreur :</strong> Veuillez corriger les champs ci-dessous.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="row g-3" id="registerForm">
                @csrf

                <div class="col-12 col-md-6">
                    <label for="firstname" class="form-label">
                        <i class="fas fa-user me-1"></i>Nom de famille
                    </label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                           id="firstname" name="firstname" value="{{ old('firstname') }}"
                           required autocomplete="given-name" style="padding: 12px;">
                    @error('firstname')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="lastname" class="form-label">
                        <i class="fas fa-user me-1"></i>Prénom
                    </label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                           id="lastname" name="lastname" value="{{ old('lastname') }}"
                           required autocomplete="family-name">
                    @error('lastname')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-1"></i>Email
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}"
                           required autocomplete="email">
                    <div id="emailFeedback" class="form-text"></div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i>Mot de pass
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    <div class="form-text">
                        <small>Minimum 8 caractères</small>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-times-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-1"></i>Confirmer votre mot de pass
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation"
                               required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                            <i class="fas fa-eye" id="passwordConfirmIcon"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" class="form-text"></div>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input @error('agreeterms') is-invalid @enderror"
                               type="checkbox" value="1" id="agreeterms" name="agreeterms"
                               {{ old('agreeterms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="agreeterms">
                            J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions d'utilisation</a>
                        </label>
                        @error('agreeterms')
                            <div class="invalid-feedback">
                                <i class="fas fa-times-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit" id="submitBtn">
                        <i class="fas fa-user-plus me-2"></i>Creer un compte
                    </button>
                </div>

                <p class="text-center text-muted mt-5">
                    Avez-vous déjà un compte?
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- Modal Terms -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conditions d'utilisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>En créant un compte sur Le Bélier-Intrépide, vous acceptez :</p>
                <ul>
                    <li>De fournir des informations exactes et à jour</li>
                    <li>De respecter les autres utilisateurs et la communauté</li>
                    <li>De ne pas publier de contenu offensant ou illégal</li>
                    <li>Que vos données personnelles soient traitées selon notre politique de confidentialité</li>
                </ul>
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (e) {
        let hasError = false;

        // Reset errors
        document.querySelectorAll('small.text-danger').forEach(el => el.textContent = '');

        const firstname = document.getElementById('firstname').value.trim();
        if (firstname.length < 2) {
            document.getElementById('firstname').classList.add('is-invalid');
            document.getElementById('firstname').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">First name must be at least 2 characters.</small>');
            hasError = true;
        }

        const lastname = document.getElementById('lastname').value.trim();
        if (lastname.length < 2) {
            document.getElementById('lastname').classList.add('is-invalid');
            document.getElementById('lastname').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">Last name must be at least 2 characters.</small>');
            hasError = true;
        }

        const email = document.getElementById('email').value.trim();
        const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        if (!emailPattern.test(email)) {
            document.getElementById('email').classList.add('is-invalid');
            document.getElementById('email').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">Please enter a valid email address.</small>');
            hasError = true;
        }

        const password = document.getElementById('password').value;
        if (password.length < 6) {
            document.getElementById('password').classList.add('is-invalid');
            document.getElementById('password').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">Password must be at least 6 characters.</small>');
            hasError = true;
        }

        const confirm = document.getElementById('password_confirmation').value;
        if (password !== confirm) {
            document.getElementById('password_confirmation').classList.add('is-invalid');
            document.getElementById('password_confirmation').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">Passwords do not match.</small>');
            hasError = true;
        }

        const terms = document.getElementById('agreeterms');
        if (!terms.checked) {
            document.getElementById('agreeterms').classList.add('is-invalid');
            document.getElementById('agreeterms').insertAdjacentHTML('afterend', '<small class="text-danger fw-bold">You must agree to the terms.</small>');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault(); // Prevent form submission
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password toggle buttons
    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', 'passwordIcon');
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', 'passwordConfirmIcon');
    });

    // Password confirmation check
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');

    function checkPasswordMatch() {
        if (passwordConfirm.value) {
            if (password.value === passwordConfirm.value) {
                passwordMatch.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Les mots de passe correspondent</span>';
                passwordConfirm.classList.remove('is-invalid');
                passwordConfirm.classList.add('is-valid');
            } else {
                passwordMatch.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Les mots de passe ne correspondent pas</span>';
                passwordConfirm.classList.remove('is-valid');
                passwordConfirm.classList.add('is-invalid');
            }
        } else {
            passwordMatch.innerHTML = '';
            passwordConfirm.classList.remove('is-valid', 'is-invalid');
        }
    }

    password.addEventListener('input', checkPasswordMatch);
    passwordConfirm.addEventListener('input', checkPasswordMatch);

    // Email validation (optionnel - vérification côté client)
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');

    emailInput.addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            // Ici vous pourriez ajouter une vérification AJAX pour voir si l'email existe déjà
            // fetch('/check-email', {...})
        }
    });

    // Form submission enhancement
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
        submitBtn.disabled = true;
    });
});

</script>
@endsection
