# Migrations & Seeders — TechMada RH (CI4 + SQLite)

## 📁 Structure des fichiers à créer

```
app/
├── Config/
│   └── Database.php          ← modifier pour SQLite
├── Database/
│   ├── Migrations/
│   │   ├── 2024-01-01-000001_CreateDepartements.php
│   │   ├── 2024-01-01-000002_CreateTypesConge.php
│   │   ├── 2024-01-01-000003_CreateEmployes.php
│   │   ├── 2024-01-01-000004_CreateSoldes.php
│   │   └── 2024-01-01-000005_CreateConges.php
│   └── Seeds/
│       └── DatabaseSeeder.php
```

---

## ⚙️ app/Config/Database.php — Configurer SQLite

```php
// Dans la section $default, remplacer tout par :
public array $default = [
    'DSN'          => '',
    'hostname'     => '',
    'username'     => '',
    'password'     => '',
    'database'     => WRITEPATH . 'data.db',   // ← fichier SQLite
    'DBDriver'     => 'SQLite3',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8',
    'DBCollat'     => '',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 3306,
];
```

---

## 📄 Migration 1 — Départements

**Fichier :** `app/Database/Migrations/2024-01-01-000001_CreateDepartements.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartements extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('departements');
    }

    public function down()
    {
        $this->forge->dropTable('departements');
    }
}
```

---

## 📄 Migration 2 — Types de congé

**Fichier :** `app/Database/Migrations/2024-01-01-000002_CreateTypesConge.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesConge extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'jours_annuels' => [
                'type'    => 'INTEGER',
                'null'    => false,
                'default' => 0,
            ],
            'deductible' => [
                'type'    => 'INTEGER',  // 0 ou 1 (SQLite n'a pas BOOLEAN)
                'null'    => false,
                'default' => 1,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('types_conge');
    }

    public function down()
    {
        $this->forge->dropTable('types_conge');
    }
}
```

---

## 📄 Migration 3 — Employés

**Fichier :** `app/Database/Migrations/2024-01-01-000003_CreateEmployes.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'prenom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'employe',
                // valeurs : 'employe' | 'rh' | 'admin'
            ],
            'departement_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'date_embauche' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'actif' => [
                'type'    => 'INTEGER',  // 0 ou 1
                'null'    => false,
                'default' => 1,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('employes');
    }

    public function down()
    {
        $this->forge->dropTable('employes');
    }
}
```

---

## 📄 Migration 4 — Soldes

**Fichier :** `app/Database/Migrations/2024-01-01-000004_CreateSoldes.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'type_conge_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'annee' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'jours_attribues' => [
                'type'    => 'INTEGER',
                'null'    => false,
                'default' => 0,
            ],
            'jours_pris' => [
                'type'    => 'INTEGER',
                'null'    => false,
                'default' => 0,
                // restant = jours_attribues - jours_pris (calculé, jamais stocké)
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soldes');
    }

    public function down()
    {
        $this->forge->dropTable('soldes');
    }
}
```

---

## 📄 Migration 5 — Congés

**Fichier :** `app/Database/Migrations/2024-01-01-000005_CreateConges.php`

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConges extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'type_conge_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'nb_jours' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'motif' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'statut' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'en_attente',
                // valeurs : 'en_attente' | 'approuvee' | 'refusee' | 'annulee'
            ],
            'commentaire_rh' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'traite_par' => [
                'type' => 'INTEGER',  // FK → employes.id (le RH qui a traité)
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('traite_par', 'employes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('conges');
    }

    public function down()
    {
        $this->forge->dropTable('conges');
    }
}
```

---

## 🌱 Seeder — DatabaseSeeder.php

**Fichier :** `app/Database/Seeds/DatabaseSeeder.php`

```php
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Départements
        $departements = [
            ['nom' => 'Informatique',   'description' => 'Équipe tech'],
            ['nom' => 'Ressources Humaines', 'description' => 'Gestion du personnel'],
            ['nom' => 'Finance',        'description' => 'Comptabilité et finance'],
        ];
        $this->db->table('departements')->insertBatch($departements);

        // 2. Types de congé
        $types = [
            ['libelle' => 'Congé annuel',   'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Congé maladie',  'jours_annuels' => 15, 'deductible' => 1],
            ['libelle' => 'Congé sans solde','jours_annuels' => 0,  'deductible' => 0],
        ];
        $this->db->table('types_conge')->insertBatch($types);

        // 3. Employés (password = password_hash de "password123")
        $hash = password_hash('password123', PASSWORD_DEFAULT);
        $employes = [
            [
                'nom'            => 'Admin',
                'prenom'         => 'Super',
                'email'          => 'admin@techmada.mg',
                'password'       => $hash,
                'role'           => 'admin',
                'departement_id' => 2,
                'date_embauche'  => '2020-01-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Rakoto',
                'prenom'         => 'Jean',
                'email'          => 'rh@techmada.mg',
                'password'       => $hash,
                'role'           => 'rh',
                'departement_id' => 2,
                'date_embauche'  => '2021-06-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Rabe',
                'prenom'         => 'Marie',
                'email'          => 'employe@techmada.mg',
                'password'       => $hash,
                'role'           => 'employe',
                'departement_id' => 1,
                'date_embauche'  => '2022-03-15',
                'actif'          => 1,
            ],
        ];
        $this->db->table('employes')->insertBatch($employes);

        // 4. Soldes initiaux pour l'employé (id=3), année courante
        $annee = date('Y');
        $soldes = [
            ['employe_id' => 3, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 0],
            ['employe_id' => 3, 'type_conge_id' => 2, 'annee' => $annee, 'jours_attribues' => 15, 'jours_pris' => 0],
            ['employe_id' => 2, 'type_conge_id' => 1, 'annee' => $annee, 'jours_attribues' => 30, 'jours_pris' => 0],
        ];
        $this->db->table('soldes')->insertBatch($soldes);
    }
}
```

---

## 🚀 Commandes à lancer (une seule fois)

```bash
# Depuis la racine du projet CI4
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve
# → http://localhost:8080
```

## 🔑 Comptes de test créés

| Email | Mot de passe | Rôle |
|---|---|---|
| admin@techmada.mg | password123 | admin |
| rh@techmada.mg | password123 | rh |
| employe@techmada.mg | password123 | employé |
