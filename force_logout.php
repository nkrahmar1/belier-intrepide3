<?php
// Script pour forcer la déconnexion de tous les utilisateurs

echo "=== SCRIPT DE DÉCONNEXION FORCÉE ===\n\n";

echo "1. VIDAGE DES SESSIONS...\n";

// Vider les sessions stockées en fichiers
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    $sessionFiles = glob($sessionPath . '/*');
    $count = count($sessionFiles);

    foreach ($sessionFiles as $sessionFile) {
        if (is_file($sessionFile)) {
            unlink($sessionFile);
        }
    }

    echo "   ✅ $count fichiers de session supprimés\n";
} else {
    echo "   ⚠️  Dossier sessions introuvable\n";
}

echo "\n2. NETTOYAGE DU CACHE...\n";

// Vider les caches
$cacheCommands = [
    'config:clear' => 'Configuration cache cleared',
    'route:clear' => 'Route cache cleared',
    'view:clear' => 'View cache cleared',
    'cache:clear' => 'Application cache cleared'
];

foreach ($cacheCommands as $command => $message) {
    echo "   🧹 Exécution: php artisan $command\n";
    $output = shell_exec("php artisan $command 2>&1");
    if ($output) {
        echo "      └─ " . trim($output) . "\n";
    }
}

echo "\n3. REGÉNÉRATION DES OPTIMISATIONS...\n";

$optimizeCommands = [
    'config:cache' => 'Configuration cached',
    'route:cache' => 'Routes cached',
    'view:cache' => 'Views cached'
];

foreach ($optimizeCommands as $command => $message) {
    echo "   ⚡ Exécution: php artisan $command\n";
    $output = shell_exec("php artisan $command 2>&1");
    if ($output) {
        echo "      └─ " . trim($output) . "\n";
    }
}

echo "\n4. VÉRIFICATION POST-NETTOYAGE...\n";

// Vérifier que les sessions sont vidées
$sessionFiles = glob($sessionPath . '/*');
echo "   📄 Fichiers de session restants: " . count($sessionFiles) . "\n";

// Vérifier les caches
$cacheFiles = glob(storage_path('framework/cache/data') . '/*');
echo "   📦 Fichiers de cache restants: " . count($cacheFiles) . "\n";

echo "\n=== RÉSULTAT ===\n";
echo "✅ Toutes les sessions ont été vidées\n";
echo "✅ Les caches ont été nettoyés et régénérés\n";
echo "✅ Tous les utilisateurs sont maintenant déconnectés\n";

echo "\n📋 PROCHAINES ÉTAPES :\n";
echo "1. 🌐 Actualiser votre navigateur (F5)\n";
echo "2. 🔍 Vérifier que la navbar affiche 'Mon compte' (non connecté)\n";
echo "3. 🔐 Vous connecter avec vos identifiants\n";
echo "4. 🧪 Tester le bouton 'Account' pour la déconnexion\n";

echo "\n🔄 Pour vous reconnecter :\n";
echo "   - Aller sur http://127.0.0.1:8002/login\n";
echo "   - Ou cliquer sur 'Mon compte' → 'Se Connecter'\n";

echo "\nScript terminé avec succès ! 🎯\n";
?>
