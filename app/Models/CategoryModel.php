<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'descricao',
        'cor',
        'icone',
        'ativo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    // Validation
    protected $validationRules = [
        'nome' => [
            'rules' => 'required|min_length[3]|max_length[100]',
            'errors' => [
                'required' => 'O nome da categoria é obrigatório',
                'min_length' => 'O nome deve ter no mínimo 3 caracteres',
                'max_length' => 'O nome deve ter no máximo 100 caracteres'
            ]
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Buscar apenas categorias ativas
     */
    public function getAtivas()
    {
        return $this->where('ativo', 1)->findAll();
    }

    /**
     * Contar tickets por categoria
     */
    public function contarTickets($categoriaId)
    {
        return $this->db->table('tickets')
            ->where('categoria_id', $categoriaId)
            ->countAllResults();
    }
}
