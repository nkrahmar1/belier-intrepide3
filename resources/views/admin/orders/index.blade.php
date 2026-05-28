@extends('layouts.admin')

@section('title', 'Liste des commandes')

@section('content')
<h1>Liste des commandes</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>ID Commande</th>
            <th>Utilisateur</th>
            <th>Statut</th>
            <th>Total (€)</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? 'N/A' }}</td>
            <td>{{ ucfirst($order->status) }}</td>
            <td>{{ number_format($order->total, 2, ',', ' ') }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}">Voir</a> |
                <a href="{{ route('admin.orders.edit', $order) }}">Éditer</a> |
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:red;cursor:pointer;">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $orders->links() }}

@endsection
