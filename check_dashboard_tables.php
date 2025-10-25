<?php
// Script pour vérifier la structure des tables du dashboard

$pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "🔍 VÉRIFICATION DES TABLES DU DASHBOARD\n";
echo "======================================\n\n";

// Tables importantes pour le dashboard
$tables = ['articles', 'users', 'orders', 'products', 'subscriptions', 'categories'];

foreach ($tables as $table) {
    echo "📋 Table: $table\n";
    echo str_repeat("-", 20) . "\n";
    
    try {
        // Vérifier si la table existe
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            echo "   ❌ Table $table n'existe pas\n\n";
            continue;
        }
        
        // Afficher la structure
        $stmt = $pdo->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   - {$row['Field']}: {$row['Type']}\n";
        }
        
        // Compter les enregistrements
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Total: $count enregistrement(s)\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Vérifications spécifiques pour les articles
echo "🔍 VÉRIFICATIONS SPÉCIFIQUES ARTICLES\n";
echo "====================================\n";

try {
    // Articles publiés
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_published = 1");
    $published = $stmt->fetchColumn();
    echo "✅ Articles publiés: $published\n";
    
    // Articles aujourd'hui
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE DATE(created_at) = CURDATE()");
    $today = $stmt->fetchColumn();
    echo "✅ Articles aujourd'hui: $today\n";
    
    // Articles premium
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_premium = 1");
    $premium = $stmt->fetchColumn();
    echo "✅ Articles premium: $premium\n";
    
    // Quelques articles exemple
    echo "\n📝 Exemples d'articles:\n";
    $stmt = $pdo->query("SELECT id, titre, is_published, created_at FROM articles LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['is_published'] ? 'Publié' : 'Brouillon';
        echo "   - [{$row['id']}] {$row['titre']} ($status) - {$row['created_at']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur articles: " . $e->getMessage() . "\n";
}

// Vérifications pour les utilisateurs
echo "\n👥 VÉRIFICATIONS UTILISATEURS\n";
echo "============================\n";

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $users = $stmt->fetchColumn();
    echo "✅ Total utilisateurs: $users\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()");
    $usersToday = $stmt->fetchColumn();
    echo "✅ Nouveaux utilisateurs aujourd'hui: $usersToday\n";
    
} catch (Exception $e) {
    echo "❌ Erreur utilisateurs: " . $e->getMessage() . "\n";
}

echo "\n✅ Vérification terminée!\n";
?>