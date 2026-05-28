<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Liste des produits</h1>
    <table class="min-w-full bg-white rounded-lg shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Nom</th>
                <th class="px-4 py-2">Prix</th>
                <th class="px-4 py-2">Stock</th>
                <th class="px-4 py-2">Date d'ajout</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $product->id }}</td>
                    <td class="px-4 py-2">{{ $product->name }}</td>
                    <td class="px-4 py-2">{{ number_format($product->price, 0, ',', ' ') }} F CFA</td>
                    <td class="px-4 py-2">{{ $product->stock }}</td>
                    <td class="px-4 py-2">{{ $product->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
