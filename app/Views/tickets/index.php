<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tickets</h1>
        <a href="/tickets/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Ticket
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 transition-colors duration-200">
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrar por:</span>

            <!-- Filtro de Status -->
            <div class="relative">
                <select id="filter-status" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todos os Status</option>
                    <option value="novo" <?= ($filtros['status'] ?? '') === 'novo' ? 'selected' : '' ?>>Novo</option>
                    <option value="aberto" <?= ($filtros['status'] ?? '') === 'aberto' ? 'selected' : '' ?>>Aberto</option>
                    <option value="em_progresso" <?= ($filtros['status'] ?? '') === 'em_progresso' ? 'selected' : '' ?>>Em Progresso</option>
                    <option value="pendente" <?= ($filtros['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="resolvido" <?= ($filtros['status'] ?? '') === 'resolvido' ? 'selected' : '' ?>>Resolvido</option>
                    <option value="fechado" <?= ($filtros['status'] ?? '') === 'fechado' ? 'selected' : '' ?>>Fechado</option>
                </select>
            </div>

            <!-- Filtro de Prioridade -->
            <div class="relative">
                <select id="filter-prioridade" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todas as Prioridades</option>
                    <?php foreach ($prioridades as $prioridade) : ?>
                        <option value="<?= $prioridade['id'] ?>" <?= ($filtros['prioridade_id'] ?? '') == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= esc($prioridade['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Filtro de Categoria -->
            <div class="relative">
                <select id="filter-categoria" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todas as Categorias</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?= $categoria['id'] ?>" <?= ($filtros['categoria_id'] ?? '') == $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <?php if (!empty($filtros['status']) || !empty($filtros['prioridade_id']) || !empty($filtros['categoria_id'])) : ?>
                <a href="/tickets" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar Filtros
                </a>
            <?php endif ?>
        </div>
    </div>
</div>


<!-- Tabela de Tickets -->
<div class="bg-white shadow-md rounded-lg overflow-hidden dark:bg-gray-800 transition-colors duration-200">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 transition-colors duration-200">
        <thead class="bg-gray-50 dark:bg-gray-700 transition-colors duration-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">Título</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">Prioridade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">Criado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider transition-colors duration-200">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 transition-colors duration-200">
            <?php if (empty($tickets)) : ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 transition-colors duration-200">Nenhum ticket encontrado</td>
                </tr>
            <?php else : ?>
                <?php foreach ($tickets as $ticket) : ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white transition-colors duration-200">#<?= $ticket['id'] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white transition-colors duration-200">
                            <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors duration-200">
                                <?= esc($ticket['titulo']) ?>
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors duration-200"><?= esc($ticket['usuario_nome']) ?></p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: <?= $ticket['prioridade_cor'] ?>20; color: <?= $ticket['prioridade_cor'] ?>">
                                <?= esc($ticket['prioridade_nome']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            helper('ticket');
                            $statusCor = corStatus($ticket['status']);
                            ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded <?= $statusCor['bg'] ?> <?= $statusCor['text'] ?>">
                                <?= traduzirStatus($ticket['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">
                            <?= date('d/m/Y', strtotime($ticket['criado_em'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="/tickets/<?= $ticket['id'] ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition-colors duration-200">Ver</a>
                            <?php if ($user->funcao !== 'cliente') : ?>
                                <a href="/tickets/<?= $ticket['id'] ?>/edit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200">Editar</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
        </tbody>
    </table>
</div>

<script>
    // Função para atualizar os filtros
    function aplicarFiltros() {
        const status = document.getElementById('filter-status').value;
        const prioridade = document.getElementById('filter-prioridade').value;
        const categoria = document.getElementById('filter-categoria').value;

        // Construir URL com parâmetros
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (prioridade) params.append('prioridade', prioridade);
        if (categoria) params.append('categoria', categoria);

        // Redirecionar com os novos filtros
        const queryString = params.toString();
        window.location.href = '/tickets' + (queryString ? '?' + queryString : '');
    }

    // Adicionar event listeners aos selects
    document.getElementById('filter-status').addEventListener('change', aplicarFiltros);
    document.getElementById('filter-prioridade').addEventListener('change', aplicarFiltros);
    document.getElementById('filter-categoria').addEventListener('change', aplicarFiltros);
</script>

<?= $this->endSection() ?>
