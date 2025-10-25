<?php

// Script de diagnostic complet pour le dashboard admin

echo "=== DIAGNOSTIC DASHBOARD ADMIN ===\n\n";

// 1. Vérifier les routes
echo "1. VÉRIFICATION DES ROUTES\n";
echo "- Routes admin définies: ";
try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';

    // Compter manuellement les routes admin
    $routeCount = 0;
    $allRoutes = \Illuminate\Support\Facades\Route::getRoutes();
    foreach ($allRoutes->getRoutes() as $route) {
        if (strpos($route->uri(), 'admin/') === 0) {
            $routeCount++;
        }
    }
    echo $routeCount . " routes trouvées\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

// 2. Vérifier la base de données
echo "\n2. VÉRIFICATION BASE DE DONNÉES\n";
try {
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    $articleCount = \Illuminate\Support\Facades\DB::table('articles')->count();
    $categoryCount = \Illuminate\Support\Facades\DB::table('categories')->count();

    echo "- Utilisateurs: $userCount\n";
    echo "- Articles: $articleCount\n";
    echo "- Catégories: $categoryCount\n";
} catch (Exception $e) {
    echo "Erreur DB: " . $e->getMessage() . "\n";
}

// 3. Vérifier les fichiers de vue
echo "\n3. VÉRIFICATION FICHIERS VUES\n";
$dashboardFile = 'resources/views/admin/dashboard.blade.php';
$layoutFile = 'resources/views/layouts/admin.blade.php';

echo "- Dashboard admin: " . (file_exists($dashboardFile) ? "✓ Existe (" . number_format(filesize($dashboardFile)) . " bytes)" : "✗ Manquant") . "\n";
echo "- Layout admin: " . (file_exists($layoutFile) ? "✓ Existe (" . number_format(filesize($layoutFile)) . " bytes)" : "✗ Manquant") . "\n";

// 4. Analyser la structure du dashboard
if (file_exists($dashboardFile)) {
    echo "\n4. ANALYSE STRUCTURE DASHBOARD\n";
    $content = file_get_contents($dashboardFile);
    $lines = explode("\n", $content);

    echo "- Nombre de lignes: " . count($lines) . "\n";
    echo "- @extends présent: " . (strpos($content, '@extends') !== false ? "✓" : "✗") . "\n";
    echo "- @section présent: " . (strpos($content, '@section') !== false ? "✓" : "✗") . "\n";
    echo "- @endsection présent: " . (strpos($content, '@endsection') !== false ? "✓" : "✗") . "\n";

    // Compter les occurrences de balises importantes
    echo "- Nombre de <div>: " . substr_count($content, '<div') . "\n";
    echo "- Nombre de </div>: " . substr_count($content, '</div>') . "\n";
    echo "- Scripts JavaScript: " . substr_count($content, '<script') . "\n";
    echo "- Appels fetch(): " . substr_count($content, 'fetch(') . "\n";

    // Vérifier les erreurs courantes
    if (substr_count($content, '<div') !== substr_count($content, '</div>')) {
        echo "⚠️  ATTENTION: Nombre de <div> et </div> ne correspond pas!\n";
    }

    // Rechercher les appels problématiques
    if (strpos($content, '/admin/articles/toggle-publish') !== false) {
        echo "⚠️  Appel toggle-publish détecté dans le dashboard\n";
    }
}

// 5. Tester les contrôleurs
echo "\n5. VÉRIFICATION CONTRÔLEURS\n";
$adminDashboardController = 'app/Http/Controllers/Admin/AdminDashboardController.php';
$userController = 'app/Http/Controllers/Admin/UserController.php';
$articleController = 'app/Http/Controllers/Admin/ArticleController.php';

echo "- AdminDashboardController: " . (file_exists($adminDashboardController) ? "✓" : "✗") . "\n";
echo "- UserController: " . (file_exists($userController) ? "✓" : "✗") . "\n";
echo "- ArticleController: " . (file_exists($articleController) ? "✓" : "✗") . "\n";

// 6. Vérifier les logs
echo "\n6. VÉRIFICATION LOGS\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logSize = filesize($logFile);
    echo "- Fichier log: ✓ Existe (" . number_format($logSize) . " bytes)\n";

    if ($logSize > 0) {
        $logContent = file_get_contents($logFile);
        $errorCount = substr_count($logContent, '[ERROR]');
        $warningCount = substr_count($logContent, '[WARNING]');
        echo "- Erreurs dans le log: $errorCount\n";
        echo "- Avertissements dans le log: $warningCount\n";

        // Dernières lignes du log
        $lines = explode("\n", trim($logContent));
        $lastLines = array_slice($lines, -5);
        echo "- Dernières lignes du log:\n";
        foreach ($lastLines as $line) {
            if (!empty(trim($line))) {
                echo "  " . substr($line, 0, 100) . "\n";
            }
        }
    } else {
        echo "- Le fichier log est vide\n";
    }
} else {
    echo "- Fichier log: ✗ Manquant\n";
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";
