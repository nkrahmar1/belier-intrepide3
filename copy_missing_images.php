<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COPIE DES IMAGES MANQUANTES ===\n\n";

// Récupérer tous les articles avec des images
$articles = App\Models\Article::whereNotNull('image')->get();

$copied = 0;
$alreadyExists = 0;
$notFound = 0;

foreach ($articles as $article) {
    $imageName = $article->image;
    $storagePath = storage_path('app/public/' . $imageName);
    
    // Si le fichier n'existe pas dans storage
    if (!file_exists($storagePath)) {
        // Chercher dans public/image avec juste le nom du fichier
        $publicPath = public_path('image/' . basename($imageName));
        
        if (file_exists($publicPath)) {
            // Créer le dossier si nécessaire
            $destDir = dirname($storagePath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            // Copier le fichier
            if (copy($publicPath, $storagePath)) {
                echo "✓ Copié: " . basename($imageName) . " -> storage/app/public/" . $imageName . "\n";
                $copied++;
            } else {
                echo "✗ Échec copie: " . basename($imageName) . "\n";
            }
        } else {
            echo "⚠ Introuvable: " . basename($imageName) . " (Article #" . $article->id . ")\n";
            $notFound++;
        }
    } else {
        $alreadyExists++;
    }
}

echo "\n=== RÉSUMÉ ===\n";
echo "Images déjà présentes: $alreadyExists\n";
echo "Images copiées: $copied\n";
echo "Images introuvables: $notFound\n";
echo "Total articles vérifiés: " . $articles->count() . "\n";
