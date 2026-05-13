<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $db = \Config\Database::connect();
        $aujourd_hui = date('Y-m-d');
        $mois        = date('Y-m');

        // Nb employés actifs
        $nbEmployesActifs = $db->table('employes')
            ->where('actif', 1)
            ->countAllResults();

        // Nb demandes en attente
        $nbEnAttente = $db->table('conges')
            ->where('statut', 'en_attente')
            ->countAllResults();

        // Nb approuvées ce mois
        $nbApprouvesMois = $db->table('conges')
            ->where('statut', 'approuvee')
            ->like('date_debut', $mois, 'after')
            ->countAllResults();

        // Nb départements
        $nbDepartements = $db->table('departements')
            ->countAllResults();

        // Absents aujourd'hui (congés approuvés qui couvrent aujourd'hui)
        $absentsAujourdhui = $db->table('conges c')
            ->select('e.nom, e.prenom, tc.libelle as type_conge, c.date_fin')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->where('c.statut', 'approuvee')
            ->where('c.date_debut <=', $aujourd_hui)
            ->where('c.date_fin >=', $aujourd_hui)
            ->get()->getResultArray();

        $nbAbsentsAujourdhui = count($absentsAujourdhui);

        // Dernières demandes (5 plus récentes)
        $demandesRecentes = $db->table('conges c')
            ->select('c.*, e.nom, e.prenom, tc.libelle as type_conge')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge tc', 'tc.id = c.type_conge_id')
            ->orderBy('c.id', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Soldes critiques (≤ 2 jours restants)
        $nbSoldesCritiques = $db->table('soldes')
            ->where('annee', date('Y'))
            ->where('(jours_attribues - jours_pris) <=', 2)
            ->countAllResults();

        // Congés par type ce mois
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

    public function employes()
    {
        $db = \Config\Database::connect();
        $employes = $db->table('employes e')
            ->select('e.*, d.nom as departement')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->orderBy('e.nom', 'ASC')
            ->get()->getResultArray();

        return view('admin/employes', [
            'employes' => $employes,
        ]);
    }

    public function departements()
    {
        $db = \Config\Database::connect();
        $departements = $db->table('departements')
            ->orderBy('nom', 'ASC')
            ->get()->getResultArray();

        return view('admin/departements', [
            'departements' => $departements,
        ]);
    }

    public function typesConge()
    {
        $db = \Config\Database::connect();
        $types = $db->table('types_conge')
            ->orderBy('libelle', 'ASC')
            ->get()->getResultArray();

        return view('admin/types-conge', [
            'types' => $types,
        ]);
    }

    public function soldes()
    {
        $db = \Config\Database::connect();
        $annee = date('Y');
        $soldes = $db->table('soldes s')
            ->select('s.*, e.nom, e.prenom, tc.libelle as type_conge')
            ->join('employes e', 'e.id = s.employe_id', 'left')
            ->join('types_conge tc', 'tc.id = s.type_conge_id', 'left')
            ->where('s.annee', $annee)
            ->orderBy('e.nom', 'ASC')
            ->get()->getResultArray();

        return view('admin/soldes', [
            'soldes' => $soldes,
        ]);
    }

    public function createEmploye()
    {
        $db = \Config\Database::connect();
        $departements = $db->table('departements')
            ->orderBy('nom', 'ASC')
            ->get()->getResultArray();

        return view('admin/create-employe', [
            'departements' => $departements,
        ]);
    }

    public function storeEmploye()
    {
        $db = \Config\Database::connect();
        
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role') ?? 'employe';
        $departement_id = $this->request->getPost('departement_id');
        $date_embauche = $this->request->getPost('date_embauche');

        // Valider
        if (!$this->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|valid_email|is_unique[employes.email]',
            'password' => 'required|min_length[6]',
            'departement_id' => 'required|numeric',
            'date_embauche' => 'required|valid_date[Y-m-d]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Données invalides');
        }

        // Insérer
        $db->table('employes')->insert([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'departement_id' => $departement_id,
            'date_embauche' => $date_embauche,
            'actif' => 1,
        ]);

        return redirect()->to('/admin/employes')
                        ->with('success', 'Employé créé avec succès');
    }

    public function editEmploye($id)
    {
        $db = \Config\Database::connect();
        $employe = $db->table('employes')->where('id', $id)->get()->getRowArray();
        
        if (!$employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé non trouvé');
        }

        $departements = $db->table('departements')
            ->orderBy('nom', 'ASC')
            ->get()->getResultArray();

        return view('admin/edit-employe', [
            'employe' => $employe,
            'departements' => $departements,
        ]);
    }

    public function updateEmploye($id)
    {
        $db = \Config\Database::connect();
        $employe = $db->table('employes')->where('id', $id)->get()->getRowArray();
        
        if (!$employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé non trouvé');
        }

        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');
        $departement_id = $this->request->getPost('departement_id');
        $date_embauche = $this->request->getPost('date_embauche');

        // Valider
        $rules = [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'role' => 'required|string',
            'departement_id' => 'required|numeric',
            'date_embauche' => 'required|valid_date[Y-m-d]',
        ];

        // Si email changé, vérifier qu'il soit unique
        if ($email !== $employe['email']) {
            $rules['email'] = 'required|valid_email|is_unique[employes.email]';
        }

        // Si nouveau mot de passe
        if ($password) {
            $rules['password'] = 'required|min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Données invalides');
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'departement_id' => $departement_id,
            'date_embauche' => $date_embauche,
        ];

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db->table('employes')->where('id', $id)->update($data);

        return redirect()->to('/admin/employes')
                        ->with('success', 'Employé mis à jour avec succès');
    }

    public function deactivateEmploye($id)
    {
        $db = \Config\Database::connect();
        $employe = $db->table('employes')->where('id', $id)->get()->getRowArray();
        
        if (!$employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé non trouvé');
        }

        $new_status = $employe['actif'] ? 0 : 1;
        $db->table('employes')->where('id', $id)->update(['actif' => $new_status]);

        $msg = $new_status ? 'Employé activé avec succès' : 'Employé désactivé avec succès';
        return redirect()->to('/admin/employes')->with('success', $msg);
    }
}