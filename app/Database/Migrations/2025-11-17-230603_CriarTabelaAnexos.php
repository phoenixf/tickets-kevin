<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaAnexos extends Migration
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
            'nome_arquivo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'caminho_arquivo' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'tamanho_arquivo' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'tipo_mime' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'enviado_por' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('ticket_id', 'tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enviado_por', 'usuarios', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('anexos');
    }

    public function down()
    {
        $this->forge->dropTable('anexos');
    }
}
