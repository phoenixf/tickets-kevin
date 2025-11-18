<?php

namespace App\Models;

use CodeIgniter\Model;

class AttachmentModel extends Model
{
    protected $table            = 'anexos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ticket_id',
        'nome_arquivo',
        'caminho_arquivo',
        'tamanho_arquivo',
        'tipo_mime',
        'enviado_por'
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
        'nome_arquivo' => [
            'rules' => 'required|max_length[255]',
            'errors' => [
                'required' => 'O nome do arquivo é obrigatório',
                'max_length' => 'O nome do arquivo é muito longo'
            ]
        ],
        'caminho_arquivo' => [
            'rules' => 'required|max_length[500]',
            'errors' => [
                'required' => 'O caminho do arquivo é obrigatório'
            ]
        ],
        'tamanho_arquivo' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'O tamanho do arquivo é obrigatório',
                'integer' => 'Tamanho inválido'
            ]
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Buscar anexos de um ticket
     */
    public function getAnexosDoTicket($ticketId)
    {
        return $this->select('
                anexos.*,
                usuarios.nome as enviado_por_nome
            ')
            ->join('usuarios', 'usuarios.id = anexos.enviado_por', 'left')
            ->where('anexos.ticket_id', $ticketId)
            ->orderBy('anexos.criado_em', 'DESC')
            ->findAll();
    }

    /**
     * Contar anexos de um ticket
     */
    public function contarAnexos($ticketId)
    {
        return $this->where('ticket_id', $ticketId)->countAllResults();
    }

    /**
     * Formatar tamanho do arquivo
     */
    public function formatarTamanho($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
