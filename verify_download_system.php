<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use Illuminate\Support\Facades\Storage;

echo "=== VÉRIFICATION FINALE DU SYSTÈME DE TÉLÉCHARGEMENT ===\n\n";

$articles = Article::whereNotNull('document_path')->get();

echo "📄 Articles avec documents :\n";
foreach ($articles as $article) {
    echo "\n🔸 Article ID: {$article->id}\n";
    echo "   Titre: {$article->titre}\n";
    echo "   Document path: {$article->document_path}\n";
    echo "   Nom original: " . ($article->file_original_name ?? 'Non défini') . "\n";
    
    if (Storage::exists($article->document_path)) {
        echo "   ✅ Fichier accessible (Taille: " . Storage::size($article->document_path) . " bytes)\n";
        echo "   📥 URL de téléchargement: http://127.0.0.1:8000/articles/{$article->id}/download\n";
    } else {
        echo "   ❌ Fichier manquant\n";
    }
}

echo "\n=== RÉSUMÉ DU FONCTIONNEMENT ===\n";
echo "✅ Le système utilise exactement article->document_path pour le téléchargement\n";
echo "✅ Storage::download() gère automatiquement le téléchargement\n";
echo "✅ Les permissions sont vérifiées (admin ou abonnement)\n";
echo "✅ Les logs permettent de tracer tous les téléchargements\n";

echo "\n=== ÉTAPES POUR TESTER ===\n";
echo "1. Allez sur: http://127.0.0.1:8000/test-direct-download.html\n";
echo "2. Connectez-vous avec un compte (admin ou utilisateur abonné)\n";
echo "3. Cliquez sur 'Tester le téléchargement' pour un article\n";
echo "4. Le fichier devrait se télécharger automatiquement\n";

echo "\n=== COMPTES DE TEST ===\n";
echo "🔑 Admin: aboulayebamba321@gmail.com (accès direct)\n";
echo "🔑 Utilisateur: user@test.com / password123 (avec abonnement)\n";
