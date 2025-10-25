<?php

// Test simple d'envoi d'email
echo "=== Test de configuration email ===\n";

// Charger les variables d'environnement
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $value = trim($value, '"');
            putenv("$key=$value");
        }
    }
}

echo "Configuration actuelle :\n";
echo "MAIL_MAILER: " . getenv('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . getenv('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . getenv('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . getenv('MAIL_USERNAME') . "\n";
echo "MAIL_FROM_ADDRESS: " . getenv('MAIL_FROM_ADDRESS') . "\n";

echo "\nPour tester avec Mailtrap, remplacez dans .env :\n";
echo "MAIL_USERNAME=votre_username_mailtrap\n";
echo "MAIL_PASSWORD=votre_password_mailtrap\n";
echo "\nPuis exécutez : php artisan queue:work --once\n";
