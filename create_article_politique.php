<?php

echo "=== SCRIPT DE TEST POUR CRÉER UN ARTICLE ===\n\n";

echo "Copie ces requêtes dans ton interface de base de données (phpMyAdmin, etc.) :\n\n";

echo "-- 1. Vérifier/Créer une catégorie\n";
echo "INSERT IGNORE INTO `categories` (`id`, `nom`, `slug`, `description`, `created_at`, `updated_at`) \n";
echo "VALUES (1, 'POLITIQUE', 'politique', 'Articles sur la politique', NOW(), NOW());\n\n";

echo "-- 2. Vérifier/Créer un utilisateur admin\n";
echo "INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) \n";
echo "VALUES (1, 'Admin', 'admin@test.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());\n\n";

echo "-- 3. Créer l'article PUBLIÉ\n";
echo "INSERT INTO `articles` (\n";
echo "    `titre`, `slug`, `contenu`, `extrait`, `image`, \n";
echo "    `document_path`, `file_original_name`, `file_size`,\n";
echo "    `is_published`, `is_premium`, `published_at`, \n";
echo "    `user_id`, `category_id`, `views_count`, \n";
echo "    `created_at`, `updated_at`\n";
echo ") VALUES (\n";
echo "    'La Politique Moderne en Côte d\\'Ivoire',\n";
echo "    'la-politique-moderne-en-cote-divoire',\n";
echo "    'La politique est une chose très simple qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens de Côte d\\'Ivoire. Dans ce contexte actuel, il est important de comprendre les enjeux politiques et leur impact sur le développement du pays. Les institutions démocratiques jouent un rôle crucial dans cette dynamique.',\n";
echo "    'Analyse approfondie de la politique moderne et de la gouvernance en Côte d\\'Ivoire',\n";
echo "    'image/politique.jpg',\n";
echo "    'articles/documents/guide-politique.pdf',\n";
echo "    'Guide Politique CI.pdf',\n";
echo "    1024000,\n";
echo "    1,\n";
echo "    1,\n";
echo "    NOW(),\n";
echo "    1,\n";
echo "    1,\n";
echo "    0,\n";
echo "    NOW(),\n";
echo "    NOW()\n";
echo ");\n\n";

echo "-- 4. Créer un article ÉCONOMIE\n";
echo "INSERT IGNORE INTO `categories` (`id`, `nom`, `slug`, `description`, `created_at`, `updated_at`) \n";
echo "VALUES (2, 'ÉCONOMIE', 'economie', 'Articles sur l\\'économie', NOW(), NOW());\n\n";

echo "INSERT INTO `articles` (\n";
echo "    `titre`, `slug`, `contenu`, `extrait`, `image`, \n";
echo "    `is_published`, `is_premium`, `published_at`, \n";
echo "    `user_id`, `category_id`, `views_count`, \n";
echo "    `created_at`, `updated_at`\n";
echo ") VALUES (\n";
echo "    'Les Défis Économiques de 2025',\n";
echo "    'les-defis-economiques-de-2025',\n";
echo "    'L\\'économie ivoirienne fait face à des défis majeurs en 2025. Entre inflation, croissance et développement durable, les enjeux sont nombreux pour le gouvernement et les entreprises.',\n";
echo "    'Analyse des principaux défis économiques de la Côte d\\'Ivoire en 2025',\n";
echo "    'image/economie.jpg',\n";
echo "    1,\n";
echo "    0,\n";
echo "    NOW(),\n";
echo "    1,\n";
echo "    2,\n";
echo "    0,\n";
echo "    NOW(),\n";
echo "    NOW()\n";
echo ");\n\n";

echo "-- 5. Vérifier les données\n";
echo "SELECT * FROM articles WHERE is_published = 1;\n";
echo "SELECT * FROM categories;\n\n";

echo "=== APRÈS AVOIR EXÉCUTÉ CES REQUÊTES ===\n";
echo "1. Va sur ton site : http://127.0.0.1:8000/articles\n";
echo "2. Tu devrais voir tes articles publiés\n";
echo "3. Va sur l'admin : http://127.0.0.1:8000/admin/articles\n";
echo "4. Tu peux gérer tes articles\n\n";

echo "=== TEST COMPLET DU SYSTÈME ===\n";
echo "1. Créer un compte utilisateur normal\n";
echo "2. Voir les articles sur /articles\n";
echo "3. Essayer de télécharger un document (premium)\n";
echo "4. S'abonner pour accéder aux documents premium\n";
echo "5. Télécharger le document après abonnement\n";
