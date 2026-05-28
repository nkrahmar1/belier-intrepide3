<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DES ROUTES ET DONNÉES ===\n\n";

try {
    // Test des articles
    $articles = \App\Models\Article::count();
    echo "📰 Articles total: $articles\n";
    
    $published = \App\Models\Article::where('is_published', true)->count();
    echo "✅ Articles publiés: $published\n";
    
    // Test des catégories
    $categories = \App\Models\Category::count();
    echo "📂 Catégories: $categories\n";
    
    // Test des utilisateurs
    $users = \App\Models\User::count();
    echo "👥 Utilisateurs: $users\n\n";
    
    // Lister quelques articles
    echo "=== ARTICLES RÉCENTS ===\n";
    $recentArticles = \App\Models\Article::with('category')->latest()->take(3)->get();
    
    foreach ($recentArticles as $article) {
        $category = $article->category ? $article->category->nom : 'Sans catégorie';
        $status = $article->is_published ? 'Publié' : 'Brouillon';
        echo "- {$article->titre} [{$category}] - {$status}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
