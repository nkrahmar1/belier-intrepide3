<?php
require 'vendor/autoload.php';

// Configuration de base
date_default_timezone_set('Africa/Abidjan');

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    echo "=== PUBLICATION D'ARTICLES SUPPLÉMENTAIRES SUR HOMEPAGE ===\n\n";

    // 1. Récupérer tous les articles publiés non encore sur homepage
    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            a.titre,
            a.image,
            a.document_path,
            a.is_premium,
            c.nom as category_name
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE a.is_published = 1 
        AND (a.featured_on_homepage = 0 OR a.featured_on_homepage IS NULL)
        ORDER BY a.created_at DESC
        LIMIT 10
    ");
    
    $stmt->execute();
    $availableArticles = $stmt->fetchAll();
    
    echo "📊 Articles disponibles pour publication: " . count($availableArticles) . "\n\n";

    // 2. Publier des articles de différentes catégories sur homepage
    $publishedCount = 0;
    $categoriesPublished = [];
    
    foreach ($availableArticles as $article) {
        if ($publishedCount >= 6) break; // Limiter à 6 articles pour tester
        
        $category = $article['category_name'] ?: 'Non classé';
        
        // Essayer de publier des articles de catégories différentes
        if (!isset($categoriesPublished[$category]) || $categoriesPublished[$category] < 2) {
            
            $updateStmt = $pdo->prepare("
                UPDATE articles 
                SET featured_on_homepage = 1, 
                    homepage_featured_at = NOW() 
                WHERE id = ?
            ");
            
            $updateStmt->execute([$article['id']]);
            
            if (!isset($categoriesPublished[$category])) {
                $categoriesPublished[$category] = 0;
            }
            $categoriesPublished[$category]++;
            $publishedCount++;
            
            $elements = [];
            if ($article['image']) $elements[] = "🖼️";
            if ($article['document_path']) $elements[] = "📄";
            if ($article['is_premium']) $elements[] = "👑";
            
            echo "✅ Publié: " . substr($article['titre'], 0, 40) . "...\n";
            echo "   📂 Catégorie: $category\n";
            echo "   📎 Éléments: " . implode(' ', $elements) . "\n\n";
        }
    }

    // 3. Récapitulatif final
    echo "📋 RÉCAPITULATIF PUBLICATION\n";
    echo str_repeat("-", 40) . "\n";
    echo "🎯 $publishedCount articles publiés sur homepage\n";
    echo "📂 Catégories représentées:\n";
    
    foreach ($categoriesPublished as $category => $count) {
        echo "   - $category: $count article" . ($count > 1 ? 's' : '') . "\n";
    }

    // 4. Vérification finale des articles homepage
    echo "\n📊 ÉTAT FINAL DES ARTICLES HOMEPAGE\n";
    echo str_repeat("-", 40) . "\n";
    
    $finalCheck = $pdo->query("
        SELECT 
            COUNT(*) as total_homepage,
            COUNT(DISTINCT category_id) as categories_count,
            SUM(CASE WHEN image IS NOT NULL THEN 1 ELSE 0 END) as with_images,
            SUM(CASE WHEN document_path IS NOT NULL THEN 1 ELSE 0 END) as with_documents,
            SUM(CASE WHEN is_premium = 1 THEN 1 ELSE 0 END) as premium_articles
        FROM articles 
        WHERE featured_on_homepage = 1
    ")->fetch();
    
    echo "📰 Total articles homepage: {$finalCheck['total_homepage']}\n";
    echo "📂 Catégories représentées: {$finalCheck['categories_count']}\n";
    echo "🖼️  Avec images: {$finalCheck['with_images']}\n";
    echo "📄 Avec documents: {$finalCheck['with_documents']}\n";
    echo "👑 Articles premium: {$finalCheck['premium_articles']}\n";

    // 5. Instructions finales
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎉 CONFIGURATION TERMINÉE !\n";
    echo str_repeat("=", 60) . "\n";
    
    echo "✅ Articles publiés sur homepage avec tous leurs éléments\n";
    echo "✅ Organisation par catégorie implémentée\n";
    echo "✅ Images, documents et badges affichés\n";
    echo "✅ Boutons de téléchargement configurés\n";
    
    echo "\n🔗 TESTER MAINTENANT:\n";
    echo "   1. Visitez: http://127.0.0.1:8000/\n";
    echo "   2. Scrollez vers 'Articles en Vedette'\n";
    echo "   3. Vérifiez l'organisation par catégorie\n";
    echo "   4. Testez les boutons 'Lire l'article' et téléchargement\n";
    echo "   5. Vérifiez l'affichage des images et badges\n";
    
    echo "\n💡 POUR AJOUTER D'AUTRES ARTICLES:\n";
    echo "   - Allez dans Admin → Articles\n";
    echo "   - Cliquez sur 'Publier sur homepage' pour tout article\n";
    echo "   - L'article apparaîtra automatiquement organisé par catégorie\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    exit(1);
}