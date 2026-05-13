<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToConges extends Migration
{
    public function up()
    {
        // Ajouter la colonne updated_at à la table conges
        $this->forge->addColumn('conges', [
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'after'   => 'created_at',
            ],
        ]);
    }

    public function down()
    {
        // Supprimer la colonne si on annule la migration
        $this->forge->dropColumn('conges', 'updated_at');
    }
}
