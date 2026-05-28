# 🚀 GUIDE RAPIDE - Upload de Fichiers

## ✅ Système Déjà Fonctionnel !

Votre formulaire de création d'article **possède déjà** tout ce qu'il faut pour uploader des fichiers depuis votre ordinateur !

---

## 📝 3 Étapes Simples

### 1️⃣ Créer un Article
```
/admin/dashboard → Cliquer "➕ Nouvel Article"
```

### 2️⃣ Uploader les Fichiers

#### 🖼️ Image
```
[📁 Choisir un fichier] ← Sélectionner depuis PC
                          ✅ Prévisualisation automatique
Formats: JPG, PNG, GIF, WEBP (max 2MB)
```

#### 📄 Document
```
[📁 Choisir un fichier] ← Sélectionner depuis PC
                          ✅ Prévisualisation avec icône
Formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (max 10MB)
```

### 3️⃣ Publier
```
☑ Publier l'article
[✅ Créer l'article]
```

---

## 🎨 Affichage Automatique

### Page Publique de l'Article

```
┌─────────────────────────────────────┐
│ 📰 Titre de l'Article               │
│                                     │
│ ┌─────────────────────────────────┐│
│ │   [IMAGE UPLOADÉE]              ││ ← Image affichée
│ └─────────────────────────────────┘│
│                                     │
│ Contenu de l'article...             │
│                                     │
│ ┌─────────────────────────────────┐│
│ │ [📥 Télécharger le document]    ││ ← Document téléchargeable
│ │     fichier.pdf (3.5 MB)        ││
│ └─────────────────────────────────┘│
└─────────────────────────────────────┘
```

---

## 📁 Où Sont Stockés les Fichiers ?

```
storage/app/public/articles/
├── images/
│   └── [timestamp]_titre-article.jpg
└── documents/
    └── [timestamp]_titre-article.pdf
```

**Les fichiers sont uploadés depuis votre PC, pas depuis le storage Laravel !**

---

## 🔐 Contrôle d'Accès

| Utilisateur | Résultat |
|-------------|----------|
| Non connecté | 🔒 Se connecter pour télécharger |
| Sans abonnement | 👑 Abonnement requis |
| Abonné | ✅ Téléchargement autorisé |

---

## ✨ Fonctionnalités

✅ **Upload depuis votre ordinateur** (pas de storage Laravel)  
✅ **Prévisualisation en temps réel**  
✅ **Validation automatique** (taille, format)  
✅ **Icônes adaptées** au type de fichier  
✅ **Affichage automatique** sur la page publique  
✅ **Contrôle d'accès** pour documents  

---

## 🧪 Test Rapide

1. `/admin/articles/create`
2. Sélectionner une image depuis votre PC
3. Sélectionner un document depuis votre PC
4. Voir les prévisualisations ✅
5. Publier
6. Vérifier sur la page de l'article

---

✅ **Tout fonctionne déjà ! Aucune configuration nécessaire !** 🎉
