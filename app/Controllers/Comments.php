<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommentModel;
use App\Models\TicketModel;
use CodeIgniter\HTTP\RedirectResponse;

class Comments extends BaseController
{
    protected $commentModel;
    protected $ticketModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->ticketModel = new TicketModel();
    }

    /**
     * Adicionar comentário a um ticket
     */
    public function store($ticketId): RedirectResponse
    {
        // Verificar se usuário está logado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado para comentar');
        }

        $user = auth()->user();

        // Verificar se ticket existe
        $ticket = $this->ticketModel->find($ticketId);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Ticket não encontrado');
        }

        // Validar dados
        $rules = [
            'conteudo' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'O comentário não pode estar vazio',
                    'min_length' => 'O comentário deve ter no mínimo 5 caracteres'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Determinar se é comentário interno
        $ehInterno = false;
        if ($user->funcao !== 'cliente') {
            $ehInterno = $this->request->getPost('eh_interno') === '1';
        }

        // Salvar comentário
        $data = [
            'ticket_id' => $ticketId,
            'usuario_id' => $user->id,
            'conteudo' => $this->request->getPost('conteudo'),
            'eh_interno' => $ehInterno
        ];

        if ($this->commentModel->insert($data)) {
            return redirect()->to("/tickets/{$ticketId}")
                ->with('success', 'Comentário adicionado com sucesso');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Erro ao adicionar comentário');
    }

    /**
     * Deletar comentário
     */
    public function delete($commentId): RedirectResponse
    {
        // Verificar se usuário está logado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado');
        }

        $user = auth()->user();

        // Buscar comentário
        $comment = $this->commentModel->find($commentId);
        if (!$comment) {
            return redirect()->back()->with('error', 'Comentário não encontrado');
        }

        // Verificar permissão
        // Apenas o autor do comentário ou admin pode deletar
        if ($comment['usuario_id'] !== $user->id && $user->funcao !== 'admin') {
            return redirect()->back()
                ->with('error', 'Você não tem permissão para deletar este comentário');
        }

        $ticketId = $comment['ticket_id'];

        if ($this->commentModel->delete($commentId)) {
            return redirect()->to("/tickets/{$ticketId}")
                ->with('success', 'Comentário removido com sucesso');
        }

        return redirect()->back()->with('error', 'Erro ao remover comentário');
    }
}
