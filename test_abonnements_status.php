<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subscription;

echo "=== DIAGNOSTIC DES ABONNEMENTS ===\n\n";

$users = User::all();

foreach ($users as $user) {
    echo "👤 Utilisateur: {$user->firstname} {$user->lastname} (ID: {$user->id})\n";
    echo "   Email: {$user->email}\n";
    echo "   Rôle: " . ($user->isAdmin() ? 'ADMIN' : 'UTILISATEUR') . "\n";
    
    // Chercher les abonnements manuellement
    $subscriptions = Subscription::where('user_id', $user->id)->get();
    
    if ($subscriptions->count() > 0) {
        foreach ($subscriptions as $subscription) {
            echo "   📝 Abonnement:\n";
            echo "     - Plan: {$subscription->plan}\n";
            echo "     - Statut: {$subscription->status}\n";
            echo "     - Fin: {$subscription->ends_at}\n";
        }
        
        if ($user->hasActiveSubscription()) {
            echo "     ✅ ABONNEMENT ACTIF\n";
        } else {
            echo "     ❌ ABONNEMENT INACTIF\n";
        }
    } else {
        echo "   ❌ Aucun abonnement\n";
    }
    echo "\n";
}

echo "=== FICHIERS DISPONIBLES POUR TÉLÉCHARGEMENT ===\n";
$articlesWithDocs = \App\Models\Article::whereNotNull('document_path')->get();
foreach ($articlesWithDocs as $article) {
    echo "📄 {$article->titre} -> {$article->document_path}\n";
}
