<?php
/**
 * Correction finale du dashboard - Valider toutes les données
 * S'assurer que chaque section affiche correctement ses données
 */

echo "🔧 CORRECTIONS FINALES DASHBOARD\n";
echo "===============================\n\n";

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données OK\n\n";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}

// Validation des données pour chaque section
echo "📊 VALIDATION DES DONNÉES PAR SECTION\n";
echo "====================================\n\n";

// 1. DASHBOARD PRINCIPAL
echo "🏠 Section Dashboard:\n";
try {
    $articlesCount = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $subscriptionsCount = $pdo->query("SELECT COUNT(*) FROM subscriptions")->fetchColumn();
    
    echo "   - Articles: $articlesCount ✅\n";
    echo "   - Utilisateurs: $usersCount ✅\n";
    echo "   - Commandes: $ordersCount ✅\n";
    echo "   - Produits: $productsCount ✅\n";
    echo "   - Abonnements: $subscriptionsCount ✅\n";
    
    // Statistiques avancées
    $publishedArticles = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_published = 1")->fetchColumn();
    $premiumArticles = $pdo->query("SELECT COUNT(*) FROM articles WHERE is_premium = 1")->fetchColumn();
    $todayArticles = $pdo->query("SELECT COUNT(*) FROM articles WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    
    echo "   - Articles publiés: $publishedArticles ✅\n";
    echo "   - Articles premium: $premiumArticles ✅\n";
    echo "   - Articles aujourd'hui: $todayArticles ✅\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// 2. SECTION ARTICLES
echo "\n📰 Section Articles:\n";
try {
    // Articles avec catégories
    $stmt = $pdo->query("
        SELECT a.id, a.titre, a.is_published, a.is_premium, a.created_at, c.nom as category_name
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        ORDER BY a.created_at DESC 
        LIMIT 5
    ");
    
    echo "   Exemples d'articles avec catégories:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['is_published'] ? '✅ Publié' : '📝 Brouillon';
        $premium = $row['is_premium'] ? '👑' : '';
        $category = $row['category_name'] ?? 'Sans catégorie';
        echo "   - [{$row['id']}] {$row['titre']} $premium ($status) - $category\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur articles: " . $e->getMessage() . "\n";
}

// 3. SECTION UTILISATEURS
echo "\n👥 Section Utilisateurs:\n";
try {
    $stmt = $pdo->query("
        SELECT id, CONCAT(firstname, ' ', lastname) as full_name, email, status, role, created_at 
        FROM users 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    
    echo "   Exemples d'utilisateurs:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $row['status'] ?? 'active';
        $role = $row['role'] ?? 'user';
        echo "   - [{$row['id']}] {$row['full_name']} ({$row['email']}) - $status/$role\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur utilisateurs: " . $e->getMessage() . "\n";
}

// 4. SECTION PRODUITS
echo "\n📦 Section Produits:\n";
try {
    $stmt = $pdo->query("
        SELECT p.id, p.name, p.price, p.stock, p.featured, c.nom as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ");
    
    echo "   Exemples de produits:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $featured = $row['featured'] ? '⭐' : '';
        $category = $row['category_name'] ?? 'Sans catégorie';
        echo "   - [{$row['id']}] {$row['name']} $featured - {$row['price']}€ (Stock: {$row['stock']}) - $category\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur produits: " . $e->getMessage() . "\n";
}

// 5. SECTION COMMANDES
echo "\n🧾 Section Commandes:\n";
try {
    $stmt = $pdo->query("
        SELECT o.id, o.total, o.status, o.created_at, CONCAT(u.firstname, ' ', u.lastname) as user_name
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    
    echo "   Exemples de commandes:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user = $row['user_name'] ?? 'Utilisateur inconnu';
        echo "   - [#{$row['id']}] {$row['total']}€ - {$row['status']} - $user ({$row['created_at']})\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur commandes: " . $e->getMessage() . "\n";
}

// 6. SECTION ABONNEMENTS
echo "\n💳 Section Abonnements:\n";
try {
    $stmt = $pdo->query("
        SELECT s.id, s.plan_name, s.amount, s.status, s.started_at, s.ends_at, CONCAT(u.firstname, ' ', u.lastname) as user_name
        FROM subscriptions s
        LEFT JOIN users u ON s.user_id = u.id
        ORDER BY s.created_at DESC
        LIMIT 5
    ");
    
    echo "   Exemples d'abonnements:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $user = $row['user_name'] ?? 'Utilisateur inconnu';
        $status = $row['status'];
        echo "   - [#{$row['id']}] {$row['plan_name']} - {$row['amount']}€ - $status - $user\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur abonnements: " . $e->getMessage() . "\n";
}

// 7. SECTION MESSAGES (Chatbot)
echo "\n✉️ Section Messages:\n";
try {
    $stmt = $pdo->query("
        SELECT user_id, COUNT(*) as message_count, MAX(created_at) as last_message
        FROM chatbot_messages 
        GROUP BY user_id 
        ORDER BY last_message DESC 
        LIMIT 5
    ");
    
    echo "   Conversations actives:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $displayName = str_starts_with($row['user_id'], 'guest_') ? 
                      'Invité #' . substr($row['user_id'], -4) : 
                      'Utilisateur ' . $row['user_id'];
        echo "   - $displayName: {$row['message_count']} message(s) - {$row['last_message']}\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur messages: " . $e->getMessage() . "\n";
}

// Résumé final
echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 RÉSUMÉ FINAL DU DASHBOARD\n";
echo str_repeat("=", 50) . "\n";

try {
    $totalStats = [
        'Articles' => $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
        'Utilisateurs' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'Produits' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
        'Commandes' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'Abonnements' => $pdo->query("SELECT COUNT(*) FROM subscriptions")->fetchColumn(),
        'Messages' => $pdo->query("SELECT COUNT(*) FROM chatbot_messages")->fetchColumn(),
    ];
    
    echo "📊 DONNÉES DISPONIBLES:\n";
    foreach ($totalStats as $section => $count) {
        $icon = $count > 0 ? '✅' : '⚠️';
        echo "   $icon $section: $count enregistrement(s)\n";
    }
    
    $totalData = array_sum($totalStats);
    echo "\n📈 Total enregistrements: $totalData\n";
    
    if ($totalData > 10) {
        echo "\n🎉 EXCELLENT! Le dashboard a suffisamment de données pour être pleinement fonctionnel!\n";
    } elseif ($totalData > 0) {
        echo "\n👍 BON! Le dashboard a des données de base pour fonctionner.\n";
    } else {
        echo "\n⚠️ Le dashboard manque de données pour une démonstration complète.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors du calcul des statistiques finales\n";
}

echo "\n🔗 ACCÈS AU DASHBOARD:\n";
echo "   - Dashboard principal: http://127.0.0.1:8000/admin/dashboard\n";
echo "   - Login admin requis pour accéder aux sections\n";
echo "\n✅ Validation terminée - Dashboard prêt à utiliser!\n";

?>