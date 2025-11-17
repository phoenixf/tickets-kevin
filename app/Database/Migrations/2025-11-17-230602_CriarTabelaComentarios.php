<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaComentarios extends Migration
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
            'conteudo' => [
                'type' => 'TEXT',
            ],
            'eh_interno' => [
                'type'    => 'TINYINT',
                'default' => 0,
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('ticket_id', 'tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('comentarios');
    }

    public function down()
    {
        $this->forge->dropTable('comentarios');
    }
}
