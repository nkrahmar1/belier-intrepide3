@extends('layouts.admin')

@section('title', 'Détail produit')

@section('content')
<h1>Détail du produit : {{ $product->name }}</h1>

<ul>
    <li><strong>Nom :</strong> {{ $product->name }}</li>
    <li><strong>Description :</strong> {{ $product->description ?? 'Aucune' }}</li>
    <li><strong>Prix :</strong> {{ number_format($product->price, 2, ',', ' ') }} €</li>
    <li><strong>Stock :</strong> {{ $product->stock }}</li>
    <li><strong>Créé le :</strong> {{ $product->created_at->format('d/m/Y') }}</li>
</ul>

<a href="{{ route('admin.products.edit', $product) }}">Modifier</a> |
<a href="{{ route('admin.products.index') }}">Retour à la liste</a>
@endsection
