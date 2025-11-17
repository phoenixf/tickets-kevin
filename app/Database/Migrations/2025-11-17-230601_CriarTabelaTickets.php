<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaTickets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'descricao' => [
                'type' => 'TEXT',
            ],
            'usuario_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'responsavel_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'categoria_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'prioridade_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 2,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['novo', 'aberto', 'em_progresso', 'pendente', 'resolvido', 'fechado'],
                'default'    => 'novo',
            ],
            'data_vencimento' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'resolvido_em' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fechado_em' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('status');
        $this->forge->addKey('prioridade_id');
        $this->forge->addKey('responsavel_id');
        $this->forge->addKey('criado_em');

        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('responsavel_id', 'usuarios', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('prioridade_id', 'prioridades', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('tickets');
    }

    public function down()
    {
        $this->forge->dropTable('tickets');
    }
}
