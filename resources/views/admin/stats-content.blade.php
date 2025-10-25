<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Statistiques du Journal</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">👥</span>
            <div class="text-lg font-semibold mt-2">Utilisateurs</div>
            <div class="text-2xl font-bold text-blue-700">{{ $usersCount }}</div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">📰</span>
            <div class="text-lg font-semibold mt-2">Articles</div>
            <div class="text-2xl font-bold text-green-700">{{ $articlesCount }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">🧾</span>
            <div class="text-lg font-semibold mt-2">Commandes</div>
            <div class="text-2xl font-bold text-yellow-700">{{ $ordersCount }}</div>
        </div>
        <div class="bg-purple-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">📦</span>
            <div class="text-lg font-semibold mt-2">Produits</div>
            <div class="text-2xl font-bold text-purple-700">{{ $productsCount }}</div>
        </div>
        <div class="bg-pink-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">✉️</span>
            <div class="text-lg font-semibold mt-2">Messages</div>
            <div class="text-2xl font-bold text-pink-700">{{ $messagesCount }}</div>
        </div>
        <div class="bg-orange-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">📂</span>
            <div class="text-lg font-semibold mt-2">Catégories</div>
            <div class="text-2xl font-bold text-orange-700">{{ $categoriesCount }}</div>
        </div>
        <div class="bg-indigo-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">💳</span>
            <div class="text-lg font-semibold mt-2">Abonnements</div>
            <div class="text-2xl font-bold text-indigo-700">{{ $subscriptionsCount }}</div>
        </div>
        <div class="bg-gray-50 rounded-lg shadow p-5 flex flex-col items-center">
            <span class="text-3xl">💰</span>
            <div class="text-lg font-semibold mt-2">Revenus</div>
            <div class="text-2xl font-bold text-gray-700">{{ number_format($totalRevenue, 0, ',', ' ') }} F CFA</div>
        </div>
    </div>
</div>
