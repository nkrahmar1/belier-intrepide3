<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ArticleDocumentController extends Controller
{
    public function download(Article $article)
    {
        // 1. Vérifier que l'article a un document
        if (!$article->document_path) {
            Log::warning("Tentative de téléchargement sans document - Article ID: {$article->id}");
            return back()->with('error', 'Aucun document disponible pour cet article.');
        }

        // 2. Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            Log::info("Tentative de téléchargement sans connexion - Article: {$article->titre}");
            return redirect()->route('login')
                ->with('error', 'Vous devez vous connecter pour télécharger ce document.');
        }

        // 3. Vérifier les permissions (utiliser la logique encapsulée dans le modèle)
        $user = Auth::user();
        if (!$article->canBeDownloadedBy($user)) {
            Log::info("Tentative de téléchargement non autorisée - User: " . ($user->email ?? 'guest') . ", Article: {$article->titre}");
            return redirect()->route('home.abonnement')
                ->with('error', 'Vous devez avoir un abonnement actif pour télécharger ce document.');
        }

        // 4. Vérifier que le fichier existe physiquement (disque public car les documents sont stockés avec le disk 'public')
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($article->document_path)) {
            Log::error("Fichier manquant - Document: {$article->document_path}, Article: {$article->titre}");
            return back()->with('error', 'Le document est introuvable sur le serveur.');
        }

        // 5. Log du téléchargement réussi
        Log::info("Téléchargement réussi - User: {$user->email}, Article: {$article->titre}, Document: {$article->document_path}");

        // 6. Ajouter l'article au panier pour tracking des téléchargements
        $cartController = new \App\Http\Controllers\CartController();
        $addedToCart = $cartController->addDownloadedArticle($article);

        if ($addedToCart) {
            Log::info("Article ajouté au panier de téléchargements - Article: {$article->titre}, User: {$user->email}");
        }

        // 7. Déclencher le téléchargement avec le document_path de l'article
        return \Illuminate\Support\Facades\Storage::disk('public')->download(
            $article->document_path,  // Utilise exactement le document_path de l'article
            $article->file_original_name ?? basename($article->document_path)  // Nom de fichier personnalisé ou nom par défaut
        );
    }
}
