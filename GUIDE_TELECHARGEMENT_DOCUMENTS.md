# 📂 GUIDE COMPLET - SYSTÈME DE TÉLÉCHARGEMENT DE DOCUMENTS

## ✅ STRUCTURE DES DOSSIERS CRÉÉS

```
belier-intrepide3/
├── storage/
│   └── app/
│       └── public/
│           └── articles/
│               ├── documents/     ← 📄 PLACE TES PDF ICI
│               └── images/        ← 🖼️ Images des articles
│
└── public/
    └── storage/  ← Lien symbolique vers storage/app/public
```

---

## 📥 OÙ PLACER TES DOCUMENTS PDF ?

### **Chemin complet :**
```
C:\Users\USER\Desktop\belier-intrepide3\storage\app\public\articles\documents\
```

### **Exemples de fichiers à placer :**
```
documents/
├── 1730923456_article-economie.pdf
├── 1730923789_rapport-pdci.pdf
└── mon-document.pdf
```

---

## ⚠️ ÉTAPE IMPORTANTE : CRÉER LE LIEN SYMBOLIQUE

### **Méthode 1 : Fichier .bat (RECOMMANDÉ)**

1. **Clic droit** sur le fichier : `create_storage_link.bat`
2. Sélectionne **"Exécuter en tant qu'administrateur"**
3. Appuie sur **Entrée**

### **Méthode 2 : PowerShell (Admin)**

Ouvre PowerShell **en tant qu'administrateur** et exécute :
```powershell
cd C:\Users\USER\Desktop\belier-intrepide3
New-Item -ItemType SymbolicLink -Path "public\storage" -Target "storage\app\public"
```

### **Méthode 3 : Laravel Artisan (Quand PHP fonctionnera)**
```bash
php artisan storage:link
```

---

## 🔧 COMMENT ÇA FONCTIONNE ?

### **1. Upload via le Dashboard Admin**
Quand tu uploades un document dans le formulaire d'article :
- Le fichier est enregistré dans : `storage/app/public/articles/documents/`
- Le nom du fichier est : `timestamp_slug-du-titre.pdf`
- Le chemin est sauvegardé dans la BDD : colonne `document_path`

### **2. Téléchargement par les utilisateurs**
Quand quelqu'un clique sur "Télécharger PDF" :
- Laravel cherche le fichier dans : `storage/app/public/articles/documents/`
- Le fichier est téléchargé avec son nom original

---

## 📝 STRUCTURE BASE DE DONNÉES

### **Table `articles`** - Colonnes importantes :
```sql
- document_path           VARCHAR(255)  -- Chemin du fichier
- file_original_name      VARCHAR(255)  -- Nom original du fichier
- file_size              BIGINT         -- Taille en octets
```

---

## 🧪 TEST MANUEL

### **Pour tester sans uploader via le dashboard :**

1. **Place un fichier PDF** dans :
   ```
   C:\Users\USER\Desktop\belier-intrepide3\storage\app\public\articles\documents\test.pdf
   ```

2. **Ajoute manuellement dans la BDD** (via phpMyAdmin) :
   ```sql
   UPDATE articles 
   SET document_path = 'articles/documents/test.pdf',
       file_original_name = 'mon-document.pdf',
       file_size = 524288
   WHERE id = 1;
   ```

3. **Vérifie le lien de téléchargement** :
   - URL : `http://localhost/articles/1/download`
   - Le fichier doit se télécharger automatiquement

---

## 🔍 VÉRIFICATION

### **Vérifier que les dossiers existent :**
```powershell
Test-Path "C:\Users\USER\Desktop\belier-intrepide3\storage\app\public\articles\documents"
# Doit retourner : True
```

### **Vérifier que le lien symbolique existe :**
```powershell
Test-Path "C:\Users\USER\Desktop\belier-intrepide3\public\storage"
# Doit retourner : True
```

---

## 🐛 DÉPANNAGE

### **Problème : Le fichier ne se télécharge pas**

**Solutions :**
1. ✅ Vérifie que le lien symbolique existe
2. ✅ Vérifie que le fichier existe physiquement
3. ✅ Vérifie les permissions du dossier `storage/`

### **Erreur : "Document non trouvé"**

**Causes possibles :**
- Le chemin dans la BDD est incorrect
- Le fichier n'existe pas physiquement
- Le lien symbolique n'existe pas

### **Erreur : "Permission denied"**

**Solution :**
```powershell
# Donner les permissions en lecture/écriture
icacls "C:\Users\USER\Desktop\belier-intrepide3\storage" /grant Users:F /T
```

---

## 📌 RÉSUMÉ RAPIDE

1. ✅ **Dossiers créés** : `storage/app/public/articles/documents/`
2. ⏳ **Lien symbolique** : À créer avec `create_storage_link.bat` (admin requis)
3. 📄 **Place tes PDF** dans : `storage/app/public/articles/documents/`
4. 🔗 **Accès web** : Accessible via `public/storage/articles/documents/`

---

## 🎯 PROCHAINES ÉTAPES

1. **Exécute** `create_storage_link.bat` en tant qu'administrateur
2. **Place** un fichier PDF test dans `storage/app/public/articles/documents/`
3. **Teste** le téléchargement sur ton site

**Besoin d'aide ?** Relis ce guide ou demande-moi ! 🚀
