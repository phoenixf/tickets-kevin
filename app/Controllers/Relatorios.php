<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RelatorioModel;
use App\Models\CategoryModel;
use App\Models\PriorityModel;
use CodeIgniter\HTTP\ResponseInterface;

class Relatorios extends BaseController
{
    protected $relatorioModel;
    protected $categoryModel;
    protected $priorityModel;

    public function __construct()
    {
        $this->relatorioModel = new RelatorioModel();
        $this->categoryModel = new CategoryModel();
        $this->priorityModel = new PriorityModel();
    }

    public function index()
    {
        // 1. Verificar se usuário está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        // 2. Bloquear clientes (apenas admin e agente podem acessar)
        if ($user->funcao === 'cliente') {
            return redirect()->to('/dashboard')
                ->with('error', 'Você não tem permissão para acessar relatórios.');
        }

        // 3. Receber filtros via GET
        $periodo_inicio = $this->request->getGet('periodo_inicio')
            ?? date('Y-m-d', strtotime('-7 days'));
        $periodo_fim = $this->request->getGet('periodo_fim')
            ?? date('Y-m-d');
        $agente_id = $this->request->getGet('agente_id') ?? null;
        $categoria_id = $this->request->getGet('categoria_id') ?? null;
        $prioridade_id = $this->request->getGet('prioridade_id') ?? null;

        // Construir array de filtros
        $filtros = [
            'periodo_inicio' => $periodo_inicio . ' 00:00:00',
            'periodo_fim' => $periodo_fim . ' 23:59:59',
        ];

        if (!empty($agente_id)) {
            $filtros['agente_id'] = $agente_id;
        }
        if (!empty($categoria_id)) {
            $filtros['categoria_id'] = $categoria_id;
        }
        if (!empty($prioridade_id)) {
            $filtros['prioridade_id'] = $prioridade_id;
        }

        // 4. Buscar dados usando RelatorioModel
        $kpis = $this->relatorioModel->getKPIs($filtros);
        $performanceAgentes = $this->relatorioModel->getPerformanceAgentes($filtros);
        $ticketsPorPeriodo = $this->relatorioModel->getTicketsPorPeriodo($filtros, 'day');
        $distribuicaoStatus = $this->relatorioModel->getDistribuicaoPorStatus($filtros);
        $distribuicaoPrioridade = $this->relatorioModel->getDistribuicaoPorPrioridade($filtros);
        $distribuicaoCategoria = $this->relatorioModel->getDistribuicaoPorCategoria($filtros);

        // 5. Buscar listas para filtros
        // Lista de agentes (apenas admin/agente função)
        $db = \Config\Database::connect();
        $agentes = $db->table('usuarios')
            ->select('usuarios.id, usuarios.nome')
            ->join('auth_groups_users', 'auth_groups_users.user_id = usuarios.id')
            ->whereIn('auth_groups_users.group', ['admin', 'agente'])
            ->groupBy('usuarios.id, usuarios.nome')
            ->orderBy('usuarios.nome', 'ASC')
            ->get()
            ->getResultArray();

        // Lista de categorias
        $categorias = $this->categoryModel->getAtivas();

        // Lista de prioridades
        $prioridades = $this->priorityModel->getOrdenadas();

        // 6. Passar para view
        $data = [
            'title' => 'Relatórios',
            'user' => $user,
            'kpis' => $kpis,
            'performanceAgentes' => $performanceAgentes,
            'ticketsPorPeriodo' => $ticketsPorPeriodo,
            'distribuicaoStatus' => $distribuicaoStatus,
            'distribuicaoPrioridade' => $distribuicaoPrioridade,
            'distribuicaoCategoria' => $distribuicaoCategoria,
            'filtros' => [
                'periodo_inicio' => $periodo_inicio,
                'periodo_fim' => $periodo_fim,
                'agente_id' => $agente_id,
                'categoria_id' => $categoria_id,
                'prioridade_id' => $prioridade_id,
            ],
            'agentes' => $agentes,
            'categorias' => $categorias,
            'prioridades' => $prioridades,
        ];

        return view('relatorios/index', $data);
    }
}
