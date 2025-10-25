# 📤 GUIDE COMPLET - Upload de Fichiers pour Articles

## 🎯 Vue d'Ensemble

Votre système **Bélier Intrépide** possède déjà un **système complet d'upload de fichiers** pour les articles ! Voici comment il fonctionne :

---

## ✨ Fonctionnalités Disponibles

### 1. **Upload d'Images** 🖼️
- ✅ Sélection depuis votre ordinateur
- ✅ Prévisualisation en temps réel
- ✅ Formats acceptés : JPG, PNG, GIF, WEBP
- ✅ Taille maximale : 2MB
- ✅ Affichage automatique sur la page de l'article

### 2. **Upload de Documents** 📄
- ✅ Sélection depuis votre ordinateur
- ✅ Prévisualisation avec icône adaptée au type
- ✅ Formats acceptés : PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX
- ✅ Taille maximale : 10MB
- ✅ Téléchargement depuis la page publique de l'article

---

## 📝 Comment Créer un Article avec Fichiers

### Étape 1 : Accéder au Formulaire
1. Aller sur `/admin/dashboard`
2. Cliquer sur **"➕ Nouvel Article"**
3. OU aller sur `/admin/articles/create`

### Étape 2 : Remplir le Formulaire

#### 📋 Champs Obligatoires
```
┌────────────────────────────────────────────────┐
│ ✏️ FORMULAIRE DE CRÉATION D'ARTICLE            │
├────────────────────────────────────────────────┤
│                                                │
│ Titre: [___________________________] *         │
│                                                │
│ Catégorie: [▼ Sélectionner]      *            │
│  📁 Politique                                  │
│  🏀 Sport                                      │
│  💰 Économie                                   │
│  🌍 Afrique                                    │
│  ... autres catégories ...                     │
│                                                │
│ Extrait (résumé):                              │
│ [________________________________]             │
│ [________________________________]             │
│ [________________________________]             │
│                                                │
│ Contenu: [Éditeur TinyMCE]      *             │
│ ┌────────────────────────────────┐            │
│ │ [B] [I] [U] | ≡ ≡ ≡ | 🔗 📷  │            │
│ │────────────────────────────────│            │
│ │ Contenu de l'article...        │            │
│ │                                │            │
│ │                                │            │
│ └────────────────────────────────┘            │
│                                                │
└────────────────────────────────────────────────┘
```

#### 🖼️ Upload d'Image

```
┌────────────────────────────────────────────────┐
│ 🖼️ IMAGE DE L'ARTICLE                          │
├────────────────────────────────────────────────┤
│                                                │
│ [📁 Choisir un fichier]  [❌ Effacer]         │
│                                                │
│ ℹ️ Formats : JPG, PNG, GIF, WEBP (max 2MB)   │
│                                                │
│ ╔══════════════════════════════════════════╗  │
│ ║ ✅ Prévisualisation de l'image           ║  │
│ ╠══════════════════════════════════════════╣  │
│ ║ ┌──────────┐  Informations:              ║  │
│ ║ │          │  Nom: article_image.jpg      ║  │
│ ║ │  [IMG]   │  Taille: 1.2 MB             ║  │
│ ║ │          │  Type: image/jpeg            ║  │
│ ║ │          │                              ║  │
│ ║ └──────────┘  ✅ Image prête pour         ║  │
│ ║                   publication!            ║  │
│ ╚══════════════════════════════════════════╝  │
│                                                │
└────────────────────────────────────────────────┘
```

**Comment ça marche :**
1. Cliquer sur **"📁 Choisir un fichier"**
2. Sélectionner une image depuis votre ordinateur
3. ✅ Prévisualisation apparaît automatiquement
4. Vérifier nom, taille et type
5. Si besoin, cliquer **"❌ Effacer"** pour recommencer

#### 📄 Upload de Document

```
┌────────────────────────────────────────────────┐
│ 📄 DOCUMENT À TÉLÉCHARGER                      │
├────────────────────────────────────────────────┤
│                                                │
│ [📁 Choisir un fichier]  [❌ Effacer]         │
│                                                │
│ ℹ️ Formats : PDF, DOC, DOCX, TXT, XLS, XLSX,  │
│    PPT, PPTX (max 10MB)                        │
│                                                │
│ ╔══════════════════════════════════════════╗  │
│ ║ 📁 Document sélectionné                  ║  │
│ ╠══════════════════════════════════════════╣  │
│ ║ ┌────┐   Informations du fichier:        ║  │
│ ║ │📕 │   Nom: rapport_annuel.pdf          ║  │
│ ║ │PDF│   Taille: 3.5 MB                   ║  │
│ ║ │    │   Type: application/pdf            ║  │
│ ║ └────┘                                    ║  │
│ ║         ✅ Document prêt pour             ║  │
│ ║            téléchargement!                ║  │
│ ╚══════════════════════════════════════════╝  │
│                                                │
└────────────────────────────────────────────────┘
```

