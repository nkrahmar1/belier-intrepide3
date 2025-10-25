<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(10);

        if (request()->ajax()) {
            return view('admin.articles-content', compact('articles'));
        }

        return view('admin.articles', compact('articles'));
    }

    public function togglePublish(Article $article)
    {
        $article->update([
            'is_published' => !$article->is_published,
            'published_at' => !$article->is_published ? null : now()
        ]);

        return response()->json([
            'success' => true,
            'message' => $article->is_published ? 'Article publié' : 'Article dépublié',
            'is_published' => $article->is_published
        ]);
    }

    /**
     * Publier un article directement sur la page d'accueil
     */
    public function publishToHomepage(Article $article)
    {
        try {
            // S'assurer que l'article est publié ET visible sur la homepage
            $article->update([
                'is_published' => true,
                'published_at' => now(),
                'featured_on_homepage' => true,
                'homepage_featured_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => '🎉 Article publié sur la page d\'accueil avec succès!',
                'is_published' => true,
                'featured_on_homepage' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retirer un article de la page d'accueil
     */
    public function removeFromHomepage(Article $article)
    {
        try {
            $article->update([
                'featured_on_homepage' => false,
                'homepage_featured_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article retiré de la page d\'accueil',
                'featured_on_homepage' => false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function duplicate(Article $article)
    {
        $newArticle = $article->replicate();
        $newArticle->fill([
            'titre' => $article->titre . ' (Copie)',
            'slug' => null, // Le slug sera régénéré automatiquement
            'is_published' => false,
            'published_at' => null,
            'views_count' => 0
        ]);
        $newArticle->save();

        return response()->json([
            'success' => true,
            'message' => 'Article dupliqué avec succès',
            'article_id' => $newArticle->id
        ]);
    }

    public function download(Article $article)
    {
        if (!$article->document_path || !Storage::exists($article->document_path)) {
            abort(404, 'Document non trouvé');
        }

        $originalName = $article->file_original_name ?? 'document.pdf';
        
        return Storage::download($article->document_path, $originalName);
    }
}
