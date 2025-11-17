<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrarUsuariosParaShield extends Migration
{
    public function up()
    {
        // 1. Adicionar campos extras à tabela users do Shield
        $fields = [
            'nome'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'funcao'   => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'cliente'],
            'avatar'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];

        $this->forge->addColumn('users', $fields);

        // 2. Criar índice único no email
        $this->db->query('CREATE UNIQUE INDEX idx_users_email ON users(email)');

        // 3. Migrar dados de 'usuarios' para 'users' e criar identities
        $usuarios = $this->db->table('usuarios')->get()->getResultArray();

        foreach ($usuarios as $usuario) {
            // Inserir na tabela users
            $userData = [
                'username'   => $usuario['email'], // Usar email como username
                'nome'       => $usuario['nome'],
                'email'      => $usuario['email'],
                'funcao'     => $usuario['funcao'],
                'avatar'     => $usuario['avatar'],
                'active'     => $usuario['ativo'],
                'created_at' => $usuario['criado_em'],
                'updated_at' => $usuario['atualizado_em'],
            ];

            $this->db->table('users')->insert($userData);
            $userId = $this->db->insertID();

            // Criar identity (email/password)
            $identityData = [
                'user_id' => $userId,
                'type'    => 'email_password',
                'name'    => null,
                'secret'  => $usuario['email'],
                'secret2' => $usuario['senha'], // Senha já está hasheada
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('auth_identities')->insert($identityData);
        }

        echo "\n✅ " . count($usuarios) . " usuários migrados com sucesso para o Shield!\n";
    }

    public function down()
    {
        // Remover campos adicionados
        $this->forge->dropColumn('users', ['nome', 'email', 'funcao', 'avatar']);

        // Limpar dados (opcional - cuidado em produção!)
        $this->db->table('users')->truncate();
        $this->db->table('auth_identities')->truncate();
    }
}