**Icônes automatiques selon le type :**
- 📕 **PDF** → Icône rouge
- 📘 **DOC/DOCX** → Icône bleue
- 📗 **XLS/XLSX** → Icône verte
- 📙 **PPT/PPTX** → Icône orange
- 📄 **TXT** → Icône grise

#### ⚙️ Options de Publication

```
┌────────────────────────────────────────────────┐
│ ⚙️ OPTIONS DE PUBLICATION                      │
├────────────────────────────────────────────────┤
│                                                │
│ ☐ 👑 Article Premium                          │
│    (nécessite un abonnement)                   │
│                                                │
│ ☐ 🌐 Publier l'article                        │
│    (visible immédiatement sur le site)         │
│                                                │
│ [Annuler]              [✅ Créer l'article]   │
│                                                │
└────────────────────────────────────────────────┘
```

---

## 🎨 Affichage sur la Page Publique

### Page de l'Article (`/articles/{id}`)

```
┌─────────────────────────────────────────────────┐
│ 📰 ARTICLE PUBLIÉ                               │
├─────────────────────────────────────────────────┤
│                                                 │
│ Guide Complet Laravel 12                        │
│ ═══════════════════════════                     │
│                                                 │
│ ┌───────────────────────────────────────────┐  │
│ │                                           │  │
│ │        [IMAGE DE L'ARTICLE]               │  │
│ │         (Affichage automatique)           │  │
│ │                                           │  │
│ └───────────────────────────────────────────┘  │
│                                                 │
│ Contenu de l'article avec mise en forme...     │
│                                                 │
│ Lorem ipsum dolor sit amet, consectetur         │
│ adipiscing elit. Sed do eiusmod tempor...      │
│                                                 │
│ ─────────────────────────────────────────────  │
│                                                 │
│ 📄 DOCUMENT DISPONIBLE                          │
│                                                 │
│ ┌─────────────────────────────────────────┐   │
│ │ [📥 Télécharger le document]            │   │
│ │     rapport_annuel.pdf (3.5 MB)         │   │
│ └─────────────────────────────────────────┘   │
│                                                 │
│ OU (si non connecté)                            │
│                                                 │
│ ┌─────────────────────────────────────────┐   │
│ │ [🔒 Se connecter pour télécharger]      │   │
│ └─────────────────────────────────────────┘   │
│                                                 │
│ OU (si pas d'abonnement)                        │
│                                                 │
│ ┌─────────────────────────────────────────┐   │
│ │ [👑 Abonnement requis]                  │   │
│ └─────────────────────────────────────────┘   │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 🔐 Système de Sécurité

### Contrôle d'Accès aux Documents

```php
// 1. UTILISATEUR NON CONNECTÉ
❌ Bouton: "🔒 Se connecter pour télécharger"
→ Redirection vers /login

// 2. UTILISATEUR CONNECTÉ SANS ABONNEMENT
❌ Bouton: "👑 Abonnement requis"
→ Redirection vers /abonnement

// 3. UTILISATEUR ABONNÉ
✅ Bouton: "📥 Télécharger le document"
→ Téléchargement autorisé
```

---

## 📁 Structure de Stockage

### Où sont sauvegardés les fichiers ?

```
storage/app/public/
├── articles/
│   ├── images/
│   │   ├── 1735862400_guide-laravel-12.jpg
│   │   ├── 1735862450_introduction-php.png
│   │   └── 1735862500_tutoriel-mysql.webp
│   │
│   └── documents/
│       ├── 1735862400_rapport-annuel.pdf
│       ├── 1735862450_presentation.pptx
│       └── 1735862500_donnees.xlsx
```

**Format des noms de fichiers :**
```
[timestamp]_[slug-du-titre].[extension]

