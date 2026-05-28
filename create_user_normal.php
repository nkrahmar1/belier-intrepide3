<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;

echo "=== CRÉATION D'UN UTILISATEUR NORMAL DE TEST ===\n\n";

// Créer un utilisateur normal
$normalUser = User::updateOrCreate(
    ['email' => 'user@test.com'],
    [
        'firstname' => 'Jean',
        'lastname' => 'Dupont',
        'email' => 'user@test.com',
        'password' => Hash::make('password123'),
        'role' => 'user',
        'is_admin' => false,
        'status' => 'active',
        'agreeterms' => true,
    ]
);

echo "✅ Utilisateur normal créé !\n";
echo "Email: {$normalUser->email}\n";
echo "Mot de passe: password123\n";
echo "Rôle: " . ($normalUser->isAdmin() ? 'ADMIN' : 'UTILISATEUR') . "\n\n";

// Créer un abonnement pour cet utilisateur
$subscription = Subscription::updateOrCreate(
    ['user_id' => $normalUser->id],
    [
        'plan_id' => 1,
        'plan_name' => 'Standard',
        'plan' => 'standard',
        'status' => 'active',
        'price' => 3000,
        'started_at' => now(),
        'ends_at' => now()->addMonths(1),
    ]
);

echo "✅ Abonnement créé pour l'utilisateur normal !\n";
echo "Plan: {$subscription->plan}\n";
echo "Statut: {$subscription->status}\n";
echo "Fin: {$subscription->ends_at}\n\n";

echo "=== RÉSUMÉ DES COMPTES DE TEST ===\n";
echo "📧 ADMIN: aboulayebamba321@gmail.com (peut télécharger sans abonnement)\n";
echo "📧 UTILISATEUR: user@test.com / password123 (a un abonnement actif)\n\n";

echo "=== INSTRUCTIONS ===\n";
echo "1. Testez avec l'admin: aboulayebamba321@gmail.com\n";
echo "2. Testez avec l'utilisateur: user@test.com / password123\n";
echo "3. Les deux peuvent télécharger mais pour des raisons différentes\n";
echo "4. Déconnectez-vous et testez sans compte (devrait demander connexion)\n";
