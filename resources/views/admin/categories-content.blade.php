<div class="p-6 md:p-10 bg-gradient-to-br from-pink-50 via-white to-indigo-100 min-h-screen">
    <h1 class="text-3xl md:text-4xl font-extrabold text-pink-700 mb-8 flex items-center gap-3">
        <span class="inline-block bg-pink-100 text-pink-600 rounded-full p-2 shadow-md">📂</span>
        Catégories
    </h1>
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 text-sm text-gray-500">Catégories récupérées : {{ $categories->total() }}</div>
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-pink-100 overflow-x-auto">
            <table class="min-w-full divide-y divide-pink-50">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-pink-700 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-pink-700 uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-pink-700 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-pink-700 uppercase">Parent</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-pink-700 uppercase">Actif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr class="hover:bg-pink-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-700">{{ $categorie->id }}</td>
                            <td class="px-4 py-3">{{ $categorie->nom }}</td>
                            <td class="px-4 py-3">{{ $categorie->description ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $categorie->parent->nom ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $categorie->actif ? 'Oui' : 'Non' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-400 italic">Aucune catégorie</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
