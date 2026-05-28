@echo off
echo ========================================
echo    TEST EMAIL BELIER-INTREPIDE
echo ========================================
echo.

cd /d "c:\Users\NAN\OneDrive\Bureau\belier-intrepide3"

echo [1/4] Nettoyage du cache...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1

echo [2/4] Verification de la configuration...
echo Configuration email actuelle:
findstr "MAIL_MAILER\|MAIL_HOST\|MAIL_USERNAME\|MAIL_FROM" .env

echo.
echo [3/4] Test de la queue...
php artisan queue:work --once --tries=1 --timeout=30

echo.
echo [4/4] Instructions:
echo - Connecte-toi sur ton site
echo - Verifie tes emails Gmail
echo - Si probleme, verifie storage/logs/debug.log
echo.
echo ========================================
pause
