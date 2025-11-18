<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-200">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 transition-colors duration-200">
        Bem-vindo, <span class="font-medium"><?= esc($user->nome) ?></span>!
    </p>
</div>

<!-- Cards de Estatísticas por Status -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">

    <!-- Total de Tickets -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Total de Tickets</dt>
                    <dd class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-200"><?= $stats['total'] ?></dd>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3 transition-colors duration-200">
            <div class="text-sm">
                <a href="/tickets" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Ver todos →</a>
            </div>
        </div>
    </div>

    <!-- Novos -->
    <a href="/tickets?status=novo" class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg hover:border-2 hover:border-blue-500 transition-all duration-200 cursor-pointer border-2 border-transparent">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Novos</dt>
                    <dd class="text-3xl font-bold text-blue-600 dark:text-blue-400 transition-colors duration-200"><?= $stats['novos'] ?></dd>
                </div>
            </div>
        </div>
    </a>

    <!-- Em Progresso -->
    <a href="/tickets?status=em_progresso" class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg hover:border-2 hover:border-yellow-500 transition-all duration-200 cursor-pointer border-2 border-transparent">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Em Progresso</dt>
                    <dd class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 transition-colors duration-200"><?= $stats['em_progresso'] ?></dd>
                </div>
            </div>
        </div>
    </a>

    <!-- Resolvidos -->
    <a href="/tickets?status=resolvido" class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg hover:border-2 hover:border-green-500 transition-all duration-200 cursor-pointer border-2 border-transparent">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Resolvidos</dt>
                    <dd class="text-3xl font-bold text-green-600 dark:text-green-400 transition-colors duration-200"><?= $stats['resolvidos'] ?></dd>
                </div>
            </div>
        </div>
    </a>

</div>

<!-- Grid de 2 Colunas -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Tickets por Prioridade -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200">Tickets por Prioridade</h3>
        <div class="space-y-3">
            <?php foreach ($ticketsPorPrioridade as $prioridade) : ?>
                <a href="/tickets?prioridade=<?= $prioridade['id'] ?>" class="block hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors duration-200 cursor-pointer">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"><?= esc($prioridade['nome']) ?></span>
                        <span class="font-semibold" style="color: <?= $prioridade['cor'] ?>"><?= $prioridade['count'] ?></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 transition-colors duration-200">
                        <?php
                        $percentage = $stats['total'] > 0 ? ($prioridade['count'] / $stats['total']) * 100 : 0;
                        ?>
                        <div class="h-2 rounded-full transition-all"
                             style="width: <?= $percentage ?>%; background-color: <?= $prioridade['cor'] ?>"></div>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </div>

    <!-- Tickets por Categoria -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 transition-colors duration-200">Tickets por Categoria</h3>
        <div class="space-y-3">
            <?php foreach ($ticketsPorCategoria as $categoria) : ?>
                <a href="/tickets?categoria=<?= $categoria['id'] ?>" class="block hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition-colors duration-200 cursor-pointer">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"><?= esc($categoria['nome']) ?></span>
                        <span class="font-semibold" style="color: <?= $categoria['cor'] ?>"><?= $categoria['count'] ?></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 transition-colors duration-200">
                        <?php
                        $percentage = $stats['total'] > 0 ? ($categoria['count'] / $stats['total']) * 100 : 0;
                        ?>
                        <div class="h-2 rounded-full transition-all"
                             style="width: <?= $percentage ?>%; background-color: <?= $categoria['cor'] ?>"></div>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </div>

</div>

<!-- Tickets Recentes -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition-colors duration-200">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white transition-colors duration-200">
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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-200">
            <thead class="bg-gray-50 dark:bg-gray-700 transition-colors duration-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">Prioridade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">Criado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider transition-colors duration-200">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-200">
                <?php if (empty($ticketsRecentes)) : ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 transition-colors duration-200">
                            Nenhum ticket encontrado
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($ticketsRecentes as $ticket) : ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200">
                                #<?= $ticket['id'] ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white transition-colors duration-200">
                                <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors duration-200">
                                    <?= esc($ticket['titulo']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded transition-colors duration-200"
                                      style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
                                    <?= esc($ticket['prioridade_nome']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                helper('ticket');
                                $statusCor = corStatus($ticket['status']);
                                ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded <?= $statusCor['bg'] ?> <?= $statusCor['text'] ?> transition-colors duration-200">
                                    <?= traduzirStatus($ticket['status']) ?>
                                </span>
                            </td>                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                <?= date('d/m/Y', strtotime($ticket['criado_em'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($ticketsRecentes)) : ?>
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700 transition-colors duration-200">
            <a href="/tickets" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                Ver todos os tickets →
            </a>
        </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
