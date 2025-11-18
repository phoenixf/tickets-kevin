<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'titulo',
        'descricao',
        'usuario_id',
        'responsavel_id',
        'categoria_id',
        'prioridade_id',
        'status',
        'data_vencimento',
        'primeira_resposta_em',
        'resolvido_em'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'titulo' => [
            'rules' => 'required|min_length[10]|max_length[255]',
            'errors' => [
                'required' => 'O título é obrigatório',
                'min_length' => 'O título deve ter no mínimo 10 caracteres',
                'max_length' => 'O título deve ter no máximo 255 caracteres'
            ]
        ],
        'descricao' => [
            'rules' => 'required|min_length[20]',
            'errors' => [
                'required' => 'A descrição é obrigatória',
                'min_length' => 'A descrição deve ter no mínimo 20 caracteres'
            ]
        ],
        'usuario_id' => [
            'rules' => 'required|integer|is_not_unique[usuarios.id]',
            'errors' => [
                'required' => 'O usuário é obrigatório',
                'integer' => 'ID do usuário inválido',
                'is_not_unique' => 'Usuário não encontrado'
            ]
        ],
        'prioridade_id' => [
            'rules' => 'required|integer|is_not_unique[prioridades.id]',
            'errors' => [
                'required' => 'A prioridade é obrigatória',
                'integer' => 'ID da prioridade inválido',
                'is_not_unique' => 'Prioridade não encontrada'
            ]
        ],
        'status' => [
            'rules' => 'required|in_list[novo,aberto,em_progresso,pendente,resolvido,fechado]',
            'errors' => [
                'required' => 'O status é obrigatório',
                'in_list' => 'Status inválido'
            ]
        ]
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['atualizarResolvidoEm'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Atualiza resolvido_em quando status muda para "resolvido" ou "fechado"
     */
    protected function atualizarResolvidoEm(array $data)
    {
        // Se não tiver ID ou status, retornar
        if (!isset($data['id']) || !isset($data['data']['status'])) {
            return $data;
        }

        $ticketId = is_array($data['id']) ? $data['id'][0] : $data['id'];
        $novoStatus = $data['data']['status'];

        // Buscar status antigo
        $db = \Config\Database::connect();
        $ticket = $db->table('tickets')->where('id', $ticketId)->get()->getRowArray();

        if (!$ticket) {
            return $data;
        }

        $statusAntigo = $ticket['status'];

        // Se mudou para resolvido/fechado e não tinha resolvido_em, atualizar
        if (in_array($novoStatus, ['resolvido', 'fechado']) && empty($ticket['resolvido_em'])) {
            $data['data']['resolvido_em'] = date('Y-m-d H:i:s');
        }

        // Se estava resolvido/fechado e mudou para outro status, limpar resolvido_em
        if (in_array($statusAntigo, ['resolvido', 'fechado']) && !in_array($novoStatus, ['resolvido', 'fechado'])) {
            $data['data']['resolvido_em'] = null;
        }

        return $data;
    }

    /**
     * Buscar ticket com todos os relacionamentos
     */
    public function getTicketCompleto($id)
    {
        return $this->select('
                tickets.*,
                usuarios.nome as usuario_nome,
                usuarios.email as usuario_email,
                responsavel.nome as responsavel_nome,
                responsavel.email as responsavel_email,
                categorias.nome as categoria_nome,
                categorias.cor as categoria_cor,
                prioridades.nome as prioridade_nome,
                prioridades.cor as prioridade_cor,
                prioridades.nivel as prioridade_nivel
            ')
            ->join('usuarios', 'usuarios.id = tickets.usuario_id', 'left')
            ->join('usuarios as responsavel', 'responsavel.id = tickets.responsavel_id', 'left')
            ->join('categorias', 'categorias.id = tickets.categoria_id', 'left')
            ->join('prioridades', 'prioridades.id = tickets.prioridade_id', 'left')
            ->find($id);
    }

    /**
     * Listar tickets com paginação e filtros
     */
    public function listarTickets($filtros = [], $limite = 20, $offset = 0)
    {
        $builder = $this->select('
                tickets.*,
                usuarios.nome as usuario_nome,
                responsavel.nome as responsavel_nome,
                categorias.nome as categoria_nome,
                prioridades.nome as prioridade_nome,
                prioridades.cor as prioridade_cor,
                COALESCE(COUNT(comentarios.id), 0) as total_mensagens,
                GREATEST(
                    COALESCE(tickets.atualizado_em, tickets.criado_em),
                    COALESCE(MAX(comentarios.criado_em), tickets.criado_em)
                ) as ultima_atividade
            ')
            ->join('usuarios', 'usuarios.id = tickets.usuario_id', 'left')
            ->join('usuarios as responsavel', 'responsavel.id = tickets.responsavel_id', 'left')
            ->join('categorias', 'categorias.id = tickets.categoria_id', 'left')
            ->join('prioridades', 'prioridades.id = tickets.prioridade_id', 'left')
            ->join('comentarios', 'comentarios.ticket_id = tickets.id', 'left');

        // Aplicar filtros
        if (!empty($filtros['status'])) {
            $builder->where('tickets.status', $filtros['status']);
        }

        if (!empty($filtros['prioridade_id'])) {
            $builder->where('tickets.prioridade_id', $filtros['prioridade_id']);
        }

        if (!empty($filtros['categoria_id'])) {
            $builder->where('tickets.categoria_id', $filtros['categoria_id']);
        }

        if (!empty($filtros['usuario_id'])) {
            $builder->where('tickets.usuario_id', $filtros['usuario_id']);
        }

        if (!empty($filtros['responsavel_id'])) {
            $builder->where('tickets.responsavel_id', $filtros['responsavel_id']);
        }

        if (!empty($filtros['busca'])) {
            $builder->groupStart()
                ->like('tickets.titulo', $filtros['busca'])
                ->orLike('tickets.descricao', $filtros['busca'])
                ->groupEnd();
        }

        return $builder->groupBy('tickets.id')
            ->orderBy('tickets.criado_em', 'DESC')
            ->limit($limite, $offset)
            ->findAll();
    }

    /**
     * Contar tickets por status
     */
    public function contarPorStatus()
    {
        return $this->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->findAll();
    }

    /**
     * Buscar tickets do usuário
     */
    public function ticketsDoUsuario($usuarioId, $limite = 10)
    {
        return $this->listarTickets(['usuario_id' => $usuarioId], $limite);
    }

    /**
     * Buscar tickets atribuídos ao responsável
     */
    public function ticketsDoResponsavel($responsavelId, $limite = 10)
    {
        return $this->listarTickets(['responsavel_id' => $responsavelId], $limite);
    }

    /**
     * Buscar um ticket completo com contagem de mensagens e última atividade
     */
    public function getTicketComAtividade($ticketId)
    {
        return $this->select('
                tickets.*,
                usuarios.nome as usuario_nome,
                usuarios.email as usuario_email,
                responsavel.nome as responsavel_nome,
                responsavel.email as responsavel_email,
                categorias.nome as categoria_nome,
                categorias.cor as categoria_cor,
                prioridades.nome as prioridade_nome,
                prioridades.cor as prioridade_cor,
                prioridades.nivel as prioridade_nivel,
                COALESCE(COUNT(comentarios.id), 0) as total_mensagens,
                GREATEST(
                    COALESCE(tickets.atualizado_em, tickets.criado_em),
                    COALESCE(MAX(comentarios.criado_em), tickets.criado_em)
                ) as ultima_atividade
            ')
            ->join('usuarios', 'usuarios.id = tickets.usuario_id', 'left')
            ->join('usuarios as responsavel', 'responsavel.id = tickets.responsavel_id', 'left')
            ->join('categorias', 'categorias.id = tickets.categoria_id', 'left')
            ->join('prioridades', 'prioridades.id = tickets.prioridade_id', 'left')
            ->join('comentarios', 'comentarios.ticket_id = tickets.id', 'left')
            ->where('tickets.id', $ticketId)
            ->groupBy('tickets.id')
            ->first();
    }
}
