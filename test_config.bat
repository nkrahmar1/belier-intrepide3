@echo off
echo === Configuration Email ===
cd /d "c:\Users\NAN\OneDrive\Bureau\belier-intrepide3"
echo.
echo Verification de la configuration...
findstr "MAIL_" .env
echo.
echo Test de la queue...
php artisan queue:work --once --tries=1 --timeout=30
echo.
echo Fin du test
pause
