<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            // Initialiser les valeurs par défaut
            $usersCount = 0;
            $articlesCount = 0;
            $categoriesCount = 0;
            $categories = collect();
            $users = collect();
            $messagesUnread = 0;
            $activeSubscriptions = 0;
            $ordersCount = 0;
            $ordersToday = 0;

            // Compter les utilisateurs
            if (Schema::hasTable('users')) {
                $usersCount = User::count();
                $users = User::orderBy('created_at', 'desc')->limit(10)->get();
            }

            // Compter les articles
            if (Schema::hasTable('articles')) {
                $articlesCount = Article::count();
            }

            // Compter les catégories
            if (Schema::hasTable('categories')) {
                $categoriesCount = Category::count();
                $categories = Category::all();
            }

            // Compter les messages non lus
            if (Schema::hasTable('messages')) {
                $messagesUnread = \App\Models\Message::where('is_read', false)->count();
            }

            // Compter les abonnements actifs
            if (Schema::hasTable('subscriptions')) {
                $activeSubscriptionsQuery = Subscription::where('status', 'active')
                    ->where(function ($query) {
                        $query->whereNull('ends_at')
                            ->orWhere('ends_at', '>', now());
                    });
                $activeSubscriptions = $activeSubscriptionsQuery->count();
            }

            // Compter les commandes
            if (Schema::hasTable('orders')) {
                $ordersCount = DB::table('orders')->count();
                $ordersToday = DB::table('orders')->whereDate('created_at', today())->count();
            }

            // Construire les statistiques
            $stats = [
                'articles_today' => Schema::hasTable('articles') ? Article::whereDate('created_at', today())->count() : 0,
                'articles_published' => Schema::hasTable('articles') ? Article::where('is_published', 1)->count() : 0,
                'articles_draft' => Schema::hasTable('articles') ? Article::where('is_published', 0)->count() : 0,
                'articles_premium' => Schema::hasTable('articles') ? Article::where('is_premium', 1)->count() : 0,
                'users_today' => Schema::hasTable('users') ? User::whereDate('created_at', today())->count() : 0,
                'active_subscriptions' => $activeSubscriptions,
                'subscriptions_total' => Schema::hasTable('subscriptions') ? Subscription::count() : 0,
                'featured_homepage' => Schema::hasTable('articles') ? Article::where(function ($query) {
                    $query->where('is_featured', true)
                        ->orWhere('featured_on_homepage', true);
                })->count() : 0,
                'users_active' => Schema::hasTable('users') ? User::where('status', 'active')->count() : 0,
                'users_inactive' => Schema::hasTable('users') ? User::where('status', 'inactive')->count() : 0,
                'users_pending' => Schema::hasTable('users') ? User::whereNull('email_verified_at')->count() : 0,
                'users_premium' => Schema::hasTable('users') ? User::where('is_premium', true)->count() : 0,
                'messages_unread' => $messagesUnread,
                'orders_today' => $ordersToday,
            ];

            // Données pour le graphique
            $months = collect(range(11, 0))->map(function ($i) {
                return now()->subMonths($i);
            });

            $chartData = [
                'labels' => $months->map(fn($m) => $m->format('M Y'))->toArray(),
                'articles' => [],
                'revenue' => [],
            ];

            // Remplir les données d'articles par mois
            if (Schema::hasTable('articles')) {
                $chartData['articles'] = $months->map(function ($month) {
                    return Article::whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
                })->toArray();
            }

            // Remplir les données de revenus par mois
            if (Schema::hasTable('subscriptions')) {
                $chartData['revenue'] = $months->map(function ($month) {
                    return Subscription::where('status', 'active')
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->sum(DB::raw('COALESCE(amount, price, 0)'));
                })->toArray();
            }

            // Revenus totaux
            $totalRevenue = Schema::hasTable('subscriptions') 
                ? Subscription::where('status', 'active')->sum(DB::raw('COALESCE(amount, price, 0)'))
                : 0;

            $subscriptionsCount = $stats['active_subscriptions'];
            $stats['revenue_total'] = $totalRevenue;
            
            if (Schema::hasTable('subscriptions')) {
                $stats['revenue_today'] = Subscription::whereDate('created_at', today())
                    ->sum(DB::raw('COALESCE(amount, price, 0)'));
                $stats['revenue_month'] = Subscription::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->sum(DB::raw('COALESCE(amount, price, 0)'));
            } else {
                $stats['revenue_today'] = 0;
                $stats['revenue_month'] = 0;
            }

            // Articles récents
            $articles = collect();
            if (Schema::hasTable('articles')) {
                $articles = Article::with('category')
                    ->latest()
                    ->paginate(10);
            }

            // Articles en homepage
            $homepageArticles = collect();
            if (Schema::hasTable('articles')) {
                $homepageArticles = Article::with('category')
                    ->where('is_published', true)
                    ->latest('published_at')
                    ->take(8)
                    ->get();
            }

            // Abonnements récents
            $recentSubscriptions = collect();
            if (Schema::hasTable('subscriptions')) {
                $recentSubscriptions = Subscription::with('user')
                    ->latest()
                    ->take(8)
                    ->get();
            }

            // Articles publiés
            $publishedArticles = collect();
            if (Schema::hasTable('articles')) {
                $publishedArticles = Article::published()
                    ->with(['category', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            }

            $salesData = [];
            $categoryData = [];

            return view('admin.dashboard', compact(
                'usersCount',
                'articlesCount',
                'categoriesCount',
                'publishedArticles',
                'users',
                'stats',
                'salesData',
                'categoryData',
                'totalRevenue',
                'subscriptionsCount',
                'categories',
                'chartData',
                'ordersCount',
                'articles',
                'homepageArticles',
                'recentSubscriptions'
            ));

        } catch (\Exception $e) {
            Log::error('Erreur Dashboard Admin: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            
            // Retourner une vue avec des données vides en cas d'erreur
            return view('admin.dashboard', [
                'usersCount' => 0,
                'articlesCount' => 0,
                'categoriesCount' => 0,
                'publishedArticles' => collect(),
                'users' => collect(),
                'stats' => [],
                'salesData' => [],
                'categoryData' => [],
                'totalRevenue' => 0,
                'subscriptionsCount' => 0,
                'categories' => collect(),
                'chartData' => ['labels' => [], 'articles' => [], 'revenue' => []],
                'ordersCount' => 0,
                'articles' => collect(),
                'homepageArticles' => collect(),
                'recentSubscriptions' => collect()
            ]);
        }
    }

    /**
     * Créer un article rapidement depuis le dashboard
     */
    public function quickCreateArticle(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255|min:3',
                'contenu' => 'required|string|min:10',
                'extrait' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'is_premium' => 'nullable|boolean',
                'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'document' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:10240',
            ]);

            // Création de l'article
            $article = new Article();
            $article->fill([
                'titre' => $validated['titre'],
                'contenu' => $validated['contenu'],
                'extrait' => $validated['extrait'] ?? null,
                'category_id' => $validated['category_id'],
                'user_id' => Auth::id(),
                'is_premium' => $validated['is_premium'] ?? false,
                'is_published' => $validated['is_published'] ?? false,
                'published_at' => $validated['is_published'] ? 
                    ($validated['published_at'] ?? now()) : null,
            ]);

            // Gestion de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles/images', 'public');
                $article->image = $imagePath;
            }

            // Gestion du document
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('articles/documents', 'public');
                $article->document_path = $documentPath;
                $article->file_original_name = $request->file('document')->getClientOriginalName();
                $article->file_size = $request->file('document')->getSize();
            }

            $article->save();

            return response()->json([
                'success' => true,
                'message' => 'Article créé avec succès !',
                'article' => [
                    'id' => $article->id,
                    'title' => $article->titre,
                    'is_published' => $article->is_published
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Erreur création article rapide: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'article'
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques pour le dashboard (API)
     */
    public function getStats()
    {
        try {
            $stats = [
                // Articles
                'articles_total' => Article::count(),
                'articles_published' => Article::where('is_published', 1)->count(),
                'articles_draft' => Article::where('is_published', 0)->count(),
                'articles_premium' => Article::where('is_premium', 1)->count(),
                'articles_today' => Article::whereDate('created_at', today())->count(),
                'articles_this_month' => Article::whereMonth('created_at', now()->month)->count(),
                
                // Utilisateurs
                'users_total' => User::count(),
                'users_active' => User::where('status', 'active')->count(),
                'users_today' => User::whereDate('created_at', today())->count(),
                'users_premium' => User::where('is_premium', true)->count(),
                
                // Messages
                'messages_total' => \App\Models\Message::count(),
                'messages_unread' => \App\Models\Message::where('is_read', false)->count(),
                'messages_today' => \App\Models\Message::whereDate('created_at', today())->count(),
                
                // Abonnements
                'subscriptions_active' => \App\Models\Subscription::where('status', 'active')
                                            ->where('ends_at', '>', now())->count(),
                'subscriptions_total' => \App\Models\Subscription::count(),
                'subscriptions_revenue' => \App\Models\Subscription::where('status', 'active')
                                            ->sum('amount'),
                'active_subscriptions' => \App\Models\Subscription::where('status', 'active')
                                            ->where('ends_at', '>', now())->count(),
                'featured_homepage' => Article::where(function ($query) {
                    $query->where('is_featured', true)
                        ->orWhere('featured_on_homepage', true);
                })->count(),
                'revenue_month' => \App\Models\Subscription::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->sum(DB::raw('COALESCE(amount, price, 0)')),
            ];

            // Données pour graphique articles par mois (12 derniers mois)
            $articlesPerMonth = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $articlesPerMonth[] = [
                    'month' => $date->format('M Y'),
                    'count' => Article::whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count()
                ];
            }

            // Données pour graphique revenus (12 derniers mois)
            $revenuePerMonth = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $revenuePerMonth[] = [
                    'month' => $date->format('M Y'),
                    'amount' => \App\Models\Subscription::where('status', 'active')
                                ->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->sum('amount')
                ];
            }

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'charts' => [
                    'articlesPerMonth' => $articlesPerMonth,
                    'revenuePerMonth' => $revenuePerMonth,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Récupérer les articles avec pagination et filtres (API)
     */
    public function getArticles(Request $request)
    {
        try {
            $query = Article::with(['category', 'user']);

            // Recherche
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('titre', 'like', "%{$search}%")
                      ->orWhere('contenu', 'like', "%{$search}%");
                });
            }

            // Filtre par statut
            if ($request->has('status') && $request->status !== 'all') {
                $isPublished = $request->status === 'published' ? 1 : 0;
                $query->where('is_published', $isPublished);
            }

            // Filtre par catégorie
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category_id', $request->category);
            }

            // Tri
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $articles = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'articles' => $articles
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération articles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des articles'
            ], 500);
        }
    }

    /**
     * Basculer le statut de publication d'un article
     */
    public function togglePublish($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->is_published = !$article->is_published;
            $article->published_at = $article->is_published ? now() : null;
            $article->save();

            return response()->json([
                'success' => true,
                'message' => $article->is_published ? 'Article publié' : 'Article mis en brouillon',
                'is_published' => $article->is_published
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur toggle publish: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut'
            ], 500);
        }
    }

    /**
     * Supprimer un article
     */
    public function deleteArticle($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Récupérer les messages avec pagination et filtres (API)
     */
    public function getMessages(Request $request)
    {
        try {
            $query = \App\Models\Message::with('user');

            // Filtre par statut de lecture
            if ($request->has('status') && $request->status !== 'all') {
                $isRead = $request->status === 'read' ? 1 : 0;
                $query->where('is_read', $isRead);
            }

            // Recherche
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            // Tri
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $messages = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des messages'
            ], 500);
        }
    }

    /**
     * Marquer un message comme lu/non lu
     */
    public function toggleMessageRead($id)
    {
        try {
            $message = \App\Models\Message::findOrFail($id);
            $message->is_read = !$message->is_read;
            $message->read_at = $message->is_read ? now() : null;
            $message->save();

            return response()->json([
                'success' => true,
                'message' => $message->is_read ? 'Message marqué comme lu' : 'Message marqué comme non lu',
                'is_read' => $message->is_read
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur toggle message read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du statut'
            ], 500);
        }
    }

    /**
     * Récupérer les abonnements avec pagination et filtres (API)
     */
    public function getSubscriptions(Request $request)
    {
        try {
            $query = \App\Models\Subscription::with('user');

            // Filtre par statut
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Recherche par utilisateur
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Tri
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $subscriptions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération abonnements: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des abonnements'
            ], 500);
        }
    }

    /**
     * Récupérer un article spécifique pour édition
     */
    public function getArticle($id)
    {
        try {
            $article = Article::with(['category', 'user'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'article' => $article
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], 404);
        }
    }

    /**
     * Mettre à jour un article
     */
    public function updateArticle(Request $request, $id)
    {
        try {
            $article = Article::findOrFail($id);

            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'contenu' => 'required|string',
                'extrait' => 'nullable|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'is_premium' => 'nullable|boolean',
                'is_published' => 'nullable|boolean',
            ]);

            $article->update($validated);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles/images', 'public');
                $article->image = $imagePath;
                $article->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Article mis à jour avec succès',
                'article' => $article
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour article: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }
}