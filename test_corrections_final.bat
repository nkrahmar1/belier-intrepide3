@echo off
echo === TEST CORRECTION DASHBOARD FINAL ===
echo.

echo 1. Nettoyage cache...
php artisan config:clear > nul
php artisan route:clear > nul
php artisan view:clear > nul

echo 2. Test de la base de donnees...
php -r "require 'vendor/autoload.php'; require 'bootstrap/app.php'; try { echo 'Utilisateurs: ' . App\Models\User::count() . PHP_EOL; echo 'Articles: ' . App\Models\Article::count() . PHP_EOL; } catch (Exception $e) { echo 'Erreur: ' . $e->getMessage() . PHP_EOL; }"

echo.
echo 3. Demarrage serveur...
echo Dashboard admin: http://127.0.0.1:8000/admin/dashboard
echo Dashboard test:  http://127.0.0.1:8000/admin/dashboard-test
echo.
echo CORRECTIONS APPLIQUEES:
echo - Protection JavaScript contre les redeclarations
echo - Gestion d'erreur innerHTML robuste
echo - Fallback pour les elements DOM manquants
echo.

start http://127.0.0.1:8000/admin/dashboard-test
php artisan serve