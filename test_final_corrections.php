<?php

echo "=== Test des pages après corrections ===\n";

// Simuler les appels des contrôleurs pour voir s'il y a des erreurs

// Test du modèle Subscription pour stats
try {
    $subscriptionsCount = \App\Models\Subscription::count();
    echo "✅ Modèle Subscription accessible: $subscriptionsCount abonnements\n";
} catch (Exception $e) {
    echo "⚠️ Problème Subscription: " . $e->getMessage() . "\n";
}

// Test du modèle Message pour messages
try {
    $messagesCount = \App\Models\Message::count();
    echo "✅ Modèle Message accessible: $messagesCount messages\n";
} catch (Exception $e) {
    echo "⚠️ Problème Message: " . $e->getMessage() . "\n";
}

// Test du modèle ChatbotMessage pour messages
try {
    $chatbotCount = \App\Models\ChatbotMessage::count();
    echo "✅ Modèle ChatbotMessage accessible: $chatbotCount messages chatbot\n";
} catch (Exception $e) {
    echo "⚠️ Problème ChatbotMessage: " . $e->getMessage() . "\n";
}

// Vérifier que les vues n'ont plus de duplications
echo "\n=== Vérification structure des vues ===\n";

$dashboardSize = count(file('resources/views/admin/dashboard.blade.php'));
$productsSize = count(file('resources/views/admin/products.blade.php'));
$statsSize = count(file('resources/views/admin/stats.blade.php'));
$messagesSize = count(file('resources/views/admin/messages.blade.php'));

echo "Dashboard: $dashboardSize lignes\n";
echo "Products: $productsSize lignes (après suppression duplication)\n";
echo "Stats: $statsSize lignes\n";
echo "Messages: $messagesSize lignes\n";

echo "\n=== Tests terminés ===\n";
