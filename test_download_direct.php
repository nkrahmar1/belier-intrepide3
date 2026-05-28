<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

echo "=== TEST DE TÉLÉCHARGEMENT DIRECT ===\n\n";

$article = Article::find(1);

if (!$article) {
    echo "❌ Article non trouvé\n";
    exit;
}

echo "📄 Article: {$article->titre}\n";
echo "📁 Document path: {$article->document_path}\n";

// Test 1: Vérifier si le fichier existe
echo "\n=== TEST 1: Existence du fichier ===\n";
if (Storage::exists($article->document_path)) {
    echo "✅ Le fichier existe dans Storage\n";
    echo "📊 Taille: " . Storage::size($article->document_path) . " bytes\n";
} else {
    echo "❌ Le fichier N'EXISTE PAS dans Storage\n";
    echo "🔍 Chemin cherché: " . storage_path('app/' . $article->document_path) . "\n";

    // Chercher le fichier manuellement
    $fullPath = storage_path('app/' . $article->document_path);
    if (file_exists($fullPath)) {
        echo "✅ Le fichier existe physiquement: $fullPath\n";
    } else {
        echo "❌ Le fichier n'existe pas physiquement: $fullPath\n";
    }
}

// Test 2: Lister tous les fichiers
echo "\n=== TEST 2: Listing des fichiers ===\n";
$files = Storage::files('documents');
echo "Fichiers trouvés:\n";
foreach ($files as $file) {
    echo "  - $file\n";
}

// Test 3: Tester avec le bon chemin
echo "\n=== TEST 3: Test direct ===\n";
$testPath = 'documents/test-download-document.txt';
if (Storage::exists($testPath)) {
    echo "✅ Le chemin direct fonctionne: $testPath\n";
    echo "📊 Contenu:\n";
    echo Storage::get($testPath);
} else {
    echo "❌ Le chemin direct ne fonctionne pas: $testPath\n";
}
