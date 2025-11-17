<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        // Verificar se o usuário está autenticado
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard',
            'user' => auth()->user(),
        ];

        return view('dashboard/index', $data);
    }
}
