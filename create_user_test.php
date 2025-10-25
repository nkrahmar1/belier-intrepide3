<?php
// Script pour créer un utilisateur test et le connecter

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Inclusion de Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION D'UTILISATEUR TEST ===\n\n";

// Créer un utilisateur test
$email = 'aimee.krah@test.com';
$user = User::where('email', $email)->first();

if (!$user) {
    $user = User::create([
        'firstname' => 'Aimée',
        'lastname' => 'Krah',
        'email' => $email,
        'password' => Hash::make('password123'),
        'is_admin' => false,
        'email_verified_at' => now(),
    ]);
    echo "✅ Utilisateur créé : {$user->firstname} {$user->lastname} ($email)\n";
} else {
    echo "✅ Utilisateur existant : {$user->firstname} {$user->lastname} ($email)\n";
}

echo "\n=== INFORMATIONS DE CONNEXION ===\n";
echo "Email : $email\n";
echo "Mot de passe : password123\n";
echo "Nom complet : {$user->firstname} {$user->lastname}\n";
echo "Initiales : " . strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1)) . "\n";

echo "\n=== INSTRUCTIONS ===\n";
echo "1. 🌐 Aller sur http://127.0.0.1:8003/login\n";
echo "2. 📧 Saisir email : $email\n";
echo "3. 🔑 Saisir mot de passe : password123\n";
echo "4. 🔐 Se connecter\n";
echo "5. 🧪 Tester le bouton Account dans la navbar\n";

echo "\n✅ Utilisateur test prêt !\n";
?>
