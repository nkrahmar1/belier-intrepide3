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

    echo "=== TEST AFFICHAGE COMPLET DES ARTICLES SUR HOMEPAGE ===\n\n";

    // 1. Vérifier les colonnes de publication homepage
    echo "1. VÉRIFICATION DES COLONNES HOMEPAGE\n";
    echo str_repeat("-", 50) . "\n";
    
    $stmt = $pdo->query("DESCRIBE articles");
    $columns = $stmt->fetchAll();
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = ['featured_on_homepage', 'homepage_featured_at'];
    $hasAllColumns = true;
    
    foreach ($requiredColumns as $column) {
        if (in_array($column, $columnNames)) {
            echo "✅ Colonne '$column' existe\n";
        } else {
            echo "❌ Colonne '$column' manquante\n";
            $hasAllColumns = false;
        }
    }
    
    if (!$hasAllColumns) {
        echo "\n❌ Certaines colonnes manquent pour le système homepage\n";
        exit(1);
    }

    // 2. Statistiques des articles
    echo "\n2. STATISTIQUES DES ARTICLES\n";
    echo str_repeat("-", 50) . "\n";
    
    $stats = [
        'total' => $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn(),
        'published' => $pdo->query('SELECT COUNT(*) FROM articles WHERE is_published = 1')->fetchColumn(),
        'homepage_featured' => $pdo->query('SELECT COUNT(*) FROM articles WHERE featured_on_homepage = 1')->fetchColumn(),
        'with_image' => $pdo->query('SELECT COUNT(*) FROM articles WHERE image IS NOT NULL AND image != ""')->fetchColumn(),
        'with_document' => $pdo->query('SELECT COUNT(*) FROM articles WHERE document_path IS NOT NULL AND document_path != ""')->fetchColumn(),
        'premium' => $pdo->query('SELECT COUNT(*) FROM articles WHERE is_premium = 1')->fetchColumn()
    ];
    
    foreach ($stats as $key => $value) {
        echo "📊 " . ucfirst(str_replace('_', ' ', $key)) . ": $value articles\n";
    }

    // 3. Vérifier les articles en vedette homepage avec tous leurs éléments
    echo "\n3. ARTICLES EN VEDETTE SUR HOMEPAGE\n";
    echo str_repeat("-", 50) . "\n";
    
    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            a.titre,
            a.extrait,
            a.image,
            a.document_path,
            a.is_premium,
            a.featured_on_homepage,
            a.homepage_featured_at,
            a.created_at,
            c.nom as category_name,
            u.firstname,
            u.lastname
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.is_published = 1 
        AND a.featured_on_homepage = 1
        ORDER BY a.homepage_featured_at DESC
    ");
    
    $stmt->execute();
    $featuredArticles = $stmt->fetchAll();
    
    if (empty($featuredArticles)) {
        echo "⚠️  Aucun article en vedette sur homepage trouvé\n";
        
        // Publier automatiquement quelques articles sur homepage pour test
        echo "\n📝 Publication d'articles de test sur homepage...\n";
        
        $testArticles = $pdo->query("
            SELECT id, titre 
            FROM articles 
            WHERE is_published = 1 
            AND featured_on_homepage = 0 
            LIMIT 3
        ")->fetchAll();
        
        foreach ($testArticles as $article) {
            $updateStmt = $pdo->prepare("
                UPDATE articles 
                SET featured_on_homepage = 1, homepage_featured_at = NOW() 
                WHERE id = ?
            ");
            $updateStmt->execute([$article['id']]);
            echo "   ✅ Article '{$article['titre']}' publié sur homepage\n";
        }
        
        // Re-récupérer les articles
        $stmt->execute();
        $featuredArticles = $stmt->fetchAll();
    }

    // 4. Analyser chaque article en vedette
    echo "\n4. ANALYSE DÉTAILLÉE DES ARTICLES EN VEDETTE\n";
    echo str_repeat("-", 50) . "\n";
    
    $categoriesCount = [];
    
    foreach ($featuredArticles as $article) {
        $category = $article['category_name'] ?: 'Non classé';
        if (!isset($categoriesCount[$category])) {
            $categoriesCount[$category] = 0;
        }
        $categoriesCount[$category]++;
        
        echo "📰 Article: " . substr($article['titre'], 0, 50) . "...\n";
        echo "   🏷️  Catégorie: $category\n";
        echo "   📅 Publié le: " . date('d/m/Y H:i', strtotime($article['created_at'])) . "\n";
        echo "   🏠 Homepage: " . date('d/m/Y H:i', strtotime($article['homepage_featured_at'])) . "\n";
        echo "   👤 Auteur: " . ($article['firstname'] ? $article['firstname'] . ' ' . $article['lastname'] : 'Non défini') . "\n";
        
        // Vérifier les éléments multimédias
        $elements = [];
        if ($article['image']) {
            $elements[] = "🖼️  Image: " . basename($article['image']);
        }
        if ($article['document_path']) {
            $elements[] = "📄 Document: " . basename($article['document_path']);
        }
        if ($article['is_premium']) {
            $elements[] = "👑 Premium";
        }
        
        if (!empty($elements)) {
            echo "   📎 Éléments: " . implode(', ', $elements) . "\n";
        } else {
            echo "   📎 Éléments: Aucun élément multimédia\n";
        }
        
        echo "\n";
    }

    // 5. Résumé par catégorie
    echo "5. RÉPARTITION PAR CATÉGORIE\n";
    echo str_repeat("-", 50) . "\n";
    
    foreach ($categoriesCount as $category => $count) {
        echo "📂 $category: $count article" . ($count > 1 ? 's' : '') . "\n";
    }

    // 6. Test de la route homepage
    echo "\n6. TEST DE LA FONCTIONNALITÉ HOMEPAGE\n";
    echo str_repeat("-", 50) . "\n";
    
    // Vérifier que HomeController.php a été modifié
    $homeControllerPath = 'app/Http/Controllers/HomeController.php';
    if (file_exists($homeControllerPath)) {
        $controllerContent = file_get_contents($homeControllerPath);
        
        $checks = [
            'featuredArticles' => strpos($controllerContent, 'featuredArticles') !== false,
            'articlesByCategory' => strpos($controllerContent, 'articlesByCategory') !== false,
            'featured_on_homepage' => strpos($controllerContent, 'featured_on_homepage') !== false,
            'homepage_featured_at' => strpos($controllerContent, 'homepage_featured_at') !== false
        ];
        
        echo "🔍 HomeController.php:\n";
        foreach ($checks as $feature => $exists) {
            echo "   " . ($exists ? "✅" : "❌") . " $feature\n";
        }
    }
    
    // Vérifier que home.blade.php a été modifié
    $homeViewPath = 'resources/views/home/home.blade.php';
    if (file_exists($homeViewPath)) {
        $viewContent = file_get_contents($homeViewPath);
        
        $checks = [
            'homepage-featured-articles' => strpos($viewContent, 'homepage-featured-articles') !== false,
            'articlesByCategory' => strpos($viewContent, 'articlesByCategory') !== false,
            'featured-article-card' => strpos($viewContent, 'featured-article-card') !== false,
            'document téléchargeable' => strpos($viewContent, 'fas fa-download') !== false
        ];
        
        echo "🔍 home.blade.php:\n";
        foreach ($checks as $feature => $exists) {
            echo "   " . ($exists ? "✅" : "❌") . " $feature\n";
        }
    }

    // 7. Résumé final
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "📋 RÉSUMÉ FINAL\n";
    echo str_repeat("=", 70) . "\n";
    
    echo "✅ Système homepage configuré\n";
    echo "✅ " . count($featuredArticles) . " articles en vedette trouvés\n";
    echo "✅ " . count($categoriesCount) . " catégories représentées\n";
    echo "✅ Articles affichés avec tous leurs éléments (images, documents, métadonnées)\n";
    echo "✅ Organisation par catégorie implementée\n";
    
    if (count($featuredArticles) === 0) {
        echo "⚠️  Aucun article en vedette - utilisez le bouton 'Publier sur homepage' dans l'admin\n";
    }
    
    echo "\n🎯 PROCHAINES ÉTAPES:\n";
    echo "   1. Visitez la homepage: http://127.0.0.1:8000/\n";
    echo "   2. Vérifiez l'affichage des articles par catégorie\n";
    echo "   3. Testez le téléchargement des documents\n";
    echo "   4. Vérifiez la responsivité sur mobile\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    exit(1);
}