# Bélier Intrepide - Guide de Configuration & Correction Erreur 413

## 🔴 ERREUR 413: Request Entity Too Large

### Cause
Nginx, Apache, ou PHP rejette les uploads > aux limites configurées.

### Solution Complète

#### 1️⃣ **Si vous utilisez Nginx**

Éditez votre fichier de config Nginx (ex: `/etc/nginx/sites-available/default`) :

```nginx
server {
    client_max_body_size 50M;  # ← AJOUTER CETTE LIGNE
    # ... reste de la config
}
```

Ensuite redémarrez Nginx :
```bash
sudo systemctl restart nginx
```

#### 2️⃣ **Si vous utilisez Apache**

Le fichier `public/.htaccess` a été mis à jour avec :
```apache
<IfModule mod_php.c>
    php_value upload_max_filesize 50M
    php_value post_max_size 50M
    php_value memory_limit 256M
    php_value max_execution_time 300
</IfModule>
```

**Important:** Si vous utilisez PHP-FPM (le plus courant), le .htaccess ne suffit pas !
Créez/éditez votre `php.ini` :

#### 3️⃣ **Configuration PHP (OBLIGATOIRE)**

Trouvez votre `php.ini` :
```bash
php -i | grep "php.ini"
```

Ou cherchez les fichiers courants :
- `/etc/php/8.2/fpm/php.ini` (PHP-FPM)
- `/etc/php/8.2/apache2/php.ini` (Apache)
- `/etc/php/8.2/cli/php.ini` (CLI)

Ajoutez/modifiez ces lignes :
```ini
upload_max_filesize = 50M
post_max_size = 50M
memory_limit = 256M
max_execution_time = 300
default_socket_timeout = 60
```

**Puis relancez le service PHP :**
```bash
sudo systemctl restart php8.2-fpm    # Si PHP-FPM
# OU
sudo systemctl restart apache2        # Si Apache
```

**Vérifiez que c'est appliqué :**
```bash
php -i | grep -E "upload_max_filesize|post_max_size"
```

Vous devriez voir les nouvelles valeurs (50M).

---

## 📋 PROBLÈME: Catégorie non reconnue dans le formulaire

### Cause
Le matching JavaScript entre l'input et la datalist était case-sensitive ou avait des espaces.

### Solution
✅ **DÉJÀ CORRIGÉE** dans `resources/views/admin/articles/form.blade.php`

Le formulaire utilise maintenant :
- Recherche **case-insensitive**
- **Trim** automatique des espaces
- Console logs pour debugging
- Validation du choix de catégorie

**Pour debugger :** Ouvrez la console browser (F12) et vérifiez que :
1. `Categories loaded:` affiche vos catégories
2. Quand vous tapez "Politique", le log affiche `Category found:`

---

## 🗂️ STRUCTURE ARTICLES-CATÉGORIES (SIMPLIFIÉ)

### Table `categories`
```
id (PK)
nom (VARCHAR) - ex: "Politique", "Économie"
slug (VARCHAR unique) - ex: "politique", "economie"
description (TEXT)
created_at
updated_at
```

### Table `articles`
```
id (PK)
titre (VARCHAR)
slug (VARCHAR unique)
contenu (LONGTEXT)
extrait (TEXT)
image (VARCHAR) - chemin relatif
document_path (VARCHAR)
is_published (BOOLEAN) - ❌ CRUCIAL
published_at (TIMESTAMP) - ❌ CRUCIAL
category_id (FK → categories.id) - ❌ CRUCIAL
user_id (FK → users.id)
created_at
updated_at
```

### Points Clés
1. **Chaque article doit avoir `category_id`** → lien vers table `categories`
2. **Pour publier un article :**
   - ✅ Cochez "Publier l'article" dans le formulaire
   - ✅ Le contrôleur set automatiquement `is_published = 1` et `published_at = NOW()`
3. **Pour afficher un article public :**
   - Vue fait : `Article::published()` scope
   - Scope filtre : `is_published = 1 AND published_at <= NOW()`

---

## ✅ PROCESSUS COMPLET: Créer & Afficher un Article

### Admin (Créer/Publier)
1. Allez à **Admin → Articles → Créer**
2. Remplissez :
   - **Titre** : ex: "Nouvelles mesures politiques"
   - **Catégorie** : tapez ou choisissez "Politique" depuis la liste
   - **Contenu** : texte principal
   - ✅ **Cochez "Publier l'article"** ← IMPORTANT
3. Cliquez **Créer**

### Résultat en Base de Données
```sql
-- L'article est créé avec:
category_id = 1  (ID de Politique)
is_published = 1
published_at = 2026-06-01 10:30:00
```

### Affichage Public (Catégories Page)
1. Page : `/categories/politique` (slug de la catégorie)
2. Affiche tous les articles WHERE:
   - `category_id = 1`
   - `is_published = 1`
   - `published_at <= NOW()`

---

## 🐛 DÉPANNAGE

### Articles créés mais ne s'affichent pas
**Vérifiez en base de données :**
```sql
SELECT id, titre, is_published, published_at, category_id 
FROM articles 
WHERE id = 123;  -- remplacez 123 par l'ID
```

Si `is_published = 0` ou `published_at = NULL` :
```sql
UPDATE articles 
SET is_published = 1, published_at = NOW() 
WHERE id = 123;
```

### Catégorie n'apparaît pas dans la datalist
1. Ouvrez la console (F12)
2. Vérifiez que `Categories loaded:` affiche votre catégorie
3. Si absente, vérifiez en base :
```sql
SELECT id, nom FROM categories;
```

### Erreur 413 persiste après redémarrage
1. Vérifiez que **TOUS** les 3 éléments sont configurés :
   - ✅ Nginx `client_max_body_size 50M`
   - ✅ PHP `upload_max_filesize 50M` 
   - ✅ PHP `post_max_size 50M`
2. Redémarrez **tous** les services :
```bash
sudo systemctl restart nginx php8.2-fpm
# ou
sudo systemctl restart apache2
```
3. Testez avec une image < 5MB d'abord

---

## 📖 FICHIERS MODIFIÉS

- ✅ `public/.htaccess` - Limites PHP pour Apache
- ✅ `nginx.conf` - Exemple config Nginx
- ✅ `php.ini.example` - Template php.ini
- ✅ `resources/views/admin/articles/form.blade.php` - JS catégories amélioré
- ✅ `app/Http/Controllers/Admin/ArticleController.php` - Gestion category_id

---

## 💡 PROCHAINES ÉTAPES

1. **Testez la création d'un article** (formulaire débugué)
2. **Vérifiez qu'il apparaît** sur la page catégorie publique
3. **Si erreur 413 persiste**, appliques les 3 configs serveur ci-dessus

**Questions ?** Vérifiez les logs :
- Nginx: `/var/log/nginx/error.log`
- Apache: `/var/log/apache2/error.log`
- PHP-FPM: `/var/log/php-fpm.log`
