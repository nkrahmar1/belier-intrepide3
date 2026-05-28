<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Order;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Statistiques globales
        $articlesCount = Article::count();
        $usersCount = User::count();
        $ordersCount = Order::count();

        $stats = [
            'articles_today' => Article::whereDate('created_at', Carbon::today())->count(),
            'articles_published' => Article::where('status', 'published')->count(),
            'articles_draft' => Article::where('status', 'draft')->count(),
            'users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'orders_today' => Order::whereDate('created_at', Carbon::today())->count(),
            'revenue_total' => Order::where('status', 'completed')->sum('total'),
            'revenue_today' => Order::whereDate('created_at', Carbon::today())
                                    ->where('status', 'completed')
                                    ->sum('total'),
        ];

        // Données pour les graphiques (12 derniers mois)
        $months = collect(range(11, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i);
        });

        $chartData = [
            'labels' => $months->map(fn($m) => $m->format('M Y'))->toArray(),
            'articles' => $months->map(function ($month) {
                return Article::whereYear('created_at', $month->year)
                              ->whereMonth('created_at', $month->month)
                              ->count();
            })->toArray(),
            'revenue' => $months->map(function ($month) {
                return Order::whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->where('status', 'completed')
                            ->sum('total');
            })->toArray(),
        ];

        // Articles récents paginés
        $articles = Article::with('category')
                           ->latest()
                           ->paginate(10);

        return view('admin.dashboard', compact(
            'articlesCount',
            'usersCount',
            'ordersCount',
            'stats',
            'chartData',
            'articles'
        ));
    }

    public function getStats()
    {
        $stats = [
            'articles_total' => Article::count(),
            'articles_today' => Article::whereDate('created_at', Carbon::today())->count(),
            'articles_published' => Article::where('status', 'published')->count(),
            'articles_draft' => Article::where('status', 'draft')->count(),
            'users_total' => User::count(),
            'users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'orders_total' => Order::count(),
            'orders_today' => Order::whereDate('created_at', Carbon::today())->count(),
            'revenue_total' => Order::where('status', 'completed')->sum('total'),
            'revenue_today' => Order::whereDate('created_at', Carbon::today())
                                    ->where('status', 'completed')
                                    ->sum('total'),
        ];

        return response()->json($stats);
    }

    public function quickCreateArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'status' => 'draft'
        ]);

        return response()->json([
            'success' => true,
            'article' => $article
        ]);
    }

    public function getArticles(Request $request)
    {
        $articles = Article::with('category', 'user')
                           ->latest()
                           ->paginate($request->get('per_page', 10));

        return response()->json($articles);
    }

    public function getArticle($id)
    {
        $article = Article::with('category', 'user')->findOrFail($id);
        return response()->json($article);
    }

    public function togglePublish($id)
    {
        $article = Article::findOrFail($id);
        $article->status = $article->status === 'published' ? 'draft' : 'published';
        $article->save();

        return response()->json([
            'success' => true,
            'status' => $article->status
        ]);
    }

    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['success' => true]);
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $article->update($request->only(['title', 'content', 'category_id']));

        return response()->json([
            'success' => true,
            'article' => $article
        ]);
    }

    public function getMessages(Request $request)
    {
        $messages = Message::with('user')
                           ->latest()
                           ->paginate($request->get('per_page', 10));

        return response()->json($messages);
    }

    public function toggleMessageRead($id)
    {
        $message = Message::findOrFail($id);
        $message->is_read = !$message->is_read;
        $message->save();

        return response()->json([
            'success' => true,
            'is_read' => $message->is_read
        ]);
    }

    public function getSubscriptions(Request $request)
    {
        $subscriptions = Subscription::with('user')
                                    ->latest()
                                    ->paginate($request->get('per_page', 10));

        return response()->json($subscriptions);
    }
}
