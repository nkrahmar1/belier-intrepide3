@extends('layouts.admin')

@section('title', 'Liste des produits')

@section('content')
<div class="products-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="breadcrumb-section">
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour au dashboard
            </a>
        </div>

        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-box"></i>
                Liste des produits
            </h1>
            <div class="header-actions">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nouveau produit
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Products Table Card -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">
                <h3>Tous les produits</h3>
                <span class="product-count">{{ $products->total() }} produit(s)</span>
            </div>
        </div>

        <div class="table-container">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="table-row">
                        <td class="product-info">
                            <div class="product-details">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-sku">SKU: {{ $product->sku ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="price-cell">
                            <span class="price">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                        </td>
                        <td class="stock-cell">
                            <span class="stock-badge {{ $product->stock > 10 ? 'stock-good' : ($product->stock > 0 ? 'stock-low' : 'stock-out') }}">
                                {{ $product->stock }} unités
                            </span>
                        </td>
                        <td class="status-cell">
                            <span class="status-badge status-{{ strtolower($product->status ?? 'active') }}">
                                {{ ucfirst($product->status ?? 'Actif') }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn-action btn-view" title="Voir le produit">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-action btn-edit" title="Modifier le produit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="delete-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Supprimer le produit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-box-open"></i>
                                <h3>Aucun produit trouvé</h3>
                                <p>Commencez par ajouter votre premier produit.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Ajouter un produit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.products-container {
    padding: 24px;
    background-color: #f8fafc;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 32px;
}

.breadcrumb-section {
    margin-bottom: 16px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    text-decoration: none;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background-color: white;
    transition: all 0.2s ease;
}

.btn-back:hover {
    color: #4f46e5;
    border-color: #4f46e5;
    background-color: #f8fafc;
    transform: translateX(-2px);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #4f46e5;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #4f46e5;
    color: white;
}

.btn-primary:hover {
    background-color: #4338ca;
    transform: translateY(-1px);
}

.alert {
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background-color: #d1fae5;
    border: 1px solid #a7f3d0;
    color: #065f46;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    background-color: #f9fafb;
}

.table-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.product-count {
    font-size: 14px;
    color: #6b7280;
    background-color: #e5e7eb;
    padding: 4px 12px;
    border-radius: 20px;
}

.table-container {
    overflow-x: auto;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th {
    background-color: #f9fafb;
    padding: 16px 24px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    border-bottom: 2px solid #e5e7eb;
}

.products-table td {
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
}

.table-row:hover {
    background-color: #f9fafb;
}

.product-info {
    max-width: 300px;
}

.product-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.product-sku {
    font-size: 13px;
    color: #6b7280;
}

.price-cell .price {
    font-weight: 600;
    font-size: 16px;
    color: #059669;
}

.stock-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.stock-good {
    background-color: #d1fae5;
    color: #065f46;
}

.stock-low {
    background-color: #fed7aa;
    color: #9a3412;
}

.stock-out {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background-color: #f3f4f6;
    color: #374151;
}

.actions-column {
    width: 120px;
}

.action-buttons {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-view {
    background-color: #e0f2fe;
    color: #0277bd;
}

.btn-view:hover {
    background-color: #b3e5fc;
}

.btn-edit {
    background-color: #fff3e0;
    color: #ef6c00;
}

.btn-edit:hover {
    background-color: #ffe0b2;
}

.btn-delete {
    background-color: #ffebee;
    color: #c62828;
}

.btn-delete:hover {
    background-color: #ffcdd2;
}

.delete-form {
    display: inline-block;
    margin: 0;
}

.empty-state {
    padding: 64px 24px;
    text-align: center;
}

.empty-content i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 16px;
}

.empty-content h3 {
    font-size: 20px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.empty-content p {
    color: #6b7280;
    margin-bottom: 24px;
}

.pagination-wrapper {
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    background-color: #f9fafb;
}

/* Responsive */
@media (max-width: 768px) {
    .products-container {
        padding: 16px;
    }

    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .page-title {
        font-size: 24px;
    }

    .products-table th,
    .products-table td {
        padding: 12px 16px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>

<!-- Font Awesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
