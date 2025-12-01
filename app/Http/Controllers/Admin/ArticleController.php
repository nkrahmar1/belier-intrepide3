<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        try {
            $articles = Article::with(['category', 'user'])
                ->latest()
                ->paginate(10);

            return view('admin.articles.index', compact('articles'));
        } catch (\Exception $e) {
            Log::error('Erreur dans ArticleController@index: ' . $e->getMessage());

            // Fallback sans les relations
            $articles = Article::latest()->paginate(10);
            return view('admin.articles.index', compact('articles'));
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255|min:3',
            'contenu' => 'required|string|min:10',
            'extrait' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'is_premium' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'article_type' => 'nullable|string|max:50',
            'content_quality' => 'nullable|integer|min:0|max:100',
            'unit_price' => 'nullable|numeric|min:0',
            'free_download_limit' => 'nullable|integer|min:0',
            'document' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:10240', // 10MB max
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
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

        // Sécurisation et valeurs par défaut
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['titre']);
        $validated['is_premium'] = $request->has('is_premium') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['article_type'] = $validated['article_type'] ?? null;
        $validated['content_quality'] = $validated['content_quality'] ?? null;
        $validated['unit_price'] = isset($validated['unit_price']) ? $validated['unit_price'] : null;
        $validated['free_download_limit'] = isset($validated['free_download_limit']) ? $validated['free_download_limit'] : null;
        $validated['views_count'] = 0;
        $validated['downloads_count'] = 0;

        // Si publié, mettre la date de publication
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        $totalStorageBytes = 0;

        // Upload sécurisé du document
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . Str::slug($validated['titre']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('articles/documents', $filename, 'public');
            $validated['document_path'] = $path;

            // Stocker des infos sur le fichier
            $validated['file_original_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();

            // Taille réelle sur le disque (en octets)
            try {
                $diskSize = Storage::disk('public')->size($path);
                $totalStorageBytes += $diskSize;
            } catch (\Exception $e) {
                // Fallback to uploaded size
                $totalStorageBytes += $file->getSize();
            }
        }

        // Upload sécurisé de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '_' . Str::slug($validated['titre']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('articles/images', $imagename, 'public');
            $validated['image'] = $path;

            try {
                $diskSize = Storage::disk('public')->size($path);
                $totalStorageBytes += $diskSize;
            } catch (\Exception $e) {
                $totalStorageBytes += $image->getSize();
            }
        }

        // Populate storage_size if any file uploaded
        if ($totalStorageBytes > 0) {
            $validated['storage_size'] = $totalStorageBytes; // in bytes
        }

        try {
            $article = Article::create($validated);

            // Notification pour nouvelle publication (si publié)
            if ($validated['is_published']) {
                // Notifier les utilisateurs abonnés
                $this->notifySubscribers($article);
            }

            return redirect()->route('admin.articles.index')
                ->with('success', 'Article créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur création article: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création de l\'article : ' . $e->getMessage()]);
        }
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255|min:3',
            'contenu' => 'required|string|min:10',
            'extrait' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'is_premium' => 'boolean',
            'is_published' => 'boolean',
            'is_featured' => 'nullable|boolean',
            'article_type' => 'nullable|string|max:50',
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
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'document.mimes' => 'Le document doit être un fichier PDF, DOC, DOCX, TXT, XLS, XLSX, PPT ou PPTX.',
            'document.max' => 'Le document ne doit pas dépasser 10MB.',
            'content_quality.integer' => 'La qualité de contenu doit être un entier entre 0 et 100.',
            'unit_price.numeric' => 'Le prix unitaire doit être un nombre valide.',
            'free_download_limit.integer' => 'Le nombre de téléchargements gratuits doit être un entier.',
        ]);

        // Mise à jour du slug si le titre a changé
        if ($validated['titre'] !== $article->titre) {
            $validated['slug'] = Str::slug($validated['titre']);
        }

        // Values and homepage/feature flags
        $validated['is_featured'] = $request->has('is_featured') ? 1 : ($validated['is_featured'] ?? $article->is_featured);
        $validated['article_type'] = $validated['article_type'] ?? $article->article_type;
        $validated['content_quality'] = $validated['content_quality'] ?? $article->content_quality;
        $validated['unit_price'] = isset($validated['unit_price']) ? $validated['unit_price'] : $article->unit_price;
        $validated['free_download_limit'] = isset($validated['free_download_limit']) ? $validated['free_download_limit'] : $article->free_download_limit;

        // Gestion de la publication
        if ($validated['is_published'] && !$article->is_published) {
            $validated['published_at'] = now();
        } elseif (!$validated['is_published']) {
            $validated['published_at'] = null;
        }

        $totalStorageBytes = $article->storage_size ?? 0;

        // Upload nouveau document
        if ($request->hasFile('document')) {
            // Supprimer l'ancien document
            if ($article->document_path) {
                Storage::disk('public')->delete($article->document_path);
            }

            $file = $request->file('document');
            $filename = time() . '_' . Str::slug($validated['titre']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('articles/documents', $filename, 'public');
            $validated['document_path'] = $path;
            $validated['file_original_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();

            try {
                $diskSize = Storage::disk('public')->size($path);
                $totalStorageBytes += $diskSize;
            } catch (\Exception $e) {
                $totalStorageBytes += $file->getSize();
            }
        }

        // Upload nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $image = $request->file('image');
            $imagename = time() . '_' . Str::slug($validated['titre']) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('articles/images', $imagename, 'public');
            $validated['image'] = $path;

            try {
                $diskSize = Storage::disk('public')->size($path);
                $totalStorageBytes += $diskSize;
            } catch (\Exception $e) {
                $totalStorageBytes += $image->getSize();
            }
        }

        if ($totalStorageBytes > 0) {
            $validated['storage_size'] = $totalStorageBytes;
        }

        try {
            $article->update($validated);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Article mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour article: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour de l\'article : ' . $e->getMessage()]);
        }
    }

    public function destroy(Article $article)
    {
        try {
            // Supprimer les fichiers associés
            if ($article->document_path) {
                Storage::disk('public')->delete($article->document_path);
            }
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $article->delete();

            return redirect()->route('admin.articles.index')
                ->with('success', 'Article supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur suppression article: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression de l\'article.']);
        }
    }

    /**
     * Afficher un article (pour prévisualisation)
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Notifier les utilisateurs abonnés d'une nouvelle publication
     */
    private function notifySubscribers($article)
    {
        try {
            // Récupérer les utilisateurs avec abonnement actif
            $subscribers = \App\Models\User::whereHas('subscriptions', function($query) {
                $query->where('end_date', '>', now())
                      ->where('status', 'active');
            })->get();

            // Envoyer notification à chaque abonné
            foreach ($subscribers as $subscriber) {
                $subscriber->notify(new \App\Notifications\NewArticleNotification($article));
            }

            Log::info("Notifications envoyées à " . $subscribers->count() . " abonnés pour l'article: " . $article->titre);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi des notifications: " . $e->getMessage());
        }
    }

    /**
     * Basculer le statut de publication d'un article (pour le dashboard)
     */
    public function togglePublish(Article $article)
    {
        try {
            $newStatus = !$article->is_published;

            $article->update([
                'is_published' => $newStatus,
                'published_at' => $newStatus ? now() : null
            ]);

            Log::info("Article {$article->id} - Statut publication basculé: " . ($newStatus ? 'publié' : 'dépublié'));

            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'Article publié avec succès' : 'Article dépublié',
                'is_published' => $newStatus,
                'published_at' => $article->published_at ? $article->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors du basculement de publication: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    /**
     * Dupliquer un article (pour le dashboard)
     */
    public function duplicate(Article $article)
    {
        try {
            // Créer une copie de l'article
            $newArticle = $article->replicate();

            // Modifier les champs spécifiques
            $newArticle->fill([
                'titre' => $article->titre . ' (Copie)',
                'slug' => null, // Sera régénéré automatiquement par l'observer
                'is_published' => false,
                'published_at' => null,
                'views_count' => 0,
                'user_id' => Auth::id()
            ]);

            // Copier les fichiers si ils existent
            if ($article->image) {
                $originalImagePath = $article->image;
                $imageExtension = pathinfo($originalImagePath, PATHINFO_EXTENSION);
                $newImagePath = 'articles/images/' . Str::random(40) . '.' . $imageExtension;

                if (Storage::disk('public')->exists($originalImagePath)) {
                    Storage::disk('public')->copy($originalImagePath, $newImagePath);
                    $newArticle->image = $newImagePath;
                }
            }

            if ($article->document_path) {
                $originalDocPath = $article->document_path;
                $docExtension = pathinfo($originalDocPath, PATHINFO_EXTENSION);
                $newDocPath = 'articles/documents/' . Str::random(40) . '.' . $docExtension;

                if (Storage::disk('public')->exists($originalDocPath)) {
                    Storage::disk('public')->copy($originalDocPath, $newDocPath);
                    $newArticle->document_path = $newDocPath;
                }
            }

            $newArticle->save();

            Log::info("Article {$article->id} dupliqué vers {$newArticle->id}");

            return response()->json([
                'success' => true,
                'message' => 'Article dupliqué avec succès',
                'article_id' => $newArticle->id,
                'article_title' => $newArticle->titre
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la duplication: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication de l\'article'
            ], 500);
        }
    }

    /**
     * Télécharger le document d'un article (pour le dashboard)
     */
    public function downloadDocument(Article $article)
    {
        try {
            if (!$article->document_path) {
                abort(404, 'Aucun document associé à cet article');
            }

            if (!Storage::disk('public')->exists($article->document_path)) {
                abort(404, 'Document non trouvé sur le serveur');
            }

            // Vérifier les droits via le modèle
            $user = auth()->user();
            $permission = $article->canUserDownload($user);

            if (!is_array($permission) || empty($permission['can_download'])) {
                // Si réponse structurée indiquant raison
                $reason = is_array($permission) && isset($permission['reason']) ? $permission['reason'] : 'forbidden';
                Log::warning("Téléchargement refusé pour l'article {$article->id}: {$reason}");
                abort(403, 'Vous n\'êtes pas autorisé à télécharger ce document. Raison: ' . $reason);
            }

            $originalName = $article->file_original_name ?? 'document_' . $article->id . '.pdf';

            Log::info("Téléchargement du document pour l'article {$article->id} par utilisateur " . ($user->id ?? 'guest'));

            // Incrémenter le compteur de téléchargements
            try {
                $article->incrementDownloads();
            } catch (\Exception $e) {
                Log::warning('Impossible d\'incrémenter downloads: ' . $e->getMessage());
            }

            return response()->download(
                storage_path('app/public/' . $article->document_path),
                $originalName
            );
        } catch (\Exception $e) {
            Log::error("Erreur lors du téléchargement: " . $e->getMessage());
            abort(500, 'Erreur lors du téléchargement du document');
        }
    }

    /**
     * Obtenir les statistiques pour le dashboard
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Article::count(),
                'published' => Article::where('is_published', true)->count(),
                'draft' => Article::where('is_published', false)->count(),
                'premium' => Article::where('is_premium', true)->count(),
                'today' => Article::whereDate('created_at', today())->count(),
                'this_week' => Article::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => Article::whereMonth('created_at', now()->month)->count(),
                'popular' => Article::orderBy('views_count', 'desc')->take(5)->get(['id', 'titre', 'views_count']),
                'recent' => Article::with(['category', 'user'])->latest()->take(5)->get()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des statistiques: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Upload rapide d'image via AJAX (pour le dashboard)
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('articles/images', $imageName, 'public');

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploadée avec succès',
                    'image_path' => $imagePath,
                    'image_url' => asset('storage/' . $imagePath)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Aucune image reçue'
            ], 400);
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'upload d'image: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload de l\'image'
            ], 500);
        }
    }

    /**
     * Toggle l'affichage d'un article sur la page d'accueil
     */
    public function toggleHomepage(Request $request, $id)
    {
        try {
            $article = Article::findOrFail($id);

            // Validation
            $request->validate([
                'featured' => 'required|boolean'
            ]);

            $featured = $request->input('featured');

            // Mise à jour du statut: synchroniser les champs possibles
            $article->is_featured = $featured;
            $article->featured_on_homepage = $featured;
            $article->homepage_featured_at = $featured ? now() : null;
            $article->save();

            $message = $featured
                ? 'Article ajouté à la page d\'accueil avec succès'
                : 'Article retiré de la page d\'accueil avec succès';

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_featured' => $article->is_featured
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Erreur toggle homepage: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }
}
