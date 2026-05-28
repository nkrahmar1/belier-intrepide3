<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Category;
use App\Models\Message;
use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function stats()
    {
        $usersCount = \App\Models\User::count();
        $articlesCount = \App\Models\Article::count();
        $ordersCount = \App\Models\Order::count();
        $productsCount = \App\Models\Product::count();
        $messagesCount = \App\Models\Message::count();
        $categoriesCount = \App\Models\Category::count();
        $subscriptionsCount = \App\Models\Subscription::count();
        $totalRevenue = \App\Models\Order::where('status', 'completed')->sum('total');

        return view('admin.stats', compact(
            'usersCount',
            'articlesCount',
            'ordersCount',
            'productsCount',
            'messagesCount',
            'categoriesCount',
            'subscriptionsCount',
            'totalRevenue'
        ));
    }

    // === MESSAGES ===
    public function messages()
    {
        try {
            // Récupérer les messages du chatbot et les messages traditionnels
            $chatbotMessages = ChatbotMessage::with('user')->latest()->take(10)->get();
            $messages = Message::latest()->paginate(15);
            
            // Compter les messages non lus du chatbot
            $unreadChatbotCount = ChatbotMessage::where('type', 'user')->whereNull('read_at')->count();
            $unreadMessagesCount = Message::where('read', false)->count();
            $unreadCount = $unreadChatbotCount + $unreadMessagesCount;
            
            $totalMessages = Message::count() + ChatbotMessage::count();
            
            // Obtenir les conversations du chatbot groupées par utilisateur (incluant les invités)
            $conversations = ChatbotMessage::leftJoin('users', 'chatbot_messages.user_id', '=', 'users.id')
                ->selectRaw('chatbot_messages.user_id, COUNT(*) as message_count, MAX(chatbot_messages.created_at) as last_message, 
                            SUM(CASE WHEN chatbot_messages.read_at IS NULL AND chatbot_messages.type = "user" THEN 1 ELSE 0 END) as unread_count,
                            users.name as user_name, users.email as user_email')
                ->groupBy('chatbot_messages.user_id', 'users.name', 'users.email')
                ->orderBy('last_message', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($conversation) {
                    // Créer un objet user pour la compatibilité avec la vue
                    $conversation->user = (object) [
                        'name' => $conversation->user_name,
                        'email' => $conversation->user_email
                    ];
                    return $conversation;
                });

            return view('admin.messages', compact(
                'messages', 
                'chatbotMessages', 
                'conversations',
                'unreadCount', 
                'totalMessages'
            ));
        } catch (\Exception $e) {
            // Si les tables n'existent pas, créer des données de test
            $messages = collect();
            $chatbotMessages = collect();
            $conversations = collect();
            $unreadCount = 0;
            $totalMessages = 0;

            return view('admin.messages', compact(
                'messages', 
                'chatbotMessages', 
                'conversations',
                'unreadCount', 
                'totalMessages'
            ));
        }
    }

    // === PARAMÈTRES ===
    public function settings()
    {
        $settings = [
            'site_name' => config('app.name', 'MonApp'),
            'admin_email' => config('mail.from.address', 'admin@example.com'),
            'timezone' => config('app.timezone', 'UTC'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function __construct()
    {
        //$this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
        // error_log("Contrôleur chargé: " . get_class($this));

    }

    // === DASHBOARD PRINCIPAL ===
    public function dashboard(Request $request)
    {
        // Statistiques principales
        $usersCount = User::count();
        $articlesCount = Article::count();
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $subscriptionsCount = \App\Models\Subscription::count();

        // Articles récents avec relations
        $recentArticles = Article::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Commandes récentes
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Activités récentes (à adapter selon votre modèle)
        $recentActivities = []; // À remplacer par vos vraies activités si besoin

        // Top produits (exemple corrigé)
        $topProducts = Product::latest()->take(5)->get();

        // Données pour le graphique (exemple)
        $salesData = [4000, 3000, 2000, 2780, 1890, 2390, 3490];

        // Statistiques supplémentaires pour le dashboard
        $stats = [
            'articles_today' => Article::whereDate('created_at', today())->count(),
            'articles_published' => Article::where('is_published', true)->count(),
            'articles_draft' => Article::where('is_published', false)->count(),
            'articles_premium' => Article::where('is_premium', true)->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'active_subscriptions' => \App\Models\Subscription::where('status', 'active')->count(),
        ];

        if ($request->ajax()) {
            return view('admin.dashboard-content', compact(
                'usersCount',
                'articlesCount',
                'ordersCount',
                'productsCount',
                'totalRevenue',
                'subscriptionsCount',
                'recentArticles',
                'recentOrders',
                'recentActivities',
                'topProducts',
                'salesData',
                'stats'
            ));
        }

        return view('admin.dashboard', compact(
            'usersCount',
            'articlesCount',
            'ordersCount',
            'productsCount',
            'totalRevenue',
            'subscriptionsCount',
            'recentArticles',
            'recentOrders',
            'recentActivities',
            'topProducts',
            'salesData',
            'stats'
        ));
    }

    private function calculateGrowth($modelClass)
    {

        $previous = $modelClass::whereMonth('created_at', now()->subMonth()->month)->count();
        $current = $modelClass::whereMonth('created_at', now()->month)->count();
        return $previous > 0
            ? (($current - $previous) / $previous) * 100
            : 0;
    }

    private function getUsersData()
    {
        return [
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'pending' => User::where('status', 'pending')->count(),
        ];
    }

    // === UTILISATEURS ===
    public function users(Request $request)
    {
        try {
            $users = User::latest()->paginate(10);
            $totalUsers = User::count();
            $activeUsers = User::where('status', 'active')->count();
            $inactiveUsers = User::where('status', 'inactive')->count();
            $pendingUsers = User::where('status', 'pending')->count();
            $usersThisMonth = User::whereMonth('created_at', now()->month)->count();

            if ($request->ajax()) {
                return view('admin.users-content', compact('users'))->render();
            }

            return view('admin.users', compact(
                'users',
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'pendingUsers',
                'usersThisMonth'
            ));
        } catch (\Exception $e) {
            $users = collect();
            $totalUsers = 0;
            $activeUsers = 0;
            $inactiveUsers = 0;
            $pendingUsers = 0;
            $usersThisMonth = 0;

            if ($request->ajax()) {
                return view('admin.users-content', compact('users'))->render();
            }

            return view('admin.users', compact(
                'users',
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'pendingUsers',
                'usersThisMonth'
            ));
        }
    }

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function storeUser(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|in:admin,user,editor',
                'status' => 'required|string|in:active,inactive,pending',
            ]);

            // Séparer le nom complet en prénom et nom de famille
            $nameParts = explode(' ', $validatedData['name'], 2);
            $firstname = $nameParts[0];
            $lastname = isset($nameParts[1]) ? $nameParts[1] : '';

            $user = User::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'status' => $validatedData['status'],
            ]);

            return response()->json(['success' => true, 'message' => 'Utilisateur créé avec succès']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur'], 500);
        }
    }

    public function storeProduct(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock'],
            ]);

            return response()->json(['success' => true, 'message' => 'Produit créé avec succès']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création du produit'], 500);
        }
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive,pending',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    // === PRODUITS ===
    public function products(Request $request)
    {
        try {
            $products = Product::latest()->paginate(15);
            $totalProducts = Product::count();
            $lowStockProducts = Product::where('stock', '<', 10)->count();
            $outOfStockProducts = Product::where('stock', 0)->count();
            $totalValue = Product::sum(DB::raw('price * stock'));

            if ($request->ajax()) {
                return view('admin.products-content', compact('products'))->render();
            }

            return view('admin.products', compact(
                'products',
                'totalProducts',
                'lowStockProducts',
                'outOfStockProducts',
                'totalValue'
            ));
        } catch (\Exception $e) {
            $products = collect();
            $totalProducts = 0;
            $lowStockProducts = 0;
            $outOfStockProducts = 0;
            $totalValue = 0;

            if ($request->ajax()) {
                return view('admin.products-content', compact('products'))->render();
            }

            return view('admin.products', compact(
                'products',
                'totalProducts',
                'lowStockProducts',
                'outOfStockProducts',
                'totalValue'
            ));
        }
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function showProduct(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé.');
    }

    // === COMMANDES ===
    public function orders(Request $request)
    {
        try {
            $orders = Order::with('user')->latest()->paginate(15);
            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $completedOrders = Order::where('status', 'completed')->count();
            $totalRevenue = Order::where('status', 'completed')->sum('total');

            if ($request->ajax()) {
                return view('admin.orders-content', compact('orders'))->render();
            }

            return view('admin.orders', compact(
                'orders',
                'totalOrders',
                'pendingOrders',
                'completedOrders',
                'totalRevenue'
            ));
        } catch (\Exception $e) {
            $orders = collect();
            $totalOrders = 0;
            $pendingOrders = 0;
            $completedOrders = 0;
            $totalRevenue = 0;

            if ($request->ajax()) {
                return view('admin.orders-content', compact('orders'))->render();
            }

            return view('admin.orders', compact(
                'orders',
                'totalOrders',
                'pendingOrders',
                'completedOrders',
                'totalRevenue'
            ));
        }
    }

    public function showOrder(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function editOrder(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'total' => 'required|numeric|min:0',
        ]);

        $order->update($validated);
        return redirect()->route('admin.orders.index')->with('success', 'Commande mise à jour.');
    }

    public function destroyOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Commande supprimée.');
    }

    // === PARTIALS AJAX ===
    public function dashboardPartial()
    {
        return view('home.partials.dashboard');
    }


    public function usersPartial()
    {
        return view('home.partials.users');
    }

    public function ajaxUsers()
    {
        return view('partials.users')->render(); // Retourne HTML
    }

    public function ajaxOrders()
    {
        return view('admin.partials.orders')->render();
    }

    // === ABONNEMENTS ===
    public function subscriptions(Request $request)
    {
        try {
            $subscriptions = \App\Models\Subscription::with('user')->latest()->paginate(15);
            $totalSubscriptions = \App\Models\Subscription::count();
            $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count();
            $cancelledSubscriptions = \App\Models\Subscription::where('status', 'cancelled')->count();
            $monthlyRevenue = \App\Models\Subscription::where('status', 'active')
                ->where('plan', 'monthly')->count() * 9.99;
            $yearlyRevenue = \App\Models\Subscription::where('status', 'active')
                ->where('plan', 'yearly')->count() * 99.99;
            $totalRevenue = $monthlyRevenue + $yearlyRevenue;

            if ($request->ajax()) {
                return view('admin.subscriptions-content', compact('subscriptions'))->render();
            }

            return view('admin.subscriptions', compact(
                'subscriptions',
                'totalSubscriptions',
                'activeSubscriptions',
                'cancelledSubscriptions',
                'monthlyRevenue',
                'yearlyRevenue',
                'totalRevenue'
            ));
        } catch (\Exception $e) {
            $subscriptions = collect();
            $totalSubscriptions = 0;
            $activeSubscriptions = 0;
            $cancelledSubscriptions = 0;
            $monthlyRevenue = 0;
            $yearlyRevenue = 0;
            $totalRevenue = 0;

            if ($request->ajax()) {
                return view('admin.subscriptions-content', compact('subscriptions'))->render();
            }

            return view('admin.subscriptions', compact(
                'subscriptions',
                'totalSubscriptions',
                'activeSubscriptions',
                'cancelledSubscriptions',
                'monthlyRevenue',
                'yearlyRevenue',
                'totalRevenue'
            ));
        }
    }

