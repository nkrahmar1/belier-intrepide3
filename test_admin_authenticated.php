<?php

// Test avec utilisateur admin simulé

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Auth;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Démarrer une session
$app->make('session.store')->start();

// Simuler un utilisateur admin connecté
$user = App\Models\User::where('role', 'admin')->orWhere('is_admin', 1)->first();

if (!$user) {
    echo "ERREUR: Aucun utilisateur admin trouvé dans la base de données.\n";
    echo "Créons un utilisateur admin de test...\n";

    $user = new App\Models\User();
    $user->name = 'Admin Test';
    $user->email = 'admin@test.com';
    $user->password = bcrypt('password');
    $user->role = 'admin';
    $user->is_admin = 1;
    $user->status = 'active';
    $user->email_verified_at = now();
    $user->save();

    echo "Utilisateur admin créé avec l'email: admin@test.com\n";
}

// Simuler la connexion
Auth::login($user);

echo "Utilisateur connecté: " . $user->name . " (ID: " . $user->id . ", Role: " . $user->role . ", is_admin: " . $user->is_admin . ")\n\n";

// Test route users
$request = Illuminate\Http\Request::create('/admin/users', 'GET');
$request->setLaravelSession($app->make('session.store'));

try {
    $response = $kernel->handle($request);

    echo "=== TEST ROUTE /admin/users (connecté) ===\n";
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response->getContent()) . "\n";

    if ($response->getStatusCode() != 200) {
        echo "Response Content: " . substr($response->getContent(), 0, 1000) . "\n";
    } else {
        echo "✅ Route /admin/users fonctionne correctement!\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR sur /admin/users: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test route articles
$request2 = Illuminate\Http\Request::create('/admin/articles', 'GET');
$request2->setLaravelSession($app->make('session.store'));

try {
    $response2 = $kernel->handle($request2);

    echo "\n=== TEST ROUTE /admin/articles (connecté) ===\n";
    echo "Status Code: " . $response2->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response2->getContent()) . "\n";

    if ($response2->getStatusCode() != 200) {
        echo "Response Content: " . substr($response2->getContent(), 0, 1000) . "\n";
    } else {
        echo "✅ Route /admin/articles fonctionne correctement!\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR sur /admin/articles: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
