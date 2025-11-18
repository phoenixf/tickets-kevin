<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttachmentModel;
use App\Models\TicketModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Attachments extends BaseController
{
    protected $attachmentModel;
    protected $ticketModel;
    protected $uploadPath;

    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->ticketModel = new TicketModel();
        $this->uploadPath = WRITEPATH . 'uploads/';
    }

    /**
     * Upload de anexo para um ticket
     */
    public function upload($ticketId): RedirectResponse
    {
        // Verificar se usuário está logado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado');
        }

        $user = auth()->user();

        // Verificar se ticket existe
        $ticket = $this->ticketModel->find($ticketId);
        if (!$ticket) {
            return redirect()->to('/tickets')->with('error', 'Ticket não encontrado');
        }

        // Verificar se arquivo foi enviado
        $file = $this->request->getFile('arquivo');
        if (!$file || !$file->isValid()) {
            return redirect()->back()
                ->with('error', 'Nenhum arquivo foi selecionado ou o arquivo é inválido');
        }

        // Validar tipo e tamanho do arquivo
        $validationRules = [
            'arquivo' => [
                'rules' => 'uploaded[arquivo]|max_size[arquivo,10240]|ext_in[arquivo,pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip,txt]',
                'errors' => [
                    'uploaded' => 'Por favor selecione um arquivo',
                    'max_size' => 'O arquivo não pode ter mais de 10MB',
                    'ext_in' => 'Tipo de arquivo não permitido'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        // Criar diretório de uploads se não existir
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }

        // Gerar nome único para o arquivo
        $fileName = $file->getRandomName();
        $filePath = $this->uploadPath . $fileName;

        // Mover arquivo para o diretório de uploads
        if ($file->move($this->uploadPath, $fileName)) {
            // Salvar informações no banco
            $data = [
                'ticket_id' => $ticketId,
                'nome_arquivo' => $file->getClientName(),
                'caminho_arquivo' => $fileName,
                'tamanho_arquivo' => $file->getSize(),
                'tipo_mime' => $file->getClientMimeType(),
                'enviado_por' => $user->id
            ];

            if ($this->attachmentModel->insert($data)) {
                return redirect()->to("/tickets/{$ticketId}")
                    ->with('success', 'Arquivo enviado com sucesso');
            }

            // Se falhar ao salvar no banco, deletar arquivo
            unlink($filePath);
            return redirect()->back()
                ->with('error', 'Erro ao salvar informações do arquivo');
        }

        return redirect()->back()
            ->with('error', 'Erro ao fazer upload do arquivo');
    }

    /**
     * Download de anexo
     */
    public function download($attachmentId): ResponseInterface
    {
        // Verificar se usuário está logado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado');
        }

        // Buscar anexo
        $attachment = $this->attachmentModel->find($attachmentId);
        if (!$attachment) {
            return redirect()->back()->with('error', 'Anexo não encontrado');
        }

        $filePath = $this->uploadPath . $attachment['caminho_arquivo'];

        // Verificar se arquivo existe
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Arquivo não encontrado no servidor');
        }

        // Fazer download
        return $this->response->download($filePath, null)
            ->setFileName($attachment['nome_arquivo']);
    }

    /**
     * Deletar anexo
     */
    public function delete($attachmentId): RedirectResponse
    {
        // Verificar se usuário está logado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado');
        }

        $user = auth()->user();

        // Buscar anexo
        $attachment = $this->attachmentModel->find($attachmentId);
        if (!$attachment) {
            return redirect()->back()->with('error', 'Anexo não encontrado');
        }

        // Verificar permissão
        // Apenas quem enviou ou admin pode deletar
        if ($attachment['enviado_por'] !== $user->id && $user->funcao !== 'admin') {
            return redirect()->back()
                ->with('error', 'Você não tem permissão para deletar este anexo');
        }

        $ticketId = $attachment['ticket_id'];
        $filePath = $this->uploadPath . $attachment['caminho_arquivo'];

        // Deletar do banco
        if ($this->attachmentModel->delete($attachmentId)) {
            // Deletar arquivo físico
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return redirect()->to("/tickets/{$ticketId}")
                ->with('success', 'Anexo removido com sucesso');
        }

        return redirect()->back()->with('error', 'Erro ao remover anexo');
    }
}
