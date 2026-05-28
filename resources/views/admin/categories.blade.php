{{-- 
    COMMENTÉ : Vue de gestion des catégories désactivée
    Cette fonctionnalité a été désactivée pour simplifier l'interface
@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Catégories</h1>
        <div class="mb-4">
            <span class="font-semibold">Total :</span> {{ $stats['total'] ?? 0 }} |
            <span class="font-semibold">Actives :</span> {{ $stats['actif'] ?? 0 }} |
            <span class="font-semibold">Inactives :</span> {{ $stats['inactif'] ?? 0 }} |
            <span class="font-semibold">Parents :</span> {{ $stats['parent'] ?? 0 }}
        </div>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Slug</th>
                        <th class="px-4 py-2">Active</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr>
                            <td class="px-4 py-2">{{ $categorie->id }}</td>
                            <td class="px-4 py-2">{{ $categorie->nom }}</td>
                            <td class="px-4 py-2">{{ $categorie->slug }}</td>
                            @extends('layouts.admin')

                            @section('content')
                                <div class="p-6">
                                    <h1 class="text-2xl font-bold mb-4">Catégories</h1>
                                    <div class="mb-4">
                                        <span class="font-semibold">Total :</span> {{ $stats['total'] ?? 0 }} |
                                        <span class="font-semibold">Actives :</span> {{ $stats['actif'] ?? 0 }} |
                                        <span class="font-semibold">Inactives :</span> {{ $stats['inactif'] ?? 0 }} |
                                        <span class="font-semibold">Parents :</span> {{ $stats['parent'] ?? 0 }}
                                    </div>
                                    <div class="overflow-x-auto bg-white rounded shadow">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-2">ID</th>
                                                    <th class="px-4 py-2">Nom</th>
                                                    <th class="px-4 py-2">Slug</th>
                                                    <th class="px-4 py-2">Active</th>
                                                    <th class="px-4 py-2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($categories as $categorie)
                                                    <tr>
                                                        <td class="px-4 py-2">{{ $categorie->id }}</td>
                                                        <td class="px-4 py-2">{{ $categorie->nom }}</td>
                                                        <td class="px-4 py-2">{{ $categorie->slug }}</td>
                                                        <td class="px-4 py-2">
                                                            @if($categorie->actif)
                                                                <span class="text-green-600 font-bold">Oui</span>
                                                            @else
                                                                <span class="text-red-600 font-bold">Non</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <!-- Actions à compléter -->
                                                            <a href="#" class="text-blue-600 hover:underline">Voir</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">Aucune catégorie trouvée.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        {{ $categories->links() }}
                                    </div>
                                </div>
                            @endsection
