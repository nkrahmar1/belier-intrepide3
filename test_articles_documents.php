<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

echo "=== DIAGNOSTIC DES DOCUMENTS ===\n\n";

$articles = Article::whereNotNull('document_path')->get();

if ($articles->isEmpty()) {
    echo "❌ Aucun article avec document trouvé dans la base de données.\n";
} else {
    echo "📄 Articles avec documents :\n";
    foreach ($articles as $article) {
        echo "- Article ID: {$article->id}\n";
        echo "  Titre: {$article->titre}\n";
        echo "  Document: {$article->document_path}\n";

        if (Storage::exists($article->document_path)) {
            echo "  ✅ Fichier existe\n";
        } else {
            echo "  ❌ Fichier MANQUANT\n";
        }
        echo "\n";
    }
}

echo "\n=== FICHIERS DANS LE DOSSIER DOCUMENTS ===\n";
$files = Storage::files('documents');
foreach ($files as $file) {
    echo "📁 {$file}\n";
}

echo "\n=== TEST DE TÉLÉCHARGEMENT ===\n";
if (!empty($articles)) {
    $firstArticle = $articles->first();
    echo "Test avec l'article: {$firstArticle->titre}\n";
    echo "Chemin: {$firstArticle->document_path}\n";

    if (Storage::exists($firstArticle->document_path)) {
        echo "✅ Le fichier est accessible pour téléchargement\n";
        echo "Taille: " . Storage::size($firstArticle->document_path) . " bytes\n";
    } else {
        echo "❌ Erreur: Le fichier n'existe pas\n";
    }
}
