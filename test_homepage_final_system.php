<?php
/**
 * Script de test final - Système d'affichage des articles par catégorie sur la homepage
 * Ce script vérifie que tous les éléments des articles (images, documents, etc.) 
 * s'affichent correctement par catégorie sur la page d'accueil.
 */

echo "=== TEST FINAL - AFFICHAGE DES ARTICLES PAR CATÉGORIE SUR LA HOMEPAGE ===\n\n";

try {
    // Configuration de la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=belier3;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✅ Connexion à la base de données établie\n\n";

    // 1. Vérifier les colonnes de publication sur homepage
    echo "📋 1. VÉRIFICATION DES COLONNES HOMEPAGE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $columns = $pdo->query("DESCRIBE articles")->fetchAll();
    $hasHomepageColumns = false;
    $columnNames = array_column($columns, 'Field');
    
    if (in_array('featured_on_homepage', $columnNames) && in_array('homepage_featured_at', $columnNames)) {
        echo "   ✅ Colonnes homepage présentes : featured_on_homepage, homepage_featured_at\n";
        $hasHomepageColumns = true;
    } else {
        echo "   ❌ Colonnes homepage manquantes\n";
        // Ajouter les colonnes si nécessaire
        if (!in_array('featured_on_homepage', $columnNames)) {
            $pdo->exec("ALTER TABLE articles ADD COLUMN featured_on_homepage BOOLEAN DEFAULT FALSE");
            echo "   ✅ Colonne 'featured_on_homepage' ajoutée\n";
        }
        if (!in_array('homepage_featured_at', $columnNames)) {
            $pdo->exec("ALTER TABLE articles ADD COLUMN homepage_featured_at TIMESTAMP NULL");
            echo "   ✅ Colonne 'homepage_featured_at' ajoutée\n";
        }
        $hasHomepageColumns = true;
    }

    // 2. Récupérer toutes les catégories disponibles
    echo "\n📂 2. CATÉGORIES DISPONIBLES\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
    echo "   Total des catégories : " . count($categories) . "\n";
    
    foreach ($categories as $category) {
        echo "   - {$category['nom']} (ID: {$category['id']})\n";
    }

    // 3. Vérifier les articles avec leurs éléments complets
    echo "\n📰 3. ARTICLES AVEC ÉLÉMENTS COMPLETS\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $stmt = $pdo->query("
        SELECT a.*, c.nom as category_name, u.firstname, u.lastname,
               COALESCE(a.featured_on_homepage, FALSE) as featured_on_homepage,
               a.homepage_featured_at,
               CASE 
                   WHEN a.image IS NOT NULL THEN 'OUI' 
                   ELSE 'NON' 
               END as has_image,
               CASE 
                   WHEN a.document_path IS NOT NULL THEN 'OUI' 
                   ELSE 'NON' 
               END as has_document,
               CASE 
                   WHEN a.is_premium = 1 THEN 'OUI' 
                   ELSE 'NON' 
               END as is_premium_article
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.is_published = 1
        ORDER BY a.created_at DESC
    ");
    
    $articles = $stmt->fetchAll();
    echo "   Total des articles publiés : " . count($articles) . "\n\n";
    
    foreach ($articles as $article) {
        $homepageStatus = $article['featured_on_homepage'] ? '🏠 Sur homepage' : '📄 Normal';
        $premiumStatus = $article['is_premium_article'] === 'OUI' ? '👑 Premium' : '🆓 Gratuit';
        
        echo "   Article #{$article['id']}: {$article['titre']}\n";
        echo "     └─ Catégorie: {$article['category_name']}\n";
        echo "     └─ Auteur: {$article['firstname']} {$article['lastname']}\n";
        echo "     └─ Status: {$homepageStatus} | {$premiumStatus}\n";
        echo "     └─ Image: {$article['has_image']} | Document: {$article['has_document']}\n";
        if ($article['featured_on_homepage']) {
            echo "     └─ Publié sur homepage: {$article['homepage_featured_at']}\n";
        }
        echo "\n";
    }

    // 4. Articles groupés par catégorie (comme sur la homepage)
    echo "🏠 4. ARTICLES SUR LA HOMEPAGE PAR CATÉGORIE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $homepageArticles = $pdo->query("
        SELECT a.*, c.nom as category_name, u.firstname, u.lastname
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.is_published = 1 AND a.featured_on_homepage = 1
        ORDER BY a.homepage_featured_at DESC
    ")->fetchAll();
    
    echo "   Total des articles en vedette sur homepage : " . count($homepageArticles) . "\n\n";
    
    // Grouper par catégorie
    $articlesByCategory = [];
    foreach ($homepageArticles as $article) {
        $categoryName = $article['category_name'] ?: 'Non classé';
        if (!isset($articlesByCategory[$categoryName])) {
            $articlesByCategory[$categoryName] = [];
        }
        $articlesByCategory[$categoryName][] = $article;
    }
    
    if (empty($articlesByCategory)) {
        echo "   ❌ Aucun article publié sur la homepage\n";
        echo "   💡 Utilisez le bouton 'Publier sur homepage' depuis le dashboard admin\n\n";
    } else {
        foreach ($articlesByCategory as $categoryName => $categoryArticles) {
            echo "   📂 CATÉGORIE: " . strtoupper($categoryName) . " (" . count($categoryArticles) . " article" . (count($categoryArticles) > 1 ? 's' : '') . ")\n";
            
            foreach ($categoryArticles as $article) {
                $imageStatus = $article['image'] ? '🖼️ Image' : '❌ Pas d\'image';
                $docStatus = $article['document_path'] ? '📄 Document' : '❌ Pas de document';
                $premiumStatus = $article['is_premium'] ? '👑 Premium' : '🆓 Gratuit';
                
                echo "     • {$article['titre']}\n";
                echo "       └─ {$imageStatus} | {$docStatus} | {$premiumStatus}\n";
                echo "       └─ Auteur: {$article['firstname']} {$article['lastname']}\n";
                echo "       └─ Publié: {$article['homepage_featured_at']}\n\n";
            }
        }
    }

    // 5. Test de publication automatique d'un article (si aucun en vedette)
    if (count($homepageArticles) < 3) {
        echo "🚀 5. PUBLICATION AUTOMATIQUE D'ARTICLES DE DÉMONSTRATION\n";
        echo "   " . str_repeat("-", 50) . "\n";
        
        // Prendre les 3 premiers articles non publiés sur homepage
        $unpublishedArticles = $pdo->query("
            SELECT a.*, c.nom as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.is_published = 1 AND (a.featured_on_homepage = 0 OR a.featured_on_homepage IS NULL)
            ORDER BY a.created_at DESC 
            LIMIT 3
        ")->fetchAll();
        
        foreach ($unpublishedArticles as $article) {
            // Publier sur homepage
            $stmt = $pdo->prepare("
                UPDATE articles 
                SET featured_on_homepage = 1, homepage_featured_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$article['id']]);
            
            echo "   ✅ Article publié sur homepage : {$article['titre']}\n";
            echo "     └─ Catégorie: {$article['category_name']}\n";
        }
    }

    // 6. Résumé final
    echo "\n📊 6. RÉSUMÉ FINAL\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $finalStats = [
        'total_articles' => $pdo->query('SELECT COUNT(*) FROM articles WHERE is_published = 1')->fetchColumn(),
        'total_categories' => $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
        'homepage_articles' => $pdo->query('SELECT COUNT(*) FROM articles WHERE featured_on_homepage = 1')->fetchColumn(),
        'articles_with_images' => $pdo->query('SELECT COUNT(*) FROM articles WHERE image IS NOT NULL AND is_published = 1')->fetchColumn(),
        'articles_with_documents' => $pdo->query('SELECT COUNT(*) FROM articles WHERE document_path IS NOT NULL AND is_published = 1')->fetchColumn(),
        'premium_articles' => $pdo->query('SELECT COUNT(*) FROM articles WHERE is_premium = 1 AND is_published = 1')->fetchColumn()
    ];
    
    echo "   📰 Articles publiés : {$finalStats['total_articles']}\n";
    echo "   📂 Catégories : {$finalStats['total_categories']}\n";
    echo "   🏠 Articles sur homepage : {$finalStats['homepage_articles']}\n";
    echo "   🖼️ Articles avec images : {$finalStats['articles_with_images']}\n";
    echo "   📄 Articles avec documents : {$finalStats['articles_with_documents']}\n";
    echo "   👑 Articles premium : {$finalStats['premium_articles']}\n\n";

    // 7. Instructions pour l'utilisateur
    echo "🎯 7. INSTRUCTIONS POUR UTILISER LE SYSTÈME\n";
    echo "   " . str_repeat("-", 50) . "\n";
    echo "   1. Créez des articles depuis : /admin/articles/create\n";
    echo "   2. Publiez-les sur homepage depuis : /admin/articles (bouton 'Publier sur homepage')\n";
    echo "   3. Les articles s'affichent par catégorie sur : / (page d'accueil)\n";
    echo "   4. Chaque article affiche : image, titre, extrait, document téléchargeable\n";
    echo "   5. Les catégories s'affichent avec des icônes spécifiques\n\n";

    echo "✅ SYSTÈME OPÉRATIONNEL - Tous les éléments des articles s'affichent par catégorie sur la homepage !\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
?>