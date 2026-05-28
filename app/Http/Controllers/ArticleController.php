<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        try {
            // Requête optimisée avec limite et pré-calculs
            $articles = \App\Models\Article::select(['id', 'titre', 'extrait', 'contenu', 'image', 'category_id', 'user_id', 'is_published', 'published_at', 'created_at', 'views_count', 'is_premium', 'document_path'])
                ->where('is_published', 1)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->with(['category:id,nom', 'user:id,firstname,lastname'])
                ->latest('published_at')
                ->limit(50) // Limiter à 50 articles maximum
                ->get();
            
            // Pré-traitement des données pour optimiser la vue
            $articles->transform(function ($article) {
                // Pré-formatage du titre avec limite
                $article->titre_limit = strlen($article->titre) > 60 
                    ? substr($article->titre, 0, 57) . '...' 
                    : $article->titre;

                // Pré-formatage de l'extrait avec limite
                if ($article->extrait) {
                    $article->extrait_limit = strlen($article->extrait) > 120 
                        ? substr($article->extrait, 0, 117) . '...' 
                        : $article->extrait;
                } else {
                    $content_stripped = strip_tags($article->contenu ?? '');
                    $article->extrait_limit = strlen($content_stripped) > 120 
                        ? substr($content_stripped, 0, 117) . '...' 
                        : ($content_stripped ?: 'Aucun extrait disponible');
                }

                // Pré-formatage de la date
                $article->date_formatted = $article->published_at 
                    ? $article->published_at->format('d/m/Y') 
                    : $article->created_at->format('d/m/Y');

                // Pré-récupération du nom de catégorie
                $article->category_nom = $article->category ? $article->category->nom : 'Non classé';

                // Nettoyage pour économiser la mémoire
                unset($article->contenu);
                
                return $article;
            });
            
            // Convertir en collection paginée manuellement
            $page = request()->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            $items = $articles->slice($offset, $perPage);
            $total = $articles->count();
            
            $paginatedArticles = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            return view('articles.index', ['articles' => $paginatedArticles]);
        } catch (\Exception $e) {
            Log::error('Erreur ArticleController@index: ' . $e->getMessage());
            
            // Fallback ultra simple
            $articles = \App\Models\Article::select(['id', 'titre', 'contenu'])
                ->limit(12)
                ->get();
            
            return view('articles.index', ['articles' => $articles]);
        }
    }

    public function show($id)
    {
        $article = \App\Models\Article::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->findOrFail($id);

        // Incrémenter les vues
        $article->increment('views_count');

        return view('articles.show', compact('article'));
    }

    public function byCategory($category)
    {
        $category = \App\Models\Category::where('slug', $category)->firstOrFail();

        $articles = \App\Models\Article::where('category_id', $category->id)
                ->latest()
                ->paginate(12);

        return view('articles.category', compact('articles', 'category'));
    }

    public function economie()
    {
        $articles = \App\Models\Article::whereHas('category', function($query) {
            $query->where('name', 'ÉCONOMIE');
        })->latest()->paginate(12);

        return view('articles.economie', compact('articles'));
    }

    public function sport()
    {
        $articles = \App\Models\Article::whereHas('category', function($query) {
            $query->where('name', 'SPORT');
        })->latest()->paginate(12);

        return view('articles.sport', compact('articles'));
    }

    public function politique()
    {
        $articles = \App\Models\Article::whereHas('category', function($query) {
            $query->where('name', 'POLITIQUE');
        })->latest()->paginate(12);

        return view('articles.politique', compact('articles'));
    }

    public function populaires()
    {
        $articles = \App\Models\Article::orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        return view('articles.populaires', compact('articles'));
    }

    /**
     * Télécharge le document associé à un article
     */
    public function download($id)
    {
        try {
            $article = \App\Models\Article::findOrFail($id);
            
            // Vérifier que l'article a un document
            if (!$article->document_path) {
                return redirect()->back()->with('error', 'Aucun document disponible pour cet article.');
            }
            
            // Construire le chemin complet
            $filePath = storage_path('app/public/' . $article->document_path);
            
            // Vérifier que le fichier existe
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Le document demandé n\'existe pas.');
            }
            
            // Récupérer le nom original du fichier ou générer un nom approprié
            $fileName = $article->file_name ?: ('article_' . $article->id . '.pdf');
            
            // Retourner le fichier en téléchargement
            return response()->download($filePath, $fileName);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du document: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du téléchargement du document.');
        }
    }
}
