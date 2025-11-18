<?php

namespace App\Models;

use CodeIgniter\Model;

class PriorityModel extends Model
{
    protected $table            = 'prioridades';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'nivel',
        'cor'
    ];

    // Validation
    protected $validationRules = [
        'nome' => [
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'O nome da prioridade é obrigatório',
                'min_length' => 'O nome deve ter no mínimo 3 caracteres'
            ]
        ],
        'nivel' => [
            'rules' => 'required|integer|greater_than[0]',
            'errors' => [
                'required' => 'O nível é obrigatório',
                'integer' => 'O nível deve ser um número inteiro',
                'greater_than' => 'O nível deve ser maior que 0'
            ]
        ],
        'cor' => [
            'rules' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'errors' => [
                'required' => 'A cor é obrigatória',
                'regex_match' => 'A cor deve estar no formato hexadecimal (#RRGGBB)'
            ]
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Buscar prioridades ordenadas por nível
     */
    public function getOrdenadas()
    {
        return $this->orderBy('nivel', 'ASC')->findAll();
    }

    /**
     * Buscar prioridade por nome
     */
    public function getPorNome($nome)
    {
        return $this->where('nome', $nome)->first();
    }
}
