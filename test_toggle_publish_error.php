<?php

// Test spécifique pour reproduire l'erreur toggle-publish

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Route;

$app = require_once 'bootstrap/app.php';

// Démarrer l'application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== TEST ERREUR TOGGLE-PUBLISH ===\n";

// Test 1: Appel direct sans paramètre (qui devrait échouer)
echo "1. Test appel toggle-publish sans paramètre...\n";
try {
    $request = Illuminate\Http\Request::create('/admin/articles/toggle-publish', 'POST');
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() != 200) {
        echo "Erreur attendue: " . substr($response->getContent(), 0, 200) . "...\n";
    }
} catch (Exception $e) {
    echo "Exception attendue: " . $e->getMessage() . "\n";
}

// Test 2: Appel GET /admin/articles (qui déclenche l'erreur selon vous)
echo "\n2. Test GET /admin/articles...\n";
try {
    $request2 = Illuminate\Http\Request::create('/admin/articles', 'GET');
    $response2 = $kernel->handle($request2);
    echo "Status: " . $response2->getStatusCode() . "\n";
    if ($response2->getStatusCode() != 200) {
        echo "Erreur: " . substr($response2->getContent(), 0, 300) . "...\n";
    } else {
        echo "✅ GET /admin/articles fonctionne sans erreur\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les routes toggle-publish disponibles
echo "\n3. Vérification des routes toggle-publish...\n";
$routes = Route::getRoutes();
foreach ($routes->getRoutes() as $route) {
    if (strpos($route->getName() ?: '', 'toggle-publish') !== false) {
        echo "Route trouvée: " . $route->getName() . " -> " . $route->uri() . " [" . implode('|', $route->methods()) . "]\n";
    }
}

echo "\n=== FIN DES TESTS ===\n";