public function createSubscription()
{
    return view('admin.subscriptions.create');
}

public function storeSubscription(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'plan' => 'required|string',
        'status' => 'required|in:active,inactive,cancelled',
        'started_at' => 'required|date',
        'ends_at' => 'nullable|date',
    ]);

    \App\Models\Subscription::create($validated);
    return redirect()->route('admin.subscriptions.index')->with('success', 'Abonnement créé.');
}

public function showSubscription(\App\Models\Subscription $subscription)
{
    return view('admin.subscriptions.show', compact('subscription'));
}

public function editSubscription(\App\Models\Subscription $subscription)
{
    return view('admin.subscriptions.edit', compact('subscription'));
}

public function updateSubscription(Request $request, \App\Models\Subscription $subscription)
{
    $validated = $request->validate([
        'plan' => 'required|string',
        'status' => 'required|in:active,inactive,cancelled',
        'started_at' => 'required|date',
        'ends_at' => 'nullable|date',
    ]);

    $subscription->update($validated);
    return redirect()->route('admin.subscriptions.index')->with('success', 'Abonnement mis à jour.');
}

public function destroySubscription(\App\Models\Subscription $subscription)
{
    $subscription->delete();
    return redirect()->route('admin.subscriptions.index')->with('success', 'Abonnement supprimé.');
}

}
