<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Article;

echo "=== Test Articles Database ===" . PHP_EOL;
echo "Articles count: " . Article::count() . PHP_EOL . PHP_EOL;

echo "First 5 articles:" . PHP_EOL;
$articles = Article::take(5)->get(['id', 'titre', 'is_published', 'is_premium', 'document_path']);

foreach ($articles as $article) {
    echo "ID: {$article->id}" . PHP_EOL;
    echo "Titre: {$article->titre}" . PHP_EOL;
    echo "Publié: " . ($article->is_published ? 'Oui' : 'Non') . PHP_EOL;
    echo "Premium: " . ($article->is_premium ? 'Oui' : 'Non') . PHP_EOL;
    echo "Document: " . ($article->document_path ?: 'Aucun') . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== Test Routes ===" . PHP_EOL;
echo "Routes articles disponibles:" . PHP_EOL;
echo "- Liste: /articles" . PHP_EOL;
echo "- Détail: /articles/{id}" . PHP_EOL;
echo "- Téléchargement: /articles/{id}/download" . PHP_EOL;
