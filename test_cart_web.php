<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

// Route temporaire pour tester le panier depuis le web
Route::get('/test-cart-web', function () {
    // Simuler une session
    session()->start();
    
    // Ajouter un article téléchargé au panier
    $cartController = new CartController();
    $result = $cartController->addDownloadedArticle(1, 'Article de test', 'Test description');
    
    // Récupérer le contenu du panier
    $cart = session('cart', []);
    
    echo "<h1>Test du panier depuis le web</h1>";
    echo "<h2>Résultat de l'ajout:</h2>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    echo "<h2>Contenu du panier:</h2>";
    echo "<pre>" . print_r($cart, true) . "</pre>";
    echo "<h2>Nombre d'articles:</h2>";
    echo "<p>Total: " . count($cart) . "</p>";
    
    // Tester le compteur
    $count = $cartController->count();
    echo "<h2>Compteur du panier:</h2>";
    echo "<pre>" . print_r($count->getData(), true) . "</pre>";
    
    // Lien vers le panier
    echo "<h2>Lien vers le panier:</h2>";
    echo "<a href='/cart'>Voir le panier</a>";
});
