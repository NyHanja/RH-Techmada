<?php
namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model{
    protected $table      = 'soldes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',         // L'annee du solde
        'jours_attribues',  // Total jours accordes
        'jours_pris',    // Jours utilises
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'employe_id'      => 'required|numeric',
        'type_conge_id'   => 'required|numeric',
        'annee'           => 'required|numeric',
        'jours_attribues' => 'required|numeric',
        'jours_pris'      => 'required|numeric',
    ];

    protected $skipValidation = false;

    public function getSolde($employe_id, $type_conge_id, $annee){
        return $this->where('employe_id', $employe_id)
                    ->where('type_conge_id', $type_conge_id)
                    ->where('annee', $annee)
                    ->first();
    }

    public function getSoldesEmploye($employe_id, $annee){
        return $this->select('soldes.*, types_conge.libelle as type_nom')
                    ->join('types_conge', 'types_conge.id = soldes.type_conge_id', 'left')
                    ->where('soldes.employe_id', $employe_id)
                    ->where('soldes.annee', $annee)
                    ->findAll();
    }

    public function deduire($employe_id, $type_conge_id, $annee, $nb_jours){
        $solde = $this->getSolde($employe_id, $type_conge_id, $annee);

        if (!$solde) {
            return false;  
        }

        // Ajouter les jours aux jours_pris
        $nouveau_jours_pris = $solde['jours_pris'] + $nb_jours;

        
        if ($nouveau_jours_pris > $solde['jours_attribues']) {
            return false;  // Pas assez de jours
        }

        return $this->update($solde['id'], ['jours_pris' => $nouveau_jours_pris]);
    }

    public function restituer($employe_id, $type_conge_id, $annee, $nb_jours){
        $solde = $this->getSolde($employe_id, $type_conge_id, $annee);

        if (!$solde) {
            return false;
        }

        // atao (-) jours des jours_pris
        $nouveau_jours_pris = max(0, $solde['jours_pris'] - $nb_jours);

        return $this->update($solde['id'], ['jours_pris' => $nouveau_jours_pris]);
    }

    public function getJoursRestants($employe_id, $type_conge_id, $annee){
        $solde = $this->getSolde($employe_id, $type_conge_id, $annee);

        if (!$solde) {
            return 0;
        }

        return $solde['jours_attribues'] - $solde['jours_pris'];
    }

    public function initializerSoldes($employe_id, $annee){
        
        $types = [1, 2, 3];  
        $jours_par_type = [
            1 => 25,  
            2 => 5,   
            3 => 3,   
        ];

        foreach ($types as $type_id) {
            $this->insert([
                'employe_id'      => $employe_id,
                'type_conge_id'   => $type_id,
                'annee'           => $annee,
                'jours_attribues' => $jours_par_type[$type_id] ?? 0,
                'jours_pris'      => 0,
            ]);
        }
    }
}