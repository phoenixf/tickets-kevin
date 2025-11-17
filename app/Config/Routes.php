<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rota pública (Home - redireciona para login se não autenticado)
$routes->get('/', static function() {
    if (auth()->loggedIn()) {
        return redirect()->to('/dashboard');
    }
    return redirect()->to('/login');
});

// Rotas de autenticação (login, register, logout, etc.)
service('auth')->routes($routes);

// Rotas protegidas (requerem autenticação)
$routes->group('', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('tickets', 'Tickets::index');
});
