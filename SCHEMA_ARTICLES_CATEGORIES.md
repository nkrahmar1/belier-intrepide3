# 📊 SCHÉMA OPTIMISÉ: Articles & Catégories - Bélier Intrepide

## 🗄️ STRUCTURE BASE DE DONNÉES (Simplifié & Robuste)

### Table 1: `categories`
| Colonne | Type | Contrainte | Description |
|---------|------|-----------|-------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identifiant unique |
| `nom` | VARCHAR(100) | UNIQUE, NOT NULL | Nom affiché (ex: "Politique") |
| `slug` | VARCHAR(100) | UNIQUE, INDEX | URL-friendly (ex: "politique") |
| `description` | TEXT | NULL | Description optionnelle |
| `image` | VARCHAR(255) | NULL | Image de catégorie (optionnel) |
| `created_at` | TIMESTAMP | DEFAULT NOW() | Date création |
| `updated_at` | TIMESTAMP | DEFAULT NOW() | Date modification |
| `deleted_at` | TIMESTAMP | NULL, INDEX | Soft delete |

**Indices:** `(slug)`, `(deleted_at)`

### Table 2: `articles`
| Colonne | Type | Contrainte | Description |
|---------|------|-----------|-------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identifiant unique |
| `titre` | VARCHAR(255) | NOT NULL | Titre affiché |
| `slug` | VARCHAR(255) | UNIQUE, INDEX | URL-friendly |
| `contenu` | LONGTEXT | NOT NULL | Texte principal |
| `extrait` | TEXT | NULL | Résumé court |
| `image` | VARCHAR(255) | NULL | Image article |
| `document_path` | VARCHAR(255) | NULL | Chemin PDF/DOC |
| **`category_id`** | BIGINT UNSIGNED | NOT NULL, FK, **INDEX** | 🔗 **LIEN CRITIQUE** → `categories.id` |
| **`is_published`** | BOOLEAN | DEFAULT 0, **INDEX** | ✅ **CLÉS DE PUBLICATION** |
| **`published_at`** | TIMESTAMP | NULL, **INDEX** | 📅 **CLÉS DE PUBLICATION** |
| `is_premium` | BOOLEAN | DEFAULT 0 | Contenu premium payant |
| `is_featured` | BOOLEAN | DEFAULT 0 | Mettre en avant (homepage) |
| `article_type` | VARCHAR(50) | NULL | Type (brève, analyse, etc.) |
| `content_quality` | TINYINT | NULL (0-100) | Score qualité |
| `unit_price` | DECIMAL(8,2) | NULL | Prix unitaire |
| `free_download_limit` | INT | DEFAULT 0 | Limites téléchargements gratuits |
| `user_id` | BIGINT UNSIGNED | NOT NULL, FK | Auteur → `users.id` |
| `views_count` | BIGINT UNSIGNED | DEFAULT 0 | Compteur vues |
| `downloads_count` | BIGINT UNSIGNED | DEFAULT 0 | Compteur téléchargements |
| `storage_size` | BIGINT UNSIGNED | NULL | Taille stockée (bytes) |
| `file_original_name` | VARCHAR(255) | NULL | Nom original document |
| `file_size` | BIGINT UNSIGNED | NULL | Taille fichier (bytes) |
| `created_at` | TIMESTAMP | DEFAULT NOW() | Date création |
| `updated_at` | TIMESTAMP | DEFAULT NOW() | Date modification |
| `deleted_at` | TIMESTAMP | NULL, INDEX | Soft delete |

**Indices prioritaires:** `(is_published, published_at)`, `(category_id, is_published)`, `(user_id)`, `(slug)`

---

## 🔑 RELATIONS & CONTRAINTES

```sql
ALTER TABLE articles ADD CONSTRAINT fk_articles_category
  FOREIGN KEY (category_id) REFERENCES categories(id) 
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE articles ADD CONSTRAINT fk_articles_user
  FOREIGN KEY (user_id) REFERENCES users(id) 
  ON DELETE CASCADE ON UPDATE CASCADE;
```

---

## 📋 FLUX COMPLET: Créer → Publier → Afficher

### 1. **Admin crée un article**

**Formulaire** (`resources/views/admin/articles/form.blade.php`):
```html
Titre: "Nouvelles mesures politiques"
Catégorie: [input datalist] → sélectionne "Politique"
  └─ JS remplit hidden field: category_id = 1
Contenu: [éditeur]
Publier l'article: ☑️ coché
```

### 2. **Contrôleur traite la création**

