<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ArticleDownloadController extends Controller
{
    /**
     * Télécharger un article avec gestion des permissions
     */
    public function download(Request $request, $articleId)
    {
        try {
            $article = Article::findOrFail($articleId);
            $user = Auth::user();

            // Vérifier les permissions de téléchargement
            $downloadCheck = $article->canUserDownload($user);

            if (!$downloadCheck['can_download']) {
                return $this->handleDownloadDenied($downloadCheck, $article);
            }

            // Vérifier l'existence du fichier
            if (!$article->document_path || !Storage::exists($article->document_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le document n\'est pas disponible.'
                ], 404);
            }

            // Enregistrer le téléchargement
            $downloadType = $this->determineDownloadType($downloadCheck['reason']);
            $cost = ($downloadCheck['reason'] === 'purchase_required') ? $article->unit_price : 0;

            if ($user) {
                ArticleDownload::recordDownload($user->id, $article->id, $downloadType, $cost);
            }

            // Incrémenter le compteur de téléchargements
            $article->increment('download_count');

            // Log de l'activité
            Log::info('Article téléchargé', [
                'article_id' => $article->id,
                'user_id' => $user->id ?? 'guest',
                'download_type' => $downloadType,
                'ip' => $request->ip()
            ]);

            // Télécharger le fichier
            return $this->downloadFile($article);

        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du téléchargement.'
            ], 500);
        }
    }

    /**
     * Gérer les téléchargements refusés
     */
    private function handleDownloadDenied($downloadCheck, $article)
    {
        $messages = [
            'login_required' => 'Vous devez être connecté pour télécharger ce contenu premium.',
            'subscription_required' => 'Un abonnement premium est requis pour télécharger ce contenu.',
            'limit_exceeded' => 'Vous avez atteint la limite de téléchargements gratuits pour cet article.',
            'purchase_required' => 'Ce contenu est disponible à l\'achat unitaire.',
        ];

        $statusCodes = [
            'login_required' => 401,
            'subscription_required' => 403,
            'limit_exceeded' => 429,
            'purchase_required' => 402,
        ];

        $reason = $downloadCheck['reason'];
        $response = [
            'success' => false,
            'message' => $messages[$reason] ?? 'Téléchargement non autorisé.',
            'reason' => $reason
        ];

        // Ajouter le prix si achat requis
        if ($reason === 'purchase_required' && isset($downloadCheck['price'])) {
            $response['price'] = $downloadCheck['price'];
            $response['currency'] = 'FCFA';
        }

        // Suggestions d'actions
        switch ($reason) {
            case 'login_required':
                $response['action_url'] = route('login');
                $response['action_text'] = 'Se connecter';
                break;
            case 'subscription_required':
                $response['action_url'] = route('subscription.choose');
                $response['action_text'] = 'Choisir un abonnement';
                break;
            case 'purchase_required':
                $response['action_url'] = route('article.purchase', $article->id);
                $response['action_text'] = 'Acheter l\'article';
                break;
        }

        return response()->json($response, $statusCodes[$reason] ?? 403);
    }

    /**
     * Déterminer le type de téléchargement
     */
    private function determineDownloadType($reason)
    {
        switch ($reason) {
            case 'premium_subscription':
                return 'premium';
            case 'purchase_required':
                return 'purchase';
            default:
                return 'free';
        }
    }

    /**
     * Effectuer le téléchargement du fichier
     */
    private function downloadFile($article)
    {
        $filePath = $article->document_path;
        $fileName = $article->file_original_name ?: $article->slug . '.pdf';
        
        return Storage::download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Prévisualiser les permissions de téléchargement
     */
    public function checkDownloadPermission($articleId)
    {
        try {
            $article = Article::findOrFail($articleId);
            $user = Auth::user();
            
            $downloadCheck = $article->canUserDownload($user);
            $classification = $article->getArticleClassification();
            
            return response()->json([
                'success' => true,
                'can_download' => $downloadCheck['can_download'],
                'reason' => $downloadCheck['reason'] ?? 'unknown',
                'article_type' => $classification['name'],
                'access_level' => $classification['access'],
                'quality' => $classification['quality'],
                'file_size' => $article->getStorageUsage(),
                'download_count' => $article->download_count,
                'price' => $downloadCheck['price'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article introuvable.'
            ], 404);
        }
    }

    /**
     * Historique des téléchargements d'un utilisateur
     */
    public function userDownloadHistory(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $downloads = ArticleDownload::with(['article' => function($query) {
                $query->select('id', 'titre', 'article_type', 'content_quality');
            }])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = ArticleDownload::getUserStats($user->id);

        return response()->json([
            'downloads' => $downloads,
            'stats' => $stats
        ]);
    }
}
