<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome'          => 'Suporte Técnico',
                'descricao'     => 'Problemas técnicos, bugs, erros do sistema',
                'cor'           => '#3B82F6',
                'icone'         => 'wrench',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Financeiro',
                'descricao'     => 'Questões financeiras, pagamentos, cobranças',
                'cor'           => '#10B981',
                'icone'         => 'dollar',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Comercial',
                'descricao'     => 'Vendas, propostas, novos clientes',
                'cor'           => '#8B5CF6',
                'icone'         => 'briefcase',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Recursos Humanos',
                'descricao'     => 'Questões de RH, férias, atestados',
                'cor'           => '#F59E0B',
                'icone'         => 'users',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Infraestrutura',
                'descricao'     => 'Servidores, rede, hardware',
                'cor'           => '#EF4444',
                'icone'         => 'server',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Outros',
                'descricao'     => 'Assuntos gerais que não se encaixam nas outras categorias',
                'cor'           => '#6B7280',
                'icone'         => 'question',
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categorias')->insertBatch($data);

        echo "✅ 6 categorias criadas com sucesso!\n";
    }
}
