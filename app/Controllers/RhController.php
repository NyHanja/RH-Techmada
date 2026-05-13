<?php
namespace App\Controllers;

class RhController extends BaseController
{
    public function dashboard() {
        try {
            $db = \Config\Database::connect();

            $nbEnAttente = $db->table('conges')
                ->where('statut', 'en_attente')
                ->countAllResults();

            $partDept = $db->table('conges c')
                ->select('d.nom as departement, COUNT(*) as nb')
                ->join('employes e', 'e.id = c.employe_id')
                ->join('departements d', 'd.id = e.departement_id')
                ->where('c.statut', 'en_attente')
                ->groupBy('d.id')
                ->get()
                ->getResult();

            $annee = date('Y');
            $soldes = $db->table('soldes s')
                ->select('e.prenom, e.nom, tc.libelle as type_conge, s.jours_attribues, s.jours_pris')
                ->join('employes e', 'e.id = s.employe_id')
                ->join('types_conge tc', 'tc.id = s.type_conge_id')
                ->where('s.annee', $annee)
                ->get()->getResultArray();

            return view('rh/dashboard', [
                'nbEnAttente' => $nbEnAttente,
                'partDept'    => $partDept,
                'soldes'      => $soldes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[RH dashboard] ' . $e->getMessage());
            return view('rh/error', ['message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        try {
           $db = \Config\Database::connect();

           $builder = $db->table('conges c')
                ->select('c.*, e.nom, e.prenom, tc.libelle as type_conge, d.nom as departement')
                ->join('employes e', 'e.id = c.employe_id')
                ->join('types_conge tc', 'tc.id = c.type_conge_id')
                ->join('departements d', 'd.id = e.departement_id');

            $dept = $this->request->getGet('departement_id');
            if($dept) {
                $builder->where('d.id', $dept);
            }

            $statut = $this->request->getGet('statut')?? 'en_attente';
            if($statut !== 'tous') {
                $builder->where('c.statut', $statut);
            }

            $demandes = $builder->get()->getResultArray();
            $departements = $db->table('departements')->get()->getResultArray();

            return view('rh/index', [
                'demandes' => $demandes,
                'departements' => $departements,
                'statut' => $statut,
                'dept' => $dept,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[RH demandes] ' . $e->getMessage());
            return view('rh/error', ['message' => 'Erreur lors de la récupération des demandes : ' . $e->getMessage()]);
        }

    }

    public function approuver($id)
    {
        $db = \Config\Database::connect();
        $conge = $db->table('conges')->where('id', $id)->get()->getRowArray();

        if(!$conge) {
            session()->setFlashdata('error', 'Demande de congé introuvable');
            return redirect()->to('/rh/demandes');
        }

        $annee = date('Y', strtotime($conge['date_debut']));
        $solde = $db->table('soldes')
            ->where('employe_id', $conge['employe_id'])
            ->where('type_conge_id', $conge['type_conge_id'])
            ->where('annee', $annee)
            ->get()
            ->getRowArray();

        $disponible = $solde['jours_attribues'] - $solde['jours_pris'];

        if($conge['nb_jours'] > $disponible) {
            session()->setFlashdata('error', 'Solde insuffisant pour approuver cette demande');
            return redirect()->to('/rh/demandes');
        }

        $db->table('conges')->where('id', $id)->update([
            'statut' => 'approuve',
            'traite_par' => session()->get('user_id')
        ]);
       
        $db->table('soldes')
            ->where('employe_id', $conge['employe_id'])
            ->where('type_conge_id', $conge['type_conge_id'])
            ->where('annee', $annee)
            ->update(['jours_pris' => $solde['jours_pris'] + $conge['nb_jours']]);

        session()->setFlashdata('success', 'Demande de congé approuvée avec succès');
        return redirect()->to('/rh/demandes');
    }

    public function refuser($id) {
        $db = \Config\Database::connect();
        $conge = $db->table('conges')->where('id', $id)->get()->getRowArray();

        if(!$conge) {
            session()->setFlashdata('error', 'Demande de congé introuvable');
            return redirect()->to('/rh/demandes');
        }

        $commentaire = $this->request->getPost('commentaire_rh');

        $db->table('conges')->where('id', $id)->update([
            'statut' => 'refuse',
            'traite_par' => session()->get('user_id'),
            'commentaire_rh' => $commentaire
        ]);

        session()->setFlashdata('success', 'Demande de congé refusée avec succès');
        return redirect()->to('/rh/demandes');

    }
}

?>