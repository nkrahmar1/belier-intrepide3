<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Test des relations Article...\n";

try {
    // Test simple sans relation
    $articles = \App\Models\Article::take(1)->get();
    echo "✅ Requête Article simple réussie\n";

    // Test avec relation category
    $articles = \App\Models\Article::with('category')->take(1)->get();
    echo "✅ Requête Article avec relation 'category' réussie\n";

    // Test d'accès à la relation
    foreach ($articles as $article) {
        if ($article->category) {
            echo "✅ Article {$article->id} a une catégorie: {$article->category->nom}\n";
        } else {
            echo "⚠️ Article {$article->id} n'a pas de catégorie\n";
        }
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
