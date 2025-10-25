<?php

session_start();

echo "=== DIAGNOSTIC DU PANIER ===\n\n";

// Vérifier la session
echo "Session ID: " . session_id() . "\n";
echo "Session active: " . (session_status() === PHP_SESSION_ACTIVE ? 'OUI' : 'NON') . "\n\n";

// Vérifier le contenu du panier
$cart = $_SESSION['cart'] ?? [];
echo "Contenu brut de la session panier:\n";
var_dump($cart);

echo "\n=== ANALYSE ===\n";
if (empty($cart)) {
    echo "❌ Le panier est vide dans la session\n";
} else {
    echo "✅ Le panier contient " . count($cart) . " éléments\n";
    foreach ($cart as $key => $item) {
        echo "   - {$key}: " . ($item['name'] ?? 'Sans nom') . " (Type: " . ($item['type'] ?? 'achat') . ")\n";
    }
}

echo "\n=== LARAVEL SESSION ===\n";
// Essayer avec Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$laravelCart = session('cart', []);
echo "Session Laravel panier:\n";
var_dump($laravelCart);

if (empty($laravelCart)) {
    echo "❌ Panier Laravel vide\n";
} else {
    echo "✅ Panier Laravel avec " . count($laravelCart) . " éléments\n";
}
