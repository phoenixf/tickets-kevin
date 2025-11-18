<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SlaSeeder extends Seeder
{
    public function run()
    {
        // Limpar dados existentes (idempotente)
        $this->db->table('sla_configuracoes')->truncate();

        $data = [
            [
                'prioridade_id'                      => 4,  // Crítica
                'tempo_primeira_resposta_minutos'    => 60,
                'tempo_resolucao_minutos'            => 240,
                'criado_em'                          => date('Y-m-d H:i:s'),
                'atualizado_em'                      => date('Y-m-d H:i:s'),
            ],
            [
                'prioridade_id'                      => 3,  // Alta
                'tempo_primeira_resposta_minutos'    => 240,
                'tempo_resolucao_minutos'            => 1440,
                'criado_em'                          => date('Y-m-d H:i:s'),
                'atualizado_em'                      => date('Y-m-d H:i:s'),
            ],
            [
                'prioridade_id'                      => 2,  // Normal
                'tempo_primeira_resposta_minutos'    => 480,
                'tempo_resolucao_minutos'            => 2880,
                'criado_em'                          => date('Y-m-d H:i:s'),
                'atualizado_em'                      => date('Y-m-d H:i:s'),
            ],
            [
                'prioridade_id'                      => 1,  // Baixa
                'tempo_primeira_resposta_minutos'    => 1440,
                'tempo_resolucao_minutos'            => 4320,
                'criado_em'                          => date('Y-m-d H:i:s'),
                'atualizado_em'                      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('sla_configuracoes')->insertBatch($data);

        echo "✅ 4 configurações de SLA criadas com sucesso!\n";
        echo "   - Crítica (id=4): 1h / 4h\n";
        echo "   - Alta (id=3): 4h / 24h\n";
        echo "   - Normal (id=2): 8h / 48h\n";
        echo "   - Baixa (id=1): 24h / 72h\n";
    }
}
