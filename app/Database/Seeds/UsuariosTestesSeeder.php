<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosTestesSeeder extends Seeder
{
    public function run()
    {
        $usuariosTeste = [
            [
                'nome'   => 'Kevin',
                'email'  => 'kevin@tickets.com',
                'senha'  => 'segredo0',
                'funcao' => 'admin',
            ],
            [
                'nome'   => 'Luciano',
                'email'  => 'luciano@tickets.com',
                'senha'  => 'segredo0',
                'funcao' => 'agente',
            ],
            [
                'nome'   => 'Roberto Lima',
                'email'  => 'roberto.lima@tickets.com',
                'senha'  => '123456',
                'funcao' => 'agente',
            ],
            [
                'nome'   => 'Fernanda Costa',
                'email'  => 'fernanda.costa@cliente.com',
                'senha'  => '123456',
                'funcao' => 'cliente',
            ],
            [
                'nome'   => 'Bruno Cardoso',
                'email'  => 'bruno.cardoso@cliente.com',
                'senha'  => '123456',
                'funcao' => 'cliente',
            ],
        ];

        $timestamp = date('Y-m-d H:i:s');

        foreach ($usuariosTeste as $usuario) {
            // Hash da senha
            $senhaHash = password_hash($usuario['senha'], PASSWORD_DEFAULT);

            // 1. Inserir na tabela 'usuarios' (tabela original)
            $dataUsuario = [
                'nome'         => $usuario['nome'],
                'email'        => $usuario['email'],
                'senha'        => $senhaHash,
                'funcao'       => $usuario['funcao'],
                'avatar'       => null,
                'ativo'        => 1,
                'criado_em'    => $timestamp,
                'atualizado_em' => $timestamp,
            ];

            $this->db->table('usuarios')->insert($dataUsuario);

            // 2. Inserir na tabela 'users' (Shield)
            $dataUser = [
                'username'   => $usuario['email'],
                'nome'       => $usuario['nome'],
                'email'      => $usuario['email'],
                'funcao'     => $usuario['funcao'],
                'avatar'     => null,
                'active'     => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            $this->db->table('users')->insert($dataUser);
            $userId = $this->db->insertID();

            // 3. Criar identity (email/password) para autenticação
            $dataIdentity = [
                'user_id'    => $userId,
                'type'       => 'email_password',
                'name'       => null,
                'secret'     => $usuario['email'],
                'secret2'    => $senhaHash,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];

            $this->db->table('auth_identities')->insert($dataIdentity);
        }

        echo "\n✅ 5 usuários de teste criados com sucesso!\n";
        echo "   - Kevin (admin): kevin@tickets.com / segredo0\n";
        echo "   - Luciano (agente): luciano@tickets.com / segredo0\n";
        echo "   - Roberto Lima (agente): roberto.lima@tickets.com / 123456\n";
        echo "   - Fernanda Costa (cliente): fernanda.costa@cliente.com / 123456\n";
        echo "   - Bruno Cardoso (cliente): bruno.cardoso@cliente.com / 123456\n";
    }
}
