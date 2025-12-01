<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TestArticleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ArticleDocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\AdminModalController;

// --------------------
// Routes d'authentification (conformes aux conventions Laravel)
// --------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.check');
Route::post('/logout', [LoginController::class, 'logout'])->name('app_logout');

// Routes de test d'authentification (temporaires)
Route::get('/api/auth-status', function (Request $request) {
    $user = Auth::user();
    return response()->json([
        'authenticated' => Auth::check(),
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->firstname . ' ' . $user->lastname,
            'email' => $user->email,
            'initials' => strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1))
        ] : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});

Route::post('/api/test-logout', function (Request $request) {
    if (Auth::check()) {
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
            'previous_user' => $user->email
        ]);
    }
    return response()->json(['success' => false, 'message' => 'Aucun utilisateur connecté']);
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.check');

// --------------------
// Route temporaire pour créer les données de test
// --------------------
Route::get('/create-test-data', function () {
    ob_start();
    require public_path('test-data.php');
    return ob_get_clean();
});

// Route de test simple
Route::get('/test-articles', function () {
    $articles = \App\Models\Article::all();
    return "Articles en base: " . $articles->count() . "<br>" . $articles->pluck('titre')->implode('<br>');
});

// Route de test pour les dropdowns Bootstrap
Route::get('/test-bootstrap-dropdowns', function () {
    return view('test-bootstrap-dropdowns');
})->name('test.bootstrap.dropdowns');

// Route de debug pour Bootstrap
Route::get('/test-bootstrap-debug', function () {
    return view('test-bootstrap-debug');
})->name('test.bootstrap.debug');

// Route de test spécifique pour les dropdowns Account et Panier
Route::get('/test-dropdowns-account-panier', function () {
    return view('test-dropdowns-account-panier');
})->name('test.dropdowns.account.panier');

// Route de debug direct navbar Bootstrap
Route::get('/debug-navbar-bootstrap', function () {
    return view('debug-navbar-bootstrap');
})->name('debug.navbar.bootstrap');

// Route de test simple Bootstrap (sans conflicts)
Route::get('/test-simple-bootstrap', function () {
    return view('test-simple-bootstrap');
})->name('test.simple.bootstrap');

// --------------------
// Pages principales (AVANT les routes admin pour éviter les conflits)
// --------------------
Route::get('/', [HomeController::class, 'home'])->name('app_home');
Route::get('/home', [HomeController::class, 'home'])->name('home'); // Route alias pour compatibilité
Route::get('/about', [HomeController::class, 'about'])->name('app_about');

// Route pour afficher un article avec contrôle d'abonnement
Route::get('/article/{id}', [HomeController::class, 'showArticle'])->name('article.show');

// --------------------
// Route pour les catégories
// --------------------
// Réactivée : fonctionnalité de filtrage par catégorie
Route::get('/category/{categorie}', [HomeController::class, 'showByCategorie'])->name('app_category');

// --------------------
// Dashboards (routes spécifiques avant les génériques)
// --------------------
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])
    ->name('admin.dashboard');
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->get('/admin/enhanced-dashboard', function () {
        return view('admin.enhanced-dashboard');
    })
    ->name('admin.enhanced-dashboard');
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->get('/admin/test-improvements', function () {
        return view('admin.test-four-improvements');
    })
    ->name('admin.test-improvements');
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->post('/admin/dashboard/quick-article', [AdminDashboardController::class, 'quickCreateArticle'])
    ->name('admin.dashboard.quick-article');

// Routes API pour le dashboard dynamique
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Statistiques
    Route::get('/api/admin/stats', [AdminDashboardController::class, 'getStats'])->name('api.admin.stats');

    // Articles
    Route::get('/api/admin/articles', [AdminDashboardController::class, 'getArticles'])->name('api.admin.articles');
    Route::get('/api/admin/articles/{id}', [AdminDashboardController::class, 'getArticle'])->name('api.admin.articles.show');
    Route::post('/api/admin/articles/{id}/toggle-publish', [AdminDashboardController::class, 'togglePublish'])->name('api.admin.articles.toggle');
    Route::delete('/api/admin/articles/{id}', [AdminDashboardController::class, 'deleteArticle'])->name('api.admin.articles.delete');
    Route::put('/api/admin/articles/{id}', [AdminDashboardController::class, 'updateArticle'])->name('api.admin.articles.update');

    // Messages
    Route::get('/api/admin/messages', [AdminDashboardController::class, 'getMessages'])->name('api.admin.messages');
    Route::post('/api/admin/messages/{id}/toggle-read', [AdminDashboardController::class, 'toggleMessageRead'])->name('api.admin.messages.toggle');

    // Abonnements
    Route::get('/api/admin/subscriptions', [AdminDashboardController::class, 'getSubscriptions'])->name('api.admin.subscriptions');
});

// Routes admin dashboard (actions directes)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::post('/admin/articles/quick-create', [AdminDashboardController::class, 'quickCreateArticle'])->name('admin.articles.quick-create');
    Route::post('/admin/articles/{id}/toggle-publish', [AdminDashboardController::class, 'togglePublish'])->name('admin.articles.toggle-publish');
    Route::delete('/admin/articles/{id}', [AdminDashboardController::class, 'deleteArticle'])->name('admin.articles.quick-delete');
});

