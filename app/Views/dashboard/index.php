<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - Sistema de Tickets</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">Sistema de Tickets</span>
                    </div>

                    <!-- Menu -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="/dashboard" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="/tickets" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Tickets
                        </a>
                    </div>
                </div>

                <!-- Menu do Usuário -->
                <div class="flex items-center">
                    <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500" id="user-menu-button">
                        <span class="sr-only">Abrir menu do usuário</span>
                        <div class="flex items-center">
                            <div class="mr-3 text-right">
                                <p class="text-sm font-medium text-gray-700"><?= esc($user->nome) ?></p>
                                <p class="text-xs text-gray-500 capitalize"><?= esc($user->funcao) ?></p>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold">
                                <?= strtoupper(substr($user->nome, 0, 2)) ?>
                            </div>
                        </div>
                    </button>

                    <!-- Logout -->
                    <form action="<?= url_to('logout') ?>" method="post" class="ml-4">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900">Bem-vindo, <?= esc($user->nome) ?>!</h1>
            <p class="mt-1 text-sm text-gray-600">
                Você está logado como <span class="font-medium capitalize"><?= esc($user->funcao) ?></span>
            </p>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="px-4 sm:px-0">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Card 1 -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Tickets</dt>
                                    <dd class="text-lg font-semibold text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="/tickets" class="font-medium text-indigo-600 hover:text-indigo-900">Ver todos</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Resolvidos</dt>
                                    <dd class="text-lg font-semibold text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Em Andamento</dt>
                                    <dd class="text-lg font-semibold text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Novos</dt>
                                    <dd class="text-lg font-semibold text-gray-900">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mensagem de Sucesso -->
        <div class="mt-8 px-4 sm:px-0">
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <strong>✅ Sistema de autenticação funcionando!</strong>
                            <br>Você está autenticado com sucesso.
                            <br>Email: <strong><?= esc($user->email) ?></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximos Passos -->
        <div class="mt-6 px-4 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        🚀 Próximos Passos do Desenvolvimento
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        v0.2.0 - Sistema de Autenticação (CONCLUÍDO!)
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-green-600">✅ Concluído</dt>
                            <dd class="mt-2 text-sm text-gray-900 space-y-1">
                                <p>• CodeIgniter Shield instalado</p>
                                <p>• 7 usuários migrados para o Shield</p>
                                <p>• Views customizadas criadas (Tailwind CSS)</p>
                                <p>• Sistema de login funcionando</p>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-blue-600">⏳ Próximo: v0.3.0 - Models</dt>
                            <dd class="mt-2 text-sm text-gray-900 space-y-1">
                                <p>• Criar TicketModel com validações</p>
                                <p>• Criar CategoryModel e PriorityModel</p>
                                <p>• Criar relacionamentos entre models</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
