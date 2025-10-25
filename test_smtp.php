<?php
// Test simple d'envoi d'email avec PHPMailer ou fonction mail native
echo "=== Test de configuration Mailtrap ===\n\n";

// Configuration Mailtrap
$host = 'sandbox.smtp.mailtrap.io';
$port = 2525;
$username = 'ddb38c4dd018b7';
$password = '52af51c2ab6079';
$from = 'noreply@belier-intrepide.com';
$to = 'test@example.com';

echo "Configuration:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Username: $username\n";
echo "From: $from\n\n";

// Test de connexion SMTP
echo "Test de connexion SMTP...\n";

$socket = @fsockopen($host, $port, $errno, $errstr, 10);
if ($socket) {
    echo "✅ Connexion SMTP réussie à $host:$port\n";
    fclose($socket);
} else {
    echo "❌ Échec de connexion SMTP: $errstr ($errno)\n";
}

echo "\n=== Pour tester Laravel ===\n";
echo "1. Connecte-toi sur ton site\n";
echo "2. Vérifie ton inbox Mailtrap : https://mailtrap.io/inboxes\n";
echo "3. Si pas d'email, vérifiez les logs : storage/logs/laravel.log\n";
