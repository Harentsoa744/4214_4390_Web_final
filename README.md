# Simulateur Mobile Money

Projet CodeIgniter 4 simulant un opérateur de Mobile Money, développé dans le cadre de l'évaluation "4214_4390_Web_final".

## Présentation

Ce projet est une application web complète permettant de simuler :
- **Un espace Opérateur** pour configurer le système (préfixes, frais, types d'opérations) et consulter les revenus.
- **Un espace Client** pour effectuer des dépôts, retraits et transferts avec calcul automatique des frais.

### Nouveautés de la Version 2
- **Multi-opérateurs :** Support d'opérateurs externes, gestion des commissions inter-opérateurs.
- **Envois multiples :** Le client peut envoyer de l'argent à plusieurs destinataires en une seule transaction (batch).
- **Inclusion des frais de retrait :** Le client peut choisir de payer les frais de retrait à la place du destinataire lors d'un transfert.
- **Reversements (Settlements) :** Suivi des montants à reverser aux opérateurs externes suite aux commissions.
- **Dashboard V2 :** Séparation des revenus internes (frais) et externes (commissions inter-opérateurs).

## Technologies
- **Backend :** PHP, CodeIgniter 4
- **Base de données :** SQLite (embarqué)
- **Frontend :** HTML5, CSS3, JavaScript Vanilla, Bootstrap 5

## Structure

Le code suit l'architecture MVC de CodeIgniter 4 :
- Les **Contrôleurs** sont séparés pour les espaces Client (`app/Controllers`) et Opérateur (`app/Controllers/Operator`).
- Les **Modèles** gèrent la base de données.
- La logique métier (calcul des frais, transferts multiples, reversements) est centralisée dans `app/Services`.
- Les vues utilisent Bootstrap 5 dans `app/Views`.

## Installation et Configuration

1. **Prérequis :** PHP 8.1+, Composer, extension SQLite3 activée.
2. Cloner le projet.
3. Installer les dépendances :
   ```bash
   composer install
   ```
4. Démarrer l'application :
   ```bash
   php spark serve
   ```

La base de données est déjà configurée et peuplée grâce aux migrations et au seeder qui ont été exécutés, ainsi qu'au fichier `base.sql` présent à la racine.

## Comptes de Démonstration

### Opérateur Principal
- **Nom d'utilisateur :** admin
- **Mot de passe :** admin123
- **URL :** `http://localhost:8080/operator/login`

### Clients (Exemples)
La connexion client se fait uniquement par numéro de téléphone. Si le numéro n'existe pas, le compte est créé automatiquement.
*(Préfixes valides: 034 (Interne), 032 et 033 (Externes))*

- `0340000001` (Interne, Solde: 50 000 Ar)
- `0320000002` (Externe, Solde: 150 000 Ar)
- **URL :** `http://localhost:8080/login`

## Membres du Binôme

- Étudiant 1 (Backend & DB)
- Étudiant 2 (Frontend & UI)