**Code** (`ArticleController::store()`):
```php
// 1. Résoudre category_id (priorité à l'ID fourni)
if ($request->filled('category_id')) {
    $category = Category::find($request->category_id);
} else {
    $category = Category::whereRaw('LOWER(nom) = ?', [ucwords(strtolower($request->category_name))])
        ->first() 
        ?? Category::create(['nom' => ..., 'slug' => ...]);
}

// 2. Si publié: set published_at
if ($request->has('is_published')) {
    $data['is_published'] = 1;
    $data['published_at'] = now();  // ✅ CRUCIAL
    Log::info("Article créé PUBLIÉ avec published_at = " . now());
}

// 3. Créer l'article
Article::create($data);
```

### 3. **Article sauvegardé en base**

```sql
INSERT INTO articles 
  (titre, slug, category_id, is_published, published_at, user_id, ...)
VALUES 
  ('Nouvelles mesures...', 'nouvelles-mesures', 1, 1, '2026-06-01 10:30:00', 5, ...);
```

### 4. **Public accède à la catégorie**

**Route publique** (`web.php`):
```php
Route::get('/categories/{slug}', [ArticleController::class, 'byCategory']);
```

**Contrôleur** (`ArticleController::byCategory($slug)`):
```php
$category = Category::where('slug', $slug)->firstOrFail();

$articles = Article::where('category_id', $category->id)
    ->published()  // ← Scope: WHERE is_published=1 AND published_at <= NOW()
    ->latest()
    ->paginate(12);

return view('articles.category', compact('category', 'articles'));
```

### 5. **Vue affiche les articles**

**Template** (`resources/views/articles/category.blade.php`):
```blade
<h1>{{ $category->nom }}</h1>

@forelse($articles as $article)
    <div class="article-card">
        <h2>{{ $article->titre }}</h2>
        <p>{{ $article->extrait }}</p>
        <a href="/articles/{{ $article->slug }}">Lire</a>
    </div>
@empty
    <p>Aucun article publié dans cette catégorie.</p>
@endforelse
```

---

## ✅ CHECKLIST: Articles Publiés Correctement

### En Admin
- [ ] Titre rempli ✓
- [ ] Catégorie sélectionnée ✓ (datalist liste "Politique")
- [ ] Contenu saisi ✓
- [ ] Checkbox "Publier l'article" ✅ **COCHÉ**
- [ ] Cliquer "Créer" ✓

### En Base de Données
Vérifier avec cette requête :
```sql
SELECT 
  a.id, 
  a.titre, 
  c.nom as categorie,
  a.is_published,
  a.published_at,
  CASE 
    WHEN a.is_published = 1 AND a.published_at <= NOW() THEN '✅ VISIBLE'
    WHEN a.is_published = 0 THEN '❌ BROUILLON'
    WHEN a.published_at > NOW() THEN '⏳ FUTUR'
    ELSE '❌ published_at NULL'
  END as statut
FROM articles a
JOIN categories c ON a.category_id = c.id
WHERE c.nom = 'Politique'
ORDER BY a.created_at DESC;
```

### Résultat attendu
```
id  | titre                      | categorie | is_published | published_at        | statut
1   | Nouvelles mesures...       | Politique | 1            | 2026-06-01 10:30:00 | ✅ VISIBLE
```

### Sur le site public
- URL: `https://domaine.com/categories/politique`
- Affiche: L'article "Nouvelles mesures politiques" avec sa vignette, extrait, etc.

---

## 🚨 ERREURS COURANTES & SOLUTIONS

| Problème | Cause | Solution |
|----------|-------|----------|
| Article ne s'affiche pas | `is_published = 0` | Cochez "Publier l'article" avant de créer |
| Article ne s'affiche pas | `published_at = NULL` | Vérifiez logs; le contrôleur aurait dû le set |
| Article ne s'affiche pas | Mauvais `category_id` | Vérifiez avec `SELECT * FROM articles WHERE id=123;` |
| Catégorie pas dans datalist | Catégorie inexistante en DB | Créez-la d'abord: `INSERT INTO categories (nom, slug) VALUES ('Politique', 'politique');` |
| Erreur 413 upload | Limites PHP trop petites | Suivez GUIDE_CORRECTION_413_CATEGORIES.md |

---

## 🔧 MAINTENANCE

### Nettoyer les brouillons orphelins
```sql
DELETE FROM articles WHERE is_published = 0 AND created_at < DATE_SUB(NOW(), INTERVAL 3 MONTH);
```

### Récupérer les articles sans category_id
```sql
SELECT id, titre FROM articles WHERE category_id IS NULL;
```

### Corriger en masse les published_at manquants
```sql
UPDATE articles 
SET published_at = NOW() 
WHERE is_published = 1 AND published_at IS NULL;
```

---

## 📈 OPTIMISATIONS FUTURES

1. **Soft deletes** → Articles archivés, catégories archivées
2. **Tags supplémentaires** → Articles liés par tags (many-to-many)
3. **Analytics** → Vues, likes, commentaires
4. **Cache** → Redis pour articles par catégorie
5. **Versioning** → Historique modifications articles

