<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$admin = App\Models\User::where('role', 'admin')->orWhere('is_admin', 1)->first();
if ($admin) {
    echo 'Admin trouvé: ' . $admin->email . ' (Role: ' . $admin->role . ', is_admin: ' . $admin->is_admin . ')' . PHP_EOL;
} else {
    echo 'Aucun admin trouvé. Création d\'un admin de test...' . PHP_EOL;
    $user = new App\Models\User();
    $user->name = 'Admin Test';
    $user->email = 'admin@test.com';
    $user->password = bcrypt('password123');
    $user->role = 'admin';
    $user->is_admin = 1;
    $user->status = 'active';
    $user->email_verified_at = now();
    $user->save();
    echo 'Admin créé: admin@test.com (mot de passe: password123)' . PHP_EOL;
}
