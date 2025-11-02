<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DES IMAGES DES ARTICLES ===\n\n";

// Récupérer quelques articles
$articles = App\Models\Article::select('id', 'titre', 'image', 'category_id')
    ->with('category:id,nom')
    ->take(10)
    ->get();

echo "Nombre d'articles trouvés: " . $articles->count() . "\n\n";

foreach ($articles as $article) {
    echo "Article #" . $article->id . " - " . $article->titre . "\n";
    echo "  Catégorie: " . ($article->category ? $article->category->nom : 'Aucune') . "\n";
    echo "  Chemin image BD: " . ($article->image ?: 'NULL') . "\n";
    
    if ($article->image) {
        $fullPath = storage_path('app/public/' . $article->image);
        echo "  Chemin complet: " . $fullPath . "\n";
        echo "  Fichier existe: " . (file_exists($fullPath) ? "OUI ✓" : "NON ✗") . "\n";
        echo "  URL publique: " . asset('storage/' . $article->image) . "\n";
    }
    echo "\n";
}

echo "\n=== VÉRIFICATION DES DOSSIERS ===\n\n";
echo "storage/app/public existe: " . (is_dir(storage_path('app/public')) ? "OUI ✓" : "NON ✗") . "\n";
echo "public/storage existe: " . (is_dir(public_path('storage')) ? "OUI ✓" : "NON ✗") . "\n";
echo "public/image existe: " . (is_dir(public_path('image')) ? "OUI ✓" : "NON ✗") . "\n";

if (is_dir(storage_path('app/public'))) {
    $files = glob(storage_path('app/public/*'));
    echo "\nFichiers dans storage/app/public: " . count($files) . "\n";
    if (count($files) > 0) {
        echo "Exemples:\n";
        foreach (array_slice($files, 0, 5) as $file) {
            echo "  - " . basename($file) . "\n";
        }
    }
}

if (is_dir(public_path('image'))) {
    $files = glob(public_path('image/*'));
    echo "\nFichiers dans public/image: " . count($files) . "\n";
    if (count($files) > 0) {
        echo "Exemples:\n";
        foreach (array_slice($files, 0, 5) as $file) {
            echo "  - " . basename($file) . "\n";
        }
    }
}
