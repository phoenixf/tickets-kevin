<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaPrioridades extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nivel' => [
                'type'     => 'INT',
                'unsigned' => false,
            ],
            'cor' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nivel');
        $this->forge->createTable('prioridades');
    }

    public function down()
    {
        $this->forge->dropTable('prioridades');
    }
}
