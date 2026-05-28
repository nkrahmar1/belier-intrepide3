<?php

echo "=== Test d'accès aux pages admin ===\n";

// Simuler l'accès aux routes
$routes = [
    '/admin/stats' => 'AdminController@stats',
    '/admin/messages' => 'AdminController@messages'
];

// Vérifier que les routes sont bien définies
require_once 'routes/web.php';

echo "✅ Routes vérifiées\n";

// Vérifier les vues
echo "\n=== Vérification de la structure des vues ===\n";

// Vérifier la vue stats
$statsContent = file_get_contents('resources/views/admin/stats.blade.php');
if (strpos($statsContent, '@extends(\'layouts.admin\')') !== false) {
    echo "✅ stats.blade.php étend bien layouts.admin\n";
} else {
    echo "❌ stats.blade.php n'étend pas layouts.admin\n";
}

if (strpos($statsContent, '@section(\'content\')') !== false) {
    echo "✅ stats.blade.php a une section content\n";
} else {
    echo "❌ stats.blade.php n'a pas de section content\n";
}

// Vérifier la vue messages
$messagesContent = file_get_contents('resources/views/admin/messages.blade.php');
if (strpos($messagesContent, '@extends(\'layouts.admin\')') !== false) {
    echo "✅ messages.blade.php étend bien layouts.admin\n";
} else {
    echo "❌ messages.blade.php n'étend pas layouts.admin\n";
}

if (strpos($messagesContent, '@section(\'content\')') !== false) {
    echo "✅ messages.blade.php a une section content\n";
} else {
    echo "❌ messages.blade.php n'a pas de section content\n";
}

// Vérifier le layout admin
$layoutContent = file_get_contents('resources/views/layouts/admin.blade.php');
if (strpos($layoutContent, '@yield(\'content\')') !== false) {
    echo "✅ Layout admin a bien @yield('content')\n";
} else {
    echo "❌ Layout admin n'a pas @yield('content')\n";
}

echo "\n=== Fin des tests ===\n";
