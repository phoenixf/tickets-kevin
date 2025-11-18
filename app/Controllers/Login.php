<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;

class Login extends ShieldLogin
{
    /**
     * Login via AJAX - Retorna JSON ao invés de redirecionar
     *
     * Endpoint moderno para login assíncrono sem reload de página
     * Retorna estrutura JSON padronizada com feedback de sucesso/erro
     */
    public function loginAjax()
    {
        // Verificar se é requisição AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Requisição inválida. Use AJAX para este endpoint.'
            ])->setStatusCode(400);
        }

        // Validar CSRF token
        if (!$this->validate(['csrf_test_name' => 'required'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Token CSRF inválido. Recarregue a página.'
            ])->setStatusCode(403);
        }

        // Regras de validação
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'O email é obrigatório',
                    'valid_email' => 'Forneça um email válido'
                ]
            ],
            'password' => [
                'label' => 'Senha',
                'rules' => 'required',
                'errors' => [
                    'required' => 'A senha é obrigatória'
                ]
            ]
        ];

        // Validar dados
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        // Obter credenciais
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        // Tentar autenticar
        $auth = service('auth');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $result = $auth->attempt($credentials);

        if (!$result->isOK()) {
            // Falha na autenticação
            return $this->response->setJSON([
                'success' => false,
                'message' => $this->getLoginErrorMessage($result->reason())
            ])->setStatusCode(401);
        }

        // Sucesso - configurar remember me se solicitado
        if ($remember) {
            service('auth')->remember();
        }

        // Determinar URL de redirecionamento baseado no grupo do usuário
        $user = auth()->user();
        $redirectUrl = $this->getRedirectUrl($user);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login realizado com sucesso!',
            'redirect' => $redirectUrl,
            'user' => [
                'email' => $user->email,
                'username' => $user->username ?? $user->email
            ]
        ])->setStatusCode(200);
    }

    /**
     * Obter URL de redirecionamento baseado no perfil do usuário
     */
    private function getRedirectUrl($user): string
    {
        // Verificar se tem URL de retorno salva
        if ($return = session('redirect_url')) {
            session()->remove('redirect_url');
            return $return;
        }

        // Redirecionar para dashboard por padrão
        return base_url('dashboard');
    }

    /**
     * Traduzir mensagens de erro do Shield para português
     */
    private function getLoginErrorMessage(string $reason): string
    {
        $messages = [
            'bad_credentials' => 'Email ou senha incorretos. Verifique suas credenciais.',
            'unknown_error' => 'Erro desconhecido ao tentar fazer login. Tente novamente.',
            'too_many_attempts' => 'Muitas tentativas de login. Aguarde alguns minutos.',
            'user_banned' => 'Sua conta foi suspensa. Entre em contato com o suporte.',
            'user_not_active' => 'Sua conta ainda não foi ativada. Verifique seu email.',
        ];

        return $messages[$reason] ?? 'Email ou senha incorretos.';
    }
}
