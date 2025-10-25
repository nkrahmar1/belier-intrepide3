@props(['products' => []])
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Produits les Plus Vendus</h3>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold text-dark">{{ $product->name }}</h5>
                            <p class="card-text text-muted mb-2">{{ $product->sales_count ?? 0 }} ventes</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold text-primary">€{{ number_format($product->revenue ?? 0, 2) }}</span>
                            <span>
                                @if(($product->trend ?? 'up') === 'up')
                                    <i class="fas fa-arrow-up text-success"></i>
                                @else
                                    <i class="fas fa-arrow-down text-danger"></i>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">Aucun produit trouvé</div>
        @endforelse
    </div>
</div>
