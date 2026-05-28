@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>✏️ Modifier le produit</h1>

    @if ($errors->any())
        <div style="color:red;">@foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Nom</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
        </div>
        <div>
            <label>Prix (€)</label>
            <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>
        </div>
        <div>
            <label>Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" required>
        </div>
        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
