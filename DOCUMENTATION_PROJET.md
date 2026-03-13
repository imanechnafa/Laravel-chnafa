# 📋 DOCUMENTATION COMPLÈTE - SYSTÈME DE GESTION DES CONGÉS

## 🎯 OBJECTIF DU PROJET

Créer une application web de **gestion des congés** pour les ressources humaines avec 3 rôles distincts:
- **Employé** : Demander et suivre ses congés
- **Manager** : Valider/refuser les congés de son équipe
- **Admin** : Gérer toute l'application

---

## 🛠️ ÉTAPES DE CRÉATION DU PROJET

### ÉTAPE 1: Initialisation de Laravel
```bash
laravel new laravel-rh-conges
cd laravel-rh-conges
```

### ÉTAPE 2: Configuration de la Base de Données
- Configuration avec MySQL: `DB_CONNECTION=mysql`
- Nom BD: `laravel-rh-conges`
- Utilisateur: `root` (sans mot de passe)

### ÉTAPE 3: Création du Système d'Authentification
```bash
composer require laravel/breeze
php artisan breeze:install blade
npm install && npm run build
```
- Utilise le système d'authentification par défaut de Laravel
- Produit des routes: `/login`, `/register`, `/logout`

### ÉTAPE 4: Création des Modèles et Migrations
```bash
php artisan make:model Departement -m
php artisan make:model TypeConge -m
php artisan make:model Employe -m
php artisan make:model Conge -m
php artisan make:model Validation -m
php artisan make:model Notification -m
```

### ÉTAPE 5: Exécution des Migrations
```bash
php artisan migrate
```

### ÉTAPE 6: Création des Contrôleurs
```bash
php artisan make:controller DashboardController
php artisan make:controller CongeController
php artisan make:controller EmployeController
php artisan make:controller DepartementController
php artisan make:controller ValidationController
php artisan make:controller ProfileController
php artisan make:controller NotificationController
```

### ÉTAPE 7: Configuration des Routes
Édition du fichier `routes/web.php` avec routes protégées par authentification

### ÉTAPE 8: Création des Vues
Génération des fichiers Blade pour chaque écran de l'application

---

## 📦 STRUCTURE DE LA BASE DE DONNÉES

### 1. **Modèle: User**
- **Table**: `users`
- **Colonnes**:
  - `id` (PK)
  - `name` : Nom complet de l'utilisateur
  - `email` : Email unique
  - `password` : Mot de passe hashé
- **Relation**: Un utilisateur = Un employé (relation 1:1)

### 2. **Modèle: Employe**
- **Table**: `employes`
- **Colonnes**:
  - `id` (PK)
  - `user_id` (FK) → Lien vers User
  - `departement_id` (FK) → Lien vers Département
  - `matricule` : Numéro unique du salarié
  - `role` : `'employe'`, `'manager'`, `'admin'`
  - `solde_conge` : Nombre de jours de congé disponibles (défaut: 25)
  - `date_embauche` : Date d'embauche
- **Relations**:
  - `user()` : belongsTo(User)
  - `departement()` : belongsTo(Departement)
  - `conges()` : hasMany(Conge)
  - `validations()` : hasMany(Validation) [pour managers]

### 3. **Modèle: Departement**
- **Table**: `departements`
- **Colonnes**:
  - `id` (PK)
  - `nom` : Nom du département
  - `description` : Description optionnelle
- **Relations**:
  - `employes()` : hasMany(Employe)