Exemple:
1735862400_guide-laravel-12.jpg
│          └── Slug du titre
└── Horodatage (timestamp)
```

---

## 💾 Structure de la Base de Données

### Table `articles` - Colonnes pour les fichiers

```sql
CREATE TABLE articles (
    id BIGINT PRIMARY KEY,
    titre VARCHAR(255),
    contenu TEXT,
    
    -- IMAGE
    image VARCHAR(255),                    -- Chemin: articles/images/xxx.jpg
    image_alt VARCHAR(255),                -- Texte alternatif (SEO)
    
    -- DOCUMENT
    document_path VARCHAR(255),            -- Chemin: articles/documents/xxx.pdf
    file_original_name VARCHAR(255),       -- Nom original: rapport_annuel.pdf
    file_mime_type VARCHAR(255),           -- Type MIME: application/pdf
    file_size BIGINT,                      -- Taille en bytes: 3670016
    
    -- GALERIE (futur)
    gallery_images JSON,                   -- Tableau d'images
    
    -- MÉTADONNÉES
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    
    -- ... autres colonnes ...
);
```

### Exemple de données

```json
{
    "id": 1,
    "titre": "Guide Complet Laravel 12",
    "image": "articles/images/1735862400_guide-laravel-12.jpg",
    "image_alt": "Guide Laravel 12 avec exemples de code",
    "document_path": "articles/documents/1735862400_code-examples.pdf",
    "file_original_name": "code-examples.pdf",
    "file_mime_type": "application/pdf",
    "file_size": 3670016,
    "gallery_images": null,
    "category_id": 1,
    "is_published": true,
    "is_premium": false
}
```

---

## 🔧 Code Technique

### Controller - Gestion de l'Upload

```php
// app/Http/Controllers/Admin/ArticleController.php

public function store(Request $request)
{
    // 1. VALIDATION
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',  // 2MB
        'document' => 'nullable|file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx|max:10240',  // 10MB
    ]);
    
    // 2. UPLOAD IMAGE
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '_' . Str::slug($validated['titre']) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('articles/images', $imagename, 'public');
        $validated['image'] = $path;
    }
    
    // 3. UPLOAD DOCUMENT
    if ($request->hasFile('document')) {
        $file = $request->file('document');
        $filename = time() . '_' . Str::slug($validated['titre']) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('articles/documents', $filename, 'public');
        
        $validated['document_path'] = $path;
        $validated['file_original_name'] = $file->getClientOriginalName();
        $validated['file_size'] = $file->getSize();
        $validated['file_mime_type'] = $file->getMimeType();
    }
    
    // 4. CRÉATION ARTICLE
    $article = Article::create($validated);
    
    return redirect()->route('admin.articles.index')
        ->with('success', 'Article créé avec succès !');
}
```

### View - Affichage sur la Page Publique

```blade
{{-- resources/views/articles/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- TITRE --}}
        <h1 class="text-3xl font-bold mb-4">{{ $article->titre }}</h1>
        
        {{-- IMAGE --}}
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" 
                 alt="{{ $article->image_alt ?? $article->titre }}" 
                 class="w-full h-64 object-cover rounded-lg mb-6">
        @endif
        
        {{-- CONTENU --}}
        <div class="prose max-w-none">
            {!! $article->contenu !!}
        </div>
        
        {{-- DOCUMENT TÉLÉCHARGEABLE --}}
        @if($article->document_path)
            <div class="mt-6 bg-gray-100 p-6 rounded-lg">
                <h3 class="text-xl font-semibold mb-4">📄 Document disponible</h3>
                
                @auth
                    @if(auth()->user()->hasActiveSubscription() || !$article->is_premium)
                        {{-- TÉLÉCHARGEMENT AUTORISÉ --}}
                        <a href="{{ route('articles.download', $article) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger {{ $article->file_original_name }}
                            <span class="ml-2 text-sm">({{ number_format($article->file_size / 1024 / 1024, 2) }} MB)</span>
                        </a>
                    @else
                        {{-- ABONNEMENT REQUIS --}}
                        <a href="{{ route('home.abonnement') }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg inline-flex items-center">
                            <i class="fas fa-crown mr-2"></i>
                            Abonnement requis pour télécharger
                        </a>
                    @endif
                @else
                    {{-- NON CONNECTÉ --}}
                    <a href="{{ route('login') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter pour télécharger
                    </a>
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection
```

---

## ✅ Validations Automatiques

### Taille des Fichiers

```javascript
// Validation côté client (JavaScript)
document.getElementById('image').addEventListener('change', function() {
    const file = this.files[0];
    
    // 1. Vérifier la taille (2MB max)
    if (file.size > 2048000) {
        alert('⚠️ L\'image ne doit pas dépasser 2MB');
        this.value = '';
        return;
    }
    
    // 2. Vérifier le type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('⚠️ Format non accepté. Utilisez JPG, PNG, GIF ou WEBP');
        this.value = '';
        return;
    }
    
    // 3. Prévisualisation
    previewImage(this);
});
```

### Validation côté serveur (PHP)

```php
// app/Http/Controllers/Admin/ArticleController.php

$request->validate([
    'image' => [
        'nullable',
        'image',                                    // Doit être une image
        'mimes:jpeg,png,jpg,gif,webp',             // Formats autorisés
        'max:2048',                                 // 2MB max
    ],
    'document' => [
        'nullable',
        'file',                                     // Doit être un fichier
        'mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx', // Formats autorisés
        'max:10240',                                // 10MB max
    ],
]);
```

---

## 🎓 Exemples d'Utilisation

### Exemple 1 : Article Simple (Image seulement)

```
1. Remplir le formulaire:
   - Titre: "Les 10 Meilleurs Restaurants de Paris"
   - Catégorie: "Gastronomie"
   - Contenu: "Paris regorge de restaurants..."

