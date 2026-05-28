<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;

echo "=== TEST RÉEL DU PANIER ===\n\n";

// Simuler une connexion
$user = User::where('email', 'aboulayebamba321@gmail.com')->first();
if ($user) {
    Auth::login($user);
    echo "✅ Connecté: {$user->firstname} {$user->lastname}\n";
} else {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

// Vider le panier d'abord
session()->forget('cart');
echo "🗑️ Panier vidé\n";

// Ajouter un article manuellement
$article = Article::whereNotNull('document_path')->first();
if ($article) {
    echo "📄 Article sélectionné: {$article->titre}\n";
    
    // Ajouter au panier
    $cartController = new CartController();
    $result = $cartController->addDownloadedArticle($article);
    
    if ($result) {
        echo "✅ Article ajouté au panier\n";
    } else {
        echo "❌ Échec ajout panier\n";
    }
    
    // Vérifier immédiatement
    $cart = session('cart', []);
    echo "\n📊 Contenu du panier après ajout:\n";
    
    if (empty($cart)) {
        echo "❌ PROBLÈME: Le panier est toujours vide!\n";
    } else {
        echo "✅ Panier contient " . count($cart) . " éléments:\n";
        foreach ($cart as $key => $item) {
            echo "  🔸 {$key}:\n";
            echo "     Nom: " . ($item['name'] ?? 'N/A') . "\n";
            echo "     Type: " . ($item['type'] ?? 'N/A') . "\n";
            echo "     Quantité: " . ($item['quantity'] ?? 'N/A') . "\n";
            echo "     Prix: " . ($item['price'] ?? 'N/A') . "\n";
        }
    }
    
    // Test du comptage pour la navbar
    echo "\n🔢 Test comptage navbar:\n";
    $cartCount = 0;
    $downloadCount = 0;
    
    foreach ($cart as $item) {
        if (($item['type'] ?? '') === 'download') {
            $downloadCount += $item['quantity'] ?? 0;
        } else {
            $cartCount += $item['quantity'] ?? 0;
        }
    }
    
    echo "   Achats: {$cartCount}\n";
    echo "   Téléchargements: {$downloadCount}\n";
    echo "   Total: " . ($cartCount + $downloadCount) . "\n";
    
} else {
    echo "❌ Aucun article trouvé\n";
}

echo "\n=== SOLUTION ===\n";
echo "Si le panier est vide, le problème peut être:\n";
echo "1. Session non persistante\n";
echo "2. Problème dans addDownloadedArticle()\n";
echo "3. Conflit de sessions\n";
