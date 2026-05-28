<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Article;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usersCount = User::count();
        $messagesCount = Message::count();
        $articles = Article::orderBy('created_at', 'desc')->take(5)->get();

        // Pie chart: utilisateurs vs abonnés
        $abonnesCount = \App\Models\Subscription::distinct('user_id')->count('user_id');
        $pieLabels = ['Utilisateurs', 'Abonnés'];
        $pieData = [$usersCount, $abonnesCount];

        // Bar chart: utilisateurs connectés par jour (7 derniers jours)
        $barLabels = [];
        $barData = [];
        $now = now();
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $barLabels[] = $date;
            $barData[] = User::whereDate('last_login_at', $date)->count();
        }

        if ($request->ajax()) {
            return view('admin.dashboard-content', compact('usersCount', 'messagesCount', 'articles', 'pieLabels', 'pieData', 'barLabels', 'barData'));
        }
        return view('admin.dashboard', compact('usersCount', 'messagesCount', 'articles', 'pieLabels', 'pieData', 'barLabels', 'barData'));
    }

    ///

     /**
     * Obtenir les statistiques en temps réel
     */
    public function getRealTimeStats()
    {
        $stats = [
            'total_sales' => $this->getTotalSales(),
            'new_orders' => $this->getNewOrders(),
            'active_users' => $this->getActiveUsers(),
            'revenue_today' => $this->getRevenueToday(),
            'pending_orders' => $this->getPendingOrders(),
            'low_stock_products' => $this->getLowStockProducts(),
            'conversion_rate' => $this->getConversionRate(),
            'average_order_value' => $this->getAverageOrderValue(),
        ];

        return response()->json($stats);
    }

    /**
     * Recherche globale
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [
            'products' => $this->searchProducts($query),
            'orders' => $this->searchOrders($query),
            'users' => $this->searchUsers($query),
            'articles' => $this->searchArticles($query),
        ];

        return response()->json(['results' => $results]);
    }

    /**
     * Suggestions de recherche
     */
    public function getSearchSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = [
            'Produit: ' . $query,
            'Commande: ' . $query,
            'Client: ' . $query,
            'Article: ' . $query,
        ];

        return response()->json(['suggestions' => $suggestions]);
    }

    /**
     * Activités récentes
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->get('limit', 10);

        $activities = [
            [
                'id' => 1,
                'type' => 'order',
                'title' => 'Nouvelle commande #1234',
                'description' => 'Commande de 150€ par Jean Dupont',
                'time' => '5 minutes',
                'icon' => 'shopping-cart',
                'color' => 'success'
            ],
            [
                'id' => 2,
                'type' => 'user',
                'title' => 'Nouvel utilisateur',
                'description' => 'Marie Martin s\'est inscrite',
                'time' => '12 minutes',
                'icon' => 'user-plus',
                'color' => 'info'
            ],
            [
                'id' => 3,
                'type' => 'product',
                'title' => 'Stock faible',
                'description' => 'Produit XYZ - 5 unités restantes',
                'time' => '25 minutes',
                'icon' => 'alert-triangle',
                'color' => 'warning'
            ],
            [
                'id' => 4,
                'type' => 'payment',
                'title' => 'Paiement reçu',
                'description' => 'Paiement de 89€ pour commande #1230',
                'time' => '1 heure',
                'icon' => 'credit-card',
                'color' => 'success'
            ],
        ];

        return response()->json(['activities' => array_slice($activities, 0, $limit)]);
    }

    /**
     * Top produits
     */
    public function getTopProducts(Request $request)
    {
        $limit = $request->get('limit', 5);
        $period = $request->get('period', '7days');

        $products = [
            [
                'id' => 1,
                'name' => 'Smartphone XY Pro',
                'sales' => 125,
                'revenue' => 24999.75,
                'growth' => 15.2,
                'image' => '/images/products/phone1.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Ordinateur portable ABC',
                'sales' => 89,
                'revenue' => 89000.00,
                'growth' => -2.1,
                'image' => '/images/products/laptop1.jpg'
            ],
            [
                'id' => 3,
                'name' => 'Casque Bluetooth Pro',
                'sales' => 156,
                'revenue' => 15600.00,
                'growth' => 8.7,
                'image' => '/images/products/headphones1.jpg'
            ],
            [
                'id' => 4,
                'name' => 'Tablette Design 10"',
                'sales' => 67,
                'revenue' => 20100.00,
                'growth' => 22.4,
                'image' => '/images/products/tablet1.jpg'
            ],
            [
                'id' => 5,
                'name' => 'Montre connectée Sport',
                'sales' => 143,
                'revenue' => 28600.00,
                'growth' => 5.8,
                'image' => '/images/products/watch1.jpg'
            ],
        ];

        return response()->json(['products' => array_slice($products, 0, $limit)]);
    }

    /**
     * Commandes récentes
     */
    public function getRecentOrders(Request $request)
    {
        $limit = $request->get('limit', 10);

        $orders = [
            [
                'id' => 1234,
                'customer_name' => 'Jean Dupont',
                'customer_email' => 'jean.dupont@email.com',
                'total' => 150.00,
                'status' => 'pending',
                'status_label' => 'En attente',
                'created_at' => now()->subMinutes(5)->format('d/m/Y H:i'),
                'items_count' => 3
            ],
            [
                'id' => 1233,
                'customer_name' => 'Marie Martin',
                'customer_email' => 'marie.martin@email.com',
                'total' => 89.50,
                'status' => 'processing',
                'status_label' => 'En cours',
                'created_at' => now()->subMinutes(25)->format('d/m/Y H:i'),
                'items_count' => 2
            ],
            [
                'id' => 1232,
                'customer_name' => 'Pierre Durand',
                'customer_email' => 'pierre.durand@email.com',
                'total' => 299.99,
                'status' => 'completed',
                'status_label' => 'Terminée',
                'created_at' => now()->subHour()->format('d/m/Y H:i'),
                'items_count' => 1
            ],
            [
                'id' => 1231,
                'customer_name' => 'Sophie Leblanc',
                'customer_email' => 'sophie.leblanc@email.com',
                'total' => 45.00,
                'status' => 'shipped',
                'status_label' => 'Expédiée',
                'created_at' => now()->subHours(2)->format('d/m/Y H:i'),
                'items_count' => 4
            ],
        ];

        return response()->json(['orders' => array_slice($orders, 0, $limit)]);
    }

    /**
     * Articles récents
     */
    public function getRecentArticles(Request $request)
    {
        $limit = $request->get('limit', 5);

        $articles = [
            [
                'id' => 1,
                'title' => 'Guide d\'utilisation des nouvelles fonctionnalités',
                'excerpt' => 'Découvrez toutes les nouvelles fonctionnalités ajoutées...',
                'author' => 'Admin',
                'status' => 'published',
                'created_at' => now()->subDays(1)->format('d/m/Y'),
                'views' => 245
            ],
            [
                'id' => 2,
                'title' => 'Mise à jour de sécurité importante',
                'excerpt' => 'Une mise à jour critique a été déployée pour...',
                'author' => 'Support',
                'status' => 'published',
                'created_at' => now()->subDays(3)->format('d/m/Y'),
                'views' => 567
            ],
            [
                'id' => 3,
                'title' => 'Nouveaux produits disponibles',
                'excerpt' => 'Consultez notre nouvelle gamme de produits...',
                'author' => 'Marketing',
                'status' => 'draft',
                'created_at' => now()->subDays(5)->format('d/m/Y'),
                'views' => 0
            ],
        ];

        return response()->json(['articles' => array_slice($articles, 0, $limit)]);
    }

    // Méthodes privées pour les statistiques

    private function getTotalSales()
    {
        // Remplacez par votre logique réelle
        return rand(1000, 5000);
    }

    private function getNewOrders()
    {
        return rand(10, 50);
    }

    private function getActiveUsers()
    {
        return rand(100, 500);
    }

    private function getRevenueToday()
    {
        return rand(500, 2000);
    }

    private function getPendingOrders()
    {
        return rand(5, 25);
    }

    private function getLowStockProducts()
    {
        return rand(2, 15);
    }

    private function getConversionRate()
    {
        return round(rand(250, 450) / 100, 2);
    }

    private function getAverageOrderValue()
    {
        return round(rand(5000, 15000) / 100, 2);
    }

    private function searchProducts($query)
    {
        // Exemple de recherche dans les produits
        return [
            ['id' => 1, 'name' => 'Produit contenant: ' . $query, 'type' => 'product'],
            ['id' => 2, 'name' => 'Autre produit: ' . $query, 'type' => 'product'],
        ];
    }

    private function searchOrders($query)
    {
        // Exemple de recherche dans les commandes
        return [
            ['id' => 1234, 'number' => '#1234', 'customer' => 'Client ' . $query, 'type' => 'order'],
        ];
    }

    private function searchUsers($query)
    {
        // Exemple de recherche dans les utilisateurs
        return [
            ['id' => 1, 'name' => 'Utilisateur ' . $query, 'email' => $query . '@email.com', 'type' => 'user'],
        ];
    }

    private function searchArticles($query)
    {
        // Exemple de recherche dans les articles
        return [
            ['id' => 1, 'title' => 'Article sur ' . $query, 'type' => 'article'],
        ];
    }
}
