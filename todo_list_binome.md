# ✅ TO-DO LIST — TechMada RH (Binôme A + B)
## Durée totale : 4h | CI4 + SQLite

---

# 👤 PERSONNE A — Setup + Auth + Espace Employé
> **Fichiers principaux :** `AuthController`, `EmployeController`, `views/employe/`, migrations, config

---

## 🔧 BLOC 1 — Setup & Base (20 min)

- [ ] **Créer le projet CI4**
  ```bash
  composer create-project codeigniter4/appstarter rh-techmada
  cd rh-techmada
  ```

- [ ] **Configurer SQLite dans `app/Config/Database.php`**
  ```php
  'database' => WRITEPATH . 'data.db',
  'DBDriver' => 'SQLite3',
  ```

- [ ] **Créer les 5 fichiers de migration** (copier depuis `migrations_et_seeders.md`)
  - `CreateDepartements`
  - `CreateTypesConge`
  - `CreateEmployes`
  - `CreateSoldes`
  - `CreateConges`

- [ ] **Créer le Seeder** `app/Database/Seeds/DatabaseSeeder.php`

- [ ] **Lancer les migrations + seed**
  ```bash
  php spark migrate
  php spark db:seed DatabaseSeeder
  ```

- [ ] **Créer les routes squelettes** dans `app/Config/Routes.php`
  ```php
  // Auth
  $routes->get('/',                'AuthController::login');
  $routes->get('login',            'AuthController::login');
  $routes->post('login',           'AuthController::loginPost');
  $routes->get('logout',           'AuthController::logout');

  // Employé
  $routes->group('employe', ['filter' => 'auth:employe'], function($routes) {
      $routes->get('dashboard',        'EmployeController::dashboard');
      $routes->get('demandes',         'EmployeController::index');
      $routes->get('demandes/new',     'EmployeController::create');
      $routes->post('demandes/new',    'EmployeController::store');
      $routes->post('demandes/annuler/(:num)', 'EmployeController::annuler/$1');
      $routes->get('profil',           'EmployeController::profil');
      $routes->post('profil',          'EmployeController::profilUpdate');
  });
  ```

- [ ] **Pusher le repo GitHub initial** (voir section GitHub ci-dessous)

---

## 🔐 BLOC 2 — Authentification (40 min)

### Filtre `app/Filters/AuthFilter.php`
- [ ] Créer le filtre qui vérifie `session()->get('user_id')`
- [ ] Rediriger vers `/login` si pas connecté
- [ ] Vérifier le rôle si passé en paramètre (`auth:employe`, `auth:rh`, `auth:admin`)

```php
<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
        if ($arguments) {
            $role = session()->get('role');
            if (!in_array($role, $arguments)) {
                return redirect()->to('/' . $role . '/dashboard');
            }
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
```

- [ ] **Enregistrer le filtre** dans `app/Config/Filters.php`
  ```php
  'aliases' => ['auth' => \App\Filters\AuthFilter::class]
  ```

### `app/Controllers/AuthController.php`
- [ ] Méthode `login()` → afficher la vue
- [ ] Méthode `loginPost()` :
  - Récupérer email + password du POST
  - Chercher l'employé par email dans la DB
  - Vérifier `password_verify($password, $employe->password)`
  - Si OK → stocker en session : `user_id`, `role`, `nom`, `prenom`
  - Rediriger selon le rôle (`employe/dashboard`, `rh/dashboard`, `admin/dashboard`)
  - Si KO → flashdata erreur + redirect login
- [ ] Méthode `logout()` → détruire la session + redirect login

### Vue `app/Views/auth/login.php`
- [ ] Formulaire (email + password)
- [ ] Afficher flashdata erreur si présent
- [ ] CSRF token dans le form (`<?= csrf_field() ?>`)

### Layout `app/Views/layout/app.php`
- [ ] Structure HTML de base avec sidebar selon rôle
- [ ] `<?= $this->renderSection('content') ?>`

---

## 👨‍💼 BLOC 3 — Espace Employé (60 min)

