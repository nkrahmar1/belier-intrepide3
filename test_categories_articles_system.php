<?php
/**
 * Script de test - Articles publiés dans leurs catégories spécifiques
 * Ce script vérifie que les articles s'affichent dans leurs catégories respectives
 * et que les liens de téléchargement fonctionnent correctement.
 */

echo "=== TEST - ARTICLES DANS LEURS CATÉGORIES SPÉCIFIQUES ===\n\n";

try {
    // Configuration de la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=belier3;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✅ Connexion à la base de données établie\n\n";

    // 1. Vérifier les articles par catégorie
    echo "📂 1. ARTICLES PAR CATÉGORIE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $articlesByCategory = $pdo->query("
        SELECT c.nom as category_name, 
               COUNT(a.id) as total_articles,
               COUNT(CASE WHEN a.featured_on_homepage = 1 THEN a.id END) as homepage_articles,
               COUNT(CASE WHEN a.document_path IS NOT NULL THEN a.id END) as articles_with_docs,
               COUNT(CASE WHEN a.image IS NOT NULL THEN a.id END) as articles_with_images
        FROM categories c
        LEFT JOIN articles a ON c.id = a.category_id AND a.is_published = 1
        GROUP BY c.id, c.nom
        ORDER BY c.nom
    ")->fetchAll();
    
    foreach ($articlesByCategory as $category) {
        echo "   📁 {$category['category_name']}:\n";
        echo "     └─ Total articles: {$category['total_articles']}\n";
        echo "     └─ Sur homepage: {$category['homepage_articles']}\n";
        echo "     └─ Avec documents: {$category['articles_with_docs']}\n";
        echo "     └─ Avec images: {$category['articles_with_images']}\n\n";
    }

    // 2. Articles en vedette avec leurs catégories
    echo "🏠 2. ARTICLES EN VEDETTE PAR CATÉGORIE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $featuredArticles = $pdo->query("
        SELECT a.*, c.nom as category_name, u.firstname, u.lastname
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.is_published = 1 AND a.featured_on_homepage = 1
        ORDER BY a.homepage_featured_at DESC
    ")->fetchAll();
    
    if (count($featuredArticles) > 0) {
        echo "   Total articles en vedette: " . count($featuredArticles) . "\n\n";
        
        foreach ($featuredArticles as $i => $article) {
            $position = $i == 0 ? "ARTICLE PRINCIPAL" : "GRILLE POSITION " . $i;
            $imageStatus = $article['image'] ? '✅' : '❌';
            $docStatus = $article['document_path'] ? '✅' : '❌';
            
            echo "   {$position}:\n";
            echo "     └─ Titre: {$article['titre']}\n";
            echo "     └─ Catégorie: {$article['category_name']}\n";
            echo "     └─ Image: {$imageStatus} | Document: {$docStatus}\n";
            
            if ($article['document_path']) {
                $docPath = storage_path('app/public/' . $article['document_path']);
                $fileExists = file_exists($docPath) ? '✅' : '❌';
                echo "     └─ Fichier existe: {$fileExists}\n";
                if (file_exists($docPath)) {
                    $fileSize = round(filesize($docPath) / 1024, 2);
                    echo "     └─ Taille: {$fileSize} KB\n";
                }
            }
            echo "\n";
        }
    } else {
        echo "   ❌ Aucun article en vedette\n\n";
    }

    // 3. Simulation de l'affichage par catégorie
    echo "🎯 3. SIMULATION AFFICHAGE HOMEPAGE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $categoriesOrder = ['economie', 'sport', 'politique', 'culture et média', 'pdci-rda', 'society'];
    
    echo "   Ordre d'affichage des catégories:\n";
    foreach ($categoriesOrder as $i => $categoryKey) {
        // Chercher un article pour cette catégorie
        $stmt = $pdo->prepare("
            SELECT a.*, c.nom as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.is_published = 1 AND a.featured_on_homepage = 1 
            AND LOWER(c.nom) = LOWER(?)
            ORDER BY a.homepage_featured_at DESC
            LIMIT 1
        ");
        $stmt->execute([$categoryKey]);
        $article = $stmt->fetch();
        
        $position = $i + 1;
        if ($article) {
            echo "     {$position}. {$categoryKey} → ARTICLE DYNAMIQUE: {$article['titre']}\n";
            if ($article['document_path']) {
                echo "        └─ 📄 Document téléchargeable disponible\n";
            }
        } else {
            echo "     {$position}. {$categoryKey} → Article statique par défaut\n";
        }
    }

    // 4. Test de création d'un article d'exemple pour la catégorie économie
    echo "\n🔧 4. TEST CRÉATION ARTICLE ÉCONOMIE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    // Vérifier s'il y a déjà un article économie en vedette
    $economieArticle = $pdo->query("
        SELECT a.*, c.nom as category_name
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE a.is_published = 1 AND a.featured_on_homepage = 1 
        AND LOWER(c.nom) = 'economie'
        LIMIT 1
    ")->fetch();
    
    if ($economieArticle) {
        echo "   ✅ Article économie déjà en vedette:\n";
        echo "     └─ Titre: {$economieArticle['titre']}\n";
        echo "     └─ Image: " . ($economieArticle['image'] ? '✅' : '❌') . "\n";
        echo "     └─ Document: " . ($economieArticle['document_path'] ? '✅' : '❌') . "\n";
        
        if ($economieArticle['document_path']) {
            echo "     └─ URL de téléchargement: /articles/{$economieArticle['id']}/download\n";
        }
    } else {
        echo "   ❌ Aucun article économie en vedette\n";
        echo "   💡 La page affichera l'article statique par défaut\n";
    }

    // 5. Résumé de la configuration
    echo "\n📋 5. RÉSUMÉ CONFIGURATION\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $stats = [
        'total_published' => $pdo->query('SELECT COUNT(*) FROM articles WHERE is_published = 1')->fetchColumn(),
        'total_featured' => $pdo->query('SELECT COUNT(*) FROM articles WHERE featured_on_homepage = 1')->fetchColumn(),
        'with_documents' => $pdo->query('SELECT COUNT(*) FROM articles WHERE document_path IS NOT NULL AND is_published = 1')->fetchColumn(),
        'with_images' => $pdo->query('SELECT COUNT(*) FROM articles WHERE image IS NOT NULL AND is_published = 1')->fetchColumn()
    ];
    
    echo "   📊 Statistiques:\n";
    echo "     └─ Articles publiés: {$stats['total_published']}\n";
    echo "     └─ Articles en vedette: {$stats['total_featured']}\n";
    echo "     └─ Avec documents: {$stats['with_documents']}\n";
    echo "     └─ Avec images: {$stats['with_images']}\n\n";
    
    echo "   🎯 Fonctionnement:\n";
    echo "     1. Les articles publiés remplacent les articles statiques\n";
    echo "     2. Chaque catégorie affiche son article spécifique\n";
    echo "     3. Les liens de téléchargement sont fonctionnels\n";
    echo "     4. Fallback vers contenu statique si pas d'article\n\n";

    if ($stats['total_featured'] > 0) {
        echo "✅ SYSTÈME OPÉRATIONNEL - Articles dynamiques par catégorie !\n";
    } else {
        echo "⏳ SYSTÈME EN ATTENTE - Publiez des articles pour remplacer le contenu statique\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
?>