@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@push('styles')
<style>
    .user-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
    }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fef2f2; color: #991b1b; }
    .status-pending { background: #fef3c7; color: #92400e; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                    👥
                </span>
                Gestion des Utilisateurs
            </h1>
            <p class="text-gray-600 mt-2">Gérez tous les utilisateurs de votre plateforme</p>
        </div>
        <button onclick="openCreateUserModal()" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Nouvel Utilisateur
        </button>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Utilisateurs Actifs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $users->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-green-600 text-xl">✅</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-yellow-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">En Attente</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $users->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-yellow-600 text-xl">⏳</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-red-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Inactifs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $users->where('status', 'inactive')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-red-600 text-xl">❌</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $users->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-blue-600 text-xl">👥</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-64">
                <input type="text" placeholder="Rechercher un utilisateur..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                <option>Tous les statuts</option>
                <option>Actif</option>
                <option>Inactif</option>
                <option>En attente</option>
            </select>
            <button class="bg-gray-100 hover:bg-gray-200 px-4 py-3 rounded-xl transition-colors">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Liste des Utilisateurs</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Inscription
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($user->is_admin)
                                            <div class="text-xs text-purple-600 font-semibold">Administrateur</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div class="text-xs text-green-600">✓ Vérifié</div>
                                @else
                                    <div class="text-xs text-red-600">✗ Non vérifié</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-{{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($user->created_at)
                                    {{ $user->created_at->format('d/m/Y') }}
                                    <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                                @else
                                    <span class="text-gray-400">Date non disponible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 px-3 py-1 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$user->is_admin)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Supprimer cet utilisateur ?')"
                                                class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun utilisateur trouvé</p>
                                    <p class="text-sm">Commencez par créer votre premier utilisateur</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($users, 'links'))
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Modal de création d'utilisateur -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <!-- Header du modal -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Créer un Nouvel Utilisateur
                    </h3>
                    <button onclick="closeCreateUserModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Formulaire -->
            <form id="createUserForm" class="p-6">
                @csrf
                <div class="space-y-4">
                    <!-- Nom complet -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-blue-500"></i>
                            Nom complet
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Entrez le nom complet">
                        <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-blue-500"></i>
                            Adresse email
                        </label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="email@exemple.com">
                        <div id="emailError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-blue-500"></i>
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                   placeholder="Minimum 8 caractères">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div id="passwordError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Confirmation du mot de passe -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-blue-500"></i>
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10"
                                   placeholder="Répétez le mot de passe">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                        <div id="passwordConfirmationError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tag mr-1 text-blue-500"></i>
                            Rôle
                        </label>
                        <select id="role" name="role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                            <option value="editor">Éditeur</option>
                        </select>
                        <div id="roleError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Statut -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-1 text-blue-500"></i>
                            Statut
                        </label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                            <option value="pending">En attente</option>
                        </select>
                        <div id="statusError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="closeCreateUserModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                        <i class="fas fa-save mr-2"></i>
                        Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fonctions pour gérer le modal
function openCreateUserModal() {
    const modal = document.getElementById('createUserModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCreateUserModal() {
    const modal = document.getElementById('createUserModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('createUserForm').reset();
    clearErrors();
}

// Fonction pour basculer la visibilité du mot de passe
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');

    if (input.type === 'password') {
        input.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        eye.className = 'fas fa-eye';
    }
}

// Fonction pour effacer les erreurs
function clearErrors() {
    const errorElements = ['nameError', 'emailError', 'passwordError', 'passwordConfirmationError', 'roleError', 'statusError'];
    errorElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.add('hidden');
            element.textContent = '';
        }
    });
}

// Fonction pour afficher les erreurs
function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + 'Error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    } else {
        console.error('Élément d\'erreur non trouvé pour:', fieldId + 'Error');
        // Afficher l'erreur dans la console à défaut
        alert(`Erreur ${fieldId}: ${message}`);
    }
}

// Validation en temps réel
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;

    if (confirmation && password !== confirmation) {
        showError('passwordConfirmation', 'Les mots de passe ne correspondent pas');
    } else {
        document.getElementById('passwordConfirmationError').classList.add('hidden');
    }
});

// Gestion de la soumission du formulaire
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    console.log('Formulaire soumis');

    // Validation côté client
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;

    clearErrors();

    let hasErrors = false;

    // Vérification de la longueur du mot de passe
    if (password.length < 8) {
        showError('password', 'Le mot de passe doit contenir au moins 8 caractères');
        hasErrors = true;
    }

    // Vérification de la correspondance des mots de passe
    if (password !== confirmation) {
        showError('passwordConfirmation', 'Les mots de passe ne correspondent pas');
        hasErrors = true;
    }

    if (hasErrors) {
        console.log('Erreurs de validation détectées');
        return;
    }

    // Préparer les données
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    console.log('Données du formulaire:', data);
    console.log('URL de la requête:', '{{ route("admin.users.store") }}');

    // Envoyer la requête
    fetch('{{ route("admin.users.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Réponse reçue:', response.status);
        if (!response.ok) {
            console.error('Réponse non-ok:', response.status, response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données de réponse:', data);
        if (data.success) {
            closeCreateUserModal();
            // Afficher un message de succès
            showSuccessMessage('Utilisateur créé avec succès !');
            // Recharger la page après 1.5 secondes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            console.log('Erreur dans la réponse:', data);
            // Afficher les erreurs de validation
            if (data.errors) {
                console.log('Erreurs de validation:', data.errors);
                Object.keys(data.errors).forEach(field => {
                    if (data.errors[field].length > 0) {
                        console.log(`Erreur pour ${field}:`, data.errors[field][0]);
                        showError(field, data.errors[field][0]);
                    }
                });
            } else {
                showError('email', data.message || 'Une erreur est survenue');
            }
        }
    })
    .catch(error => {
        console.error('Erreur de requête:', error);
        showError('email', 'Erreur de connexion. Veuillez réessayer.');
    });
});

// Fonction pour afficher un message de succès
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';
    successDiv.innerHTML = `
        <i class="fas fa-check-circle mr-2"></i>
        ${message}
    `;

    document.body.appendChild(successDiv);

    // Faire disparaître le message après 3 secondes
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('createUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateUserModal();
    }
});

// Ouvrir automatiquement le modal si action=create dans l'URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        openCreateUserModal();
        // Nettoyer l'URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
@endsection
