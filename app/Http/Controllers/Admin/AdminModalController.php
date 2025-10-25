<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Subscription;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminModalController extends Controller
{
    /**
     * 👥 Gestion des Utilisateurs
     */
    public function users(Request $request)
    {
        // Vérifier si c'est une requête AJAX
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $users = User::orderBy('created_at', 'desc')
                ->paginate(15);
            
            return view('admin.modals.users', compact('users'));
        }
        
        // Fallback : rediriger vers dashboard si accès direct
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * 🧾 Gestion des Commandes
     */
    public function orders(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Récupérer les commandes (à adapter selon votre modèle)
            // Si vous n'avez pas de modèle Order, afficher un message
            $orders = collect([]); // Vide pour l'instant
            
            return view('admin.modals.orders', compact('orders'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * 📰 Gestion des Articles
     */
    public function articles(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $articles = Article::with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            
            return view('admin.modals.articles', compact('articles'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * 📦 Gestion des Produits
     */
    public function products(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Récupérer les produits (à adapter selon votre modèle)
            $products = collect([]); // Vide pour l'instant
            
            return view('admin.modals.products', compact('products'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * 💳 Gestion des Abonnements
     */
    public function subscriptions(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $subscriptions = Subscription::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            return view('admin.modals.subscriptions', compact('subscriptions'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * 📊 Statistiques
     */
    public function stats(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Récupérer les statistiques
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'today' => User::whereDate('created_at', today())->count(),
                    'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                    'this_month' => User::whereMonth('created_at', now()->month)->count(),
                ],
                'articles' => [
                    'total' => Article::count(),
                    'published' => Article::where('status', 'published')->count(),
                    'draft' => Article::where('status', 'draft')->count(),
                    'today' => Article::whereDate('created_at', today())->count(),
                ],
                'subscriptions' => [
                    'total' => Subscription::count(),
                    'active' => Subscription::where('status', 'active')->count(),
                    'expired' => Subscription::where('status', 'expired')->count(),
                    'this_month' => Subscription::whereMonth('created_at', now()->month)->count(),
                ],
                'messages' => [
                    'total' => Message::count(),
                    'unread' => Message::where('is_read', false)->count(),
                    'today' => Message::whereDate('created_at', today())->count(),
                ],
            ];
            
            // Statistiques par mois (6 derniers mois)
            $monthlyUsers = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
            $monthlyArticles = Article::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
            return view('admin.modals.stats', compact('stats', 'monthlyUsers', 'monthlyArticles'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * ✉️ Messages
     */
    public function messages(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $messages = Message::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            return view('admin.modals.messages', compact('messages'));
        }
        
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * ⚙️ Paramètres
     */
    public function settings(Request $request)
    {
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Paramètres de l'application
            $settings = [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ];
            
            return view('admin.modals.settings', compact('settings'));
        }
        
        return redirect()->route('admin.dashboard');
    }
}
