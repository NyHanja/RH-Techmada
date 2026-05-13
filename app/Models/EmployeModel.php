<?php 

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'departement_id',
        'nom',
        'prenom',
        'email',
        'role',
        'password',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    protected $validationRules = [
        'nom'        => 'required|min_length[3]',
        'prenom'     => 'required|min_length[2]',
        'email'      => 'required|valid_email|is_unique[employes.email]',
        'password'   => 'required|min_length[8]',
        'role'       => 'required|in_list[employe,rh,admin]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé',
        ],
    ];
    protected $skipValidation = false;

    public function findByEmail($email){
        return $this->where('email', $email)->first();
    }

    public function createEmploye($data){
        if(isset($data['password'])){
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        return $this->insert($data);
    }
    
    public function getEmployeWithDepartement($employe_id){
        return $this->select('employes.*, departements.nom as departement_nom')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->where('employes.id', $employe_id)
                    ->first();
    }

    public function getAllEmployes(){
        return $this->select('employes.*, departements.nom as departement_nom')
                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                    ->findAll();
    }

    public function updateProfil($employe_id, $data){
        return $this->update($employe_id, $data);
    }

    public function changePassword($employe_id, $newPassword){
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($employe_id, ['password' => $hashedPassword]);
    }

    public function getEmployesByDepartement($departement_id){
        return $this->where('departement_id', $departement_id)->findAll();
    }

     public function getRh(){
        return $this->where('role', 'rh')->findAll();
    }
}