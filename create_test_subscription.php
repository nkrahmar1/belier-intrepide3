<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subscription;

echo "=== CRÉATION D'UN ABONNEMENT DE TEST ===\n\n";

// Chercher un utilisateur ou en créer un
$user = User::first();

if (!$user) {
    echo "❌ Aucun utilisateur trouvé. Créez un compte d'abord.\n";
    exit;
}

echo "👤 Utilisateur: {$user->firstname} {$user->lastname}\n";

// Créer un abonnement actif avec tous les champs obligatoires
$subscription = Subscription::updateOrCreate(
    ['user_id' => $user->id],
    [
        'plan_id' => 1,
        'plan_name' => 'Premium',   // Champ obligatoire
        'plan' => 'premium',
        'status' => 'active',
        'price' => 5000,            // Prix au lieu d'amount
        'started_at' => now(),
        'ends_at' => now()->addMonths(1),
    ]
);

echo "✅ Abonnement créé/mis à jour !\n";
echo "Plan: {$subscription->plan}\n";
echo "Statut: {$subscription->status}\n";
echo "Fin: {$subscription->ends_at}\n";

// Vérifier que l'utilisateur a un abonnement actif
if ($user->hasActiveSubscription()) {
    echo "🎉 L'utilisateur a maintenant un abonnement ACTIF !\n";
} else {
    echo "❌ Problème avec l'abonnement\n";
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Connectez-vous avec: {$user->email}\n";
echo "2. Allez sur une page de catégorie\n";
echo "3. Cliquez sur 'Télécharger' pour l'article: Les Enjeux politique Actuels\n";
echo "4. Le document devrait se télécharger automatiquement !\n";
