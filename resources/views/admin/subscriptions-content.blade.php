<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Liste des abonnements</h1>
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Utilisateur</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Début</th>
                <th class="px-4 py-2">Fin</th>
                <th class="px-4 py-2">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $subscription->id }}</td>
                    <td class="px-4 py-2">{{ $subscription->user->name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $subscription->type ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $subscription->start_date ? $subscription->start_date->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-2">{{ $subscription->end_date ? $subscription->end_date->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-2">{{ $subscription->status ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
