<?php
/**
 * Script de diagnostic pour le dashboard admin
 * Teste la connexion DB et identifie les problèmes
 */

echo "=== DIAGNOSTIC DASHBOARD ADMIN ===\n\n";

// 1. Tester l'inclusion d'autoload
echo "1. Test du bootstrap Laravel...\n";
try {
    require 'bootstrap/app.php';
    echo "✓ Bootstrap OK\n";
} catch (Exception $e) {
    echo "✗ Erreur bootstrap: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Tester la connexion à la base de données
echo "\n2. Test connexion base de données...\n";
try {
    $connection = \Illuminate\Support\Facades\DB::connection();
    $pdo = $connection->getPdo();
    echo "✓ Connexion MySQL OK\n";
    
    // Vérifier les tables essentielles
    $tables = ['users', 'articles', 'subscriptions', 'messages', 'categories'];
    foreach ($tables as $table) {
        $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
        echo "  - Table '{$table}': " . ($exists ? "✓ Existe" : "✗ Manquante") . "\n";
    }
} catch (Exception $e) {
    echo "✗ Erreur DB: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Tester les modèles
echo "\n3. Test des modèles...\n";
try {
    $usersCount = \App\Models\User::count();
    echo "✓ Users: $usersCount\n";
} catch (Exception $e) {
    echo "✗ Erreur Users: " . $e->getMessage() . "\n";
}

try {
    $articlesCount = \App\Models\Article::count();
    echo "✓ Articles: $articlesCount\n";
} catch (Exception $e) {
    echo "✗ Erreur Articles: " . $e->getMessage() . "\n";
}

try {
    $subscriptionsCount = \App\Models\Subscription::count();
    echo "✓ Subscriptions: $subscriptionsCount\n";
} catch (Exception $e) {
    echo "✗ Erreur Subscriptions: " . $e->getMessage() . "\n";
}

// 4. Tester le contrôleur
echo "\n4. Test du contrôleur dashboard...\n";
try {
    $controller = new \App\Http\Controllers\Admin\AdminDashboardController();
    echo "✓ Contrôleur instancié\n";
} catch (Exception $e) {
    echo "✗ Erreur contrôleur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
