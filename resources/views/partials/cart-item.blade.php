<li class="cart-item">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold">{{ $item['name'] }}</div>
            <small class="text-muted">{{ $item['quantity'] ?? 0 }} × {{ $item['price'] ?? 0 }} FCFA</small>
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
