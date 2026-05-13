<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\SoldeModel;

class EmployeController extends BaseController
{
    protected $employeModel;
    protected $congeModel;
    protected $soldeModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
        $this->congeModel = new CongeModel();
        $this->soldeModel = new SoldeModel();
    }

    /**
     * Afficher le dashboard avec les soldes
     */
    public function dashboard()
    {
        $employe_id = session()->get('user_id');
        $annee = date('Y');

        //rec solde de lannee
        $soldes = $this->soldeModel->getSoldesEmploye($employe_id, $annee);

        return view('employe/dashboard', [
            'title' => 'Mon Tableau de bord',
            'soldes' => $soldes,
        ]);
    }

    /**
     * Listerdemamnde conge employe
     */
    public function index()
    {
        $employe_id = session()->get('user_id');

        // Récupérer toutes les demandes de cet employé
        $demandes = $this->congeModel->getMesDemandes($employe_id);

        return view('employe/index', [
            'title' => 'Mes demandes de congé',
            'demandes' => $demandes,
        ]);
    }

    /**
     * Afficher le formulaire creation demande
     */
    public function create()
    {
        // Récupérer les types de congé
        $db = \Config\Database::connect();
        $types_conge = $db->table('types_conge')->get()->getResultArray();

        return view('employe/create', [
            'title' => 'Nouvelle demande de congé',
            'types_conge' => $types_conge,
        ]);
    }

    /**
     * Stocker une nouvelle demande de conge
     */
    public function store()
    {
        $employe_id = session()->get('user_id');
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $type_conge_id = $this->request->getPost('type_conge_id');
        $motif = $this->request->getPost('motif') ?? '';

        // 1. Valider les dates
        if (!$this->validate([
            'date_debut' => 'required|valid_date[Y-m-d]',
            'date_fin' => 'required|valid_date[Y-m-d]',
            'type_conge_id' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Données invalides');
        }

        // 2. date_debut < date_fin
        if (strtotime($date_debut) >= strtotime($date_fin)) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit être après la date de début');
        }

        // 3. Verfr s'il y a chevauchement de dates
        if ($this->congeModel->hasChevauchement($employe_id, $date_debut, $date_fin)) {
            return redirect()->back()->withInput()->with('error', 'Vous avez déjà une demande sur ces dates');
        }

        // 4. Calculer le nombre de jours
        $nb_jours = CongeModel::calculerNombreJours($date_debut, $date_fin);

        // 5. Verfle solde
        $annee = date('Y');
        $solde = $this->soldeModel->getSolde($employe_id, $type_conge_id, $annee);

        if (!$solde) {
            return redirect()->back()->with('error', 'Solde non trouvé pour cette année');
        }

        $jours_restants = $solde['jours_attribues'] - $solde['jours_pris'];
        if ($nb_jours > $jours_restants) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Vous avez ' . $jours_restants . ' jours disponibles');
        }

        // 6. creer demande
        $this->congeModel->createDemande([
            'employe_id' => $employe_id,
            'type_conge_id' => $type_conge_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'motif' => $motif,
        ]);

        return redirect()->to('/employe/demandes')
                        ->with('success', 'Demande créée avec succès et en attente d\'approbation');
    }

    /**
     * Annuler une demande (seulement si en attente)
     */
    public function annuler($id)
    {
        $employe_id = session()->get('user_id');

        // rec demande
        $demande = $this->congeModel->find($id);

        if (!$demande) {
            return redirect()->back()->with('error', 'Demande non trouvée');
        }

        if ($demande['employe_id'] != $employe_id) {
            return redirect()->to('/employe/demandes')->with('error', 'Accès refusé');
        }

        // en attente
        if ($demande['statut'] !== 'en_attente') {
            return redirect()->to('/employe/demandes')->with('error', 'Seules les demandes en attente peuvent être annulées');
        }

        // Annuler la demande
        $this->congeModel->annuler($id);

        return redirect()->to('/employe/demandes')
                        ->with('success', 'Demande annulée');
    }

    /**
     * Afficher le profil
     */
    public function profil()
    {
        $employe_id = session()->get('user_id');
        $employe = $this->employeModel->find($employe_id);

        return view('employe/profil', [
            'title' => 'Mon profil',
            'employe' => $employe,
        ]);
    }

    /**
     * Mettre a jour le profil
     */
    public function profilUpdate()
    {
        $employe_id = session()->get('user_id');
        $new_prenom = $this->request->getPost('prenom');
        $new_nom = $this->request->getPost('nom');
        $new_password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');

    
        if (!$this->validate([
            'prenom' => 'required|min_length[2]',
            'nom' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Prénom ou nom invalide');
        }

    
        if ($new_password) {
            if (!$this->validate([
                'password' => 'required|min_length[8]',
            ])) {
                return redirect()->back()->withInput()->with('error', 'Le mot de passe doit faire au moins 8 caractères');
            }

            if ($new_password !== $password_confirm) {
                return redirect()->back()->withInput()->with('error', 'Les mots de passe ne correspondent pas');
            }

            
            $this->employeModel->changePassword($employe_id, $new_password);
        }

        // 3. Maj le profil
        $this->employeModel->updateProfil($employe_id, [
            'prenom' => $new_prenom,
            'nom' => $new_nom,
        ]);

        // 4. Maj la session
        session()->set([
            'prenom' => $new_prenom,
            'nom' => $new_nom,
        ]);

        return redirect()->to('/employe/profil')
                        ->with('success', 'Profil mis à jour avec succès');
    }
}
