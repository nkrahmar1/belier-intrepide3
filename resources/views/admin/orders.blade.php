@extends('layouts.admin')

@section('content')
<style>
    .orders-page {
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .orders-page h1 {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .status.completed { color: green; font-weight: bold; }
    .status.pending { color: orange; font-weight: bold; }
    .status.cancelled { color: red; font-weight: bold; }

    .pagination {
        margin-top: 20px;
    }

    .pagination .page-link {
        margin: 0 5px;
        padding: 8px 12px;
        border-radius: 4px;
        background: #f1f1f1;
        color: #333;
        text-decoration: none;
    }

    .pagination .active span {
        background-color: #007bff;
        color: white;
    }
</style>

<div class="orders-page">
    <h1>🛒 Liste des Commandes</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                    <td><span class="status {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Aucune commande trouvée.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $orders->links() }}
    </div>
</div>
@endsection
