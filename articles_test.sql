-- REQUÊTES SQL POUR CRÉER DES ARTICLES DE TEST
-- Copie ces requêtes dans phpMyAdmin ou ton interface de base de données

-- 1. Nettoyer d'abord (optionnel)
-- DELETE FROM articles;
-- DELETE FROM categories;
-- DELETE FROM users WHERE email = 'admin@test.com';

-- 2. Créer une catégorie POLITIQUE
INSERT IGNORE INTO `categories` (`id`, `nom`, `slug`, `description`, `created_at`, `updated_at`)
VALUES (1, 'POLITIQUE', 'politique', 'Articles sur la politique', NOW(), NOW());

-- 3. Créer un utilisateur admin
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`)
VALUES (1, 'Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());-- 4. Créer l'article POLITIQUE (PUBLIÉ simple)
INSERT INTO `articles` (
    `titre`, `slug`, `contenu`, `extrait`, `image`,
    `is_published`, `is_premium`, `published_at`,
    `user_id`, `category_id`, `views_count`,
    `created_at`, `updated_at`
) VALUES (
    'La Politique Moderne en Côte d''Ivoire',
    'la-politique-moderne-en-cote-divoire',
    'La politique est une chose très simple qui régit notre société ivoirienne. Elle englobe les décisions prises par le gouvernement, les débats publics, et les choix qui affectent la vie quotidienne des citoyens de Côte d''Ivoire.',
    'Analyse de la politique moderne en Côte d''Ivoire',
    'image/politique.jpg',
    1,
    0,
    NOW(),
    1,
    1,
    0,
    NOW(),
    NOW()
);

-- 4. Créer une catégorie ÉCONOMIE
INSERT IGNORE INTO `categories` (`id`, `nom`, `slug`, `description`, `created_at`, `updated_at`)
VALUES (2, 'ÉCONOMIE', 'economie', 'Articles sur l''économie', NOW(), NOW());

-- 5. Créer un article ÉCONOMIE (PUBLIÉ gratuit)
INSERT INTO `articles` (
    `titre`, `slug`, `contenu`, `extrait`, `image`,
    `is_published`, `is_premium`, `published_at`,
    `user_id`, `category_id`, `views_count`,
    `created_at`, `updated_at`
) VALUES (
    'Les Défis Économiques de 2025',
    'les-defis-economiques-de-2025',
    'L''économie ivoirienne fait face à des défis majeurs en 2025. Entre inflation, croissance et développement durable, les enjeux sont nombreux pour le gouvernement et les entreprises. Cette analyse propose un aperçu des principales problématiques économiques du pays.',
    'Analyse des principaux défis économiques de la Côte d''Ivoire en 2025',
    'image/economie.jpg',
    1,
    0,
    NOW(),
    1,
    2,
    0,
    NOW(),
    NOW()
);

-- 6. Vérifier les données créées
SELECT 'Articles créés:' as info;
SELECT id, titre, is_published, is_premium, category_id FROM articles WHERE is_published = 1;

SELECT 'Catégories créées:' as info;
SELECT id, nom, slug FROM categories;
