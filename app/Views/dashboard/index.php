<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-600">
        Bem-vindo, <span class="font-medium"><?= esc($user->nome) ?></span>!
    </p>
</div>

<!-- Cards de Estatísticas por Status -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">

    <!-- Total de Tickets -->
    <div class="bg-white overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-shadow">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Tickets</dt>
                    <dd class="text-3xl font-bold text-gray-900"><?= $stats['total'] ?></dd>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="/tickets" class="font-medium text-indigo-600 hover:text-indigo-900">Ver todos →</a>
            </div>
        </div>
    </div>

    <!-- Novos -->
    <div class="bg-white overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-shadow">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 truncate">Novos</dt>
                    <dd class="text-3xl font-bold text-blue-600"><?= $stats['novos'] ?></dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Em Progresso -->
    <div class="bg-white overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-shadow">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 truncate">Em Progresso</dt>
                    <dd class="text-3xl font-bold text-yellow-600"><?= $stats['em_progresso'] ?></dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolvidos -->
    <div class="bg-white overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-shadow">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 truncate">Resolvidos</dt>
                    <dd class="text-3xl font-bold text-green-600"><?= $stats['resolvidos'] ?></dd>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Grid de 2 Colunas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Tickets por Prioridade -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tickets por Prioridade</h3>
        <div class="space-y-3">
            <?php foreach ($ticketsPorPrioridade as $nome => $data) : ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700"><?= esc($nome) ?></span>
                        <span class="font-semibold" style="color: <?= $data['cor'] ?>"><?= $data['count'] ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <?php
                        $percentage = $stats['total'] > 0 ? ($data['count'] / $stats['total']) * 100 : 0;
                        ?>
                        <div class="h-2 rounded-full transition-all"
                             style="width: <?= $percentage ?>%; background-color: <?= $data['cor'] ?>"></div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Tickets por Categoria -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tickets por Categoria</h3>
        <div class="space-y-3">
            <?php foreach ($ticketsPorCategoria as $nome => $data) : ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700"><?= esc($nome) ?></span>
                        <span class="font-semibold" style="color: <?= $data['cor'] ?>"><?= $data['count'] ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <?php
                        $percentage = $stats['total'] > 0 ? ($data['count'] / $stats['total']) * 100 : 0;
                        ?>
                        <div class="h-2 rounded-full transition-all"
                             style="width: <?= $percentage ?>%; background-color: <?= $data['cor'] ?>"></div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

</div>

<!-- Tickets Recentes -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
            <?php if ($user->funcao === 'cliente') : ?>
                Meus Tickets Recentes
            <?php elseif ($user->funcao === 'agente') : ?>
                Tickets Atribuídos a Mim
            <?php else : ?>
                Tickets Recentes
            <?php endif ?>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($ticketsRecentes)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum ticket encontrado
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($ticketsRecentes as $ticket) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #<?= $ticket['id'] ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    <?= esc($ticket['titulo']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded"
                                      style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
                                    <?= esc($ticket['prioridade_nome']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                    <?= esc($ticket['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($ticket['criado_em'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($ticketsRecentes)) : ?>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <a href="/tickets" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                Ver todos os tickets →
            </a>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
