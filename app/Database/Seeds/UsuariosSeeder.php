<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Senha padrão para todos: "123456"
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);

        $data = [
            // Administrador
            [
                'nome'          => 'Administrador',
                'email'         => 'admin@tickets.com',
                'senha'         => $senhaHash,
                'funcao'        => 'admin',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            // Agentes
            [
                'nome'          => 'João Silva',
                'email'         => 'joao.silva@tickets.com',
                'senha'         => $senhaHash,
                'funcao'        => 'agente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Maria Santos',
                'email'         => 'maria.santos@tickets.com',
                'senha'         => $senhaHash,
                'funcao'        => 'agente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Carlos Oliveira',
                'email'         => 'carlos.oliveira@tickets.com',
                'senha'         => $senhaHash,
                'funcao'        => 'agente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            // Clientes
            [
                'nome'          => 'Ana Costa',
                'email'         => 'ana.costa@cliente.com',
                'senha'         => $senhaHash,
                'funcao'        => 'cliente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Pedro Almeida',
                'email'         => 'pedro.almeida@cliente.com',
                'senha'         => $senhaHash,
                'funcao'        => 'cliente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
            [
                'nome'          => 'Juliana Ferreira',
                'email'         => 'juliana.ferreira@cliente.com',
                'senha'         => $senhaHash,
                'funcao'        => 'cliente',
                'avatar'        => null,
                'ativo'         => 1,
                'criado_em'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('usuarios')->insertBatch($data);

        echo "✅ 7 usuários criados com sucesso!\n";
        echo "   - 1 Admin: admin@tickets.com\n";
        echo "   - 3 Agentes: joao.silva@tickets.com, maria.santos@tickets.com, carlos.oliveira@tickets.com\n";
        echo "   - 3 Clientes: ana.costa@cliente.com, pedro.almeida@cliente.com, juliana.ferreira@cliente.com\n";
        echo "   - Senha padrão para todos: 123456\n";
    }
}
