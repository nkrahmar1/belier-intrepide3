<?php
/**
 * Script de diagnostic pour vérifier les IDs des articles
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC DES ARTICLES ===\n\n";

try {
    $articles = App\Models\Article::with('category')->latest()->take(20)->get();
    
    echo "Nombre total d'articles: " . App\Models\Article::count() . "\n";
    echo "Articles récupérés: " . $articles->count() . "\n\n";
    
    if ($articles->isEmpty()) {
        echo "⚠️ Aucun article trouvé dans la base de données.\n";
        exit;
    }
    
    echo "=== DÉTAILS DES 20 DERNIERS ARTICLES ===\n\n";
    
    $problemArticles = 0;
    
    foreach ($articles as $index => $article) {
        $status = "✅";
        $issues = [];
        
        // Vérifier l'ID
        if (empty($article->id) || is_null($article->id)) {
            $status = "❌";
            $issues[] = "ID MANQUANT ou NULL";
            $problemArticles++;
        }
        
        // Vérifier le titre
        if (empty($article->titre)) {
            $issues[] = "Titre manquant";
        }
        
        // Vérifier la catégorie
        if (empty($article->category_id)) {
            $issues[] = "Pas de catégorie";
        }
        
        echo "Article #" . ($index + 1) . " $status\n";
        echo "  ID: " . ($article->id ?? 'NULL') . "\n";
        echo "  Titre: " . ($article->titre ?? 'N/A') . "\n";
        echo "  Category ID: " . ($article->category_id ?? 'NULL') . "\n";
        echo "  Category: " . ($article->category->nom ?? 'N/A') . "\n";
        echo "  Publié: " . ($article->is_published ? 'Oui' : 'Non') . "\n";
        echo "  Created: " . ($article->created_at ? $article->created_at->format('d/m/Y H:i') : 'N/A') . "\n";
        
        if (!empty($issues)) {
            echo "  ⚠️ PROBLÈMES: " . implode(', ', $issues) . "\n";
        }
        
        echo "\n";
    }
    
    echo "=== RÉSUMÉ ===\n";
    echo "Articles avec problèmes d'ID: $problemArticles / " . $articles->count() . "\n";
    
    if ($problemArticles > 0) {
        echo "\n❌ ERREUR DÉTECTÉE: Certains articles n'ont pas d'ID valide!\n";
        echo "   Cela va causer l'erreur 'Missing required parameter for [Route: admin.articles.edit]'\n\n";
        echo "   SOLUTIONS:\n";
        echo "   1. Supprimer les articles défectueux\n";
        echo "   2. Vérifier que la colonne 'id' de la table 'articles' est AUTO_INCREMENT\n";
        echo "   3. Recréer la table si nécessaire\n";
    } else {
        echo "\n✅ Tous les articles ont des IDs valides.\n";
        echo "   L'erreur vient probablement d'ailleurs.\n";
    }
    
    // Vérifier la structure de la table
    echo "\n=== STRUCTURE DE LA TABLE 'articles' ===\n";
    $columns = DB::select("DESCRIBE articles");
    
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) ";
        if ($column->Key === 'PRI') {
            echo "[PRIMARY KEY] ";
        }
        if ($column->Extra === 'auto_increment') {
            echo "[AUTO_INCREMENT]";
        }
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
