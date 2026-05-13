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