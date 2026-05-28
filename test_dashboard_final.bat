@echo off
echo === CORRECTION DASHBOARD ADMIN - TEST COMPLET ===
echo.

echo 1. Nettoyage des caches Laravel...
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo 2. Vérification des routes admin...
php artisan route:list --name=admin | findstr "dashboard\|users\|articles" | head -10

echo.
echo 3. Test de la base de données...
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
try {
    \$users = \App\Models\User::count();
    \$articles = \App\Models\Article::count();
    echo 'Utilisateurs: ' . \$users . PHP_EOL;
    echo 'Articles: ' . \$articles . PHP_EOL;
    echo 'Base de données: OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Erreur DB: ' . \$e->getMessage() . PHP_EOL;
}
"

echo.
echo 4. Démarrage du serveur Laravel...
echo Server will be available at: http://127.0.0.1:8000
echo Admin dashboard: http://127.0.0.1:8000/admin/dashboard
echo Dashboard test: http://127.0.0.1:8000/admin/dashboard-test
echo.
echo INSTRUCTIONS:
echo 1. Connectez-vous avec un compte admin
echo 2. Testez d'abord /admin/dashboard-test
echo 3. Puis testez /admin/dashboard
echo 4. Appuyez sur Ctrl+C pour arrêter le serveur
echo.

php artisan serve --host=127.0.0.1 --port=8000
