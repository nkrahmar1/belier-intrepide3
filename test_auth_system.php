<?php

// Test de l'authentification Laravel
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Simulation d'une requête
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "=== Test de l'authentification Laravel ===\n\n";

// Test 1: Vérifier les routes d'authentification
echo "1. Routes d'authentification :\n";
$routes = [
    'login' => 'GET /login',
    'register' => 'GET /register', 
    'app_logout' => 'POST /logout'
];

foreach ($routes as $name => $path) {
    try {
        $routeExists = Route::has($name);
        echo "   ✅ Route '$name' : " . ($routeExists ? "EXISTE" : "MANQUANTE") . "\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur pour '$name' : " . $e->getMessage() . "\n";
    }
}

echo "\n2. Configuration d'authentification :\n";

// Test 2: Vérifier la configuration auth
try {
    $authConfig = config('auth');
    echo "   ✅ Guard par défaut : " . $authConfig['defaults']['guard'] . "\n";
    echo "   ✅ Provider par défaut : " . $authConfig['defaults']['passwords'] . "\n";
} catch (Exception $e) {
    echo "   ❌ Erreur config auth : " . $e->getMessage() . "\n";
}

echo "\n3. Test des modèles :\n";

// Test 3: Vérifier le modèle User
try {
    $userModel = app('App\Models\User');
    echo "   ✅ Modèle User : DISPONIBLE\n";
    
    // Compter les utilisateurs
    $userCount = $userModel::count();
    echo "   ✅ Nombre d'utilisateurs : $userCount\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur modèle User : " . $e->getMessage() . "\n";
}

echo "\n4. Test des contrôleurs :\n";

// Test 4: Vérifier les contrôleurs
$controllers = [
    'LoginController' => 'App\Http\Controllers\LoginController',
    'RegisterController' => 'App\Http\Controllers\RegisterController'
];

foreach ($controllers as $name => $class) {
    try {
        if (class_exists($class)) {
            echo "   ✅ $name : DISPONIBLE\n";
        } else {
            echo "   ❌ $name : MANQUANT\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur $name : " . $e->getMessage() . "\n";
    }
}

echo "\n5. Test des middlewares :\n";

// Test 5: Vérifier les middlewares
try {
    $middlewares = app('router')->getMiddleware();
    $authMiddlewares = ['auth', 'guest'];
    
    foreach ($authMiddlewares as $middleware) {
        if (isset($middlewares[$middleware])) {
            echo "   ✅ Middleware '$middleware' : DISPONIBLE\n";
        } else {
            echo "   ❌ Middleware '$middleware' : MANQUANT\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur middlewares : " . $e->getMessage() . "\n";
}

echo "\n=== Fin du test ===\n";