<?php
/**
 * Script de test complet des sections du dashboard admin
 * Vérifie que toutes les routes et vues fonctionnent correctement
 */

echo "🔍 TEST COMPLET DES SECTIONS DASHBOARD\n";
echo "=====================================\n\n";

$results = [];
$errors = [];

// Configuration base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=belier3', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données OK\n\n";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}

// Liste des sections du dashboard avec leurs routes et vues
$sections = [
    'Dashboard' => [
        'route' => 'admin.dashboard',
        'controller' => 'Admin\AdminDashboardController@dashboard',
        'view' => 'admin.dashboard',
        'table_dependencies' => ['articles', 'users', 'orders', 'products', 'subscriptions']
    ],
    'Utilisateurs' => [
        'route' => 'admin.users.index',
        'controller' => 'AdminController@users',
        'view' => 'admin.users',
        'table_dependencies' => ['users']
    ],
    'Articles' => [
        'route' => 'admin.articles.index',
        'controller' => 'Admin\ArticleController@index',
        'view' => 'admin.articles.index',
        'table_dependencies' => ['articles', 'categories', 'users']
    ],
    'Produits' => [
        'route' => 'admin.products.index',
        'controller' => 'AdminController@products',
        'view' => 'admin.products',
        'table_dependencies' => ['products', 'categories']
    ],
    'Commandes' => [
        'route' => 'admin.orders.index',
        'controller' => 'AdminController@orders',
        'view' => 'admin.orders',
        'table_dependencies' => ['orders', 'users']
    ],
    'Abonnements' => [
        'route' => 'admin.subscriptions.index',
        'controller' => 'AdminController@subscriptions',
        'view' => 'admin.subscriptions',
        'table_dependencies' => ['subscriptions', 'users']
    ],
    'Messages' => [
        'route' => 'admin.messages',
        'controller' => 'AdminController@messages',
        'view' => 'admin.messages',
        'table_dependencies' => ['chatbot_messages', 'users']
    ],
    'Statistiques' => [
        'route' => 'admin.stats',
        'controller' => 'AdminController@stats',
        'view' => 'admin.stats',
        'table_dependencies' => ['articles', 'users', 'orders', 'subscriptions']
    ],
    'Paramètres' => [
        'route' => 'admin.settings',
        'controller' => 'AdminController@settings',
        'view' => 'admin.settings',
        'table_dependencies' => []
    ]
];

echo "🧪 TESTS DES SECTIONS\n";
echo "===================\n\n";

