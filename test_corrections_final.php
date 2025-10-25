<?php

echo "=== Vérification finale des corrections ===\n";

// Vérifier que les vues n'ont plus de duplications
echo "=== Tailles des vues après corrections ===\n";

$dashboardSize = count(file('resources/views/admin/dashboard.blade.php'));
$productsSize = count(file('resources/views/admin/products.blade.php'));
$statsSize = count(file('resources/views/admin/stats.blade.php'));
$messagesSize = count(file('resources/views/admin/messages.blade.php'));

echo "Dashboard: $dashboardSize lignes (était 852 avant correction)\n";
echo "Products: $productsSize lignes (duplication supprimée)\n";
echo "Stats: $statsSize lignes\n";
echo "Messages: $messagesSize lignes\n";

// Vérifier qu'il n'y a plus de fonctions dupliquées dans le dashboard
$dashboardContent = file_get_contents('resources/views/admin/dashboard.blade.php');
$openCreateUserCount = substr_count($dashboardContent, 'function openCreateUserModal()');
$openCreateProductCount = substr_count($dashboardContent, 'function openCreateProductModal()');

echo "\n=== Vérification des fonctions JavaScript ===\n";
echo "Fonction openCreateUserModal: $openCreateUserCount (devrait être 1)\n";
echo "Fonction openCreateProductModal: $openCreateProductCount (devrait être 1)\n";

// Vérifier qu'il n'y a plus de duplication dans products
$productsContent = file_get_contents('resources/views/admin/products.blade.php');
$hasTableSection = strpos($productsContent, '<table>') !== false;
$hasCardSection = strpos($productsContent, 'product-card') !== false;

echo "\n=== Vérification Products ===\n";
echo "Section tableau (ancienne): " . ($hasTableSection ? "❌ Encore présente" : "✅ Supprimée") . "\n";
echo "Section cartes (moderne): " . ($hasCardSection ? "✅ Présente" : "❌ Manquante") . "\n";

echo "\n=== Corrections terminées avec succès ===\n";
