-- Script de debug pour vérifier la structure des tables

-- Vérifier la structure de la table categories
DESCRIBE categories;

-- Vérifier la structure de la table articles
DESCRIBE articles;

-- Vérifier s'il y a des données dans categories
SELECT * FROM categories LIMIT 5;

-- Vérifier s'il y a des données dans articles
SELECT id, titre, category_id, is_published FROM articles LIMIT 5;

-- Vérifier les foreign keys
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'articles';

-- Tester la jointure
SELECT
    a.id,
    a.titre,
    a.category_id,
    c.nom as category_nom
FROM articles a
LEFT JOIN categories c ON a.category_id = c.id
LIMIT 5;
