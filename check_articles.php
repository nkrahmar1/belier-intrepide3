<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Vérifier les articles
echo "=== ARTICLES DANS LA BASE ===\n";
$articles = \App\Models\Article::all();
echo "Nombre d'articles total: " . $articles->count() . "\n\n";

foreach ($articles as $article) {
    echo "ID: {$article->id}\n";
    echo "Titre: {$article->titre}\n";
    echo "Slug: {$article->slug}\n";
    echo "Publié: " . ($article->is_published ? 'OUI' : 'NON') . "\n";
    echo "Category ID: {$article->category_id}\n";
    echo "User ID: {$article->user_id}\n";
    echo "Date publication: {$article->published_at}\n";
    echo "---\n";
}

// Vérifier les catégories
echo "\n=== CATÉGORIES DANS LA BASE ===\n";
$categories = \App\Models\Category::all();
echo "Nombre de catégories: " . $categories->count() . "\n\n";

foreach ($categories as $category) {
    echo "ID: {$category->id}\n";
    echo "Nom: {$category->nom}\n";
    echo "Slug: {$category->slug}\n";
    echo "---\n";
}

// Vérifier les articles publiés
echo "\n=== ARTICLES PUBLIÉS ===\n";
$publishedArticles = \App\Models\Article::where('is_published', 1)->get();
echo "Nombre d'articles publiés: " . $publishedArticles->count() . "\n";

foreach ($publishedArticles as $article) {
    echo "- {$article->titre} (ID: {$article->id})\n";
}
