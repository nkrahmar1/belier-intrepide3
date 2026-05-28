@props(['orders' => []])
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Commandes Récentes</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase">Montant</th>
                    <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-2 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="py-3 px-2 text-sm text-gray-600">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="py-3 px-2 text-sm font-medium text-gray-900">€{{ number_format($order->total ?? 0, 2) }}</td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full @if($order->status === 'completed') bg-green-100 text-green-800 @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 @elseif($order->status === 'processing') bg-blue-100 text-blue-800 @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-gray-400 hover:text-blue-600" title="Voir détails">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Aucune commande récente</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
