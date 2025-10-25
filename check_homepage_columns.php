<?php
/**
 * Script pour vérifier et ajouter les colonnes manquantes pour la gestion de la page d'accueil
 */

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔍 VÉRIFICATION DE LA STRUCTURE DE LA TABLE ARTICLES\n";
    echo "==================================================\n\n";
    
    // Vérifier les colonnes existantes
    $stmt = $pdo->query('DESCRIBE articles');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📋 Colonnes existantes:\n";
    $columnNames = [];
    foreach ($columns as $column) {
        $columnNames[] = $column['Field'];
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
    
    // Vérifier si les colonnes pour la page d'accueil existent
    $needsHomepageColumns = !in_array('featured_on_homepage', $columnNames) || !in_array('homepage_featured_at', $columnNames);
    
    if ($needsHomepageColumns) {
        echo "\n⚠️ Colonnes manquantes pour la gestion de la page d'accueil!\n";
        echo "🔧 Ajout des colonnes nécessaires...\n\n";
        
        // Ajouter featured_on_homepage si elle n'existe pas
        if (!in_array('featured_on_homepage', $columnNames)) {
            $pdo->exec("ALTER TABLE articles ADD COLUMN featured_on_homepage BOOLEAN DEFAULT FALSE");
            echo "✅ Colonne 'featured_on_homepage' ajoutée\n";
        }
        
        // Ajouter homepage_featured_at si elle n'existe pas
        if (!in_array('homepage_featured_at', $columnNames)) {
            $pdo->exec("ALTER TABLE articles ADD COLUMN homepage_featured_at TIMESTAMP NULL DEFAULT NULL");
            echo "✅ Colonne 'homepage_featured_at' ajoutée\n";
        }
        
        echo "\n🎉 Colonnes ajoutées avec succès!\n";
    } else {
        echo "\n✅ Toutes les colonnes nécessaires sont présentes!\n";
    }
    
    // Afficher les articles avec leur statut
    echo "\n📰 STATUT ACTUEL DES ARTICLES:\n";
    echo "============================\n";
    
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
        echo "Aucun article trouvé.\n";
    } else {
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
    
    echo "✅ Vérification terminée - Prêt pour la publication sur homepage!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>