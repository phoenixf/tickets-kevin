<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSLAFieldsToTickets extends Migration
{
    public function up()
    {
        $fields = [
            'primeira_resposta_em' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Data/hora da primeira resposta do agente'
            ],
        ];

        $this->forge->addColumn('tickets', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tickets', ['primeira_resposta_em']);
    }
}