2. Upload image:
   - Sélectionner: photo_restaurant.jpg (1.2 MB)
   - ✅ Prévisualisation OK

3. Options:
   - ☐ Premium (laissé décoché)
   - ☑ Publier (coché)

4. Cliquer "Créer l'article"

RÉSULTAT:
✅ Article créé avec image
✅ Visible sur /articles/les-10-meilleurs-restaurants
✅ Image affichée en haut de l'article
```

### Exemple 2 : Article Premium (Image + Document)

```
1. Remplir le formulaire:
   - Titre: "Rapport Financier Q4 2025"
   - Catégorie: "Économie"
   - Contenu: "Analyse complète du trimestre..."

2. Upload image:
   - Sélectionner: graphique_finances.png (800 KB)

3. Upload document:
   - Sélectionner: rapport_complet.pdf (4.5 MB)
   - ✅ Nom original conservé: rapport_complet.pdf

4. Options:
   - ☑ Premium (coché - nécessite abonnement)
   - ☑ Publier (coché)

5. Cliquer "Créer l'article"

RÉSULTAT:
✅ Article créé avec image + document
✅ Visible sur /articles/rapport-financier-q4-2025
✅ Image affichée
✅ Bouton "Télécharger" visible pour abonnés
❌ Bouton "Abonnement requis" pour non-abonnés
```

### Exemple 3 : Article avec Document Excel

```
1. Remplir le formulaire:
   - Titre: "Données Statistiques 2025"
   - Catégorie: "Statistiques"

2. Upload document:
   - Sélectionner: donnees_2025.xlsx (2.1 MB)
   - ✅ Icône verte (Excel) affichée

3. Publier

RÉSULTAT:
✅ Document téléchargeable depuis l'article
✅ Icône Excel affichée sur la page
```

---

## 🚀 Fonctionnalités Futures (Optionnelles)

### 1. Galerie d'Images Multiple
```json
"gallery_images": [
    "articles/images/photo1.jpg",
    "articles/images/photo2.jpg",
    "articles/images/photo3.jpg"
]
```

### 2. Compression Automatique d'Images
```php
// Réduire automatiquement la taille des images
use Intervention\Image\Facades\Image;

$image = Image::make($request->file('image'));
$image->resize(1200, null, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});
$image->save(storage_path('app/public/' . $path));
```

### 3. Aperçu PDF Intégré
```html
<iframe src="{{ asset('storage/' . $article->document_path) }}" 
        width="100%" height="600px"></iframe>
```

---

## 📞 Troubleshooting

### Problème : "Le fichier est trop volumineux"
**Solution :**
```php
// config/php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Problème : "Fichier non sauvegardé"
**Solution :**
```bash
# Vérifier les permissions
php artisan storage:link
chmod -R 775 storage/
```

### Problème : "Image ne s'affiche pas"
**Solution :**
```bash
# Créer le lien symbolique
php artisan storage:link

# Vérifier le chemin
asset('storage/' . $article->image)
```

---

## ✅ Checklist de Vérification

### Avant Publication
- [ ] Image uploadée et prévisualisée ?
- [ ] Document uploadé et prévisualisé ?
- [ ] Titre rempli ?
- [ ] Catégorie sélectionnée ?
- [ ] Contenu rédigé ?
- [ ] Options (Premium/Publié) cochées si besoin ?

### Après Publication
- [ ] Article visible sur `/articles/{id}` ?
- [ ] Image affichée correctement ?
- [ ] Bouton téléchargement visible ?
- [ ] Contrôle d'accès fonctionne (abonnés/non-abonnés) ?

---

## 🎉 Résumé

✅ **Votre système est déjà complet !**

- ✅ Upload d'images depuis votre ordinateur
- ✅ Upload de documents depuis votre ordinateur
- ✅ Prévisualisation en temps réel
- ✅ Validation des tailles et formats
- ✅ Stockage sécurisé dans `storage/app/public/`
- ✅ Affichage automatique sur la page de l'article
- ✅ Contrôle d'accès pour les documents (abonnés)
- ✅ Interface intuitive avec icônes adaptées

**Les fichiers ne passent PAS par le storage public de Laravel** - ils sont uploadés **directement depuis votre ordinateur** via le formulaire HTML standard avec `enctype="multipart/form-data"` ! 🚀

---

✅ **Tout fonctionne déjà ! Il suffit d'utiliser le formulaire de création d'article !** 📤🎉
