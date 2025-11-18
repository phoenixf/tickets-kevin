<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tickets</h1>
        <a href="/tickets/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Ticket
        </a>
    </div>
</div>

<!-- Tabela de Tickets -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
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
            <?php if (empty($tickets)) : ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum ticket encontrado</td>
                </tr>
            <?php else : ?>
                <?php foreach ($tickets as $ticket) : ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $ticket['id'] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                <?= esc($ticket['titulo']) ?>
                            </a>
                            <p class="text-xs text-gray-500"><?= esc($ticket['usuario_nome']) ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
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
                            <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                            <?php if ($user->funcao !== 'cliente') : ?>
                                <a href="/tickets/<?= $ticket['id'] ?>/edit" class="text-green-600 hover:text-green-900">Editar</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
