<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaHistoricoTickets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'usuario_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'acao' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'campo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'valor_antigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'valor_novo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('ticket_id', 'tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('historico_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('historico_tickets');
    }
}
