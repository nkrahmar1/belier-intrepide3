<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;

// Mettre à jour l'article avec le nouveau document de test
$article = Article::find(1);
if ($article) {
    $article->document_path = 'documents/test-document.txt';
    $article->file_original_name = 'document-test.txt';
    $article->save();
    echo "✅ Article mis à jour avec le document qui fonctionne!\n";
    echo "Article: {$article->titre}\n";
    echo "Document: {$article->document_path}\n";
} else {
    echo "❌ Article non trouvé\n";
}

// Vérifier que le fichier existe
use Illuminate\Support\Facades\Storage;
if (Storage::exists('documents/test-document.txt')) {
    echo "✅ Le fichier de test existe\n";
    echo "Taille: " . Storage::size('documents/test-document.txt') . " bytes\n";
} else {
    echo "❌ Le fichier de test n'existe pas\n";
}
