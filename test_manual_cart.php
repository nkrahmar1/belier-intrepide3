<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== AJOUT MANUEL AU PANIER ===\n\n";

// Connexion utilisateur
$user = User::where('email', 'aboulayebamba321@gmail.com')->first();
if ($user) {
    Auth::login($user);
    echo "✅ Connecté: {$user->firstname} {$user->lastname}\n";
} else {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

// Récupérer un article
$article = Article::whereNotNull('document_path')->first();
if (!$article) {
    echo "❌ Aucun article trouvé\n";
    exit;
}

echo "📄 Article: {$article->titre}\n";

// Ajouter manuellement au panier
$cart = session('cart', []);
$downloadKey = 'download_' . $article->id;

$cart[$downloadKey] = [
    'name' => $article->titre,
    'price' => 0,
    'quantity' => 1,
    'type' => 'download',
    'article_id' => $article->id,
    'downloaded_at' => now()->format('Y-m-d H:i:s'),
    'document_path' => $article->document_path
];

// Ajouter aussi un achat normal pour test
$purchaseKey = 'purchase_test';
$cart[$purchaseKey] = [
    'name' => 'Produit Test',
    'price' => 1500,
    'quantity' => 2,
    'type' => 'purchase'
];

// Sauvegarder en session
session(['cart' => $cart]);

echo "\n✅ Ajouté au panier :\n";
echo "   - Téléchargement: {$article->titre}\n";
echo "   - Achat: Produit Test (2x 1500 FCFA)\n";

// Vérifier immédiatement
$verif = session('cart', []);
echo "\n📊 Vérification panier :\n";
echo "   Nombre d'éléments: " . count($verif) . "\n";

$downloadCount = 0;
$purchaseCount = 0;
$total = 0;

foreach ($verif as $key => $item) {
    echo "   - {$key}: {$item['name']} (Type: " . ($item['type'] ?? 'N/A') . ")\n";
    
    if (($item['type'] ?? '') === 'download') {
        $downloadCount += $item['quantity'] ?? 1;
    } else {
        $purchaseCount += $item['quantity'] ?? 0;
        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
    }
}

echo "\n📈 Résumé :\n";
echo "   Téléchargements: {$downloadCount}\n";
echo "   Achats: {$purchaseCount}\n";
echo "   Total: {$total} FCFA\n";

echo "\n🌐 Maintenant allez sur : http://127.0.0.1:8000/cart\n";
echo "Vous devriez voir le contenu du panier !\n";
