@extends('layouts.admin')

@section('title', 'Gestion des Produits')

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .price-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
    }
    .stock-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }
    .stock-high { background-color: #10b981; }
    .stock-medium { background-color: #f59e0b; }
    .stock-low { background-color: #ef4444; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                    📦
                </span>
                Gestion des Produits
            </h1>
            <p class="text-gray-600 mt-2">Gérez votre catalogue de produits</p>
        </div>
        <button onclick="openCreateProductModal()" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Nouveau Produit
        </button>
    </div>

    <!-- Statistiques des produits -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Total Produits</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $products->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-purple-600 text-xl">📦</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">En Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $products->where('stock', '>', 0)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-green-600 text-xl">✅</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-red-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Rupture Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $products->where('stock', '<=', 0)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-red-600 text-xl">❌</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-yellow-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Valeur Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($products->sum(function($p) { return $p->price * $p->stock; })) }}€</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <span class="text-yellow-600 text-xl">💰</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-64">
                <input type="text" placeholder="Rechercher un produit..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <select class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                <option>Tous les stocks</option>
                <option>En stock</option>
                <option>Stock faible</option>
                <option>Rupture</option>
            </select>
        </div>
    </div>

    <!-- Grille des produits -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden product-card">
                <!-- Image produit -->
                <div class="h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                    <span class="text-6xl">📦</span>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold text-gray-800 flex-1">{{ $product->name }}</h3>
                        <span class="price-tag text-sm">{{ number_format($product->price) }}€</span>
                    </div>

                    <!-- Stock indicator -->
                    <div class="flex items-center mb-4">
                        <span class="stock-indicator {{ $product->stock > 10 ? 'stock-high' : ($product->stock > 0 ? 'stock-medium' : 'stock-low') }}"></span>
                        <span class="text-sm text-gray-600">
                            @if($product->stock > 10)
                                Stock disponible ({{ $product->stock }})
                            @elseif($product->stock > 0)
                                Stock faible ({{ $product->stock }})
                            @else
                                Rupture de stock
                            @endif
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-600 py-2 px-3 rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>Voir
                        </button>
                        <button class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 py-2 px-3 rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Éditer
                        </button>
                        <button class="bg-red-100 hover:bg-red-200 text-red-600 py-2 px-3 rounded-lg transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Badge de statut -->
                @if($product->stock <= 0)
                    <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                        Épuisé
                    </div>
                @elseif($product->stock <= 5)
                    <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                        Stock faible
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-gray-400">
                        <i class="fas fa-box-open text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Aucun produit</h3>
                        <p class="text-gray-500 mb-6">Commencez par créer votre premier produit</p>
                        <button class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-semibold hover:scale-105 transition-transform">
                            <i class="fas fa-plus mr-2"></i>Créer un produit
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($products, 'links'))
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .pagination .page-link {
        margin: 0 5px;
        padding: 8px 12px;
        border-radius: 4px;
        background: #f1f1f1;
        color: #333;
        text-decoration: none;
    }

    .pagination .active span {
        background-color: #007bff;
        color: white;
    }
</style>
@endpush

<!-- Modal de création de produit -->
<div id="createProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
            <!-- Header du modal -->
            <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-box mr-2"></i>
                        Créer un Nouveau Produit
                    </h3>
                    <button onclick="closeCreateProductModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Formulaire -->
            <form id="createProductForm" class="p-6">
                @csrf
                <div class="space-y-4">
                    <!-- Nom du produit -->
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-purple-500"></i>
                            Nom du produit
                        </label>
                        <input type="text" id="product_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Entrez le nom du produit">
                        <div id="nameError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="product_description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-1 text-purple-500"></i>
                            Description
                        </label>
                        <textarea id="product_description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                  placeholder="Description du produit"></textarea>
                        <div id="descriptionError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="product_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-euro-sign mr-1 text-purple-500"></i>
                            Prix (€)
                        </label>
                        <input type="number" id="product_price" name="price" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="0.00">
                        <div id="priceError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="product_stock" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-boxes mr-1 text-purple-500"></i>
                            Quantité en stock
                        </label>
                        <input type="number" id="product_stock" name="stock" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="0">
                        <div id="stockError" class="text-red-500 text-xs mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="closeCreateProductModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                        <i class="fas fa-save mr-2"></i>
                        Créer le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonctions pour gérer le modal de produit
function openCreateProductModal() {
    const modal = document.getElementById('createProductModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeCreateProductModal() {
    const modal = document.getElementById('createProductModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    document.getElementById('createProductForm').reset();
    clearProductErrors();
}

// Fonction pour effacer les erreurs
function clearProductErrors() {
    const errorElements = ['nameError', 'descriptionError', 'priceError', 'stockError'];
    errorElements.forEach(id => {
        const element = document.getElementById(id);
        element.classList.add('hidden');
        element.textContent = '';
    });
}

// Fonction pour afficher les erreurs
function showProductError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + 'Error');
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');
}

// Gestion de la soumission du formulaire de produit
document.getElementById('createProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    clearProductErrors();
    
    // Préparer les données
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Envoyer la requête
    fetch('{{ route("admin.products.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateProductModal();
            showProductSuccessMessage('Produit créé avec succès !');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    if (data.errors[field].length > 0) {
                        showProductError(field, data.errors[field][0]);
                    }
                });
            } else {
                showProductError('name', data.message || 'Une erreur est survenue');
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showProductError('name', 'Erreur de connexion. Veuillez réessayer.');
    });
});

// Fonction pour afficher un message de succès
function showProductSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';
    successDiv.innerHTML = `
        <i class="fas fa-check-circle mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('createProductModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateProductModal();
    }
});

// Ouvrir automatiquement le modal si action=create dans l'URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        openCreateProductModal();
        // Nettoyer l'URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
@endpush
