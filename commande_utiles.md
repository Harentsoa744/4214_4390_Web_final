# Commandes Utiles — Mobile Money (CodeIgniter 4 + SQLite)

## ⚠️ IMPORTANT : Ordre d'exécution pour une installation propre
Exécuter les commandes dans cet ordre depuis le dossier du projet.

---

## 1. Vérifier l'état des migrations
```bash
php spark migrate:status
```

## 2. Lancer les migrations (créer les tables)
```bash
php spark migrate
```

## 3. Annuler toutes les migrations (supprimer les tables)
```bash
php spark migrate:rollback
```

## 4. Lancer le seeder (insérer les données de test)
```bash
php spark db:seed DatabaseSeeder
```

---

## 🔄 Reset complet de la base de données (en cas de conflit)

### Option A : Rollback + Migrate + Seed (recommandé)
```bash
php spark migrate:rollback
php spark migrate
php spark db:seed DatabaseSeeder
```

### Option B : Supprimer le fichier SQLite et tout recréer
```bash
# 1. Supprimer le fichier de base de données
del writable\database\mobile_money.sqlite

# 2. Recréer les tables
php spark migrate

# 3. Insérer les données de test
php spark db:seed DatabaseSeeder
```

---

## 🚀 Démarrer le serveur de développement
```bash
php spark serve --port 9090
```

---

## 🔍 Commandes de diagnostic

### Voir les routes disponibles
```bash
php spark routes
```

### Vérifier la config de la base de données
```bash
php spark db:table --show
```

### Lister les tables existantes
```bash
php spark db:table
```

---

## 📝 Notes

- **Erreur UNIQUE constraint** : Le seeder a été corrigé pour être idempotent (vérification avant insertion). Tu peux désormais le relancer sans erreur.
- **Erreur "array offset on null"** : Arrive quand la session contient un `client_id` qui n'existe plus en base (ex: après un reset). Le controller redirige maintenant vers `/login` automatiquement.
- **Fichier SQLite** : La base est stockée dans `writable/database/mobile_money.sqlite`
- **Compte opérateur par défaut** : `admin` / `admin123`
- **Clients de test** : `0340000001`, `0320000002`, `0330000003`
