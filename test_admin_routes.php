<?php

// Test direct des routes admin pour diagnostiquer les erreurs 500

// Configuration de base
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Créer une requête de test
$request = Illuminate\Http\Request::create('/admin/users', 'GET');

try {
    $response = $kernel->handle($request);

    echo "=== TEST ROUTE /admin/users ===\n";
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response->getContent()) . "\n";

    if ($response->getStatusCode() != 200) {
        echo "Response Content: " . substr($response->getContent(), 0, 500) . "\n";
    }

} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test route articles
$request2 = Illuminate\Http\Request::create('/admin/articles', 'GET');

try {
    $response2 = $kernel->handle($request2);

    echo "\n=== TEST ROUTE /admin/articles ===\n";
    echo "Status Code: " . $response2->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response2->getContent()) . "\n";

    if ($response2->getStatusCode() != 200) {
        echo "Response Content: " . substr($response2->getContent(), 0, 500) . "\n";
    }

} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
