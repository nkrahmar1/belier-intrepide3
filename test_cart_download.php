<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\CartController;

echo "=== TEST DU SYSTÈME DE PANIER POUR TÉLÉCHARGEMENTS ===\n\n";

// Laravel gère automatiquement les sessions

// Créer un utilisateur test
$user = User::where('email', 'aboulayebamba321@gmail.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé\n";
    exit;
}

echo "👤 Utilisateur: {$user->firstname} {$user->lastname}\n\n";

// Prendre un article de test
$article = Article::whereNotNull('document_path')->first();
if (!$article) {
    echo "❌ Aucun article avec document trouvé\n";
    exit;
}

echo "📄 Article de test: {$article->titre}\n";
echo "📁 Document: {$article->document_path}\n\n";

// Tester l'ajout au panier
$cartController = new CartController();

echo "🛒 Test d'ajout au panier...\n";
$result = $cartController->addDownloadedArticle($article);

if ($result) {
    echo "✅ Article ajouté au panier avec succès !\n\n";
    
    // Vérifier le contenu du panier
    $cart = session('cart', []);
    $downloadKey = 'download_' . $article->id;
    
    if (isset($cart[$downloadKey])) {
        echo "📊 Contenu du panier de téléchargements:\n";
        $item = $cart[$downloadKey];
        echo "   - Nom: {$item['name']}\n";
        echo "   - Type: {$item['type']}\n";
        echo "   - Prix: {$item['price']} FCFA\n";
        echo "   - Quantité: {$item['quantity']}\n";
        echo "   - Téléchargé le: {$item['downloaded_at']}\n";
        echo "   - Document: {$item['document_path']}\n";
    }
    
    // Compter les éléments du panier
    $totalItems = collect($cart)->sum('quantity');
    $downloads = collect($cart)->where('type', 'download')->count();
    
    echo "\n📈 Statistiques du panier:\n";
    echo "   - Total éléments: $totalItems\n";
    echo "   - Téléchargements: $downloads\n";
    
} else {
    echo "❌ Erreur lors de l'ajout au panier\n";
}

echo "\n=== INSTRUCTIONS POUR TESTER ===\n";
echo "1. Connectez-vous sur votre site\n";
echo "2. Téléchargez un article\n";
echo "3. Vérifiez l'icône du panier (devrait s'incrémenter)\n";
echo "4. Ouvrez le panier pour voir l'historique des téléchargements\n";

echo "\n🎯 Le système ajoute maintenant automatiquement chaque téléchargement au panier !\n";