// Routes Modal AJAX pour Sidebar (Système SPA)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin/modal')
    ->name('admin.modal.')
    ->group(function () {
        Route::get('/users', [AdminModalController::class, 'users'])->name('users');
        Route::get('/orders', [AdminModalController::class, 'orders'])->name('orders');
        Route::get('/articles', [AdminModalController::class, 'articles'])->name('articles');
        Route::get('/products', [AdminModalController::class, 'products'])->name('products');
        Route::get('/subscriptions', [AdminModalController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/stats', [AdminModalController::class, 'stats'])->name('stats');
        Route::get('/messages', [AdminModalController::class, 'messages'])->name('messages');
        Route::get('/settings', [AdminModalController::class, 'settings'])->name('settings');
    });

// Route de test pour diagnostiquer les problèmes dashboard
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->get('/admin/dashboard-test', function () {
        $stats = [
            'users_count' => \App\Models\User::count(),
            'articles_count' => \App\Models\Article::count(),
            'categories_count' => \App\Models\Category::count(),
            'orders_count' => DB::table('orders')->count() ?? 0,
        ];
        return view('admin.dashboard-test', compact('stats'));
    })
    ->name('admin.dashboard.test');

// --------------------
// Routes administrateur
// --------------------
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Articles avec gestion complète
        Route::resource('articles', AdminArticleController::class);
        Route::post('/articles/{article}/toggle-publish', [AdminArticleController::class, 'togglePublish'])->name('articles.toggle');
        Route::post('/articles/{article}/duplicate', [AdminArticleController::class, 'duplicate'])->name('articles.duplicate');
        Route::get('/articles/{article}/download', [AdminArticleController::class, 'downloadDocument'])->name('articles.download');
        Route::get('/articles/stats', [AdminArticleController::class, 'getStats'])->name('articles.stats');
        Route::post('/articles/upload-image', [AdminArticleController::class, 'uploadImage'])->name('articles.upload-image');
        Route::patch('/articles/{article}/toggle-homepage', [AdminArticleController::class, 'toggleHomepage'])->name('articles.toggle-homepage');

        // Nouvelles routes pour la publication sur la page d'accueil
        Route::post('/articles/{article}/publish-homepage', [AdminArticleController::class, 'publishToHomepage'])->name('articles.publish-homepage');
        Route::post('/articles/{article}/remove-homepage', [AdminArticleController::class, 'removeFromHomepage'])->name('articles.remove-homepage');

        // Utilisateurs (admin) - CRUD Complet
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
        Route::get('/users/stats', [UserController::class, 'getStats'])->name('users.stats');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');

        // Produits (admin)
        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}', [AdminController::class, 'showProduct'])->name('products.show');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.destroy');

        // Commandes (admin)
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('orders.edit');
        Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');

        // Abonnements (admin)
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::get('/', [AdminController::class, 'subscriptions'])->name('index');
            Route::get('/create', [AdminController::class, 'createSubscription'])->name('create');
            Route::post('/', [AdminController::class, 'storeSubscription'])->name('store');
            Route::get('/{subscription}', [AdminController::class, 'showSubscription'])->name('show');
            Route::get('/{subscription}/edit', [AdminController::class, 'editSubscription'])->name('edit');
            Route::put('/{subscription}', [AdminController::class, 'updateSubscription'])->name('update');
            Route::delete('/{subscription}', [AdminController::class, 'destroySubscription'])->name('destroy');
        });

        // Statistiques et autres
        Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
        Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        // Routes pour le système modal SPA (contenu partiel sans layout)
        Route::get('/modal/users', function () {
            $users = \App\Models\User::paginate(10);
            return view('admin.users-content', compact('users'))->render();
        })->name('modal.users');

        Route::get('/modal/articles', function () {
            $articles = \App\Models\Article::with('user', 'category')->paginate(10);
            return view('admin.articles-content', compact('articles'))->render();
        })->name('modal.articles');

        Route::get('/modal/orders', function () {
            $orders = DB::table('orders')->paginate(10);
            return view('admin.orders-content', compact('orders'))->render();
        })->name('modal.orders');

        Route::get('/modal/products', function () {
            $products = \App\Models\Product::paginate(10);
            return view('admin.products-content', compact('products'))->render();
        })->name('modal.products');

        Route::get('/modal/subscriptions', function () {
            $subscriptions = DB::table('subscriptions')->paginate(10);
            return view('admin.subscriptions-content', compact('subscriptions'))->render();
        })->name('modal.subscriptions');

        Route::get('/modal/stats', function () {
            $stats = [
                'users_count' => \App\Models\User::count(),
                'articles_count' => \App\Models\Article::count(),
                'categories_count' => \App\Models\Category::count(),
                'orders_count' => DB::table('orders')->count() ?? 0,
            ];
            return view('admin.stats-content', compact('stats'))->render();
        })->name('modal.stats');

        Route::get('/modal/messages', function () {
            $messages = []; // À implémenter selon votre système de messages
            return view('admin.messages', compact('messages'))->render();
        })->name('modal.messages');

        Route::get('/modal/settings', function () {
            return view('admin.settings')->render();
        })->name('modal.settings');

        // Routes de catégories supprimées car nous utilisons maintenant Category
    });

