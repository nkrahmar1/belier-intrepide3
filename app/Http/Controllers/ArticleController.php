<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        try {
            // Requête optimisée avec limite et pré-calculs
            $articles = Article::select(['id', 'titre', 'extrait', 'contenu', 'image', 'category_id', 'user_id', 'is_published', 'published_at', 'created_at', 'views_count', 'is_premium', 'document_path'])
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
            
            return view('articles.index', ['articles' => $paginatedArticles, 'listingTitle' => '📰 Nos Articles']);
        } catch (\Exception $e) {
            Log::error('Erreur ArticleController@index: ' . $e->getMessage());
            
            // Fallback ultra simple
            $articles = Article::select(['id', 'titre', 'contenu'])
                ->limit(12)
                ->get();
            
            return view('articles.index', ['articles' => $articles, 'listingTitle' => '📰 Nos Articles']);
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255|min:3',
            'contenu' => 'required|string|min:10',
            'extrait' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'article_type' => 'nullable|string|max:50',
            'is_premium' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'content_quality' => 'nullable|integer|min:0|max:100',
            'unit_price' => 'nullable|numeric|min:0',
            'free_download_limit' => 'nullable|integer|min:0',
            'document' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.min' => 'Le titre doit contenir au moins 3 caractères.',
            'contenu.required' => 'Le contenu est obligatoire.',
            'contenu.min' => 'Le contenu doit contenir au moins 10 caractères.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'document.mimes' => 'Le document doit être un fichier PDF, DOC, DOCX, TXT, XLS, XLSX, PPT ou PPTX.',
            'document.max' => 'Le document ne doit pas dépasser 10MB.',
            'content_quality.integer' => 'La qualité de contenu doit être un entier entre 0 et 100.',
            'unit_price.numeric' => 'Le prix unitaire doit être un nombre valide.',
            'free_download_limit.integer' => 'Le nombre de téléchargements gratuits doit être un entier.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_premium'] = $request->has('is_premium') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['content_quality'] = $request->filled('content_quality') ? $validated['content_quality'] : null;
        $validated['unit_price'] = $request->filled('unit_price') ? $validated['unit_price'] : null;
        $validated['free_download_limit'] = $request->filled('free_download_limit') ? $validated['free_download_limit'] : null;
        $validated['views_count'] = 0;
        $validated['downloads_count'] = 0;

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['titre']) . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('articles/images', $filename, 'public');
        }

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $filename = time() . '_' . Str::slug($validated['titre']) . '.' . $document->getClientOriginalExtension();
            $validated['document_path'] = $document->storeAs('articles/documents', $filename, 'public');
            $validated['file_original_name'] = $document->getClientOriginalName();
            $validated['file_size'] = $document->getSize();
        }

        try {
            Article::create($validated);

            return redirect()->route('articles.mine')->with('success', 'Votre article a bien été enregistré.');
        } catch (\Exception $e) {
            Log::error('Erreur ArticleController@store: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'enregistrement de l\'article. Veuillez réessayer.']);
        }
    }

    public function mine()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $articles = \App\Models\Article::where('user_id', Auth::id())
            ->with(['category:id,nom', 'user:id,firstname,lastname'])
            ->latest('created_at')
            ->get();

        $articles->transform(function ($article) {
            $article->titre_limit = strlen($article->titre) > 60 
                ? substr($article->titre, 0, 57) . '...' 
                : $article->titre;

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

            $article->date_formatted = $article->published_at 
                ? $article->published_at->format('d/m/Y') 
                : $article->created_at->format('d/m/Y');

            $article->category_nom = $article->category ? $article->category->nom : 'Non classé';

            unset($article->contenu);
            return $article;
        });

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

        return view('articles.index', [
            'articles' => $paginatedArticles,
            'listingTitle' => '📰 Mes Articles',
        ]);
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
