<?php
/**
 * Vérification des catégories et amélioration du formulaire de création d'articles
 */

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📋 CATÉGORIES DISPONIBLES POUR LE FORMULAIRE\n";
    echo "===========================================\n\n";
    
    $stmt = $pdo->query('SELECT id, nom, description FROM categories ORDER BY nom');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($categories)) {
        echo "⚠️ Aucune catégorie trouvée!\n";
        echo "Création des catégories de base...\n\n";
        
        // Créer les catégories de base basées sur la navbar
        $defaultCategories = [
            ['nom' => 'Afrique', 'description' => 'Actualités africaines'],
            ['nom' => 'Sport', 'description' => 'Actualités sportives'],
            ['nom' => 'Culture et média', 'description' => 'Culture et médias'],
            ['nom' => 'Société', 'description' => 'Actualités de société'],
            ['nom' => 'Economie', 'description' => 'Actualités économiques'],
            ['nom' => 'Politique', 'description' => 'Actualités politiques'],
            ['nom' => 'PDCI-RDA', 'description' => 'Parti PDCI-RDA'],
            ['nom' => 'Dossiers', 'description' => 'Dossiers spéciaux']
        ];
        
        foreach ($defaultCategories as $category) {
            $stmt = $pdo->prepare("INSERT INTO categories (nom, description, slug, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $slug = strtolower(str_replace([' ', '&'], ['-', 'et'], $category['nom']));
            $stmt->execute([$category['nom'], $category['description'], $slug]);
            echo "✅ Catégorie '{$category['nom']}' créée\n";
        }
        
        // Recharger les catégories
        $stmt = $pdo->query('SELECT id, nom, description FROM categories ORDER BY nom');
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\n";
    }
    
    echo "📁 Catégories disponibles (" . count($categories) . "):\n";
    foreach ($categories as $cat) {
        echo "   - [{$cat['id']}] {$cat['nom']} ({$cat['description']})\n";
    }
    
    echo "\n🎯 AMÉLIORATIONS NÉCESSAIRES DU FORMULAIRE:\n";
    echo "==========================================\n";
    echo "1. ✅ Récupération des catégories de la navbar\n";
    echo "2. 🔧 Prévisualisation d'image à implémenter\n";
    echo "3. 🔧 Prévisualisation de fichier à implémenter\n";
    echo "4. 🔧 Upload avec validation en temps réel\n";
    
    echo "\n📝 Le formulaire sera amélioré avec:\n";
    echo "   - Liste des catégories dynamique depuis la DB\n";
    echo "   - Prévisualisation d'image avant upload\n";
    echo "   - Affichage du nom/type de fichier sélectionné\n";
    echo "   - Validation en temps réel\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
