<?php

// Script simple pour créer un article de test
echo "Création d'un article de test...\n";

// Simuler les données INSERT SQL que vous pouvez exécuter directement dans votre base
echo "\n=== REQUÊTES SQL À EXÉCUTER ===\n\n";

echo "1. Créer une catégorie POLITIQUE :\n";
echo "INSERT INTO `categories` (`nom`, `slug`, `description`, `created_at`, `updated_at`) VALUES \n";
echo "('POLITIQUE', 'politique', 'Articles sur la politique et la gouvernance', NOW(), NOW());\n\n";

echo "2. Vérifier l'ID de la catégorie créée :\n";
echo "SELECT id FROM categories WHERE slug = 'politique';\n\n";

echo "3. Créer un utilisateur admin (si pas encore fait) :\n";
echo "INSERT INTO `users` (`name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES \n";
echo "('Admin', 'admin@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());\n\n";

echo "4. Créer l'article POLITIQUE :\n";
echo "INSERT INTO `articles` (\n";
echo "    `titre`, `slug`, `contenu`, `extrait`, `image`, \n";
echo "    `is_published`, `is_premium`, `published_at`, \n";
echo "    `user_id`, `category_id`, `views_count`, \n";
echo "    `created_at`, `updated_at`\n";
echo ") VALUES (\n";
echo "    'La Politique Moderne en Côte d\\'Ivoire',\n";
echo "    'la-politique-moderne-en-cote-divoire',\n";
echo "    'La politique est une chose très simple qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens.',\n";
echo "    'Analyse de la politique moderne en Côte d\\'Ivoire',\n";
echo "    'image/politique.jpg',\n";
echo "    1,\n";
echo "    0,\n";
echo "    NOW(),\n";
echo "    1,\n";
echo "    (SELECT id FROM categories WHERE slug = 'politique'),\n";
echo "    0,\n";
echo "    NOW(),\n";
echo "    NOW()\n";
echo ");\n\n";

echo "=== INSTRUCTIONS ===\n";
echo "1. Connectez-vous à votre base de données MySQL\n";
echo "2. Exécutez ces requêtes dans l'ordre\n";
echo "3. Rechargez votre page pour voir l'article\n\n";

echo "Si vous utilisez phpMyAdmin ou un autre outil, copiez-collez ces requêtes.\n";
