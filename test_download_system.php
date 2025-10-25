<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Article;
use App\Models\User;

echo "=== Test du Système de Téléchargement ===" . PHP_EOL . PHP_EOL;

// Test 1: Vérifier les articles avec documents
echo "1. Articles avec documents disponibles:" . PHP_EOL;
$articlesWithDocs = Article::whereNotNull('document_path')->get(['id', 'titre', 'is_premium', 'document_path']);

if ($articlesWithDocs->count() > 0) {
    foreach ($articlesWithDocs as $article) {
        echo "  - Article #{$article->id}: {$article->titre}" . PHP_EOL;
        echo "    Premium: " . ($article->is_premium ? 'Oui' : 'Non') . PHP_EOL;
        echo "    Document: {$article->document_path}" . PHP_EOL;
        echo "    Fichier existe: " . (file_exists(storage_path('app/' . $article->document_path)) ? 'Oui' : 'Non') . PHP_EOL;
        echo "---" . PHP_EOL;
    }
} else {
    echo "  Aucun article avec document trouvé." . PHP_EOL;
}

echo PHP_EOL;

// Test 2: Vérifier les utilisateurs avec abonnement
echo "2. Utilisateurs avec abonnement:" . PHP_EOL;
$users = User::all();
foreach ($users as $user) {
    $hasSubscription = $user->hasActiveSubscription();
    echo "  - {$user->name} ({$user->email}): " . ($hasSubscription ? 'Abonné' : 'Non abonné') . PHP_EOL;
}

echo PHP_EOL;

// Test 3: Simuler la logique de téléchargement
echo "3. Test de la logique de téléchargement:" . PHP_EOL;
$testArticle = Article::whereNotNull('document_path')->first();

if ($testArticle) {
    echo "  Article de test: {$testArticle->titre}" . PHP_EOL;
    echo "  Est premium: " . ($testArticle->is_premium ? 'Oui' : 'Non') . PHP_EOL;
    
    $testUser = User::first();
    if ($testUser) {
        $canDownload = $testArticle->canBeDownloadedBy($testUser);
        echo "  Utilisateur: {$testUser->name}" . PHP_EOL;
        echo "  Peut télécharger: " . ($canDownload ? 'Oui' : 'Non') . PHP_EOL;
        
        if (!$canDownload && $testArticle->is_premium) {
            echo "  Raison: Article premium et utilisateur sans abonnement actif" . PHP_EOL;
        }
    }
} else {
    echo "  Aucun article avec document pour tester" . PHP_EOL;
}

echo PHP_EOL . "=== Routes de téléchargement ===" . PHP_EOL;
echo "URL de téléchargement: /articles/{id}/download" . PHP_EOL;
echo "Contrôleur: ArticleDocumentController@download" . PHP_EOL;
