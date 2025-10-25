@extends('layouts.admin')

@section('title', 'Modifier commande')

@section('content')
<h1>Modifier la commande #{{ $order->id }}</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.orders.update', $order) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="status">Statut</label>
    <select name="status" id="status" required>
        <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>En attente</option>
        <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>En traitement</option>
        <option value="completed" {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>Terminé</option>
        <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
    </select>

    <label for="total">Total (€)</label>
    <input type="number" name="total" id="total" step="0.01" min="0" value="{{ old('total', $order->total) }}" required>

    <button type="submit">Enregistrer</button>
</form>

<a href="{{ route('admin.orders.index') }}">Retour à la liste</a>
@endsection
