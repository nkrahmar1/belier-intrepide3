<?php

require_once 'vendor/autoload.php';

// Tester les classes et méthodes admin
echo "=== Test des pages admin ===\n";

try {
    // Test de la classe AdminController
    $reflectionClass = new ReflectionClass('App\Http\Controllers\AdminController');
    echo "✅ AdminController existe\n";

    // Test de la méthode stats
    if ($reflectionClass->hasMethod('stats')) {
        echo "✅ Méthode stats() existe\n";
    } else {
        echo "❌ Méthode stats() manquante\n";
    }

    // Test de la méthode messages
    if ($reflectionClass->hasMethod('messages')) {
        echo "✅ Méthode messages() existe\n";
    } else {
        echo "❌ Méthode messages() manquante\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur AdminController: " . $e->getMessage() . "\n";
}

// Test des modèles
$models = ['User', 'Article', 'Order', 'Product', 'Message', 'Category', 'Subscription'];

foreach ($models as $model) {
    try {
        $className = "App\\Models\\$model";
        if (class_exists($className)) {
            echo "✅ Modèle $model existe\n";
        } else {
            echo "❌ Modèle $model manquant\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur modèle $model: " . $e->getMessage() . "\n";
    }
}

// Test des vues
$views = [
    'resources/views/admin/stats.blade.php',
    'resources/views/admin/messages.blade.php',
    'resources/views/layouts/admin.blade.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "✅ Vue $view existe\n";
    } else {
        echo "❌ Vue $view manquante\n";
    }
}

echo "\n=== Fin des tests ===\n";
