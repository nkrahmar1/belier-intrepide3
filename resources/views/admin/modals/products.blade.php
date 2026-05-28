{{-- Vue partielle pour le modal Produits --}}
<div class="space-y-4 p-4">
    <div class="text-center py-12">
        <span class="text-6xl mb-4 block">📦</span>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            Gestion des Produits
        </h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
            Cette section affichera la liste de tous vos produits.
            Fonctionnalité à venir prochainement.
        </p>
        
        @if($products->count() > 0)
            <div class="mt-8 text-left max-w-4xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $products->count() }} produit(s) trouvé(s)
                    </p>
                </div>
            </div>
        @else
            <div class="mt-8">
                <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold 
                               transform hover:scale-105 transition-all duration-200">
                    ➕ Ajouter un produit
                </button>
            </div>
        @endif
    </div>
</div>
