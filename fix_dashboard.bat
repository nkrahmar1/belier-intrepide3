@echo off
REM Script de correction des erreurs dashboard
echo === CORRECTION DES ERREURS DASHBOARD ===

REM 1. Vérifier les routes admin
echo 1. Vérification des routes admin...
php artisan route:list --name=admin

REM 2. Créer un utilisateur admin si nécessaire
echo 2. Création d'un utilisateur admin...
php -r "require 'vendor/autoload.php'; require 'bootstrap/app.php'; use App\Models\User; use Illuminate\Support\Facades\Hash; try { $admin = User::where('role', 'admin')->orWhere('is_admin', 1)->first(); if (!$admin) { $admin = User::create(['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => Hash::make('admin123'), 'role' => 'admin', 'is_admin' => 1, 'status' => 'active', 'email_verified_at' => now()]); echo 'Utilisateur admin créé: admin@admin.com (mot de passe: admin123)' . PHP_EOL; } else { echo 'Admin existe déjà: ' . $admin->email . PHP_EOL; } } catch (Exception $e) { echo 'Erreur: ' . $e->getMessage() . PHP_EOL; }"

REM 3. Nettoyer le cache
echo 3. Nettoyage du cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear

REM 4. Démarrer le serveur
echo 4. Démarrage du serveur...
echo Serveur disponible sur: http://127.0.0.1:8000
echo Admin: admin@admin.com / admin123
echo Dashboard admin: http://127.0.0.1:8000/admin/dashboard
echo.
php artisan serve --host=127.0.0.1 --port=8000
