<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

echo "=== TEST DE TÉLÉCHARGEMENT EN DIRECT ===\n\n";

// Créer un fichier de test propre
$testContent = "DOCUMENT DE TEST POUR TÉLÉCHARGEMENT\n\nCe fichier teste le système de téléchargement.\nSi vous le voyez, le téléchargement fonctionne parfaitement !\n\nDate: " . date('Y-m-d H:i:s');

// Utiliser put pour créer le fichier
Storage::put('documents/download-test.txt', $testContent);

echo "✅ Fichier de test créé avec Storage::put\n";

// Vérifier qu'il existe
if (Storage::exists('documents/download-test.txt')) {
    echo "✅ Le fichier existe dans Storage\n";
    echo "Taille: " . Storage::size('documents/download-test.txt') . " bytes\n";
} else {
    echo "❌ Le fichier n'existe pas dans Storage\n";
}

// Mettre à jour l'article avec ce nouveau fichier
$article = Article::find(2); // L'article avec CNI.pdf
if ($article) {
    $article->document_path = 'documents/download-test.txt';
    $article->file_original_name = 'Document-Test.txt';
    $article->save();
    
    echo "\n✅ Article '{$article->titre}' mis à jour\n";
    echo "Nouveau document: {$article->document_path}\n";
} else {
    echo "\n❌ Article non trouvé\n";
}

echo "\n=== TEST DIRECT DU TÉLÉCHARGEMENT ===\n";
echo "URL de test: http://127.0.0.1:8000/articles/{$article->id}/download\n";
echo "Connectez-vous d'abord, puis testez cette URL\n";
