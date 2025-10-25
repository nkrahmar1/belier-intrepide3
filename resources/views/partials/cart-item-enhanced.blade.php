@if(isset($item['type']) && $item['type'] === 'download')
    {{-- Article téléchargé --}}
    <li class="cart-item download-item">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold text-success">
                    <i class="fas fa-download me-1"></i>
                    {{ $item['name'] }}
                </div>
                <small class="text-muted">
                    📥 Téléchargé {{ $item['quantity'] ?? 1 }} fois
                    @if(isset($item['downloaded_at']))
                        • {{ \Carbon\Carbon::parse($item['downloaded_at'])->format('d/m/Y H:i') }}
                    @endif
                </small>
                @if(isset($item['document_path']))
                    <div class="mt-1">
                        <a href="{{ route('articles.download', $item['article_id']) }}" 
                           class="btn btn-sm btn-outline-success">
                            <i class="fas fa-redo me-1"></i> Re-télécharger
                        </a>
                    </div>
                @endif
            </div>
            <div class="text-end">
                <span class="badge bg-success">Téléchargé</span>
                <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline mt-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-secondary" 
                            title="Retirer de l'historique">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </li>
@else
    {{-- Article normal dans le panier --}}
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
@endif
]]>
