# 🔧 Guide de Diagnostic et Correction du Dashboard Admin

## Étape 1: Vérifier la Configuration `.env`

Assure-toi que ton `.env` est correctement configuré:

```bash
# Vérifier ton .env contient:
cat .env | grep -E "DB_|APP_DEBUG"
```

**Doit afficher:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=...
APP_DEBUG=true (temporairement pour le debug)
```

## Étape 2: Exécuter les Migrations

**TRÈS IMPORTANT** - Les tables doivent être créées:

```bash
php artisan migrate --force
```

Cela va créer/mettre à jour toutes les tables de la base de données.

## Étape 3: Vérifier les Tables

```bash
php artisan tinker
>>> \Illuminate\Support\Facades\Schema::hasTable('users')
>>> \Illuminate\Support\Facades\Schema::hasTable('articles')
>>> \Illuminate\Support\Facades\Schema::hasTable('subscriptions')
>>> \Illuminate\Support\Facades\Schema::hasTable('messages')
>>> exit
```

## Étape 4: Exécuter le Diagnostic

```bash
php test-dashboard-diagnostic.php
```

Cela va te montrer exactement quel est le problème.

## Étape 5: Vérifier les Logs

```bash
# Afficher les 100 dernières lignes du log
tail -n 100 storage/logs/laravel.log

# Ou sur Windows (PowerShell):
Get-Content -Path storage/logs/laravel.log -Tail 100
```

## Étape 6: Vider le Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Étape 7: Tester le Dashboard

Accède à: `https://belierintrepide.com/admin/dashboard`

---

## ⚠️ Problèmes Courants

### Erreur 500 persiste?
1. **Vérifie la base de données existe**: `mysql -u forge -p -e "SHOW DATABASES;" | grep forge`
2. **Vérifie les permissions**: `php artisan migrate:status`
3. **Colle-moi les 100 dernières lignes de `storage/logs/laravel.log`**

### Pas de logs?
```bash
# Créer le dossier s'il n'existe pas
mkdir -p storage/logs
chmod -R 775 storage
```

### Migration échoue?
```bash
# Voir quel est le problème
php artisan migrate:rollback
php artisan migrate --verbose
```

---

**Dis-moi quand tu as exécuté ces commandes et colle-moi:**
1. Les résultats du diagnostic
2. Les 100 dernières lignes du laravel.log
3. Les erreurs de migration (s'il y en a)
