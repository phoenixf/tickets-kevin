<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\CategoryModel;
use App\Models\PriorityModel;
use App\Models\CommentModel;
use App\Models\AttachmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Tickets extends BaseController
{
    protected $ticketModel;
    protected $categoryModel;
    protected $priorityModel;
    protected $commentModel;
    protected $attachmentModel;

    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->categoryModel = new CategoryModel();
        $this->priorityModel = new PriorityModel();
        $this->commentModel = new CommentModel();
        $this->attachmentModel = new AttachmentModel();
    }

    /**
     * Listar todos os tickets
     */
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        // Filtros da query string
        $filtros = [
            'status' => $this->request->getGet('status'),
            'prioridade_id' => $this->request->getGet('prioridade'),
            'categoria_id' => $this->request->getGet('categoria'),
            'busca' => $this->request->getGet('busca'),
        ];

        // Se for cliente, mostrar apenas seus tickets
        if ($user->funcao === 'cliente') {
            $filtros['usuario_id'] = $user->id;
        }

        $tickets = $this->ticketModel->listarTickets($filtros, 50);
        $categorias = $this->categoryModel->getAtivas();
        $prioridades = $this->priorityModel->getOrdenadas();

        $data = [
            'title' => 'Tickets',
            'user' => $user,
            'tickets' => $tickets,
            'categorias' => $categorias,
            'prioridades' => $prioridades,
            'filtros' => $filtros,
        ];

        return view('tickets/index', $data);
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Novo Ticket',
            'user' => auth()->user(),
            'categorias' => $this->categoryModel->getAtivas(),
            'prioridades' => $this->priorityModel->getOrdenadas(),
        ];

        return view('tickets/create', $data);
    }

    /**
     * Salvar novo ticket
     */
    public function store()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        $data = [
            'titulo' => $this->request->getPost('titulo'),
            'descricao' => $this->request->getPost('descricao'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'prioridade_id' => $this->request->getPost('prioridade_id'),
            'usuario_id' => $user->id,
            'status' => 'novo',
        ];

        if ($this->ticketModel->save($data)) {
            return redirect()->to('/tickets')
                ->with('success', 'Ticket criado com sucesso!');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->ticketModel->errors());
    }

    /**
     * Visualizar ticket
     */
    public function show($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $ticket = $this->ticketModel->getTicketCompleto($id);

        if (!$ticket) {
            return redirect()->to('/tickets')
                ->with('error', 'Ticket não encontrado');
        }

        // Clientes só podem ver seus próprios tickets
        if ($user->funcao === 'cliente' && $ticket['usuario_id'] != $user->id) {
            return redirect()->to('/tickets')
                ->with('error', 'Você não tem permissão para visualizar este ticket');
        }

        // Buscar comentários (clientes não veem comentários internos)
        $incluirInternos = $user->funcao !== 'cliente';
        $comentarios = $this->commentModel->getComentariosDoTicket($id, $incluirInternos);

        // Buscar anexos
        $anexos = $this->attachmentModel->getAnexosDoTicket($id);

        $data = [
            'title' => 'Ticket #' . $ticket['id'],
            'user' => $user,
            'ticket' => $ticket,
            'comentarios' => $comentarios,
            'anexos' => $anexos,
        ];

        return view('tickets/show', $data);
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $ticket = $this->ticketModel->find($id);

        if (!$ticket) {
            return redirect()->to('/tickets')
                ->with('error', 'Ticket não encontrado');
        }

        // Clientes não podem editar
        if ($user->funcao === 'cliente') {
            return redirect()->to('/tickets')
                ->with('error', 'Você não tem permissão para editar tickets');
        }

        $data = [
            'title' => 'Editar Ticket #' . $ticket['id'],
            'user' => $user,
            'ticket' => $ticket,
            'categorias' => $this->categoryModel->getAtivas(),
            'prioridades' => $this->priorityModel->getOrdenadas(),
        ];

        return view('tickets/edit', $data);
    }

    /**
     * Atualizar ticket
     */
    public function update($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        // Clientes não podem atualizar
        if ($user->funcao === 'cliente') {
            return redirect()->to('/tickets')
                ->with('error', 'Você não tem permissão para editar tickets');
        }

        $data = [
            'titulo' => $this->request->getPost('titulo'),
            'descricao' => $this->request->getPost('descricao'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'prioridade_id' => $this->request->getPost('prioridade_id'),
            'status' => $this->request->getPost('status'),
            'responsavel_id' => $this->request->getPost('responsavel_id') ?: null,
        ];

        if ($this->ticketModel->update($id, $data)) {
            return redirect()->to('/tickets/' . $id)
                ->with('success', 'Ticket atualizado com sucesso!');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->ticketModel->errors());
    }

    /**
     * Deletar ticket
     */
    public function delete($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();

        // Apenas admins podem deletar
        if ($user->funcao !== 'admin') {
            return redirect()->to('/tickets')
                ->with('error', 'Você não tem permissão para deletar tickets');
        }

        if ($this->ticketModel->delete($id)) {
            return redirect()->to('/tickets')
                ->with('success', 'Ticket deletado com sucesso!');
        }

        return redirect()->back()
            ->with('error', 'Erro ao deletar ticket');
    }
}
