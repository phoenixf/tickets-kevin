<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSLAConfiguracoesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'prioridade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tempo_primeira_resposta_minutos' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Tempo em minutos para primeira resposta'
            ],
            'tempo_resolucao_minutos' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Tempo em minutos para resolução'
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
        $this->forge->addForeignKey('prioridade_id', 'prioridades', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sla_configuracoes', true); // true = IF NOT EXISTS

        // Inserir configurações padrão de SLA apenas se não existirem
        $count = $this->db->table('sla_configuracoes')->countAllResults();

        if ($count == 0) {
            $data = [
                [
                    'prioridade_id' => 1, // Baixa
                    'tempo_primeira_resposta_minutos' => 1440, // 24 horas
                    'tempo_resolucao_minutos' => 10080, // 7 dias
                ],
                [
                    'prioridade_id' => 2, // Normal
                    'tempo_primeira_resposta_minutos' => 480, // 8 horas
                    'tempo_resolucao_minutos' => 2880, // 2 dias
                ],
                [
                    'prioridade_id' => 3, // Alta
                    'tempo_primeira_resposta_minutos' => 120, // 2 horas
                    'tempo_resolucao_minutos' => 480, // 8 horas
                ],
                [
                    'prioridade_id' => 4, // Crítica
                    'tempo_primeira_resposta_minutos' => 30, // 30 minutos
                    'tempo_resolucao_minutos' => 240, // 4 horas
                ],
            ];

            $this->db->table('sla_configuracoes')->insertBatch($data);
        }
    }

    public function down()
    {
        $this->forge->dropTable('sla_configuracoes');
    }
}
