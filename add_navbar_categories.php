<?php
/**
 * Ajout des catégories manquantes depuis la navbar
 */

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $navbarCategories = [
        ['nom' => 'Afrique', 'description' => 'Actualités africaines', 'slug' => 'afrique'],
        ['nom' => 'Sport', 'description' => 'Actualités sportives', 'slug' => 'sport'],
        ['nom' => 'Culture et média', 'description' => 'Culture et médias', 'slug' => 'culture-et-media'],
        ['nom' => 'Société', 'description' => 'Actualités de société', 'slug' => 'societe'],
        ['nom' => 'Economie', 'description' => 'Actualités économiques', 'slug' => 'economie'],
        ['nom' => 'Politique', 'description' => 'Actualités politiques', 'slug' => 'politique'],
        ['nom' => 'PDCI-RDA', 'description' => 'Parti PDCI-RDA', 'slug' => 'pdci-rda'],
        ['nom' => 'Dossiers', 'description' => 'Dossiers spéciaux', 'slug' => 'dossiers']
    ];
    
    echo "=== AJOUT DES CATÉGORIES NAVBAR ===\n";
    
    foreach ($navbarCategories as $category) {
        // Vérifier si la catégorie existe déjà
        $stmt = $pdo->prepare('SELECT id FROM categories WHERE nom = ? OR slug = ?');
        $stmt->execute([$category['nom'], $category['slug']]);
        
        if (!$stmt->fetch()) {
            // Créer la catégorie
            $stmt = $pdo->prepare('INSERT INTO categories (nom, description, slug, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
            $stmt->execute([$category['nom'], $category['description'], $category['slug']]);
            echo "✅ Catégorie '{$category['nom']}' ajoutée\n";
        } else {
            echo "ℹ️ Catégorie '{$category['nom']}' existe déjà\n";
        }
    }
    
    // Afficher toutes les catégories
    echo "\n=== CATÉGORIES FINALES ===\n";
    $stmt = $pdo->query('SELECT id, nom, description FROM categories ORDER BY nom');
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($categories as $cat) {
        echo "[{$cat['id']}] {$cat['nom']} - {$cat['description']}\n";
    }
    
    echo "\n✅ Total: " . count($categories) . " catégories disponibles\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>