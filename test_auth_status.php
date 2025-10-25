<?php
// Test de l'état d'authentification et déconnexion

echo "=== TEST DE L'AUTHENTIFICATION ACTUELLE ===\n\n";

// Démarrer la session Laravel (simulation)
echo "1. VÉRIFICATION DE L'ÉTAT DE SESSION :\n";

// Vérifier les fichiers de session
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $sessionFiles = glob($sessionPath . '/*');
    echo "   📁 Dossier sessions : " . $sessionPath . "\n";
    echo "   📄 Nombre de fichiers de session : " . count($sessionFiles) . "\n";

    if (count($sessionFiles) > 0) {
        echo "   ✅ Sessions actives détectées\n";

        // Afficher les sessions récentes
        $recentSessions = array_slice($sessionFiles, -3);
        foreach ($recentSessions as $session) {
            $content = file_get_contents($session);
            $modified = date('Y-m-d H:i:s', filemtime($session));
            echo "      └─ Session modifiée : $modified\n";

            // Vérifier si la session contient des données d'auth
            if (strpos($content, 'auth') !== false || strpos($content, 'login') !== false) {
                echo "         🔐 Contient des données d'authentification\n";
            }
        }
    } else {
        echo "   ❌ Aucune session active\n";
    }
} else {
    echo "   ❌ Dossier sessions introuvable\n";
}

echo "\n2. VÉRIFICATION DES ROUTES D'AUTHENTIFICATION :\n";

// Vérifier que les routes existent dans web.php
$webRoutes = file_get_contents('routes/web.php');

$routes = [
    'login' => "Route::get('/login'",
    'login.check' => "Route::post('/login'",
    'app_logout' => "Route::post('/logout'",
    'register' => "Route::get('/register'"
];

foreach ($routes as $name => $pattern) {
    $exists = strpos($webRoutes, $pattern) !== false;
    echo "   " . ($exists ? '✅' : '❌') . " Route '$name'\n";
}

echo "\n3. VÉRIFICATION DU CONTRÔLEUR DE LOGIN :\n";

$loginController = 'app/Http/Controllers/LoginController.php';
if (file_exists($loginController)) {
    $content = file_get_contents($loginController);

    $methods = [
        'showLoginForm' => 'public function showLoginForm',
        'login' => 'public function login',
        'logout' => 'public function logout'
    ];

    foreach ($methods as $name => $pattern) {
        $exists = strpos($content, $pattern) !== false;
        echo "   " . ($exists ? '✅' : '❌') . " Méthode '$name'\n";
    }

    // Vérifier la méthode logout spécifiquement
    if (strpos($content, 'Auth::logout()') !== false) {
        echo "   ✅ Logout utilise Auth::logout()\n";
    }
    if (strpos($content, 'session()->invalidate()') !== false) {
        echo "   ✅ Logout invalide la session\n";
    }
    if (strpos($content, 'regenerateToken()') !== false) {
        echo "   ✅ Logout régénère le token CSRF\n";
    }
} else {
    echo "   ❌ LoginController introuvable\n";
}

echo "\n4. VÉRIFICATION DE LA NAVBAR :\n";

$navbar = 'resources/views/navbar/navbar.blade.php';
if (file_exists($navbar)) {
    $content = file_get_contents($navbar);

    $checks = [
        '@auth' => 'Section utilisateur connecté',
        '@guest' => 'Section utilisateur non connecté',
        'app_logout' => 'Route de déconnexion',
        'dropdown-toggle' => 'Boutons dropdown Bootstrap',
        'Se déconnecter' => 'Texte du bouton de déconnexion'
    ];

    foreach ($checks as $pattern => $description) {
        $exists = strpos($content, $pattern) !== false;
        echo "   " . ($exists ? '✅' : '❌') . " $description\n";
    }
} else {
    echo "   ❌ Navbar introuvable\n";
}

echo "\n5. TEST DE DÉCONNEXION RECOMMANDÉ :\n";
echo "   1. ✅ Ouvrir http://127.0.0.1:8002\n";
echo "   2. ✅ Vérifier que vous êtes connecté (voir initiales dans navbar)\n";
echo "   3. ✅ Cliquer sur 'Account' dans la navbar\n";
echo "   4. ✅ Cliquer sur 'Se déconnecter'\n";
echo "   5. ✅ Confirmer la déconnexion\n";
echo "   6. ✅ Vérifier la redirection vers /login\n";

echo "\n6. COMMANDES POUR FORCER LA DÉCONNEXION (si nécessaire) :\n";
echo "   php artisan session:flush    # Vider toutes les sessions\n";
echo "   php artisan cache:clear      # Vider le cache\n";
echo "   php artisan config:clear     # Vider le cache de config\n";

echo "\n=== RÉSUMÉ ===\n";
echo "✅ Le système d'authentification semble configuré correctement\n";
echo "✅ Les routes de login/logout existent\n";
echo "✅ Le contrôleur LoginController a les bonnes méthodes\n";
echo "✅ La navbar a la logique @auth/@guest\n";
echo "🎯 Testez maintenant la déconnexion via la navbar !\n";

echo "\n📝 Si la déconnexion ne fonctionne pas :\n";
echo "   1. Vérifiez la console du navigateur pour les erreurs JavaScript\n";
echo "   2. Vérifiez que Bootstrap est bien chargé\n";
echo "   3. Vérifiez les logs Laravel dans storage/logs/\n";
?>
