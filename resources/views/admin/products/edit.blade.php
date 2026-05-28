@extends('layouts.admin')

@section('title', 'Modifier produit')

@section('content')
<h1>Modifier {{ $product->name }}</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="name">Nom</label>
    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>

    <label for="description">Description</label>
    <textarea name="description" id="description">{{ old('description', $product->description) }}</textarea>

    <label for="price">Prix (€)</label>
    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>

    <label for="stock">Stock</label>
    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}" required>

    <button type="submit">Enregistrer</button>
</form>

<a href="{{ route('admin.products.index') }}">Retour à la liste</a>
@endsection
