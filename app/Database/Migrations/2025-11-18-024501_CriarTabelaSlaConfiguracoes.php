<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaSlaConfiguracoes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'prioridade_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'tempo_primeira_resposta_minutos' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Tempo máximo em minutos para primeira resposta',
            ],
            'tempo_resolucao_minutos' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Tempo máximo em minutos para resolução completa',
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
        $this->forge->addUniqueKey('prioridade_id');

        $this->forge->addForeignKey(
            'prioridade_id',
            'prioridades',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('sla_configuracoes');
    }

    public function down()
    {
        $this->forge->dropTable('sla_configuracoes');
    }
}