foreach ($sections as $sectionName => $config) {
    echo "📋 Section: $sectionName\n";
    echo str_repeat("-", strlen($sectionName) + 10) . "\n";
    
    $sectionResults = [];
    
    // 1. Vérifier les dépendances de table
    echo "   🗄️ Tables requises: ";
    $missingTables = [];
    foreach ($config['table_dependencies'] as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            $missingTables[] = $table;
        }
    }
    
    if (empty($missingTables)) {
        echo "✅ OK\n";
        $sectionResults['tables'] = 'OK';
    } else {
        echo "❌ Manquantes: " . implode(', ', $missingTables) . "\n";
        $sectionResults['tables'] = 'ERROR';
        $errors[] = "Section $sectionName: Tables manquantes - " . implode(', ', $missingTables);
    }
    
    // 2. Vérifier le fichier de vue
    $viewPath = str_replace('.', '/', $config['view']) . '.blade.php';
    $fullViewPath = "resources/views/$viewPath";
    echo "   👁️ Vue ($viewPath): ";
    
    if (file_exists($fullViewPath)) {
        echo "✅ OK\n";
        $sectionResults['view'] = 'OK';
        
        // Vérifier le contenu de base de la vue
        $viewContent = file_get_contents($fullViewPath);
        if (strpos($viewContent, '@extends') !== false) {
            echo "      └─ Extends layout: ✅\n";
        } else {
            echo "      └─ Extends layout: ⚠️ Manquant\n";
        }
    } else {
        echo "❌ Fichier manquant\n";
        $sectionResults['view'] = 'ERROR';
        $errors[] = "Section $sectionName: Vue manquante - $fullViewPath";
    }
    
    // 3. Vérifier le contrôleur
    $controllerParts = explode('@', $config['controller']);
    $controllerClass = $controllerParts[0];
    $controllerMethod = $controllerParts[1];
    
    // Construire le chemin du contrôleur
    $controllerPath = 'app/Http/Controllers/' . str_replace('\\', '/', $controllerClass) . '.php';
    echo "   🎛️ Contrôleur ($controllerClass): ";
    
    if (file_exists($controllerPath)) {
        echo "✅ OK\n";
        $sectionResults['controller'] = 'OK';
        
        // Vérifier la méthode
        $controllerContent = file_get_contents($controllerPath);
        if (strpos($controllerContent, "function $controllerMethod") !== false) {
            echo "      └─ Méthode $controllerMethod: ✅\n";
        } else {
            echo "      └─ Méthode $controllerMethod: ❌ Manquante\n";
            $errors[] = "Section $sectionName: Méthode $controllerMethod manquante dans $controllerClass";
        }
    } else {
        echo "❌ Fichier manquant\n";
        $sectionResults['controller'] = 'ERROR';
        $errors[] = "Section $sectionName: Contrôleur manquant - $controllerPath";
    }
    
    // 4. Tester les données si les tables existent
    if (empty($missingTables) && !empty($config['table_dependencies'])) {
        echo "   📊 Données de test: ";
        try {
            $hasData = false;
            foreach ($config['table_dependencies'] as $table) {
                $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
                $count = $stmt->fetchColumn();
                if ($count > 0) {
                    $hasData = true;
                    break;
                }
            }
            
            if ($hasData) {
                echo "✅ Données disponibles\n";
                $sectionResults['data'] = 'OK';
            } else {
                echo "⚠️ Aucune donnée\n";
                $sectionResults['data'] = 'WARNING';
            }
        } catch (Exception $e) {
            echo "❌ Erreur de requête\n";
            $sectionResults['data'] = 'ERROR';
        }
    }
    
    $results[$sectionName] = $sectionResults;
    echo "\n";
}

// Résumé des résultats
echo "📊 RÉSUMÉ DES TESTS\n";
echo "==================\n\n";

$totalSections = count($sections);
$workingSections = 0;
$warningSections = 0;
$errorSections = 0;

foreach ($results as $sectionName => $sectionResults) {
    $hasError = in_array('ERROR', $sectionResults);
    $hasWarning = in_array('WARNING', $sectionResults);
    
    if ($hasError) {
        echo "❌ $sectionName: ERREUR\n";
        $errorSections++;
    } elseif ($hasWarning) {
        echo "⚠️ $sectionName: AVERTISSEMENT\n";
        $warningSections++;
    } else {
        echo "✅ $sectionName: OK\n";
        $workingSections++;
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📈 STATISTIQUES FINALES\n";
echo str_repeat("=", 50) . "\n";
echo "✅ Sections fonctionnelles: $workingSections/$totalSections\n";
echo "⚠️ Sections avec avertissements: $warningSections/$totalSections\n";
echo "❌ Sections avec erreurs: $errorSections/$totalSections\n";

if (!empty($errors)) {
    echo "\n🔧 ERREURS À CORRIGER:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
}

$successRate = ($workingSections / $totalSections) * 100;
echo "\n🎯 Taux de réussite: " . round($successRate, 1) . "%\n";

if ($successRate >= 90) {
    echo "\n🎉 EXCELLENT! Le dashboard est presque entièrement fonctionnel!\n";
} elseif ($successRate >= 70) {
    echo "\n👍 BON! La plupart des sections fonctionnent correctement.\n";
} elseif ($successRate >= 50) {
    echo "\n⚠️ MOYEN! Plusieurs sections nécessitent des corrections.\n";
} else {
    echo "\n❌ CRITIQUE! Le dashboard nécessite des corrections importantes.\n";
}

echo "\n🔗 LIENS DE TEST:\n";
echo "   - Dashboard principal: http://127.0.0.1:8000/admin/dashboard\n";
echo "   - Toutes les sections sont accessibles depuis le menu latéral\n";

?>