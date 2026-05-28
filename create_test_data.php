<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Article;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Storage;

echo "=== Création de Données de Test ===" . PHP_EOL;

// 1. Créer un fichier test
$testContent = "Ceci est un document de test pour l'article.";
$testFile = 'documents/test-document.txt';
Storage::put($testFile, $testContent);
echo "1. Fichier de test créé: {$testFile}" . PHP_EOL;

// 2. Créer ou mettre à jour un article avec document
$article = Article::first();
if (!$article) {
    $article = Article::create([
        'titre' => 'Article Test avec Document',
        'contenu' => 'Ceci est un article de test avec un document téléchargeable.',
        'is_published' => true,
        'is_premium' => true,
        'document_path' => $testFile,
        'file_original_name' => 'document-test.txt'
    ]);
    echo "2. Article créé avec ID: {$article->id}" . PHP_EOL;
} else {
    $article->update([
        'document_path' => $testFile,
        'file_original_name' => 'document-test.txt',
        'is_premium' => true
    ]);
    echo "2. Article mis à jour avec ID: {$article->id}" . PHP_EOL;
}

// 3. Créer un utilisateur de test
$user = User::where('email', 'test@example.com')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Utilisateur Test',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);
    echo "3. Utilisateur créé: {$user->email}" . PHP_EOL;
} else {
    echo "3. Utilisateur existant: {$user->email}" . PHP_EOL;
}

// 4. Créer un abonnement actif pour cet utilisateur
$subscription = Subscription::where('user_id', $user->id)->first();
if (!$subscription) {
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_name' => 'Premium',
        'plan' => 'premium',
        'status' => 'active',
        'started_at' => now(),
        'ends_at' => now()->addMonth(),
        'price' => 9.99
    ]);
    echo "4. Abonnement créé pour l'utilisateur" . PHP_EOL;
} else {
    $subscription->update([
        'status' => 'active',
        'ends_at' => now()->addMonth()
    ]);
    echo "4. Abonnement mis à jour" . PHP_EOL;
}

echo PHP_EOL . "=== Test de la Logique ===" . PHP_EOL;
echo "Article: {$article->titre}" . PHP_EOL;
echo "Premium: " . ($article->is_premium ? 'Oui' : 'Non') . PHP_EOL;
echo "Document: {$article->document_path}" . PHP_EOL;
echo "Utilisateur: {$user->name}" . PHP_EOL;
echo "Abonnement actif: " . ($user->hasActiveSubscription() ? 'Oui' : 'Non') . PHP_EOL;
echo "Peut télécharger: " . ($article->canBeDownloadedBy($user) ? 'Oui' : 'Non') . PHP_EOL;

echo PHP_EOL . "=== URLs de Test ===" . PHP_EOL;
echo "Page des articles: http://127.0.0.1:8000/articles" . PHP_EOL;
echo "Téléchargement: http://127.0.0.1:8000/articles/{$article->id}/download" . PHP_EOL;
