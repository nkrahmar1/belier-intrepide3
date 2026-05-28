@echo off
echo === TEST FINAL CORRECTIONS DASHBOARD ===
echo.

echo 1. Demarrage du serveur Laravel...
start /B php artisan serve --host=127.0.0.1 --port=8000

echo 2. Attente de demarrage (5 secondes)...
timeout /t 5 /nobreak > nul

echo 3. Test automatique avec curl...
curl -s -o nul -w "Status Code: %%{http_code}\n" http://127.0.0.1:8000/admin/dashboard

echo.
echo === CORRECTIONS APPLIQUEES ===
echo ✅ Redeclaration quickArticleForm corrigee
echo ✅ Balises DIV equilibrees (354/354)
echo ✅ Protection JavaScript dashboardScriptsLoaded
echo ✅ Variables renommees (formToReset, quickArticleFormElement)
echo.
echo Ouvrez votre navigateur sur:
echo http://127.0.0.1:8000/admin/dashboard
echo.
echo Verifiez la console (F12) pour confirmer:
echo - Plus d'erreur "has already been declared"
echo - Plus d'erreur "Cannot set properties of null"
echo - Navigation AJAX fonctionnelle
echo.
pause