// --------------------
// Routes pour les abonnements utilisateur (AVANT les routes publiques génériques)
// --------------------
Route::middleware(['auth'])->group(function () {
    // Choix de l'abonnement
    Route::get('/subscription/choose', [SubscriptionController::class, 'chooseSubscription'])->name('subscription.choose');

    // Sélection du mode de paiement
    Route::get('/subscription/payment/{planId}', [SubscriptionController::class, 'paymentMethod'])->name('subscription.payment');

    // Traitement du paiement
    Route::post('/subscription/process', [SubscriptionController::class, 'processPayment'])->name('subscription.process-payment');

    // Anciennes routes pour compatibilité
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/subscribe/{planId}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
});

// Routes CinetPay (publiques pour les callbacks)
Route::get('/subscription/cinetpay/return', [SubscriptionController::class, 'handleCinetPayReturn'])->name('subscription.cinetpay.return');
Route::get('/subscription/cinetpay/cancel', [SubscriptionController::class, 'handleCinetPayCancel'])->name('subscription.cinetpay.cancel');
Route::post('/subscription/cinetpay/notify', [SubscriptionController::class, 'handleCinetPayNotify'])->name('subscription.cinetpay.notify');

// Route publique pour la page d'abonnement (accessible à tous)
Route::get('/abonnement', [AbonnementController::class, 'index'])->name('home.abonnement');

// --------------------
// Articles (publiques)
// --------------------
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');

// Routes d'articles par catégories/thèmes
Route::prefix('articles')->group(function () {
    Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('articles.category');
    Route::get('/economie', [ArticleController::class, 'economie'])->name('articles.economie');
    Route::get('/sport', [ArticleController::class, 'sport'])->name('articles.sport');
    Route::get('/politique', [ArticleController::class, 'politique'])->name('articles.politique');
    Route::get('/populaires', [ArticleController::class, 'populaires'])->name('articles.populaires');
    // Route de catégorie article (réactivée)
    Route::get('/category/{slug}', [ArticleController::class, 'byCategory'])->name('articles.byCategory');
});

// --------------------
// Téléchargement de document (protégé par login)
// --------------------
Route::get('/articles/{article}/download', [ArticleDocumentController::class, 'download'])
    ->name('articles.download')
    ->middleware('auth');

// --------------------
// Panier
// --------------------
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// --------------------
// Chatbot (accessible à tous les utilisateurs)
// --------------------
Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::get('/', [ChatbotController::class, 'index'])->name('index');
    Route::get('/messages', [ChatbotController::class, 'getMessages'])->name('messages');
    Route::post('/send', [ChatbotController::class, 'sendMessage'])->name('send');
});

// Routes admin pour le chatbot
Route::middleware(['auth', 'admin'])->prefix('admin/chatbot')->name('admin.chatbot.')->group(function () {
    Route::get('/conversations', [ChatbotController::class, 'getConversations'])->name('conversations');
    Route::get('/conversation/{userId}', [ChatbotController::class, 'getConversation'])->name('conversation');
    Route::post('/reply', [ChatbotController::class, 'replyMessage'])->name('reply');
    Route::post('/mark-read', [ChatbotController::class, 'markAsRead'])->name('mark-read');
});

// --------------------
// Routes publiques : Catégories (SUPPRIMÉ - remplacé par la route générique ci-dessus)
// --------------------
// La route resource categories a été supprimée pour éviter les conflits
// avec la route générique app_category

// --------------------
// Routes de test (À LA FIN)
// --------------------
Route::get('/test-route', fn () => 'Route de test - OK');
Route::get('/articles-test', fn () => 'Page des articles de test');
Route::get('/test-articles', [TestArticleController::class, 'index']);

// Route de test pour le téléchargement
Route::get('/test-download', function () {
    return view('test-download');
});

// Route de test pour les dropdowns d'authentification
Route::get('/test-auth-dropdowns', function () {
    return view('test-auth-dropdowns');
});

// Route de test pour le CSRF
Route::get('/test-csrf', function () {
    return view('test-csrf');
});

// Route pour régénérer le token CSRF
Route::post('/test-csrf-regenerate', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::get('/test-download-direct', function () {
    $testContent = "Contenu du document de test\nCeci est un fichier de test pour vérifier le téléchargement.";
    $fileName = 'document-test.txt';

    return response($testContent)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
});

// Routes pour créer un article de test
Route::get('/create-test-article', function () {
    return view('create-test-article');
});

Route::post('/create-test-article', function (Request $request) {
    $article = \App\Models\Article::create([
        'titre' => $request->titre,
        'contenu' => $request->contenu,
        'is_premium' => $request->has('is_premium'),
        'is_published' => $request->has('is_published'),
        'document_path' => 'documents/article-test.txt',
        'file_original_name' => 'document-article-test.txt'
    ]);

    return redirect('/articles')->with('success', 'Article de test créé avec succès! ID: ' . $article->id);
});
