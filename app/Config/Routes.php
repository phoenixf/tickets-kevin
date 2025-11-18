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
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Tickets - CRUD Completo
    $routes->get('tickets', 'Tickets::index');                    // Listar
    $routes->get('tickets/create', 'Tickets::create');            // Formulário criar
    $routes->post('tickets', 'Tickets::store');                   // Salvar
    $routes->get('tickets/(:num)', 'Tickets::show/$1');           // Visualizar
    $routes->get('tickets/(:num)/edit', 'Tickets::edit/$1');      // Formulário editar
    $routes->post('tickets/(:num)', 'Tickets::update/$1');        // Atualizar
    $routes->delete('tickets/(:num)', 'Tickets::delete/$1');      // Deletar
});
