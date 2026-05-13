<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    // -------------------------------------------------------
    // Dashboard
    // -------------------------------------------------------
    public function dashboard()
    {
        $db          = \Config\Database::connect();
        $aujourd_hui = date('Y-m-d');
        $mois        = date('Y-m');

        $nbEmployesActifs = $db->table('employes')
            ->where('actif', 1)
            ->countAllResults();

        $nbEnAttente = $db->table('conges')
            ->where('statut', 'en_attente')
            ->countAllResults();

        $nbApprouvesMois = $db->table('conges')
            ->where('statut', 'approuvee')
            ->like('date_debut', $mois, 'after')
            ->countAllResults();

        $nbDepartements = $db->table('departements')
            ->countAllResults();

        $absentsAujourdhui = $db->table('conges c')
            ->select('e.nom, e.prenom, tc.libelle as type_conge, c.date_fin')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->where('c.statut', 'approuvee')
            ->where('c.date_debut <=', $aujourd_hui)
            ->where('c.date_fin >=', $aujourd_hui)
            ->get()->getResultArray();

        $nbAbsentsAujourdhui = count($absentsAujourdhui);

        $demandesRecentes = $db->table('conges c')
            ->select('c.*, e.nom, e.prenom, tc.libelle as type_conge')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->orderBy('c.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $nbSoldesCritiques = $db->table('soldes')
            ->where('annee', date('Y'))
            ->where('(jours_attribues - jours_pris) <=', 2)
            ->countAllResults();

        $congesParType = $db->table('conges c')
            ->select('tc.libelle as type_conge, COUNT(*) as nb')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->like('c.date_debut', $mois, 'after')
            ->groupBy('tc.id')
            ->get()->getResultArray();

        return view('admin/dashboard', [
            'nbEmployesActifs'    => $nbEmployesActifs,
            'nbEnAttente'         => $nbEnAttente,
            'nbApprouvesMois'     => $nbApprouvesMois,
            'nbDepartements'      => $nbDepartements,
            'nbAbsentsAujourdhui' => $nbAbsentsAujourdhui,
            'absentsAujourdhui'   => $absentsAujourdhui,
            'demandesRecentes'    => $demandesRecentes,
            'nbSoldesCritiques'   => $nbSoldesCritiques,
            'congesParType'       => $congesParType,
        ]);
    }

    // -------------------------------------------------------
    // Liste des employés
    // -------------------------------------------------------
    public function employes()
    {
        $db = \Config\Database::connect();

        $employes = $db->table('employes e')
            ->select('e.*, d.nom as departement')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->orderBy('e.nom', 'ASC')
            ->get()->getResultArray();

        $departements = $db->table('departements')->get()->getResultArray();

        return view('admin/employes', [
            'employes'     => $employes,
            'departements' => $departements,
        ]);
    }

    // -------------------------------------------------------
    // Formulaire création employé
    // -------------------------------------------------------
    public function employeCreate()
    {
        $db           = \Config\Database::connect();
        $departements = $db->table('departements')->get()->getResultArray();
        $typesConge   = $db->table('types_conge')->get()->getResultArray();

        return view('admin/employe_create', [
            'departements' => $departements,
            'typesConge'   => $typesConge,
        ]);
    }

    // -------------------------------------------------------
    // Enregistrer nouvel employé
    // -------------------------------------------------------
    public function employeStore()
    {
        $db = \Config\Database::connect();

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id'),
            'actif'          => 1,
        ];

        $db->table('employes')->insert($data);
        $employe_id = $db->insertID();

        // Créer automatiquement les soldes pour l'année courante
        $typesConge = $db->table('types_conge')->get()->getResultArray();
        $annee      = date('Y');

        foreach ($typesConge as $type) {
            $db->table('soldes')->insert([
                'employe_id'      => $employe_id,
                'type_conge_id'   => $type['id'],
                'annee'           => $annee,
                'jours_attribues' => $type['jours_annuels'] ?? ($type['jours_par_defaut'] ?? 0),
                'jours_pris'      => 0,
            ]);
        }

        session()->setFlashdata('success', 'Employé créé avec succès.');
        return redirect()->to('/admin/employes');
    }

    // -------------------------------------------------------
    // Formulaire modification employé
    // -------------------------------------------------------
    public function employeEdit($id)
    {
        $db      = \Config\Database::connect();
        $employe = $db->table('employes')->where('id', $id)->get()->getRowArray();

        if (!$employe) {
            session()->setFlashdata('error', 'Employé introuvable.');
            return redirect()->to('/admin/employes');
        }

        $departements = $db->table('departements')->get()->getResultArray();

        return view('admin/employe_edit', [
            'employe'      => $employe,
            'departements' => $departements,
        ]);
    }

    // -------------------------------------------------------
    // Enregistrer modifications employé
    // -------------------------------------------------------
    public function employeUpdate($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'prenom'         => $this->request->getPost('prenom'),
            'email'          => $this->request->getPost('email'),
            'role'           => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id'),
        ];

        // Changer le mot de passe seulement si fourni
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db->table('employes')->where('id', $id)->update($data);

        session()->setFlashdata('success', 'Employé mis à jour.');
        return redirect()->to('/admin/employes');
    }

    // -------------------------------------------------------
    // Désactiver un employé (jamais supprimer)
    // -------------------------------------------------------
    public function employeDesactiver($id)
    {
        $db = \Config\Database::connect();

        $db->table('employes')->where('id', $id)->update(['actif' => 0]);

        session()->setFlashdata('success', 'Employé désactivé.');
        return redirect()->to('/admin/employes');
    }

    public function employeSupprimer($id)
    {
        $db = \Config\Database::connect();

        // Empêche l'admin connecté de supprimer son propre compte.
        if ((int) $id === (int) session()->get('user_id')) {
            session()->setFlashdata('error', 'Suppression impossible: vous ne pouvez pas supprimer votre propre compte.');
            return redirect()->to('/admin/employes');
        }

        $employe = $db->table('employes')->where('id', $id)->get()->getRowArray();
        if (!$employe) {
            session()->setFlashdata('error', 'Employé introuvable.');
            return redirect()->to('/admin/employes');
        }

        $db->table('employes')->where('id', $id)->delete();

        session()->setFlashdata('success', 'Employé supprimé.');
        return redirect()->to('/admin/employes');
    }

    // -------------------------------------------------------
    // Départements
    // -------------------------------------------------------
    public function departements()
    {
        $db           = \Config\Database::connect();
        $departements = $db->table('departements')->get()->getResultArray();

        return view('admin/departements', [
            'departements' => $departements,
        ]);
    }

    public function departementStore()
    {
        $db = \Config\Database::connect();

        $db->table('departements')->insert([
            'nom' => $this->request->getPost('nom'),
        ]);

        session()->setFlashdata('success', 'Département ajouté.');
        return redirect()->to('/admin/departements');
    }

    // -------------------------------------------------------
    // Types de congé
    // -------------------------------------------------------
    public function typesConge()
    {
        $db         = \Config\Database::connect();
        $typesConge = $db->table('types_conge')->get()->getResultArray();

        return view('admin/types_conge', [
            'typesConge' => $typesConge,
        ]);
    }

    public function typeCongeStore()
    {
        $db = \Config\Database::connect();

        $db->table('types_conge')->insert([
            'libelle'        => $this->request->getPost('nom'),
            'jours_annuels'  => $this->request->getPost('jours_par_defaut'),
        ]);

        session()->setFlashdata('success', 'Type de congé ajouté.');
        return redirect()->to('/admin/types-conge');
    }

    // -------------------------------------------------------
    // Initialiser / ajuster le solde d'un employé
    // -------------------------------------------------------
    public function initSolde($employe_id)
    {
        $db            = \Config\Database::connect();
        $type_conge_id = $this->request->getPost('type_conge_id');
        $jours         = $this->request->getPost('jours_attribues');
        $annee         = $this->request->getPost('annee') ?? date('Y');

        // Vérifier si le solde existe déjà
        $solde = $db->table('soldes')
            ->where('employe_id', $employe_id)
            ->where('type_conge_id', $type_conge_id)
            ->where('annee', $annee)
            ->get()->getRowArray();

        if ($solde) {
            // Mettre à jour
            $db->table('soldes')
                ->where('employe_id', $employe_id)
                ->where('type_conge_id', $type_conge_id)
                ->where('annee', $annee)
                ->update(['jours_attribues' => $jours]);
        } else {
            // Créer
            $db->table('soldes')->insert([
                'employe_id'      => $employe_id,
                'type_conge_id'   => $type_conge_id,
                'annee'           => $annee,
                'jours_attribues' => $jours,
                'jours_pris'      => 0,
            ]);
        }

        session()->setFlashdata('success', 'Solde mis à jour.');
        return redirect()->to('/admin/employes');
    }
}