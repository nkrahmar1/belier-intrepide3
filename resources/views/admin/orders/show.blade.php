@extends('layouts.admin')

@section('title', 'Détail commande')

@section('content')
<h1>Détail de la commande #{{ $order->id }}</h1>

<ul>
    <li><strong>Utilisateur :</strong> {{ $order->user->name ?? 'N/A' }}</li>
    <li><strong>Status :</strong> {{ ucfirst($order->status) }}</li>
    <li><strong>Total :</strong> {{ number_format($order->total, 2, ',', ' ') }} €</li>
    <li><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</li>
</ul>

<a href="{{ route('admin.orders.edit', $order) }}">Modifier</a> |
<a href="{{ route('admin.orders.index') }}">Retour à la liste</a>
@endsection
