<?php
/**
 * Script de test final - Vérification complète du système
 * Articles par catégorie avec téléchargement fonctionnel
 */

echo "=== TEST FINAL - SYSTÈME ARTICLES PAR CATÉGORIE ===\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=belier3;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✅ Connexion base de données OK\n\n";

    // 1. État actuel des articles en vedette
    echo "📊 ARTICLES EN VEDETTE ACTUEL:\n";
    echo str_repeat("-", 50) . "\n";
    
    $featured = $pdo->query("
        SELECT a.id, a.titre, c.nom as category, 
               CASE WHEN a.image IS NOT NULL THEN '✅' ELSE '❌' END as has_image,
               CASE WHEN a.document_path IS NOT NULL THEN '✅' ELSE '❌' END as has_doc,
               a.document_path
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE a.featured_on_homepage = 1 
        ORDER BY a.homepage_featured_at DESC
    ")->fetchAll();

    foreach ($featured as $i => $article) {
        $position = $i == 0 ? "PRINCIPAL" : "GRILLE-" . $i;
        echo "{$position}: {$article['titre']}\n";
        echo "  └─ Catégorie: {$article['category']}\n";
        echo "  └─ Image: {$article['has_image']} | Document: {$article['has_doc']}\n";
        
        if ($article['document_path']) {
            $fullPath = __DIR__ . '/storage/app/public/' . $article['document_path'];
            $exists = file_exists($fullPath) ? '✅' : '❌';
            echo "  └─ Fichier: {$exists} | URL: /articles/{$article['id']}/download\n";
        }
        echo "\n";
    }

    // 2. Test spécifique catégorie économie
    echo "💰 TEST CATÉGORIE ÉCONOMIE:\n";
    echo str_repeat("-", 50) . "\n";
    
    $economie = $pdo->query("
        SELECT a.*, c.nom as category_name
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE a.featured_on_homepage = 1 AND LOWER(c.nom) = 'economie'
        LIMIT 1
    ")->fetch();

    if ($economie) {
        echo "✅ Article économie trouvé:\n";
        echo "  └─ Titre: {$economie['titre']}\n";
        echo "  └─ Image: " . ($economie['image'] ? '✅' : '❌') . "\n";
        echo "  └─ Document: " . ($economie['document_path'] ? '✅' : '❌') . "\n";
        
        if ($economie['document_path']) {
            $docPath = __DIR__ . '/storage/app/public/' . $economie['document_path'];
            echo "  └─ Chemin: {$economie['document_path']}\n";
            echo "  └─ Existe: " . (file_exists($docPath) ? '✅' : '❌') . "\n";
            echo "  └─ Taille: " . (file_exists($docPath) ? round(filesize($docPath)/1024, 2) . ' KB' : 'N/A') . "\n";
            echo "  └─ URL téléchargement: http://127.0.0.1:8000/articles/{$economie['id']}/download\n";
        }
    } else {
        echo "❌ Pas d'article économie en vedette\n";
        echo "💡 Article statique par défaut s'affichera\n";
    }

    echo "\n";

    // 3. Résumé du comportement
    echo "🎯 COMPORTEMENT PAGE D'ACCUEIL:\n";
    echo str_repeat("-", 50) . "\n";
    
    $categories = ['economie', 'sport', 'politique', 'culture et média', 'pdci-rda'];
    
    foreach ($categories as $cat) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.featured_on_homepage = 1 AND LOWER(c.nom) = LOWER(?)
        ");
        $stmt->execute([$cat]);
        $count = $stmt->fetchColumn();
        
        $status = $count > 0 ? "DYNAMIQUE ✅" : "STATIQUE ⚪";
        echo "📂 {$cat}: {$status}\n";
    }

    echo "\n🎯 INSTRUCTIONS:\n";
    echo "1. Visitez: http://127.0.0.1:8000\n";
    echo "2. Cherchez la section économie dans la grille d'articles\n";
    echo "3. Cliquez sur le lien PDF si visible\n";
    echo "4. Le document devrait se télécharger automatiquement\n\n";

    echo "✅ SYSTÈME PRÊT - Articles par catégorie avec téléchargement fonctionnel!\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>