# ?? Système de Gestion des Congés

## ?? Objectif
Application web de **gestion des congés** pour les ressources humaines, développée avec **Laravel 12** et **MySQL**.  
Elle permet de gérer les demandes de congés avec trois rôles distincts :  
- **Employé** : Demander et suivre ses congés  
- **Manager** : Valider ou refuser les congés de son équipe  
- **Admin** : Gérer l’ensemble du système (employés, départements, types de congés)

---

## ?? Fonctionnalités principales
- Authentification sécurisée (Laravel Breeze)  
- Gestion des rôles et permissions (Employé, Manager, Admin)  
- Création et suivi des demandes de congés  
- Validation et rejet par les managers/admins  
- Notifications automatiques pour chaque action importante  
- Dashboards personnalisés selon le rôle  
- Statistiques globales et par département (Admin)  

---

## ??? Installation
### 1. Cloner le projet
```bash
git clone https://github.com/imanechnafa/Laravel-chnafa.git
cd laravel-rh-conges
```

### 2. Installer les dépendances
```bash
composer install
npm install && npm run build
```

### 3. Configurer la base de données
Dans `.env` :
```
DB_CONNECTION=mysql
DB_DATABASE=laravel-rh-conges
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Lancer les migrations
```bash
php artisan migrate
```

### 5. Démarrer le serveur
```bash
php artisan serve
```

---

## ?? Structure de la base de données
- **Users** : comptes utilisateurs (email, mot de passe hashé)  
- **Employés** : informations RH (matricule, rôle, solde congés, département)  
- **Départements** : organisation interne  
- **Types de congés** : annuel, maladie, maternité…  
- **Congés** : demandes avec statut (`en_attente`, `approuve`, `rejete`)  
- **Validations** : historique des décisions des managers/admins  
- **Notifications** : messages envoyés aux utilisateurs  

---

## ?? Sécurité
- Mots de passe hashés (bcrypt/argon2)  
- Middleware `auth` pour protéger les routes  
- Vérification stricte des permissions par rôle  
- Exclusion des fichiers sensibles via `.gitignore`  

---

## ?? Interface
- **Bootstrap 5** pour un design responsive  
- **Dashboards** adaptés à chaque rôle  
- **Badges colorés** pour les statuts des congés  
- **Notifications en temps réel**  

---

## ?? Points forts
? Architecture claire et maintenable  
? Permissions granulaires par rôle  
? Notifications intégrées  
? Statistiques et suivi complet  
? Interface moderne et responsive  

---

## ?? Comptes de test

Pour tester l’application, voici des comptes déjà configurés :

| Rôle        | Email              | Mot de passe   | Accès principal |
|-------------|--------------------|----------------|-----------------|
| **Admin**   | admin@rh.com       | password123    | Dashboard global, gestion employés, départements, types de congés, statistiques |
| **Manager** | imane@rh.com       | password123    | Dashboard manager, validation des congés de son équipe, gestion de son département |
| **Employé** | alaa@rh.com        | password123    | Dashboard employé, création et suivi de ses demandes de congés |

---

?? **Remarque** : Les mots de passe sont hashés en base de données, mais pour les tests, ces identifiants sont disponibles après migration et création des utilisateurs.