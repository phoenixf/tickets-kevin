<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TicketsTestesSeeder extends Seeder
{
    public function run()
    {
        // Buscar todos os usuários disponíveis
        $usuarios = $this->db->table('usuarios')->get()->getResult();
        if (count($usuarios) < 2) {
            echo "❌ Erro: Necessário pelo menos 2 usuários no banco\n";
            return;
        }

        // Usar os primeiros usuários disponíveis
        $usuario1 = $usuarios[0];
        $usuario2 = $usuarios[1] ?? $usuarios[0];
        $usuario3 = $usuarios[2] ?? $usuarios[0];

        // Buscar IDs de prioridades
        $prioridades = $this->db->table('prioridades')->orderBy('nivel', 'ASC')->get()->getResult();
        if (count($prioridades) < 4) {
            echo "❌ Erro: Necessário pelo menos 4 prioridades no banco\n";
            return;
        }

        $baixa = $prioridades[0];
        $normal = $prioridades[1];
        $alta = $prioridades[2];
        $urgente = $prioridades[3];

        // Buscar IDs de categorias
        $categorias = $this->db->table('categorias')->get()->getResult();
        if (count($categorias) < 3) {
            echo "❌ Erro: Necessário pelo menos 3 categorias no banco\n";
            return;
        }

        $tecnico = $categorias[0];
        $financeiro = $categorias[1];
        $comercial = $categorias[2];

        // Tickets de teste
        $tickets = [
            [
                'titulo' => 'Sistema está lento para acessar relatórios',
                'descricao' => 'Quando tento acessar os relatórios gerenciais, o sistema fica extremamente lento e às vezes dá timeout. Isso acontece principalmente no período da tarde.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario2->id,
                'categoria_id' => $tecnico->id,
                'prioridade_id' => $alta->id,
                'status' => 'em_progresso',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'titulo' => 'Erro ao emitir nota fiscal de serviço',
                'descricao' => 'Estou tentando emitir a nota fiscal do serviço #4521 mas aparece um erro: "Código de serviço inválido". Já conferi os dados e está tudo correto.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario1->id,
                'categoria_id' => $financeiro->id,
                'prioridade_id' => $urgente->id,
                'status' => 'novo',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'titulo' => 'Solicito acesso ao módulo de CRM',
                'descricao' => 'Preciso de acesso ao módulo de CRM para gerenciar meus clientes e oportunidades de vendas.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario1->id,
                'categoria_id' => $comercial->id,
                'prioridade_id' => $normal->id,
                'status' => 'pendente',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'titulo' => 'Integração com API do banco não está funcionando',
                'descricao' => 'A integração com a API do Banco XYZ para consulta de boletos parou de funcionar desde ontem. O erro retornado é: "Authentication failed - Invalid API key".',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario2->id,
                'categoria_id' => $tecnico->id,
                'prioridade_id' => $urgente->id,
                'status' => 'em_progresso',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'titulo' => 'Dúvida sobre exportação de dados',
                'descricao' => 'Como faço para exportar os dados de vendas do último trimestre em formato Excel? Não estou encontrando essa opção no sistema.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => null,
                'categoria_id' => $tecnico->id,
                'prioridade_id' => $baixa->id,
                'status' => 'resolvido',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-6 days'))
            ],
            [
                'titulo' => 'Treinamento para novos colaboradores',
                'descricao' => 'Contratamos 5 novos colaboradores e precisamos de um treinamento sobre o sistema. Quando podemos agendar?',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario2->id,
                'categoria_id' => $comercial->id,
                'prioridade_id' => $normal->id,
                'status' => 'fechado',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-15 days')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-10 days'))
            ],
            [
                'titulo' => 'Não consigo resetar minha senha',
                'descricao' => 'Esqueci minha senha e tentei usar a opção "Esqueci minha senha" mas o email de recuperação não está chegando. Já verifiquei spam e lixeira.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => null,
                'categoria_id' => $tecnico->id,
                'prioridade_id' => $alta->id,
                'status' => 'novo',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'titulo' => 'Erro 500 ao tentar cadastrar novo cliente',
                'descricao' => 'Ao tentar cadastrar um novo cliente no sistema, aparece um erro 500 (Internal Server Error). Testei em diferentes navegadores e o problema persiste.',
                'usuario_id' => $usuario3->id,
                'responsavel_id' => $usuario1->id,
                'categoria_id' => $tecnico->id,
                'prioridade_id' => $urgente->id,
                'status' => 'em_progresso',
                'criado_em' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'atualizado_em' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
        ];

        // Inserir tickets
        foreach ($tickets as $ticket) {
            $this->db->table('tickets')->insert($ticket);
        }

        echo "✅ 8 tickets de teste criados com sucesso!\n";

        // Adicionar alguns comentários aos tickets
        $ticket1 = $this->db->table('tickets')->where('titulo', 'Sistema está lento para acessar relatórios')->get()->getRow();
        $ticket4 = $this->db->table('tickets')->where('titulo', 'Integração com API do banco não está funcionando')->get()->getRow();

        if ($ticket1 && $ticket4) {
            $comentarios = [
                [
                    'ticket_id' => $ticket1->id,
                    'usuario_id' => $usuario2->id,
                    'conteudo' => 'Estou investigando o problema. Parece ser relacionado ao banco de dados que está com alto uso de CPU no período da tarde.',
                    'eh_interno' => 1,
                    'criado_em' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'atualizado_em' => date('Y-m-d H:i:s', strtotime('-1 day'))
                ],
                [
                    'ticket_id' => $ticket1->id,
                    'usuario_id' => $usuario3->id,
                    'conteudo' => 'Obrigada por verificar! O problema persiste hoje também.',
                    'eh_interno' => 0,
                    'criado_em' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                    'atualizado_em' => date('Y-m-d H:i:s', strtotime('-12 hours'))
                ],
                [
                    'ticket_id' => $ticket4->id,
                    'usuario_id' => $usuario2->id,
                    'conteudo' => 'Identifiquei o problema: a API key expirou. Já solicitei uma nova ao banco.',
                    'eh_interno' => 0,
                    'criado_em' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'atualizado_em' => date('Y-m-d H:i:s', strtotime('-2 hours'))
                ],
                [
                    'ticket_id' => $ticket4->id,
                    'usuario_id' => $usuario1->id,
                    'conteudo' => 'Quando receber a nova chave, já atualize no ambiente de produção e teste.',
                    'eh_interno' => 1,
                    'criado_em' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'atualizado_em' => date('Y-m-d H:i:s', strtotime('-1 hour'))
                ],
            ];

            foreach ($comentarios as $comentario) {
                $this->db->table('comentarios')->insert($comentario);
            }

            echo "✅ 4 comentários de teste criados com sucesso!\n";
        }
    }
}
