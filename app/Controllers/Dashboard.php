<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\CategoryModel;
use App\Models\PriorityModel;
use App\Models\RelatorioModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $ticketModel;
    protected $categoryModel;
    protected $priorityModel;
    protected $relatorioModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->categoryModel = new CategoryModel();
        $this->priorityModel = new PriorityModel();
        $this->relatorioModel = new RelatorioModel();
    }

    public function index()
    {
        // Verificar se o usuário está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        // Estatísticas gerais
        $stats = [
            'total' => 0,
            'novos' => 0,
            'abertos' => 0,
            'em_progresso' => 0,
            'pendentes' => 0,
            'resolvidos' => 0,
            'fechados' => 0,
        ];

        if ($user->funcao === 'cliente') {
            // Estatísticas dos tickets do cliente
            $stats['total'] = $this->ticketModel->where('usuario_id', $user->id)->countAllResults();
            $stats['novos'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'novo')->countAllResults();
            $stats['abertos'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'aberto')->countAllResults();
            $stats['em_progresso'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'em_progresso')->countAllResults();
            $stats['pendentes'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'pendente')->countAllResults();
            $stats['resolvidos'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'resolvido')->countAllResults();
            $stats['fechados'] = $this->ticketModel->where('usuario_id', $user->id)->where('status', 'fechado')->countAllResults();

            // Tickets recentes do cliente
            $ticketsRecentes = $this->ticketModel->ticketsDoUsuario($user->id, 5);
        } elseif ($user->funcao === 'agente') {
            // Estatísticas de todos os tickets (agentes veem tudo)
            $stats['total'] = $this->ticketModel->countAll();
            $stats['novos'] = $this->ticketModel->where('status', 'novo')->countAllResults();
            $stats['abertos'] = $this->ticketModel->where('status', 'aberto')->countAllResults();
            $stats['em_progresso'] = $this->ticketModel->where('status', 'em_progresso')->countAllResults();
            $stats['pendentes'] = $this->ticketModel->where('status', 'pendente')->countAllResults();
            $stats['resolvidos'] = $this->ticketModel->where('status', 'resolvido')->countAllResults();
            $stats['fechados'] = $this->ticketModel->where('status', 'fechado')->countAllResults();

            // Tickets atribuídos ao agente
            $ticketsRecentes = $this->ticketModel->ticketsDoResponsavel($user->id, 5);
        } else {
            // Admin - estatísticas completas
            $stats['total'] = $this->ticketModel->countAll();
            $stats['novos'] = $this->ticketModel->where('status', 'novo')->countAllResults();
            $stats['abertos'] = $this->ticketModel->where('status', 'aberto')->countAllResults();
            $stats['em_progresso'] = $this->ticketModel->where('status', 'em_progresso')->countAllResults();
            $stats['pendentes'] = $this->ticketModel->where('status', 'pendente')->countAllResults();
            $stats['resolvidos'] = $this->ticketModel->where('status', 'resolvido')->countAllResults();
            $stats['fechados'] = $this->ticketModel->where('status', 'fechado')->countAllResults();

            // Tickets recentes (todos)
            $ticketsRecentes = $this->ticketModel->listarTickets([], 5);
        }

        // Estatísticas por prioridade
        $prioridades = $this->priorityModel->getOrdenadas();
        $ticketsPorPrioridade = [];
        foreach ($prioridades as $prioridade) {
            $count = $this->ticketModel->where('prioridade_id', $prioridade['id']);
            if ($user->funcao === 'cliente') {
                $count->where('usuario_id', $user->id);
            }
            $ticketsPorPrioridade[] = [
                'id' => $prioridade['id'],
                'nome' => $prioridade['nome'],
                'count' => $count->countAllResults(),
                'cor' => $prioridade['cor']
            ];
        }

        // Estatísticas por categoria
        $categorias = $this->categoryModel->getAtivas();
        $ticketsPorCategoria = [];
        foreach ($categorias as $categoria) {
            $count = $this->ticketModel->where('categoria_id', $categoria['id']);
            if ($user->funcao === 'cliente') {
                $count->where('usuario_id', $user->id);
            }
            $ticketsPorCategoria[] = [
                'id' => $categoria['id'],
                'nome' => $categoria['nome'],
                'count' => $count->countAllResults(),
                'cor' => $categoria['cor']
            ];
        }

        // Tickets próximos ao vencimento do SLA (crítico)
        $ticketsProximosVencimento = $this->relatorioModel->getTicketsProximosVencimento(5);

        $data = [
            'title' => 'Dashboard',
            'user' => $user,
            'stats' => $stats,
            'ticketsRecentes' => $ticketsRecentes,
            'ticketsPorPrioridade' => $ticketsPorPrioridade,
            'ticketsPorCategoria' => $ticketsPorCategoria,
            'ticketsProximosVencimento' => $ticketsProximosVencimento,
        ];

        return view('dashboard/index', $data);
    }
}
