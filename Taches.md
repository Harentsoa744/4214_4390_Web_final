# Tâches Réalisées - Simulateur Mobile Money

## Version 1

### Étudiant 1 (Backend & Base de données)
- Configuration de l'environnement CodeIgniter 4
- Configuration de la base de données SQLite embarquée (`database/mobile_money.sqlite`)
- Création du fichier `base.sql` avec structure et données de démo
- Création des Migrations pour générer les tables
- Création du `DatabaseSeeder`
- Création des Modèles (Operator, PhonePrefix, OperationType, FeeBracket, Client, Transaction)
- Implémentation du service métier `FeeCalculatorService` pour le calcul dynamique des frais
- Implémentation du service métier `TransactionService` pour gérer les opérations financières (dépôt, retrait, transfert) de manière atomique avec les transactions SQLite
- Implémentation des Filtres de sécurité `ClientAuthFilter` et `OperatorAuthFilter`
- Configuration globale du routage dans `Routes.php`

### Étudiant 2 (Frontend & Contrôleurs)
- Création des layouts Bootstrap 5 (`app` et `operator`)
- Implémentation de l'authentification client (sans mot de passe, avec validation de préfixe)
- Implémentation de l'authentification opérateur
- Développement des contrôleurs et vues de l'Espace Opérateur :
  - Dashboard avec statistiques et indicateurs financiers
  - Gestion (CRUD) des préfixes téléphoniques
  - Gestion des types d'opérations
  - Gestion des barèmes de frais
  - Suivi détaillé des comptes clients
- Développement des contrôleurs et vues de l'Espace Client :
  - Dashboard affichant le solde
  - Formulaires de dépôt, retrait et transfert
  - Historique paginé et filtrable des transactions
- Amélioration de l'UX avec des messages flash et un design responsive
- Création du fichier `README.md`
