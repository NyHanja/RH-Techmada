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
}