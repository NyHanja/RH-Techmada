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