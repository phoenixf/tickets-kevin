<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TicketsCompletosSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        echo "\n🎫 Criando tickets realistas com comentários e histórico...\n\n";

        // LIMPAR DADOS EXISTENTES (permitir múltiplas execuções)
        echo "🧹 Limpando dados existentes...\n";

        // Desabilitar foreign key checks temporariamente
        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->table('historico_tickets')->truncate();
        $db->table('comentarios')->truncate();
        $db->table('tickets')->truncate();

        // Limpar agentes criados anteriormente (exceto admin e clientes)
        $db->table('usuarios')->where('funcao', 'agente')->delete();

        // Reabilitar foreign key checks
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        echo "  ✓ Dados limpos com sucesso\n\n";

        // CRIAR AGENTES ADICIONAIS
        echo "👥 Criando agentes...\n";

        $agentesNovos = [
            [
                'nome' => 'João Silva',
                'email' => 'joao@tickets.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'funcao' => 'agente',
                'ativo' => 1,
                'criado_em' => date('Y-m-d H:i:s'),
            ],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria@tickets.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'funcao' => 'agente',
                'ativo' => 1,
                'criado_em' => date('Y-m-d H:i:s'),
            ],
            [
                'nome' => 'Pedro Costa',
                'email' => 'pedro@tickets.com',
                'senha' => password_hash('123456', PASSWORD_DEFAULT),
                'funcao' => 'agente',
                'ativo' => 1,
                'criado_em' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($agentesNovos as $agente) {
            $db->table('usuarios')->insert($agente);
            echo "  ✓ Agente criado: {$agente['nome']} ({$agente['email']})\n";
        }
        echo "\n";

        // Buscar IDs dos usuários
        $usuarios = $db->table('usuarios')->get()->getResultArray();
        $admin = array_values(array_filter($usuarios, fn($u) => $u['funcao'] === 'admin'))[0] ?? null;
        $agentes = array_values(array_filter($usuarios, fn($u) => $u['funcao'] === 'agente'));
        $clientes = array_values(array_filter($usuarios, fn($u) => $u['funcao'] === 'cliente'));

        // Buscar categorias e prioridades
        $categorias = $db->table('categorias')->get()->getResultArray();
        $prioridades = $db->table('prioridades')->get()->getResultArray();

        // Status possíveis (conforme ENUM do banco de dados)
        $statuses = ['novo', 'aberto', 'em_progresso', 'pendente', 'resolvido', 'fechado'];

        // Array de tickets realistas
        $ticketsData = [
            // CRÍTICOS - Problemas graves
            [
                'titulo' => 'Sistema de pagamento não está processando cartões de crédito',
                'descricao' => "Desde as 14h de hoje, o sistema de pagamento parou de processar transações com cartão de crédito. Os clientes recebem erro 500 ao tentar finalizar compras.\n\nImpacto: CRÍTICO - vendas paradas\nTentativas: Gateway reiniciado 3x sem sucesso\nLogs: Anexados no sistema",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Crítica',
                'status' => 'em_progresso',
                'cliente' => 0,
                'agente' => 0,
                'dias_atras' => 0,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 0, 'horas' => 2, 'texto' => 'Identificado problema na integração com a operadora. Equipe técnica já acionada.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 0, 'horas' => 1, 'texto' => 'Operadora confirmou instabilidade no gateway. Previsão de normalização: 2h', 'interno' => true],
                    ['usuario' => 'cliente', 'dias' => 0, 'horas' => 0.5, 'texto' => 'Precisamos de uma solução urgente! Já perdemos R$ 50mil em vendas.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Banco de dados principal apresentando lentidão extrema',
                'descricao' => "Queries que levavam 100ms agora estão demorando 30+ segundos. Sistema praticamente inutilizável.\n\nServidor: db-prod-01\nCPU: 98%\nRAM: 16GB/16GB em uso\nConexões ativas: 487/500",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Crítica',
                'status' => 'resolvido',
                'cliente' => 1,
                'agente' => 1,
                'dias_atras' => 2,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 0, 'texto' => 'Analisando logs do banco. Identificadas queries N+1 em produtos.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 12, 'texto' => 'Otimizadas 15 queries e adicionados índices. Performance normalizada.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 1, 'horas' => 10, 'texto' => 'Confirmado! Sistema voltou ao normal. Obrigado pela agilidade!', 'interno' => false],
                ]
            ],

            // ALTA PRIORIDADE - Urgente mas não crítico
            [
                'titulo' => 'Relatórios financeiros não gerando PDF - erro de memória',
                'descricao' => "Ao tentar exportar relatórios mensais em PDF, sistema retorna erro de memória insuficiente.\n\nErro: PHP Fatal error: Allowed memory size of 256MB exhausted\nRelatório: 15.000 transações do mês\nFormato: PDF com gráficos",
                'categoria' => 'Financeiro',
                'prioridade' => 'Alta',
                'status' => 'pendente',
                'cliente' => 2,
                'agente' => 0,
                'dias_atras' => 1,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 0, 'texto' => 'Aumentado limite de memória para 512MB. Pode testar novamente?', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 0, 'horas' => 12, 'texto' => 'Cliente não respondeu. Aguardando retorno para fechar ticket.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Integração com API de email marketing retornando 401',
                'descricao' => "Desde a atualização de ontem, a integração com a API da plataforma de email marketing está retornando erro 401 Unauthorized.\n\nAPI: Mailchimp\nEndpoint: /lists/subscribers\nToken: Verificado e válido até 2026",
                'categoria' => 'Comercial',
                'prioridade' => 'Alta',
                'status' => 'em_progresso',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 3,
                'comentarios' => [
                    ['usuario' => 'cliente', 'dias' => 3, 'horas' => 0, 'texto' => 'Tentei reautenticar mas continua dando erro.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 18, 'texto' => 'Mailchimp alterou versão da API. Atualizando SDK para v3.0', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 12, 'texto' => 'Código atualizado em homologação. Testando integração.', 'interno' => true],
                ]
            ],

            // NORMAL - Solicitações comuns
            [
                'titulo' => 'Adicionar novo usuário ao sistema com perfil de gerente',
                'descricao' => "Preciso cadastrar a nova gerente comercial no sistema.\n\nNome: Maria Silva\nEmail: maria.silva@empresa.com\nPerfil: Gerente (acesso a relatórios e dashboard)\nDepartamento: Comercial",
                'categoria' => 'Recursos Humanos',
                'prioridade' => 'Normal',
                'status' => 'fechado',
                'cliente' => 1,
                'agente' => 1,
                'dias_atras' => 5,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 5, 'horas' => 0, 'texto' => 'Usuário criado com sucesso! Credenciais enviadas por email.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 4, 'horas' => 20, 'texto' => 'Perfeito! Maria já conseguiu acessar. Obrigado!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Dúvida sobre como gerar relatório de vendas por região',
                'descricao' => "Gostaria de saber como faço para gerar o relatório de vendas filtrado por região.\n\nJá tentei:\n- Menu Relatórios > Vendas (só mostra geral)\n- Dashboard (só mostra totais)\n\nPreciso filtrar por: Sudeste, Sul, etc.",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Normal',
                'status' => 'resolvido',
                'cliente' => 2,
                'agente' => 0,
                'dias_atras' => 4,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 2, 'texto' => 'No menu Relatórios > Vendas, clique em "Filtros Avançados" (ícone de funil no canto superior direito). Lá você encontra a opção "Região".', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 4, 'horas' => 1, 'texto' => 'Achei! Estava escondido mesmo haha. Funcionou perfeitamente!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Solicitação de aumento de limite de armazenamento',
                'descricao' => "Nossa conta está com 92% do armazenamento utilizado (46GB de 50GB).\n\nSolicitação: Upgrade para 100GB\nPrazo: Próximos 15 dias\nOrçamento: Aprovado até R$ 500/mês adicional",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Normal',
                'status' => 'em_progresso',
                'cliente' => 0,
                'agente' => 1,
                'dias_atras' => 7,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 7, 'horas' => 0, 'texto' => 'Solicitação encaminhada ao financeiro para aprovação.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 5, 'horas' => 0, 'texto' => 'Upgrade aprovado. Processando aumento para 100GB.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 0, 'texto' => 'Aguardando equipe de infraestrutura alocar recursos.', 'interno' => true],
                ]
            ],

            // BAIXA - Melhorias e sugestões
            [
                'titulo' => 'Sugestão: Adicionar tema escuro no sistema',
                'descricao' => "Seria muito útil ter a opção de tema escuro (dark mode) no sistema, principalmente para quem trabalha à noite.\n\nBenefícios:\n- Menos cansaço visual\n- Economia de bateria em notebooks\n- Padrão em apps modernos",
                'categoria' => 'Geral',
                'prioridade' => 'Baixa',
                'status' => 'novo',
                'cliente' => 1,
                'agente' => null,
                'dias_atras' => 10,
                'comentarios' => []
            ],
            [
                'titulo' => 'Melhoria: Permitir anexar múltiplos arquivos de uma vez',
                'descricao' => "Atualmente só consigo anexar um arquivo por vez nos tickets. Seria mais prático poder selecionar múltiplos arquivos.\n\nCenário comum: Preciso anexar 5 screenshots, tenho que fazer um por um.",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Baixa',
                'status' => 'novo',
                'cliente' => 2,
                'agente' => null,
                'dias_atras' => 8,
                'comentarios' => []
            ],

            // Tickets variados - simulando situações reais
            [
                'titulo' => 'Não consigo redefinir minha senha - link expirado',
                'descricao' => "Tentei redefinir minha senha mas o link do email sempre diz que expirou, mesmo clicando imediatamente após receber.\n\nEmail: joao@empresa.com\nNavegador: Chrome 120\nJá tentei: Limpar cache, outro navegador",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Normal',
                'status' => 'resolvido',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 6,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 6, 'horas' => 1, 'texto' => 'Identificado bug no timezone do servidor. Corrigido!', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 6, 'horas' => 0.5, 'texto' => 'Funcionou! Consegui redefinir. Valeu!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Fatura de outubro não foi enviada por email',
                'descricao' => "Não recebi a fatura referente ao mês de outubro/2024.\n\nConta: #45789\nVencimento: 10/11/2024\nJá verifiquei spam e lixeira.",
                'categoria' => 'Financeiro',
                'prioridade' => 'Normal',
                'status' => 'fechado',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 15,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 15, 'horas' => 2, 'texto' => 'Fatura reenviada para seu email. Favor confirmar recebimento.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 15, 'horas' => 1, 'texto' => 'Recebi! Pagamento efetuado. Obrigado!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Exportação de dados para Excel gerando caracteres estranhos',
                'descricao' => "Ao exportar relatórios para Excel, os acentos estão aparecendo como caracteres estranhos.\n\nExemplo: 'São Paulo' vira 'S├úo Paulo'\nFormato: CSV\nExcel: 2021",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Normal',
                'status' => 'em_progresso',
                'cliente' => 2,
                'agente' => 1,
                'dias_atras' => 3,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 3, 'horas' => 0, 'texto' => 'Problema de encoding UTF-8. Ajustando exportação.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 0, 'texto' => 'Correção aplicada. Testando em diferentes versões do Excel.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Dashboard não carrega no Safari (Mac)',
                'descricao' => "O dashboard fica em loading infinito quando acesso pelo Safari no Mac.\n\nSafari: Versão 17.1\nmacOS: Sonoma 14.1\nOutros navegadores: Chrome funciona normal",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Alta',
                'status' => 'pendente',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 2,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 4, 'texto' => 'Provável incompatibilidade com JavaScript moderno. Pode tentar limpar cache do Safari?', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 2, 'horas' => 2, 'texto' => 'Limpei o cache mas continua travado.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 0, 'texto' => 'Correção aplicada (Promise.allSettled substituído por alternativa). Por favor teste novamente.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Solicitar acesso ao módulo de BI/Analytics',
                'descricao' => "Gostaria de ter acesso ao módulo de Business Intelligence para criar dashboards personalizados.\n\nUsuário: carlos.oliveira@empresa.com\nJustificativa: Gerente de vendas, precisa analisar métricas",
                'categoria' => 'Recursos Humanos',
                'prioridade' => 'Baixa',
                'status' => 'novo',
                'cliente' => 1,
                'agente' => null,
                'dias_atras' => 1,
                'comentarios' => []
            ],
            [
                'titulo' => 'Erro 500 ao tentar editar produtos em lote',
                'descricao' => "Quando seleciono mais de 50 produtos e tento editar em lote, o sistema retorna erro 500.\n\nQuantidade: 127 produtos\nAção: Alterar categoria\nNavegador: Firefox 119",
                'categoria' => 'Comercial',
                'prioridade' => 'Alta',
                'status' => 'em_progresso',
                'cliente' => 2,
                'agente' => 0,
                'dias_atras' => 1,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 3, 'texto' => 'Timeout no servidor. Aumentando limite para 300s e otimizando query.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 1, 'texto' => 'Implementado processamento em lotes de 50 itens. Testando...', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Treinamento para novos funcionários - módulo de vendas',
                'descricao' => "Contratamos 5 novos vendedores e precisamos de treinamento sobre o sistema.\n\nData preferencial: Próxima semana\nModalidade: Presencial ou online\nDuração: 2-3 horas",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Normal',
                'status' => 'pendente',
                'cliente' => 0,
                'agente' => 1,
                'dias_atras' => 4,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 0, 'texto' => 'Temos disponibilidade nos seguintes dias:\n- Terça 14h\n- Quinta 10h\n- Sexta 15h\n\nQual prefere?', 'interno' => false],
                ]
            ],

            // NOVOS TICKETS ADICIONAIS (20 tickets)
            [
                'titulo' => 'Sistema de notificações push não enviando alertas mobile',
                'descricao' => "Desde ontem os usuários do app mobile não estão recebendo notificações push.\n\nPlataforma: Android e iOS\nTeste realizado: Envio manual também falha\nFirebase: Credenciais verificadas e válidas\nÚltimo envio bem-sucedido: 17/11/2025 23:45",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Crítica',
                'status' => 'em_progresso',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 0,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 0, 'horas' => 1, 'texto' => 'Identificado erro no serviço FCM. Aguardando resposta do Google Cloud Support.', 'interno' => true],
                    ['usuario' => 'cliente', 'dias' => 0, 'horas' => 0.5, 'texto' => 'Urgente! Nossos clientes não estão sendo avisados de pedidos aprovados.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Backup automático falhou nos últimos 3 dias',
                'descricao' => "Rotina de backup diário está falhando silenciosamente.\n\nErro no log: Connection timeout to storage server\nDestino: AWS S3\nTamanho BD: 45GB\nÚltimo backup OK: 14/11/2025",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Crítica',
                'status' => 'em_progresso',
                'cliente' => 2,
                'agente' => 2,
                'dias_atras' => 1,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 2, 'texto' => 'Credenciais S3 expiraram. Gerando novas chaves de acesso.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 1, 'horas' => 0.5, 'texto' => 'Backup manual executado com sucesso. Rotina automática será testada hoje à noite.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Certificado SSL expira em 5 dias',
                'descricao' => "Recebemos alerta de que o certificado SSL do domínio principal vence em breve.\n\nDomínio: www.empresa.com.br\nExpira em: 23/11/2025\nEmissor: Let's Encrypt\nRenovação automática: Configurada mas não executou",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Alta',
                'status' => 'aberto',
                'cliente' => 0,
                'agente' => 1,
                'dias_atras' => 2,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 0, 'texto' => 'Verificando por que o Certbot não renovou automaticamente.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Relatório de comissões apresentando valores incorretos',
                'descricao' => "Vendedores reclamando que comissões no relatório não batem com vendas realizadas.\n\nExemplo: João vendeu R$ 50.000, comissão deveria ser R$ 2.500 (5%), mas aparece R$ 1.200\nPeríodo: Novembro/2025\nAfetados: 8 de 12 vendedores",
                'categoria' => 'Financeiro',
                'prioridade' => 'Alta',
                'status' => 'em_progresso',
                'cliente' => 1,
                'agente' => 2,
                'dias_atras' => 3,
                'comentarios' => [
                    ['usuario' => 'cliente', 'dias' => 3, 'horas' => 0, 'texto' => 'Equipe de vendas pressionando muito. Precisamos resolver isso logo!', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 18, 'texto' => 'Encontrado bug: vendas canceladas não estão sendo descontadas. Corrigindo...', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 12, 'texto' => 'Correção aplicada. Recalculando relatórios de novembro.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Integração com transportadora retornando prazo errado',
                'descricao' => "Cálculo de frete está mostrando prazos muito maiores que o real.\n\nExemplo: SP para RJ mostrando 15 dias (normal seria 2-3 dias)\nTransportadora: Correios\nModalidade: PAC e SEDEX\nWebservice: Versão antiga (precisa migrar para v2?)",
                'categoria' => 'Comercial',
                'prioridade' => 'Alta',
                'status' => 'pendente',
                'cliente' => 2,
                'agente' => 0,
                'dias_atras' => 5,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 5, 'horas' => 4, 'texto' => 'Correios descontinuou a API antiga. Migração para nova API necessária.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 5, 'horas' => 2, 'texto' => 'Quanto tempo leva essa migração? Clientes reclamando muito.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 20, 'texto' => 'Estimativa: 3 dias úteis. Já iniciamos o desenvolvimento.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 0, 'texto' => 'Cliente não respondeu se aprova o prazo. Marcando como pendente.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Implementar autenticação de dois fatores (2FA)',
                'descricao' => "Por questões de segurança, gostaríamos de implementar 2FA para todos usuários.\n\nPreferência: Google Authenticator / Authy\nObrigatoriedade: Opcional inicialmente, obrigatório após 60 dias\nUsuários impactados: ~150 contas",
                'categoria' => 'Geral',
                'prioridade' => 'Normal',
                'status' => 'novo',
                'cliente' => 0,
                'agente' => null,
                'dias_atras' => 8,
                'comentarios' => []
            ],
            [
                'titulo' => 'Migração de servidor de email para Office 365',
                'descricao' => "Planejamos migrar emails corporativos do servidor atual para Microsoft 365.\n\nCaixas: 45 contas\nEspaço total: ~120GB\nDomínio: @empresa.com.br\nPrazo desejado: 30 dias\nBackup: Necessário manter emails antigos",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Normal',
                'status' => 'novo',
                'cliente' => 1,
                'agente' => null,
                'dias_atras' => 12,
                'comentarios' => []
            ],
            [
                'titulo' => 'Adicionar campo CPF/CNPJ no cadastro de clientes',
                'descricao' => "Precisamos coletar CPF ou CNPJ obrigatoriamente no cadastro.\n\nRequisitos:\n- Validação de formato\n- Máscara automática (CPF: 000.000.000-00, CNPJ: 00.000.000/0000-00)\n- Campo obrigatório para novos cadastros\n- Permitir edição para cadastros existentes",
                'categoria' => 'Comercial',
                'prioridade' => 'Normal',
                'status' => 'aberto',
                'cliente' => 2,
                'agente' => 1,
                'dias_atras' => 6,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 6, 'horas' => 0, 'texto' => 'Desenvolvimento iniciado. Previsão de entrega: 5 dias úteis.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 3, 'horas' => 0, 'texto' => 'Validação e máscaras prontas. Testando em homologação.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Gráficos do dashboard não carregam no Internet Explorer',
                'descricao' => "Alguns usuários ainda usam IE11 e os gráficos não aparecem.\n\nErro console: Promise is not defined\nBiblioteca: Chart.js 3.x\nSolução possível: Polyfill ou downgrade biblioteca?",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Baixa',
                'status' => 'aberto',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 9,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 9, 'horas' => 0, 'texto' => 'IE11 está descontinuado pela Microsoft. Recomendamos migrar para Edge ou Chrome.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 8, 'horas' => 20, 'texto' => 'Entendo, mas temos alguns PCs antigos ainda. Não tem como adicionar suporte?', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 8, 'horas' => 18, 'texto' => 'Vou adicionar polyfill para compatibilidade temporária.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Criar relatório de produtos mais vendidos por categoria',
                'descricao' => "Precisamos de um novo relatório mostrando top 10 produtos por categoria.\n\nCampos necessários:\n- Nome do produto\n- Categoria\n- Quantidade vendida\n- Receita total\n- Período selecionável (mensal/trimestral/anual)\n- Exportação para Excel",
                'categoria' => 'Comercial',
                'prioridade' => 'Normal',
                'status' => 'em_progresso',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 7,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 7, 'horas' => 0, 'texto' => 'Requisitos aprovados. Desenvolvimento em andamento.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 0, 'texto' => 'Relatório base pronto. Implementando filtros e exportação Excel.', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Email de confirmação de pedido indo para spam',
                'descricao' => "Clientes reclamam que não recebem confirmação de pedidos. Emails estão caindo em spam.\n\nServidor SMTP: SendGrid\nDomínio: noreply@empresa.com.br\nSPF/DKIM: Configurados (verificar se ainda válidos?)\nTaxa rejeição: ~40%",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Alta',
                'status' => 'em_progresso',
                'cliente' => 2,
                'agente' => 1,
                'dias_atras' => 4,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 2, 'texto' => 'Registro DKIM estava quebrado. Corrigido no DNS.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 0, 'texto' => 'Aguardar 24-48h para propagação DNS. Monitorando taxa de entrega.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 3, 'horas' => 18, 'texto' => 'Clientes já começaram a receber! Taxa melhorou bastante.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Sistema travando ao processar planilha com 10.000 linhas',
                'descricao' => "Importação de produtos via Excel trava o navegador.\n\nArquivo: 10.000 produtos (9MB)\nNavegador: Chrome congela, Firefox idem\nTempo tentativa: 5+ minutos até travar\nProcessamento: Aparentemente acontece tudo no frontend",
                'categoria' => 'Suporte Técnico',
                'prioridade' => 'Alta',
                'status' => 'resolvido',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 10,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 10, 'horas' => 4, 'texto' => 'Problema identificado: processamento síncrono. Refatorando para processar em background.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 9, 'horas' => 20, 'texto' => 'Implementado: Upload + fila de processamento assíncrono + notificação ao concluir.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 9, 'horas' => 18, 'texto' => 'Testei com 15.000 produtos! Funcionou perfeitamente, recebi notificação em 2 minutos.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Solicitar acesso VPN para trabalho remoto',
                'descricao' => "Novo funcionário precisa acessar sistemas internos remotamente.\n\nNome: Ana Rodrigues\nCargo: Analista Financeiro\nEquipamento: Notebook corporativo (já configurado)\nSistemas: ERP + BI + Compartilhamento arquivos",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Normal',
                'status' => 'fechado',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 14,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 14, 'horas' => 1, 'texto' => 'Credenciais VPN criadas. Enviado email com instruções de configuração.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 14, 'horas' => 0.5, 'texto' => 'Ana conseguiu conectar! Está acessando tudo normalmente. Obrigado!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Atualização de sistema operacional nos servidores',
                'descricao' => "Servidores rodando Ubuntu 18.04 LTS que sai de suporte em breve.\n\nServidores: 5 (web-01, web-02, db-01, cache-01, jobs-01)\nVersão atual: Ubuntu 18.04 LTS\nVersão alvo: Ubuntu 22.04 LTS\nJanela manutenção: Sábado 3h-7h\nBackup: Obrigatório antes",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Alta',
                'status' => 'pendente',
                'cliente' => 2,
                'agente' => 1,
                'dias_atras' => 11,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 11, 'horas' => 0, 'texto' => 'Plano de migração elaborado. Preciso aprovação para agendar janela de manutenção.', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 10, 'horas' => 20, 'texto' => 'Preciso verificar com diretoria. Qual impacto para usuários?', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 10, 'horas' => 18, 'texto' => 'Sistema ficará offline por ~4h (madrugada sábado). Usuários não serão impactados.', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Remover usuário inativo que saiu da empresa',
                'descricao' => "Funcionário desligado, favor desativar acesso.\n\nNome: Roberto Alves\nEmail: roberto.alves@empresa.com\nData desligamento: 10/11/2025\nAções: Desativar login + transferir tickets abertos para supervisor",
                'categoria' => 'Recursos Humanos',
                'prioridade' => 'Normal',
                'status' => 'fechado',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 13,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 13, 'horas' => 0, 'texto' => 'Usuário desativado. 3 tickets transferidos para Maria Silva (supervisora).', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 12, 'horas' => 22, 'texto' => 'Perfeito, obrigado!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Bug: Desconto de cupom não sendo aplicado no checkout',
                'descricao' => "Clientes inserem cupom de desconto válido mas o valor não é abatido.\n\nCupom teste: DESCONTO20 (20% off)\nCarrinho: R$ 500,00\nDesconto esperado: R$ 100,00\nDesconto aplicado: R$ 0,00\nErro console: Nenhum",
                'categoria' => 'Comercial',
                'prioridade' => 'Crítica',
                'status' => 'resolvido',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 5,
                'comentarios' => [
                    ['usuario' => 'cliente', 'dias' => 5, 'horas' => 0, 'texto' => 'URGENTE! Campanha de Black Friday começou e cupons não funcionam!', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 5, 'horas' => 0.5, 'texto' => 'Priorizando! Investigando código de aplicação de cupons.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 4, 'horas' => 22, 'texto' => 'Bug encontrado: validação de data estava com timezone incorreto. CORRIGIDO!', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 4, 'horas' => 21, 'texto' => 'Testado! Funcionando perfeitamente. Salvaram nossa campanha!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Configurar monitoramento de uptime e alertas',
                'descricao' => "Gostaríamos de receber alertas automáticos se o sistema cair.\n\nMonitorar:\n- Site principal (HTTP)\n- API backend (HTTP)\n- Banco de dados (MySQL)\n- Servidor email (SMTP)\n\nAlertas via: Email + SMS (números urgência)\nIntervalo verificação: 5 minutos",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Normal',
                'status' => 'em_progresso',
                'cliente' => 2,
                'agente' => 1,
                'dias_atras' => 8,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 8, 'horas' => 0, 'texto' => 'Sugestão: Usar UptimeRobot (gratuito até 50 monitores). Aprovar?', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 7, 'horas' => 20, 'texto' => 'Aprovado! Pode configurar.', 'interno' => false],
                    ['usuario' => 'agente', 'dias' => 6, 'horas' => 0, 'texto' => 'Monitores configurados. Testando alertas...', 'interno' => true],
                ]
            ],
            [
                'titulo' => 'Dúvida sobre política de backup e recuperação',
                'descricao' => "Gostaria de entender nossa política atual de backups.\n\nDúvidas:\n1. Frequência dos backups?\n2. Onde ficam armazenados?\n3. Tempo de retenção?\n4. Já foi testada recuperação?\n5. Quanto tempo leva restaurar tudo?",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Baixa',
                'status' => 'fechado',
                'cliente' => 0,
                'agente' => 2,
                'dias_atras' => 20,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 20, 'horas' => 2, 'texto' => 'Política atual:\n1. Diários (todo dia 2h)\n2. AWS S3 (região us-east-1)\n3. 30 dias\n4. Sim, último teste: 01/11/2025\n5. ~2h para restauração completa', 'interno' => false],
                    ['usuario' => 'cliente', 'dias' => 20, 'horas' => 1, 'texto' => 'Perfeito! Esclareceu tudo. Obrigado!', 'interno' => false],
                ]
            ],
            [
                'titulo' => 'Página de checkout lenta em horário de pico',
                'descricao' => "Entre 20h-22h a finalização de compras fica muito lenta.\n\nTempo normal: 2-3 segundos\nTempo pico: 15-30 segundos\nUsuários simultâneos pico: ~500\nInfraestrutura: 2 servidores web + 1 banco\nCache: Redis configurado",
                'categoria' => 'TI / Infraestrutura',
                'prioridade' => 'Alta',
                'status' => 'aberto',
                'cliente' => 1,
                'agente' => 0,
                'dias_atras' => 3,
                'comentarios' => [
                    ['usuario' => 'agente', 'dias' => 3, 'horas' => 0, 'texto' => 'Analisando logs e métricas de performance. Provável gargalo no banco.', 'interno' => true],
                    ['usuario' => 'agente', 'dias' => 2, 'horas' => 18, 'texto' => 'Identificadas queries lentas. Otimizando índices e adicionando cache para cálculos de frete.', 'interno' => false],
                ]
            ],
        ];

        // Processar e inserir tickets
        $ticketCount = 0;
        foreach ($ticketsData as $ticketData) {
            // Encontrar categoria
            $categoria = array_values(array_filter($categorias,
                fn($c) => $c['nome'] === $ticketData['categoria']
            ))[0] ?? $categorias[0];

            // Encontrar prioridade
            $prioridade = array_values(array_filter($prioridades,
                fn($p) => $p['nome'] === $ticketData['prioridade']
            ))[0] ?? $prioridades[1];

            // Selecionar cliente e agente
            $cliente = $clientes[$ticketData['cliente']] ?? $clientes[0];
            $agente = $ticketData['agente'] !== null ? $agentes[$ticketData['agente']] : null;

            // Data de criação (X dias atrás)
            $criadoEm = Time::now()->subDays($ticketData['dias_atras']);

            // Inserir ticket
            $ticket = [
                'titulo' => $ticketData['titulo'],
                'descricao' => $ticketData['descricao'],
                'usuario_id' => $cliente['id'],
                'responsavel_id' => $agente['id'] ?? null,
                'categoria_id' => $categoria['id'],
                'prioridade_id' => $prioridade['id'],
                'status' => $ticketData['status'],
                'criado_em' => $criadoEm->toDateTimeString(),
                'atualizado_em' => Time::now()->toDateTimeString(),
            ];

            $db->table('tickets')->insert($ticket);
            $ticketId = $db->insertID();
            $ticketCount++;

            echo "  ✓ Ticket #{$ticketId}: {$ticketData['titulo']}\n";

            // Inserir comentários
            foreach ($ticketData['comentarios'] as $comentarioData) {
                $usuarioComentario = null;
                if ($comentarioData['usuario'] === 'cliente') {
                    $usuarioComentario = $cliente;
                } elseif ($comentarioData['usuario'] === 'agente') {
                    $usuarioComentario = $agente;
                }

                if ($usuarioComentario) {
                    $comentadoEm = Time::now()
                        ->subDays($comentarioData['dias'])
                        ->subHours($comentarioData['horas']);

                    $comentario = [
                        'ticket_id' => $ticketId,
                        'usuario_id' => $usuarioComentario['id'],
                        'conteudo' => $comentarioData['texto'],
                        'eh_interno' => $comentarioData['interno'] ? 1 : 0,
                        'criado_em' => $comentadoEm->toDateTimeString(),
                    ];

                    $db->table('comentarios')->insert($comentario);
                }
            }

            // Inserir histórico de mudanças
            if ($ticketData['status'] !== 'novo') {
                $statusTransitions = [
                    'novo' => ['em_andamento', 'aguardando_cliente'],
                    'em_andamento' => ['aguardando_cliente', 'resolvido'],
                    'aguardando_cliente' => ['em_andamento', 'resolvido'],
                    'resolvido' => ['fechado'],
                ];

                $historico = [
                    [
                        'ticket_id' => $ticketId,
                        'usuario_id' => $agente['id'] ?? $admin['id'],
                        'acao' => 'alteracao_status',
                        'campo' => 'status',
                        'valor_antigo' => 'novo',
                        'valor_novo' => 'em_andamento',
                        'criado_em' => $criadoEm->addHours(1)->toDateTimeString(),
                    ]
                ];

                if (in_array($ticketData['status'], ['resolvido', 'fechado'])) {
                    $historico[] = [
                        'ticket_id' => $ticketId,
                        'usuario_id' => $agente['id'] ?? $admin['id'],
                        'acao' => 'alteracao_status',
                        'campo' => 'status',
                        'valor_antigo' => 'em_progresso',
                        'valor_novo' => 'resolvido',
                        'criado_em' => $criadoEm->addDays(1)->toDateTimeString(),
                    ];
                }

                if ($ticketData['status'] === 'fechado') {
                    $historico[] = [
                        'ticket_id' => $ticketId,
                        'usuario_id' => $cliente['id'],
                        'acao' => 'alteracao_status',
                        'campo' => 'status',
                        'valor_antigo' => 'resolvido',
                        'valor_novo' => 'fechado',
                        'criado_em' => $criadoEm->addDays(2)->toDateTimeString(),
                    ];
                }

                foreach ($historico as $hist) {
                    $db->table('historico_tickets')->insert($hist);
                }
            }
        }

        echo "\n✅ SEEDER CONCLUÍDO COM SUCESSO!\n\n";

        echo "👥 Agentes criados: " . count($agentes) . "\n";
        foreach ($agentes as $agente) {
            echo "  - {$agente['nome']} ({$agente['email']})\n";
        }

        echo "\n🎫 Total de tickets: {$ticketCount}\n\n";
        echo "📊 Distribuição por status:\n";

        $statusCount = $db->table('tickets')
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        foreach ($statusCount as $stat) {
            $emoji = [
                'novo' => '🆕',
                'aberto' => '📂',
                'em_progresso' => '🔄',
                'pendente' => '⏳',
                'resolvido' => '✅',
                'fechado' => '🔒'
            ];
            $status = $stat['status'] ?? '';
            if (isset($emoji[$status])) {
                echo "  {$emoji[$status]} " . ucfirst(str_replace('_', ' ', $status)) . ": {$stat['total']}\n";
            }
        }

        // Estatísticas adicionais
        $prioridadeCount = $db->table('tickets')
            ->select('p.nome as prioridade, COUNT(*) as total')
            ->join('prioridades p', 'p.id = tickets.prioridade_id')
            ->groupBy('p.nome')
            ->get()
            ->getResultArray();

        echo "\n📈 Distribuição por prioridade:\n";
        foreach ($prioridadeCount as $prio) {
            echo "  - {$prio['prioridade']}: {$prio['total']}\n";
        }

        $comentariosTotal = $db->table('comentarios')->countAllResults();
        $historicoTotal = $db->table('historico_tickets')->countAllResults();

        echo "\n💬 Comentários criados: {$comentariosTotal}\n";
        echo "📋 Registros de histórico: {$historicoTotal}\n";
        echo "\n✨ Banco de dados populado com dados realistas!\n";
    }
}
