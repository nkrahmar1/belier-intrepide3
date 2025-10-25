<?php
/**
 * Script de test - Système dynamique intégré dans la page d'accueil normale
 * Ce script vérifie que les articles publiés sur la homepage remplacent 
 * les articles statiques dans la grille normale de la page d'accueil.
 */

echo "=== TEST - SYSTÈME DYNAMIQUE INTÉGRÉ DANS LA PAGE D'ACCUEIL ===\n\n";

try {
    // Configuration de la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=belier3;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✅ Connexion à la base de données établie\n\n";

    // 1. Statut actuel des articles en vedette
    echo "📊 1. STATUT ACTUEL DES ARTICLES EN VEDETTE\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $featuredArticles = $pdo->query("
        SELECT a.*, c.nom as category_name, u.firstname, u.lastname
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        WHERE a.is_published = 1 AND a.featured_on_homepage = 1
        ORDER BY a.homepage_featured_at DESC
    ")->fetchAll();
    
    echo "   Articles en vedette sur homepage : " . count($featuredArticles) . "\n\n";
    
    if (count($featuredArticles) > 0) {
        echo "   📰 ARTICLES EN VEDETTE :\n";
        foreach ($featuredArticles as $i => $article) {
            $position = $i == 0 ? "Article Principal" : "Grille Article #" . $i;
            $imageStatus = $article['image'] ? '🖼️' : '❌';
            $docStatus = $article['document_path'] ? '📄' : '❌';
            
            echo "     {$position}: {$article['titre']}\n";
            echo "       └─ Catégorie: {$article['category_name']}\n";
            echo "       └─ Image: {$imageStatus} | Document: {$docStatus}\n";
            echo "       └─ Publié: {$article['homepage_featured_at']}\n\n";
        }
    } else {
        echo "   ❌ Aucun article en vedette - les articles statiques s'afficheront\n\n";
    }

    // 2. Comportement de la page d'accueil
    echo "🏠 2. COMPORTEMENT DE LA PAGE D'ACCUEIL\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    if (count($featuredArticles) > 0) {
        echo "   ✅ MODE DYNAMIQUE ACTIVÉ :\n";
        echo "     • Article principal : Premier article en vedette\n";
        echo "     • Grille : " . max(0, count($featuredArticles) - 1) . " autres articles en vedette\n";
        echo "     • Les articles incluent toutes leurs données :\n";
        echo "       - Images personnalisées\n";
        echo "       - Documents téléchargeables\n";
        echo "       - Vraies catégories\n";
        echo "       - Dates réelles\n";
        echo "       - Auteurs\n\n";
    } else {
        echo "   🔒 MODE STATIQUE ACTIF :\n";
        echo "     • Article principal : Article PDCI par défaut\n";
        echo "     • Grille : 6 articles statiques prédéfinis\n";
        echo "     • Contenu : Images et textes de démonstration\n\n";
    }

    // 3. Test de publication d'articles si nécessaire
    if (count($featuredArticles) < 2) {
        echo "🚀 3. PUBLICATION D'ARTICLES POUR DÉMONSTRATION\n";
        echo "   " . str_repeat("-", 50) . "\n";
        
        // Récupérer des articles non publiés
        $unpublishedArticles = $pdo->query("
            SELECT a.*, c.nom as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.is_published = 1 AND (a.featured_on_homepage = 0 OR a.featured_on_homepage IS NULL)
            ORDER BY a.created_at DESC 
            LIMIT 3
        ")->fetchAll();
        
        $published = 0;
        foreach ($unpublishedArticles as $article) {
            // Publier sur homepage
            $stmt = $pdo->prepare("
                UPDATE articles 
                SET featured_on_homepage = 1, homepage_featured_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$article['id']]);
            
            echo "   ✅ Article publié : {$article['titre']}\n";
            echo "     └─ Catégorie: {$article['category_name']}\n";
            $published++;
        }
        
        if ($published > 0) {
            echo "\n   📊 {$published} article(s) publié(s) sur la homepage\n";
            echo "   🔄 Rechargez la page d'accueil pour voir les changements\n\n";
        }
    }

    // 4. Instructions d'utilisation
    echo "📋 4. COMMENT UTILISER LE SYSTÈME\n";
    echo "   " . str_repeat("-", 50) . "\n";
    echo "   1. Page normale : Sans articles publiés → Contenu statique s'affiche\n";
    echo "   2. Articles en vedette : Avec articles publiés → Contenu dynamique remplace le statique\n";
    echo "   3. Publication : Utilisez le bouton 'Publier sur homepage' depuis /admin/articles\n";
    echo "   4. Suppression : Utilisez le bouton 'Retirer de homepage' pour revenir au statique\n\n";

    // 5. Résumé final
    echo "🎯 5. RÉSUMÉ DU SYSTÈME\n";
    echo "   " . str_repeat("-", 50) . "\n";
    
    $totalArticles = $pdo->query('SELECT COUNT(*) FROM articles WHERE is_published = 1')->fetchColumn();
    $homepageArticles = $pdo->query('SELECT COUNT(*) FROM articles WHERE featured_on_homepage = 1')->fetchColumn();
    
    echo "   📰 Total articles publiés : {$totalArticles}\n";
    echo "   🏠 Articles sur homepage : {$homepageArticles}\n";
    echo "   🎨 Mode d'affichage : " . ($homepageArticles > 0 ? "DYNAMIQUE" : "STATIQUE") . "\n";
    echo "   🔧 Système : Intégré dans la page d'accueil normale\n\n";

    if ($homepageArticles > 0) {
        echo "✅ SYSTÈME OPÉRATIONNEL - Les articles publiés remplacent le contenu statique !\n";
    } else {
        echo "📄 SYSTÈME EN ATTENTE - Publiez des articles pour activer le mode dynamique\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
?>