### 4. **Modèle: TypeConge**
- **Table**: `type_conges`
- **Colonnes**:
  - `id` (PK)
  - `nom` : Type de congé (Congé annuel, Maladie, Maternité, etc.)
  - `jours_annuels` : Nombre de jours annuels (défaut: 25)
  - `est_paye` : Boolean, si c'est un congé payé
  - `couleur` : Couleur pour affichage (ex: #FF5733)
- **Relations**:
  - `conges()` : hasMany(Conge)

### 5. **Modèle: Conge**
- **Table**: `conges`
- **Colonnes**:
  - `id` (PK)
  - `employe_id` (FK) → Lien vers Employé
  - `type_conge_id` (FK) → Lien vers TypeConge
  - `date_debut` : Date de début de congé
  - `date_fin` : Date de fin de congé
  - `nombre_jours` : Nombre de jours de congé
  - `motif` : Raison du congé
  - `statut` : `'en_attente'`, `'approuve'`, `'rejete'`
  - `commentaire_validation` : Commentaire du manager/admin
- **Relations**:
  - `employe()` : belongsTo(Employe)
  - `typeConge()` : belongsTo(TypeConge)
  - `validations()` : hasMany(Validation)

### 6. **Modèle: Validation**
- **Table**: `validations`
- **Colonnes**:
  - `id` (PK)
  - `conge_id` (FK) → Lien vers Congé
  - `validated_by_user_id` (FK) → Qui a validé (manager/admin)
  - `statut` : `'approuve'` ou `'rejete'`
  - `commentaire` : Raison de la validation/rejection
- **Relations**:
  - `conge()` : belongsTo(Conge)
  - `validatedBy()` : belongsTo(User, 'validated_by_user_id')

### 7. **Modèle: Notification**
- **Table**: `notifications`
- **Colonnes**:
  - `id` (PK)
  - `user_id` (FK) → Destinataire
  - `title` : Titre de la notification
  - `message` : Contenu
  - `type` : `'info'`, `'success'`, `'warning'`, `'danger'`
  - `read` : Booléen si lue
  - `read_at` : Timestamp de lecture
- **Relations**:
  - `user()` : belongsTo(User)

---

## 🎮 CONTRÔLEURS ET LEURS FONCTIONNALITÉS

### **1. DashboardController**

#### `index()`
- Fonction d'entrée principale au dashboard
- Redirige selon le rôle de l'utilisateur vers son dashboard spécifique

#### `adminDashboard()`
- **Rôle requis**: Admin uniquement
- **Ce qu'il fait**:
  - Récupère les statistiques globales du système
  - Nombre total d'employés, départements, congés en attente
  - Derniers congés demandés (avec relations)
  - Congés par département
- **Vue**: `dashboard.admin`
- **Variables passées**: `$stats`, `$derniersConges`, `$congesParDepartement`

#### `managerDashboard()`
- **Rôle requis**: Manager
- **Ce qu'il fait**:
  - Charge les infos du manager (département)
  - Récupère statistiques de son équipe
  - Congés en attente de validation (son département)
  - Prochaines absences prévues
- **Vue**: `dashboard.manager`
- **Variables**: `$stats`, `$manager`, `$congesEnAttente`, `$absencesAVenir`

#### `employeDashboard()`
- **Rôle requis**: Employé
- **Ce qu'il fait**:
  - Affiche les stats personnelles (solde, demandes, approbations)
  - Derniers congés demandés par l'employé
  - Congés à venir approuvés
- **Vue**: `dashboard.employe`
- **Variables**: `$stats`, `$employe`, `$mesDerniersConges`, `$congesAVenir`

#### `statistiques()`
- **Rôle requis**: Admin
- **Ce qu'il fait**: Affiche des graphiques et statistiques détaillées
- **Vue**: `dashboard.statistiques`

---

### **2. CongeController**

#### `index()`
- **Ce qu'il fait**:
  - Liste les congés selon le rôle
  - Employé: ses própres congés
  - Manager: congés de son département
  - Admin: tous les congés
- **Pagination**: 10-15 résultats par page

#### `create()`
- **Rôle requis**: Employé uniquement
- **Ce qu'il fait**: Affiche formulaire de création de congé
- **Variables**: `$typesConge`, `$employe`

#### `store(Request $request)`
- **Validation**:
  ```
  - date_debut: date requise
  - date_fin: date requise
  - type_conge_id: existe dans type_conges
  - motif: texte obligatoire
  ```
- **Logique**:
  - Calcul des jours ouvrés (lundi-vendredi)
  - Vérification du solde disponible
  - Création du congé avec statut `'en_attente'`
- **Redirection**: Vers `conges.index`

#### `show(Conge $conge)`
- Affiche les détails d'une demande de congé
- **Permissions**:
  - Employé: peut voir ses proprio congés
  - Manager: peut voir les congés de son département
  - Admin: peut voir tous les congés

#### `edit()` et `update()`
- **Permission**: Seulement si statut = `'en_attente'`
- Permet modifier les demandes en attente

#### `destroy()`
- Supprime uniquement les congés `'en_attente'`

#### `validation()`
- **Rôle requis**: Manager
- **Ce qu'il fait**:
  - Liste TOUS les congés en attente du département
  - Affiche vue de validation
- **Variables**: `$congesEnAttente` (paginated)

---

### **3. ValidationController**

#### `approve($congeId)`
- **Logique**:
  1. Vérifier les permissions (manager ou admin)
  2. Changer statut de `'en_attente'` à `'approuve'`
  3. Créer entrée dans table `validations`
  4. Créer notification pour l'employé
- **Notification**: "Votre demande a été approuvée"

#### `reject(Request $request, $congeId)`
- **Logique**:
  1. Vérifier permissions
  2. Changer statut à `'rejete'`
  3. Sauvegarder raison du rejet
  4. Créer validation + notification
- **Notification**: "Votre demande a été refusée. Raison: ..."

#### `index()`
- Liste l'historique des validations effectuées
- Pour managers et admins

---

### **4. EmployeController**

#### `index()`
- Liste TOUS les employés (admin)
- Avec pagination + relations

#### `create()`
- Formulaire de création d'employé
- Variables: `$departements`

#### `store(Request $request)`
- **Validation**: Email unique, password size ≥8, matricule unique
- **Logique**:
  1. Créer User
  2. Créer Employe associé
  3. Ajouter au département
- **Défauts**: 
  - solde_conge = 20 jours
  - date_embauche = aujourd'hui

#### `show(Employe $employe)`
- Affiche profil complet d'un employé

#### `edit()/update()`
- Modification des infos d'un employé
- Update User ET Employe

#### `destroy()`
- Vérification: Impossible si l'employé a des congés
- Sinon: Suppression User + Employe (cascade)

#### `mesEmployes()` ⭐ **NOUVEAU**
- **Rôle requis**: Manager uniquement
- **Ce qu'il fait**:
  - Liste les employés du département du manager
  - Filtre role = 'employe' (pas les managers)
- **Variables**: `$employes` (paginated)

---

### **5. DepartementController**

#### `index()`
- Liste tous les départements

#### `create()/store()`
- Création d'un nouveau département

#### `edit()/update()`
- Modification d'un département

#### `destroy()`
- Suppression (si aucun employé assigné)

---

### **6. ProfileController**

#### `edit(Request $request)`
- Affiche profil de l'utilisateur connecté
- Variables: `$user`

#### `update(ProfileUpdateRequest $request)`
- **Changements possibles**:
  - Nom
  - Email
- **Logique**: Reset `email_verified_at` si email change

#### `destroy(Request $request)`
- Supprimer le compte utilisateur
- **Vérification**: Mot de passe actuel requis
- **Actions**:
  - Supprimer User + Employe associé
  - Invalidate session
  - Redirection vers `/`

---

### **7. NotificationController**

#### `index()`
- Liste les notifications de l'utilisateur connecté
- Trie par plus récentes

#### `markRead($notification)`
- Marque une notification comme lue
- Met à jour `read_at`

#### `markAllRead()`
- Marquer TOUTES les notifications comme lues

#### `destroy($notification)`
- Supprimer une notification

---

## 🎨 STRUCTURE DES VUES

### **Layout Principal**: `layouts/app.blade.php`
- Navbar supérieure avec menu utilisateur
- Sidebar navigation avec :
  - Menu selon le rôle
  - Liens vers congés, validations, équipe
  - Icône notifications
- Footer
- Bootstrap 5 CSS

### **Authentification** (Laravel Breeze)
- `auth/login.blade.php`
- `auth/register.blade.php`
- `auth/forgot-password.blade.php`

### **Dashboards**
| Rôle | Vue | Contenu |
|------|-----|---------|
| Employé | `dashboard/employe.blade.php` | Stats personnelles, mes congés, profil |
| Manager | `dashboard/manager.blade.php` | Équipe, congés attente, absences prévues |
| Admin | `dashboard/admin.blade.php` | Tout du système, statistiques globales |

### **Gestion des Congés**
- `conges/index-employe.blade.php` : List des propres congés
- `conges/index-manager.blade.php` : Congés de l'équipe
- `conges/index-admin.blade.php` : Tous les congés
- `conges/validation.blade.php` : Page de validation pour manager
- `conges/create.blade.php` : Formulaire nouvelle demande
- `conges/show.blade.php` : Détails d'une demande

### **Gestion Employés**
- `employes/index.blade.php` : Liste (admin)
- `employes/mes-employes.blade.php` : Équipe du manager ⭐ **NOUVEAU**
- `employes/create.blade.php` : Formulaire création
- `employes/edit.blade.php` : Modification

### **Profil Utilisateur**
- `profile/edit.blade.php` : Édition profil + changement mot de passe + suppression compte

---

## 🔄 FLUX DE TRAVAIL - LOGIQUE APPLICATIVE

### **1. AUTHENTIFICATION**
```
Utilisateur → Page Login
      ↓
Vérification email/password
      ↓
Redirection vers dashboard approprié selon rôle
```

### **2. DEMANDE DE CONGÉ (Employé)**
```
Employé click "Nouvelle Demande"
      ↓
Formulaire: date début, date fin, type, motif
      ↓
Validation: dates valides, solde suffisant
      ↓
Congé créé avec statut "en_attente"
      ↓
Notification au Manager du département
```

### **3. VALIDATION DE CONGÉ (Manager)**
```
Manager voit "Validations" dans menu
      ↓
Page liste congés en attente de SON département
      ↓
Click sur congé → Voir détails
      ↓
Deux boutons: [Approuver] [Refuser]
      ↓
Si APPROUVÉ:
  - Statut → "approuve"
  - Solde congé de l'employé ← réduit
  - Notification employé: "Approuvée"
  
Si REFUSÉ:
  - Statut → "rejete"
  - Commentaire sauvegardé
  - Notification employé: "Refusée, raison: ..."
```

### **4. SUIVI DES CONGÉS**
```
Employé → "Mes Congés"
      ↓
Voir liste avec filtres:
  - En attente (jaune)
  - Approuvés (vert)
  - Refusés (rouge)
```

### **5. GESTION ÉQUIPE (Manager)**
```
Manager → "Mon Équipe"
      ↓
Liste employés du département
      ↓
Click employé → Voir profil détaillé
      ↓
Voir congés prévus, solde disponible
```

### **6. ADMINISTRATION (Admin)**
```
Admin → Dashboard
      ↓
Statistiques: total employés, départements, congés
      ↓
Menu: Gestion Employés, Départements, Validations
      ↓
Créer/Modifier/Supprimer n'importe quel élément
```

---

## 🔐 SYSTÈME DE PERMISSIONS

### **Middleware/Autorisation**:

```php
// Dans contrôleurs
if ($user->employe->role !== 'manager') {
    abort(403, 'Seuls les managers...');
}
```

### **Permissions par Rôle**:

| Action | Employé | Manager | Admin |
|--------|---------|---------|-------|
| Créer demande congé | ✅ | ❌ | ❌ |
| Voir ses congés | ✅ | ✅ | ✅ |
| Valider congés | ❌ | ✅* | ✅ |
| Voir équipe | ❌ | ✅* | ✅ |
| Gérer tous employés | ❌ | ❌ | ✅ |
| Gérer départements | ❌ | ❌ | ✅ |
| Gérer types congés | ❌ | ❌ | ✅ |

*Manager peut seulement voir son département

---

## 🗄️ MIGRATIONS EXÉCUTÉES (Ordre)

1. `create_users_table` - Table utilisateurs
2. `create_cache_table` - Système de cache
3. `create_jobs_table` - File d'attente jobs
4. `create_departements_table` - Départements
5. `create_type_conges_table` - Types de congés
6. `create_employes_table` - Employés
7. `create_conges_table` - Demandes congés
8. `create_validations_table` - Historique validations
9. `create_notifications_table` - Notifications
10. `add_user_id_to_notifications_table` - Ajout FK

---

## 📍 ROUTES PRINCIPALES

### Routes d'Authentification
```
GET/POST  /login          - Connexion
GET/POST  /register       - Inscription
POST      /logout         - Déconnexion
```

### Routes Employé
```
GET       /employe/dashboard           - Dashboard employé
GET/POST  /employe/conges              - Mes congés
GET/POST  /employe/conges/create       - Nouvelle demande
PATCH     /employe/conges/{id}         - Modifier demande
DELETE    /employe/conges/{id}         - Supprimer demande
```

### Routes Manager
```
GET       /manager/dashboard           - Dashboard manager
GET       /manager/employes            - Mon équipe
GET       /manager/conges/validation   - Validations en attente
POST      /validations/{id}/approve    - Approuver congé
POST      /validations/{id}/reject     - Refuser congé
```

### Routes Admin
```
GET       /admin/dashboard             - Dashboard admin
GET/POST  /admin/employes              - Gestion employés
GET/POST  /admin/departements          - Gestion départements
GET       /statistiques                - Statistiques détaillées
GET       /validations                 - Historique validations
```

### Routes Profil
```
GET       /profile                     - Édition profil
PATCH     /profile                     - Mise à jour profil
PUT       /password                    - Changement mot de passe
DELETE    /profile                     - Suppression compte
```

### Routes Notifications
```
GET       /notifications               - Mes notifications
POST      /notifications/{id}/read     - Marquer lue
POST      /notifications/mark-all      - Tout marquer lu
DELETE    /notifications/{id}          - Supprimer notification
```

---

## 💾 CALAGE DES ERREURS RENCONTRÉES ET CORRECTIONS

### Erreur 1: `Route [conges.validation.index] not defined`
**Cause**: Route manquante pour la validation des congés
**Solution**: Ajout route dans `routes/web.php`:
```php
Route::get('/conges/validation', [CongeController::class, 'validation'])
    ->name('conges.validation.index');
```

### Erreur 2: `View [dashboard.manager] not found`
**Cause**: Vue manquante
**Solution**: Création du fichier `resources/views/dashboard/manager.blade.php`

### Erreur 3: `Undefined variable $manager`
**Cause**: Closure route ne passe pas les variables
**Solution**: Changer closure vers `DashboardController::managerDashboard`

### Erreur 4: `Route [employes.mes-employes] not defined`
**Cause**: Route manquante pour l'équipe du manager
**Solution**: Ajout dans routes + création du contrôleur `mesEmployes()`

### Erreur 5: `Route [profile.edit] not defined`
**Cause**: Routes profil non définie
**Solution**: Ajout dans `routes/web.php`:
```php
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
```

### Erreur 6: `Route [profil] not defined`
**Cause**: Référence à ancien nom de route
**Solution**: Remplacement `route('profil')` par `route('profile.edit')`

### Erreur 7: `Route [dashboard.statistiques] not defined`
**Cause**: Routes statistiques et validations manquantes
**Solution**: Ajout routes au niveau global (pas dans prefix)

---

## 📊 TECHNOLOGIES UTILISÉES

- **Framework**: Laravel 12.48.1
- **PHP**: 8.2.12
- **Base de Données**: MySQL
- **Authentification**: Laravel Breeze (Blade)
- **Frontend**: Bootstrap 5
- **ORM**: Eloquent
- **Patterns**: MVC, Middleware, Factories

---

## 🎓 CONCEPTS CLÉ À MÉMORISER POUR LE PROF

### 1. **Modèle d'authentification à 3 rôles**
   - Stocké dans table `employes.role` enum
   - Vérifié par middlewares et checks dans contrôleurs

### 2. **Relations Eloquent**
   - User ← (1:1) → Employe
   - Employe ← (N:1) → Departement
   - Employe ← (1:N) → Conge
   - Conge ← (1:1) → TypeConge
   - Conge ← (1:N) → Validation

### 3. **Logique métier validation congés**
   - Calcul jours ouvrés (Lundi-Vendredi)
   - Vérification solde avant création
   - Historique des validations
   - Notifications par rôle

### 4. **Sécurité**
   - Route groups avec middleware `auth`
   - Vérification permissions dans contrôleurs
   - Soft-delete implicite (cascade delete)

### 5. **UX/UI**
   - Dashboard personnalisé par rôle
   - Sidebar navigation contextuelle
   - Statuts visuels avec couleurs/badges
   - Notifications en temps réel (table notifications)

---

## 🚀 POINTS FORTS DU PROJET

✅ **Architecture claire** : Séparation des responsabilités  
✅ **Logique de validation** : Vérification avant création  
✅ **Historique traçable** : Table validations  
✅ **Notifications intégrées** : Pour chaque action importante  
✅ **Permissions granulaires** : Par rôle et département  
✅ **Interface responsive** : Bootstrap 5  
✅ **Code maintenable** : Conventions Laravel respectées  

---

**Date de création**: 21 Février 2026  
**Version Laravel**: 12.48.1  
**État**: ✅ Fonctionnel et testé
