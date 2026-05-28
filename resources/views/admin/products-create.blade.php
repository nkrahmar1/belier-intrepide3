@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>➕ Ajouter un produit</h1>

    @if ($errors->any())
        <div style="color:red;">@foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div>
            <label>Nom</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Prix (€)</label>
            <input type="number" name="price" step="0.01" required>
        </div>
        <div>
            <label>Stock</label>
            <input type="number" name="stock" required>
        </div>
        <button type="submit">Créer</button>
    </form>
</div>
@endsection
