<?php

require __DIR__ . '/vendor/autoload.php';

// Simuler une requête web basique
$_SERVER['HTTP_HOST'] = '127.0.0.1:8000';
$_SERVER['REQUEST_URI'] = '/test-cart-session';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Démarrer la session
session_start();

// Ajouter manuellement au panier via session
$cart = [
    'download_1' => [
        'id' => 1,
        'title' => 'Article de test',
        'description' => 'Test description',
        'price' => 0,
        'quantity' => 1,
        'type' => 'download',
        'user_id' => 1
    ],
    'purchase_2' => [
        'id' => 2,
        'title' => 'Article payant',
        'description' => 'Article à acheter',
        'price' => 29.99,
        'quantity' => 1,
        'type' => 'purchase',
        'user_id' => 1
    ]
];

$_SESSION['cart'] = $cart;

echo "<h1>Test Session PHP Natif</h1>";
echo "<h2>Contenu du panier en session:</h2>";
echo "<pre>" . print_r($_SESSION['cart'], true) . "</pre>";
echo "<h2>Nombre d'articles:</h2>";
echo "<p>Total: " . count($_SESSION['cart']) . "</p>";
echo "<h2>Session ID:</h2>";
echo "<p>" . session_id() . "</p>";

// Tester si on peut récupérer les données
if (isset($_SESSION['cart'])) {
    echo "<h2>✅ Session fonctionne</h2>";
} else {
    echo "<h2>❌ Problème de session</h2>";
}
