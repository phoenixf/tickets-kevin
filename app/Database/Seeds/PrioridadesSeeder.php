<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrioridadesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome'      => 'Baixa',
                'nivel'     => 1,
                'cor'       => '#10B981',
                'criado_em' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Normal',
                'nivel'     => 2,
                'cor'       => '#EAB308',
                'criado_em' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Alta',
                'nivel'     => 3,
                'cor'       => '#F97316',
                'criado_em' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Crítica',
                'nivel'     => 4,
                'cor'       => '#EF4444',
                'criado_em' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('prioridades')->insertBatch($data);

        echo "✅ 4 prioridades criadas com sucesso!\n";
    }
}