### Modèle `app/Models/CongeModel.php`
- [ ] `$table = 'conges'`
- [ ] Méthode `getMesDemandes($employe_id)` → retourne toutes les demandes de cet employé
- [ ] Méthode `getDemandesEnAttente()` → pour le RH (Personne B l'utilisera)

### Modèle `app/Models/SoldeModel.php`
- [ ] `$table = 'soldes'`
- [ ] Méthode `getSolde($employe_id, $type_conge_id, $annee)` → retourne le solde
- [ ] Méthode `getSoldesEmploye($employe_id, $annee)` → tous les soldes d'un employé
- [ ] Méthode `deduire($employe_id, $type_conge_id, $annee, $nb_jours)` → `jours_pris += nb_jours`
- [ ] Méthode `restituer($employe_id, $type_conge_id, $annee, $nb_jours)` → `jours_pris -= nb_jours`

### `app/Controllers/EmployeController.php`
- [ ] `dashboard()` → afficher soldes restants par type
- [ ] `index()` → lister ses demandes avec statut
- [ ] `create()` → afficher le formulaire de nouvelle demande
- [ ] `store()` :
  - Valider : date_debut < date_fin, solde suffisant, pas de chevauchement
  - Calculer `nb_jours` (tous calendaires pour simplicité)
  - Vérifier `jours_pris + nb_jours <= jours_attribues`
  - Insérer dans `conges` avec statut `en_attente`
  - **NE PAS déduire le solde ici** (seulement à l'approbation !)
  - Flashdata succès + redirect index
- [ ] `annuler($id)` :
  - Vérifier que la demande appartient à l'employé connecté
  - Vérifier que le statut est `en_attente`
  - Mettre statut → `annulee`
  - Flashdata + redirect
- [ ] `profil()` → afficher le formulaire profil
- [ ] `profilUpdate()` → mettre à jour nom, prénom, et password si fourni

### Vues `app/Views/employe/`
- [ ] `dashboard.php` → tableau des soldes
- [ ] `index.php` → liste des demandes avec badges de statut
- [ ] `create.php` → formulaire : type_conge (select), date_debut, date_fin, motif
- [ ] `profil.php` → formulaire nom/prénom/password

---

---

# 👤 PERSONNE B — Admin + Espace RH + Finition
> **Fichiers principaux :** `AdminController`, `RhController`, `views/admin/`, `views/rh/`

---

## 🚀 DÉMARRAGE (5 min)

- [ ] **Cloner le repo** après que Personne A l'a pushé
  ```bash
  git clone https://github.com/[username]/rh-techmada.git
  cd rh-techmada
  composer install
  php spark migrate
  php spark db:seed DatabaseSeeder
  php spark serve
  ```

- [ ] **Créer sa branche de travail**
  ```bash
  git checkout -b feature/admin-rh
  ```

---

## 🧑‍⚕️ BLOC 4 — Espace RH (50 min)

### Ajouter les routes RH dans `Routes.php` (sur sa branche)
```php
$routes->group('rh', ['filter' => 'auth:rh'], function($routes) {
    $routes->get('dashboard',              'RhController::dashboard');
    $routes->get('demandes',               'RhController::index');
    $routes->post('demandes/approuver/(:num)', 'RhController::approuver/$1');
    $routes->post('demandes/refuser/(:num)',   'RhController::refuser/$1');
});
```

### `app/Controllers/RhController.php`
- [ ] `dashboard()` → statistiques rapides (nb demandes en attente, par département)
- [ ] `index()` :
  - Lister **toutes** les demandes `en_attente`
  - Permettre filtre par département (GET param)
  - Permettre filtre par statut (GET param)
- [ ] `approuver($id)` :
  - Récupérer la demande
  - Vérifier que le solde est encore suffisant (re-vérification)
  - Mettre statut → `approuvee`
  - **Déduire le solde** : `UPDATE soldes SET jours_pris = jours_pris + $nb_jours`
  - Stocker `traite_par` = id du RH connecté
  - Flashdata + redirect
- [ ] `refuser($id)` :
  - Mettre statut → `refusee`
  - Enregistrer `commentaire_rh` (depuis POST)
  - **Ne pas toucher au solde**
  - Flashdata + redirect

### Vues `app/Views/rh/`
- [ ] `dashboard.php` → stats + solde de chaque employé visible
- [ ] `index.php` → tableau demandes avec boutons Approuver/Refuser
  - Colonne : Employé, Type, Dates, Nb jours, Statut, Actions
  - Formulaire inline pour le commentaire de refus

---

## 🔑 BLOC 5 — Back-office Admin (30 min)

### Ajouter les routes Admin dans `Routes.php`
```php
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard',                    'AdminController::dashboard');
    // Employés
    $routes->get('employes',                     'AdminController::employes');
    $routes->get('employes/new',                 'AdminController::employeCreate');
    $routes->post('employes/new',                'AdminController::employeStore');
    $routes->get('employes/edit/(:num)',         'AdminController::employeEdit/$1');
    $routes->post('employes/edit/(:num)',        'AdminController::employeUpdate/$1');
    $routes->post('employes/desactiver/(:num)',  'AdminController::employeDesactiver/$1');
    // Départements
    $routes->get('departements',                 'AdminController::departements');
    $routes->post('departements/new',            'AdminController::departementStore');
    // Types de congé
    $routes->get('types-conge',                  'AdminController::typesConge');
    $routes->post('types-conge/new',             'AdminController::typeCongeStore');
    // Soldes
    $routes->post('soldes/init/(:num)',          'AdminController::initSolde/$1');
});
```

### `app/Controllers/AdminController.php`
- [ ] `dashboard()` :
  - Nombre total d'absences du mois en cours
  - Employés actifs/inactifs
  - Congés par type ce mois
- [ ] `employes()` → liste tous les employés (actifs + inactifs)
- [ ] `employeCreate()` + `employeStore()` :
  - Créer un employé avec `password_hash`
  - Créer automatiquement les soldes pour l'année courante
- [ ] `employeEdit($id)` + `employeUpdate($id)` → modifier nom, email, rôle, département
- [ ] `employeDesactiver($id)` → `actif = 0` (ne jamais supprimer)
- [ ] `departements()` + `departementStore()` → CRUD basique
- [ ] `typesConge()` + `typeCongeStore()` → CRUD basique
- [ ] `initSolde($employe_id)` → initialiser/ajuster le solde annuel d'un employé

### Vues `app/Views/admin/`
- [ ] `dashboard.php` → tableau de bord absences du mois
- [ ] `employes.php` → liste avec boutons Éditer/Désactiver + formulaire création
- [ ] `departements.php` → liste + formulaire ajout
- [ ] `types_conge.php` → liste + formulaire ajout

---

## 🎨 BLOC 6 — Finition (20 min) — ENSEMBLE ou Personne B

- [ ] **Flashdata partout** : chaque action POST doit avoir un message succès/erreur
  ```php
  session()->setFlashdata('success', 'Demande soumise avec succès');
  // Dans la vue :
  <?php if(session()->getFlashdata('success')): ?>
      <div class="alert"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  ```

- [ ] **Pattern PRG respecté** : tous les POST redirigent après traitement (pas de double soumission)

- [ ] **Layout `app/Views/layout/app.php`** finalisé :
  - Sidebar avec liens selon le rôle
  - Afficher nom de l'utilisateur connecté
  - Lien déconnexion

- [ ] **README.md** à la racine :
  ```markdown
  # TechMada RH
  ## Installation
  composer install
  php spark migrate
  php spark db:seed DatabaseSeeder
  php spark serve
  ## Comptes de test
  - admin@techmada.mg / password123 (admin)
  - rh@techmada.mg / password123 (rh)
  - employe@techmada.mg / password123 (employé)
  ```

---

# 🐙 GitHub — Setup du repo

## Personne A (crée le repo)

```bash
# 1. Sur GitHub.com → "New repository" → nom: rh-techmada → Create

# 2. En local, depuis le dossier du projet :
git init
git add .
git commit -m "feat: setup initial CI4 + SQLite + migrations + seeder"
git branch -M main
git remote add origin https://github.com/[TON_USERNAME]/rh-techmada.git
git push -u origin main

# 3. Sur GitHub.com :
# Settings → Collaborators → Add people → [username de Personne B]
# Choisir le rôle "Write" (permet push, branches, PR)
```

## Personne B (rejoint le repo)

```bash
# 1. Accepter l'invitation reçue par email

# 2. Cloner :
git clone https://github.com/[USERNAME_A]/rh-techmada.git
cd rh-techmada
composer install
php spark migrate
php spark db:seed DatabaseSeeder

# 3. Travailler sur une branche :
git checkout -b feature/admin-rh
# ... coder ...
git add .
git commit -m "feat: espace RH + approuver/refuser"
git push origin feature/admin-rh

# 4. Créer une Pull Request sur GitHub pour merger dans main
```

## ⚠️ Règles anti-conflit

| Règle | Pourquoi |
|---|---|
| Personne A ne touche jamais `RhController`, `AdminController` | Ce sont les fichiers de B |
| Personne B ne touche jamais `AuthController`, `EmployeController` | Ce sont les fichiers de A |
| Le fichier `Routes.php` peut être modifié par les deux → **merger avec soin** | Chacun ajoute son bloc dans une zone distincte |
| Les migrations → **Personne A seulement** | Évite les conflits de schéma |
| Commiter souvent avec des messages clairs | Facilite le debug |

---

# 📋 RÉCAPITULATIF CHECKLIST FINALE

## Obligatoire (noté)
- [ ] Connexion/déconnexion fonctionnelle
- [ ] Soumettre une demande de congé
- [ ] Lister ses demandes avec statut
- [ ] Voir son solde par type
- [ ] Approuver/Refuser une demande (RH)
- [ ] Mise à jour automatique du solde à l'approbation
- [ ] CRUD employés (Admin)
- [ ] Tableau de bord absences du mois (Admin)

## Bonus (si temps)
- [ ] Annuler une demande
- [ ] Modifier profil
- [ ] Filtre par département/statut (RH)
- [ ] Voir solde de chaque employé (RH)
- [ ] CRUD départements + types de congé (Admin)
- [ ] Initialiser/ajuster solde (Admin)
