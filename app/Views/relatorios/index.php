<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header com Filtros -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-200">📊 Relatórios e Métricas</h1>
        <button id="export-btn" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar PDF
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 transition-colors duration-200">
        <form method="GET" action="/relatorios" class="flex items-center gap-3 flex-wrap">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtrar por:</span>

            <!-- Período -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Período:</label>
                <input type="date"
                       name="periodo_inicio"
                       value="<?= esc($filtros['periodo_inicio'] ?? date('Y-m-d', strtotime('-7 days'))) ?>"
                       class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                <span class="text-gray-500 dark:text-gray-400">até</span>
                <input type="date"
                       name="periodo_fim"
                       value="<?= esc($filtros['periodo_fim'] ?? date('Y-m-d')) ?>"
                       class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
            </div>

            <!-- Agente -->
            <div class="relative">
                <select name="agente_id" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todos os Agentes</option>
                    <?php foreach ($agentes as $agente) : ?>
                        <option value="<?= $agente['id'] ?>" <?= ($filtros['agente_id'] ?? '') == $agente['id'] ? 'selected' : '' ?>>
                            <?= esc($agente['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Categoria -->
            <div class="relative">
                <select name="categoria_id" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todas as Categorias</option>
                    <?php foreach ($categorias as $categoria) : ?>
                        <option value="<?= $categoria['id'] ?>" <?= ($filtros['categoria_id'] ?? '') == $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Prioridade -->
            <div class="relative">
                <select name="prioridade_id" class="block w-auto px-4 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
                    <option value="">Todas as Prioridades</option>
                    <?php foreach ($prioridades as $prioridade) : ?>
                        <option value="<?= $prioridade['id'] ?>" <?= ($filtros['prioridade_id'] ?? '') == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= esc($prioridade['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Botões -->
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Atualizar
            </button>

            <?php if (!empty($filtros['agente_id']) || !empty($filtros['categoria_id']) || !empty($filtros['prioridade_id'])) : ?>
                <a href="/relatorios?periodo_inicio=<?= esc($filtros['periodo_inicio']) ?>&periodo_fim=<?= esc($filtros['periodo_fim']) ?>"
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar Filtros
                </a>
            <?php endif ?>
        </form>
    </div>
</div>

<!-- KPIs Principais -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">

    <!-- Total de Tickets -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Total de Tickets</dt>
                    <dd class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-200"><?= $kpis['total_tickets'] ?></dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Resolvidos -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Resolvidos</dt>
                    <dd class="text-3xl font-bold text-green-600 dark:text-green-400 transition-colors duration-200"><?= $kpis['tickets_resolvidos'] ?></dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Tempo Médio Resolução -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Tempo Médio</dt>
                    <dd class="text-3xl font-bold text-blue-600 dark:text-blue-400 transition-colors duration-200">
                        <?php
                        $horas = floor($kpis['tempo_medio_resolucao'] / 60);
                        $minutos = $kpis['tempo_medio_resolucao'] % 60;
                        echo $horas . 'h ' . $minutos . 'm';
                        ?>
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Taxa de Resolução -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Taxa Resolução</dt>
                    <dd class="text-3xl font-bold text-purple-600 dark:text-purple-400 transition-colors duration-200"><?= number_format($kpis['taxa_resolucao'], 1) ?>%</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Abertos -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg hover:shadow-lg transition-all duration-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate transition-colors duration-200">Abertos Agora</dt>
                    <dd class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 transition-colors duration-200"><?= $kpis['tickets_abertos_agora'] ?></dd>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Placeholder para próximas seções -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6 transition-colors duration-200">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🚧 Em Desenvolvimento</h3>
    <p class="text-gray-600 dark:text-gray-300">As próximas seções com gráficos ApexCharts serão implementadas em breve:</p>
    <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-300">
        <li>Performance por Agente (tabela + gráfico barras)</li>
        <li>Tickets Criados vs Resolvidos (gráfico linha)</li>
        <li>Distribuição por Status/Prioridade/Categoria (gráficos pizza/barras)</li>
        <li>Análise Temporal (distribuição semanal, heatmap)</li>
    </ul>
</div>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
// Placeholder para scripts dos gráficos
console.log('📊 Relatórios carregados!');
console.log('KPIs:', <?= json_encode($kpis) ?>);
console.log('Performance Agentes:', <?= json_encode($performanceAgentes) ?>);
</script>

<?= $this->endSection() ?>
