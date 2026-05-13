<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{

    protected $table      = 'conges';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'motif',
        'statut',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    protected $validationRules = [
        'employe_id'     => 'required|numeric',
        'type_conge_id'  => 'required|numeric',
        'date_debut'     => 'required|valid_date[Y-m-d]',
        'date_fin'       => 'required|valid_date[Y-m-d]',
        'motif'          => 'permit_empty|string',
    ];

    protected $skipValidation = false;

   

    /**
     * Obtenir les demandes conge pas empl
     */
    public function getMesDemandes($employe_id)
    {
        return $this->select('conges.*, types_conge.nom as type_nom')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.employe_id', $employe_id)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Obtenir les demandes en attente (pour le RH)
     */
    public function getDemandesEnAttente()
    {
        return $this->select('conges.*, employes.nom, employes.prenom, types_conge.nom as type_nom')
                    ->join('employes', 'employes.id = conges.employe_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.statut', 'en_attente')
                    ->orderBy('conges.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Obtenir une demande de conge detail
     */
    public function getDemandeWithDetails($id)
    {
        return $this->select('conges.*, employes.nom, employes.prenom, types_conge.nom as type_nom')
                    ->join('employes', 'employes.id = conges.employe_id', 'left')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id', 'left')
                    ->where('conges.id', $id)
                    ->first();
    }

    /**
     * verf chevauchement 
     */
    public function hasChevauchement($employe_id, $date_debut, $date_fin, $conge_id = null)
    {
        $query = $this->where('employe_id', $employe_id)
                      ->where('statut !=', 'refusee')
                      ->where('statut !=', 'annulee')
                      ->whereIn('statut', ['en_attente', 'approuvee']);
        
        if ($conge_id) {
            $query->where('id !=', $conge_id);
        }

        $query->where("date_fin >= '{$date_debut}'", null, false)
              ->where("date_debut <= '{$date_fin}'", null, false);

        return $query->first() !== null;
    }

    /**
     * new demande conge
     */
    public function createDemande($data)
    {
        $data['statut'] = 'en_attente';
        return $this->insert($data);
    }

    /**
     * Approuver une demande
     */
    public function approuver($id)
    {
        return $this->update($id, ['statut' => 'approuvee']);
    }

    /**
     * Refuser une demande
     */
    public function refuser($id)
    {
        return $this->update($id, ['statut' => 'refusee']);
    }

    /**
     * Annuler une demande
     */
    public function annuler($id)
    {
        return $this->update($id, ['statut' => 'annulee']);
    }

    /**
     * Calculer le nombre de jours entre deux dates (jours calendaires)
     */
    public static function calculerNombreJours($date_debut, $date_fin)
    {
        $debut = new \DateTime($date_debut);
        $fin = new \DateTime($date_fin);
        $intervalle = $debut->diff($fin);
        
        // +1 pour inclure le dernier jour
        return $intervalle->days + 1;
    }
}