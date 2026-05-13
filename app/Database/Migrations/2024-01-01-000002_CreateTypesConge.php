<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesConge extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'libelle' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'jours_annuels' => [
                'type'    => 'INTEGER',
                'null'    => false,
                'default' => 0,
            ],
            'deductible' => [
                'type'    => 'INTEGER',  // 0 ou 1 (SQLite n'a pas BOOLEAN)
                'null'    => false,
                'default' => 1,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('types_conge');
    }

    public function down()
    {
        $this->forge->dropTable('types_conge');
    }
}