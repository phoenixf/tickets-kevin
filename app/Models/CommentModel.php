<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table            = 'comentarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_id',
        'usuario_id',
        'conteudo',
        'eh_interno'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    // Validation
    protected $validationRules = [
        'ticket_id' => [
            'rules' => 'required|integer|is_not_unique[tickets.id]',
            'errors' => [
                'required' => 'O ticket é obrigatório',
                'is_not_unique' => 'Ticket não encontrado'
            ]
        ],
        'usuario_id' => [
            'rules' => 'required|integer|is_not_unique[usuarios.id]',
            'errors' => [
                'required' => 'O usuário é obrigatório',
                'is_not_unique' => 'Usuário não encontrado'
            ]
        ],
        'conteudo' => [
            'rules' => 'required|min_length[10]',
            'errors' => [
                'required' => 'O conteúdo do comentário é obrigatório',
                'min_length' => 'O comentário deve ter no mínimo 10 caracteres'
            ]
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Buscar comentários de um ticket com dados do usuário
     */
    public function getComentariosDoTicket($ticketId, $incluirInternos = false)
    {
        $builder = $this->select('
                comentarios.*,
                usuarios.nome as usuario_nome,
                usuarios.funcao as usuario_funcao
            ')
            ->join('usuarios', 'usuarios.id = comentarios.usuario_id', 'left')
            ->where('comentarios.ticket_id', $ticketId);

        if (!$incluirInternos) {
            $builder->where('comentarios.eh_interno', 0);
        }

        return $builder->orderBy('comentarios.criado_em', 'ASC')->findAll();
    }

    /**
     * Contar comentários de um ticket
     */
    public function contarComentarios($ticketId)
    {
        return $this->where('ticket_id', $ticketId)->countAllResults();
    }
}
