# Tâches Réalisées - Simulateur Mobile Money

## Version 2

### Apinga (Backend & Base de données)
- Création de la migration V2 (`operators`, `transfer_batches`, `settlements`, `operator_commissions`)
- Création du `Version2Seeder` pour peupler les nouveaux opérateurs et configurer les commissions
- Création des nouveaux Modèles (TransferBatchModel, SettlementModel, OperatorCommissionModel)
- Développement du `OperatorResolverService` pour identifier les opérateurs via préfixes
- Développement du `TransferCostCalculatorService` pour la gestion complexe des frais et commissions (Inter-opérateur, frais de retrait)
- Développement du `MultipleTransferService` garantissant l'atomicité des envois multiples (Batch)
- Développement du `SettlementService` pour l'agrégation des reversements inter-opérateurs
- Mise à jour du `TransactionController` et `DashboardController`

### Harentsoa (Frontend & Contrôleurs)
- Mise à jour du Dashboard Opérateur (Onglets Interne / Externe, Tableaux détaillés)
- Interface de configuration des commissions inter-opérateurs
- Interface de gestion des Opérateurs
- Interface de suivi des Reversements (Settlements) avec boutons d'action
- Mise à jour du formulaire de transfert Client :
  - Ajout dynamique de destinataires en Javascript
  - Aperçu estimatif des transferts
  - Case à cocher "Inclure les frais de retrait"
- Mise à jour de l'historique Client (Badge "Externe", affichage des commissions)

---

## Version 1

### Apinga (Backend & Base de données)
- Creation de l'environnement CodeIgniter 
- Creation de la base de donnees SQLite 
- Creation du fichier `base.sql` avec le structure de la base de donnees et les donnees de demo
- Creation des Migrations pour générer tables
- Creation de la seeder
- Creation des Modèles sur les transactions, client et operateurs
- Creation du service `FeeCalculatorService` ho an'le frais
- Creation du service `TransactionService` pour gérer les differentes operations
- Creation des Filtres pour l'autentification
- Inscription auto
- Configuration des routes

### Harentsoa (Frontend & Contrôleurs)
- Création des layouts
- Utilisation Bootstrap
- Creation page authentifications
- Creation page authentification client 
- Creation page authentification opérateur
- Développement des controleurs et vues pour operateur :
  - Dashboard
  - Gestion (CRUD) des prefixes
  - Gestion des types d'opérations
  - Gestion des frais
  - Suivi détaillé des comptes clients (CRM)
- Développement des controller et vues pour client :
  - Dashboard
  - Formulaires operations
  - Historique des transactions
- Design UI/UX avec couleurs
- Mode sombre(tsy ilaina fa te anandrana ony)