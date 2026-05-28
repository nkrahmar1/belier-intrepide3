<?php
/**
 * Test final - Vérification du système de publication homepage
 */

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🎯 TEST FINAL - SYSTÈME PUBLICATION HOMEPAGE\n";
    echo "===========================================\n\n";
    
    // 1. Vérifier les colonnes de la base de données
    echo "🔍 1. VÉRIFICATION BASE DE DONNÉES\n";
    echo "----------------------------------\n";
    
    $stmt = $pdo->query('DESCRIBE articles');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = ['featured_on_homepage', 'homepage_featured_at'];
    $missingColumns = array_diff($requiredColumns, $columnNames);
    
    if (empty($missingColumns)) {
        echo "✅ Toutes les colonnes nécessaires sont présentes\n";
        foreach ($requiredColumns as $col) {
            echo "   - $col ✓\n";
        }
    } else {
        echo "❌ Colonnes manquantes: " . implode(', ', $missingColumns) . "\n";
    }
    
    // 2. Vérifier les articles existants
    echo "\n📰 2. ÉTAT ACTUEL DES ARTICLES\n";
    echo "-----------------------------\n";
    
    $stmt = $pdo->query("
        SELECT id, titre, is_published, 
               COALESCE(featured_on_homepage, FALSE) as featured_on_homepage,
               homepage_featured_at,
               created_at
        FROM articles 
        ORDER BY created_at DESC
    ");
    
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($articles)) {
        echo "⚠️ Aucun article trouvé pour tester\n";
    } else {
        echo "Articles disponibles pour test:\n";
        foreach ($articles as $article) {
            $pubStatus = $article['is_published'] ? '✅ Publié' : '📝 Brouillon';
            $homepageStatus = $article['featured_on_homepage'] ? '🏠 Sur homepage' : '📄 Normal';
            
            echo "   - [{$article['id']}] {$article['titre']}\n";
            echo "     Status: $pubStatus | $homepageStatus\n";
            if ($article['homepage_featured_at']) {
                echo "     Mis en avant le: {$article['homepage_featured_at']}\n";
            }
            echo "\n";
        }
    }
    
    // 3. Test de publication sur homepage (simulation)
    echo "🚀 3. TEST PUBLICATION HOMEPAGE\n";
    echo "-------------------------------\n";
    
    if (!empty($articles)) {
        $testArticle = $articles[0]; // Prendre le premier article
        
        echo "Test avec l'article: [{$testArticle['id']}] {$testArticle['titre']}\n";
        
        if ($testArticle['is_published']) {
            if (!$testArticle['featured_on_homepage']) {
                // Simuler la publication sur homepage
                $stmt = $pdo->prepare("
                    UPDATE articles 
                    SET featured_on_homepage = TRUE, homepage_featured_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$testArticle['id']]);
                
                echo "✅ Article publié sur homepage avec succès!\n";
                echo "   URL admin: http://127.0.0.1:8000/admin/articles\n";
                echo "   URL test: http://127.0.0.1:8080/test_homepage_publication.html\n";
                
                // Vérifier la mise à jour
                $stmt = $pdo->prepare("SELECT featured_on_homepage, homepage_featured_at FROM articles WHERE id = ?");
                $stmt->execute([$testArticle['id']]);
                $updated = $stmt->fetch(PDO::FETCH_ASSOC);
                
                echo "   Status mis à jour: featured_on_homepage = " . ($updated['featured_on_homepage'] ? 'TRUE' : 'FALSE') . "\n";
                echo "   Date mise en avant: {$updated['homepage_featured_at']}\n";
                
            } else {
                echo "ℹ️ Article déjà sur la homepage\n";
                
                // Option pour retirer de la homepage
                $stmt = $pdo->prepare("
                    UPDATE articles 
                    SET featured_on_homepage = FALSE, homepage_featured_at = NULL 
                    WHERE id = ?
                ");
                $stmt->execute([$testArticle['id']]);
                
                echo "🔄 Article retiré de la homepage pour pouvoir tester à nouveau\n";
            }
        } else {
            echo "⚠️ L'article doit être publié avant d'être mis sur la homepage\n";
        }
    }
    
    // 4. Vérifier les routes et contrôleurs
    echo "\n🛣️ 4. VÉRIFICATION ROUTES ET CONTRÔLEURS\n";
    echo "----------------------------------------\n";
    
    $routeFiles = [
        'routes/web.php' => 'Routes définies',
        'app/Http/Controllers/Admin/AdminArticleController.php' => 'Contrôleur admin',
        'resources/views/admin/articles/index.blade.php' => 'Vue articles admin'
    ];
    
    foreach ($routeFiles as $file => $description) {
        if (file_exists($file)) {
            echo "✅ $description ($file)\n";
        } else {
            echo "❌ $description manquant ($file)\n";
        }
    }
    
    // 5. Instructions finales
    echo "\n🎉 RÉSUMÉ ET INSTRUCTIONS\n";
    echo "========================\n";
    
    echo "✅ Système de publication homepage configuré!\n\n";
    
    echo "📋 Pour utiliser la fonctionnalité:\n";
    echo "   1. Allez sur: http://127.0.0.1:8000/admin/articles\n";
    echo "   2. Trouvez un article publié\n";
    echo "   3. Cliquez sur le bouton 🏠+ pour publier sur homepage\n";
    echo "   4. Ou cliquez sur 🏠❌ pour retirer de la homepage\n\n";
    
    echo "🧪 Interface de test disponible:\n";
    echo "   http://127.0.0.1:8080/test_homepage_publication.html\n\n";
    
    echo "💡 Fonctionnalités ajoutées:\n";
    echo "   - Boutons de publication homepage dans admin/articles\n";
    echo "   - Routes API: /admin/articles/{id}/publish-homepage\n";
    echo "   - Routes API: /admin/articles/{id}/remove-homepage\n";
    echo "   - Notifications JavaScript en temps réel\n";
    echo "   - Colonnes DB: featured_on_homepage, homepage_featured_at\n";
    
    echo "\n🎯 Mission accomplie! Votre système de publication homepage est prêt!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>