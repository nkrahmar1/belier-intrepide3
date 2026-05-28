<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Statistiques principales
        $usersCount = User::count();
        $articlesCount = Article::count();
        $categoriesCount = Category::count();
        
        // Récupération des catégories pour le modal de création
        $categories = Category::all();

        // Articles publiés avec statistiques complètes
        $publishedArticles = Article::published()
            ->with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($article) {
                // Calcul simulé des abonnés qui ont lu (à ajuster selon votre logique)
                $article->subscribers_read = rand(5, 50);
                return $article;
            });

        // Statistiques avancées
        $stats = [
            'articles_today' => Article::whereDate('created_at', today())->count(),
            'articles_published' => Article::where('is_published', 1)->count(),
            'articles_draft' => Article::where('is_published', 0)->count(),
            'articles_premium' => Article::where('is_premium', 1)->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'active_subscriptions' => \App\Models\Subscription::where('status', 'active')
                                         ->where('ends_at', '>', now())->count()
        ];

        // Données pour les graphiques
        $salesData = [12, 19, 15, 25, 22, 30, 28, 35, 32];
        $categoryData = [5, 8, 3, 7, 4];

        // Calculs de revenus (simulés - à ajuster selon votre modèle)
        $totalRevenue = $stats['active_subscriptions'] * 29.99; // Prix d'abonnement moyen
        $subscriptionsCount = $stats['active_subscriptions'];

        return view('admin.dashboard', compact(
            'usersCount',
            'articlesCount',
            'categoriesCount',
            'publishedArticles',
            'stats',
            'salesData',
            'categoryData',
            'totalRevenue',
            'subscriptionsCount',
            'categories'
        ));
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
}
