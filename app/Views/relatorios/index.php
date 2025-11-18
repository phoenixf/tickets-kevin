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

    <!-- Quick Filters (Filtros Rápidos) -->
    <div class="flex gap-2 mb-4 flex-wrap">
        <button onclick="aplicarFiltroRapido('hoje')" class="quick-filter-btn px-4 py-2 text-sm font-medium rounded-lg border-2 border-transparent bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
            Hoje
        </button>
        <button onclick="aplicarFiltroRapido('ontem')" class="quick-filter-btn px-4 py-2 text-sm font-medium rounded-lg border-2 border-transparent bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
            Ontem
        </button>
        <button onclick="aplicarFiltroRapido('ultimos7')" class="quick-filter-btn px-4 py-2 text-sm font-medium rounded-lg border-2 border-transparent bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
            Últimos 7 dias
        </button>
        <button onclick="aplicarFiltroRapido('ultimos30')" class="quick-filter-btn px-4 py-2 text-sm font-medium rounded-lg border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 transition-all duration-200">
            Últimos 30 dias
        </button>
        <button onclick="aplicarFiltroRapido('esteAno')" class="quick-filter-btn px-4 py-2 text-sm font-medium rounded-lg border-2 border-transparent bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
            Este Ano
        </button>
    </div>

    <!-- Filtros Avançados -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 transition-colors duration-200">
        <form method="GET" action="/relatorios" id="form-filtros" class="flex items-center gap-3 flex-wrap">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros:</span>

            <!-- Período -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Período:</label>
                <input type="date"
                       name="periodo_inicio"
                       value="<?= esc($filtros['periodo_inicio'] ?? date('Y-m-d', strtotime('-30 days'))) ?>"
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

<!-- SLA Metrics - Compactado -->
<div class="mb-8">
    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">⏱️ Service Level Agreement (SLA)</h2>

    <!-- KPIs de SLA - Grid Compacto -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- SLA Primeira Resposta -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 <?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'border-green-500' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'border-yellow-500' : 'border-red-500') ?> transition-colors duration-200">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase truncate">Primeira Resposta</dt>
                    <dd class="mt-1 text-2xl font-bold <?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'text-green-600 dark:text-green-400' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') ?>">
                        <?= $slaMetrics['sla_compliance_primeira_resposta'] ?>%
                    </dd>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5"><?= $slaMetrics['primeira_resposta_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?></p>
                </div>
                <svg class="w-10 h-10 flex-shrink-0 <?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'text-green-500' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'text-yellow-500' : 'text-red-500') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- SLA Resolução -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 <?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'border-green-500' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'border-yellow-500' : 'border-red-500') ?> transition-colors duration-200">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase truncate">Resolução</dt>
                    <dd class="mt-1 text-2xl font-bold <?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'text-green-600 dark:text-green-400' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') ?>">
                        <?= $slaMetrics['sla_compliance_resolucao'] ?>%
                    </dd>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5"><?= $slaMetrics['resolucao_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?></p>
                </div>
                <svg class="w-10 h-10 flex-shrink-0 <?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'text-green-500' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'text-yellow-500' : 'text-red-500') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- FCR (First Contact Resolution) -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 border-purple-500 transition-colors duration-200">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase truncate">FCR Rate</dt>
                    <dd class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400"><?= $fcrMetrics['fcr_rate'] ?>%</dd>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5"><?= $fcrMetrics['resolvidos_primeiro_contato'] ?> no 1º contato</p>
                </div>
                <svg class="w-10 h-10 flex-shrink-0 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Performance por Agente -->
<?php if (!empty($performanceAgentes)) : ?>
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6 transition-colors duration-200">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">👥 Performance por Agente</h3>

    <!-- Tabela -->
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agente</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Resolvidos</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pendentes</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Taxa Resolução</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tempo Médio</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reaberturas</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php foreach ($performanceAgentes as $agente) : ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"><?= esc($agente['agente_nome']) ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700 dark:text-gray-300"><?= $agente['total_atribuido'] ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-green-600 dark:text-green-400 font-semibold"><?= $agente['resolvidos'] ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-yellow-600 dark:text-yellow-400"><?= $agente['pendentes'] ?></td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-purple-600 dark:text-purple-400 font-semibold"><?= $agente['taxa_resolucao'] ?>%</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-blue-600 dark:text-blue-400">
                        <?php
                        $horas = floor($agente['tempo_medio_minutos'] / 60);
                        $minutos = $agente['tempo_medio_minutos'] % 60;
                        echo $horas . 'h ' . $minutos . 'm';
                        ?>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <span class="text-gray-700 dark:text-gray-300"><?= $agente['total_reaberturas'] ?></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(<?= $agente['taxa_reabertura'] ?>%)</span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Gráfico de Barras -->
    <div id="chartPerformanceAgentes"></div>
</div>
<?php endif ?>

<!-- Tickets Criados vs Resolvidos -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6 transition-colors duration-200">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📈 Tickets Criados vs Resolvidos</h3>
    <div id="chartTicketsPorPeriodo"></div>
</div>

<!-- Distribuições -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Status -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📊 Por Status</h3>
        <div id="chartStatus"></div>
    </div>

    <!-- Prioridade -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">⚡ Por Prioridade</h3>
        <div id="chartPrioridade"></div>
    </div>

    <!-- Categoria -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 transition-colors duration-200">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🏷️ Por Categoria</h3>
        <div id="chartCategoria"></div>
    </div>
</div>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
// Dados do backend
const kpis = <?= json_encode($kpis) ?>;
const performanceAgentes = <?= json_encode($performanceAgentes) ?>;
const ticketsPorPeriodo = <?= json_encode($ticketsPorPeriodo) ?>;
const distribuicaoStatus = <?= json_encode($distribuicaoStatus) ?>;
const distribuicaoPrioridade = <?= json_encode($distribuicaoPrioridade) ?>;
const distribuicaoCategoria = <?= json_encode($distribuicaoCategoria) ?>;

// Verificar dark mode
const isDark = document.documentElement.classList.contains('dark');
const textColor = isDark ? '#e5e7eb' : '#374151';
const gridColor = isDark ? '#374151' : '#e5e7eb';

// Cores para gráficos
const statusColors = {
    'novo': '#3b82f6',
    'em_andamento': '#f59e0b',
    'aguardando_resposta': '#8b5cf6',
    'resolvido': '#10b981',
    'fechado': '#6b7280'
};

// 1. Gráfico Performance por Agente (Barras Horizontais)
if (performanceAgentes.length > 0) {
    const optionsPerformance = {
        series: [{
            name: 'Resolvidos',
            data: performanceAgentes.map(a => a.resolvidos)
        }, {
            name: 'Pendentes',
            data: performanceAgentes.map(a => a.pendentes)
        }],
        chart: {
            type: 'bar',
            height: 300,
            stacked: true,
            background: 'transparent',
            toolbar: { show: true }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 4
            }
        },
        colors: ['#10b981', '#f59e0b'],
        xaxis: {
            categories: performanceAgentes.map(a => a.agente_nome),
            labels: { style: { colors: textColor } }
        },
        yaxis: {
            labels: { style: { colors: textColor } }
        },
        legend: {
            position: 'top',
            labels: { colors: textColor }
        },
        grid: {
            borderColor: gridColor
        },
        theme: {
            mode: isDark ? 'dark' : 'light'
        }
    };

    const chartPerformance = new ApexCharts(document.querySelector("#chartPerformanceAgentes"), optionsPerformance);
    chartPerformance.render();
}

// 2. Gráfico Tickets Criados vs Resolvidos (Linha)
const optionsTickets = {
    series: [{
        name: 'Criados',
        data: ticketsPorPeriodo.map(t => t.criados)
    }, {
        name: 'Resolvidos',
        data: ticketsPorPeriodo.map(t => t.resolvidos)
    }],
    chart: {
        type: 'line',
        height: 350,
        background: 'transparent',
        toolbar: { show: true },
        zoom: { enabled: true }
    },
    colors: ['#3b82f6', '#10b981'],
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    markers: {
        size: 4
    },
    xaxis: {
        categories: ticketsPorPeriodo.map(t => t.data),
        labels: {
            style: { colors: textColor },
            rotate: -45
        }
    },
    yaxis: {
        labels: { style: { colors: textColor } }
    },
    legend: {
        position: 'top',
        labels: { colors: textColor }
    },
    grid: {
        borderColor: gridColor
    },
    theme: {
        mode: isDark ? 'dark' : 'light'
    }
};

const chartTickets = new ApexCharts(document.querySelector("#chartTicketsPorPeriodo"), optionsTickets);
chartTickets.render();

// 3. Gráfico Status (Pizza)
const optionsStatus = {
    series: distribuicaoStatus.map(s => s.total),
    chart: {
        type: 'donut',
        height: 280,
        background: 'transparent'
    },
    labels: distribuicaoStatus.map(s => {
        const labels = {
            'novo': 'Novo',
            'em_andamento': 'Em Andamento',
            'aguardando_resposta': 'Aguardando',
            'resolvido': 'Resolvido',
            'fechado': 'Fechado'
        };
        return labels[s.status] || s.status;
    }),
    colors: distribuicaoStatus.map(s => statusColors[s.status] || '#6b7280'),
    legend: {
        position: 'bottom',
        labels: { colors: textColor }
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },
    theme: {
        mode: isDark ? 'dark' : 'light'
    }
};

const chartStatus = new ApexCharts(document.querySelector("#chartStatus"), optionsStatus);
chartStatus.render();

// 4. Gráfico Prioridade (Pizza)
const optionsPrioridade = {
    series: distribuicaoPrioridade.map(p => p.total),
    chart: {
        type: 'donut',
        height: 280,
        background: 'transparent'
    },
    labels: distribuicaoPrioridade.map(p => p.nome),
    colors: distribuicaoPrioridade.map(p => p.cor),
    legend: {
        position: 'bottom',
        labels: { colors: textColor }
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },
    theme: {
        mode: isDark ? 'dark' : 'light'
    }
};

const chartPrioridade = new ApexCharts(document.querySelector("#chartPrioridade"), optionsPrioridade);
chartPrioridade.render();

// 5. Gráfico Categoria (Pizza)
const optionsCategoria = {
    series: distribuicaoCategoria.map(c => c.total),
    chart: {
        type: 'donut',
        height: 280,
        background: 'transparent'
    },
    labels: distribuicaoCategoria.map(c => c.nome),
    colors: distribuicaoCategoria.map(c => c.cor),
    legend: {
        position: 'bottom',
        labels: { colors: textColor }
    },
    plotOptions: {
        pie: {
            donut: {
                size: '60%'
            }
        }
    },
    theme: {
        mode: isDark ? 'dark' : 'light'
    }
};

const chartCategoria = new ApexCharts(document.querySelector("#chartCategoria"), optionsCategoria);
chartCategoria.render();

console.log('📊 Relatórios carregados com gráficos!');

// Exportar PDF functionality
document.getElementById('export-btn').addEventListener('click', function() {
    // Capturar filtros atuais
    const form = document.getElementById('form-filtros');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    // Redirecionar para rota de exportação PDF
    window.location.href = '/relatorios/exportarPdf?' + params.toString();
});

// Quick Filters functionality
function aplicarFiltroRapido(periodo) {
    const form = document.getElementById('form-filtros');
    const periodoInicio = form.querySelector('[name="periodo_inicio"]');
    const periodoFim = form.querySelector('[name="periodo_fim"]');
    const hoje = new Date();
    const formatDate = (date) => date.toISOString().split('T')[0];

    // Remove active class from all buttons
    document.querySelectorAll('.quick-filter-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20', 'text-indigo-700', 'dark:text-indigo-300');
        btn.classList.add('border-transparent', 'bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
    });

    // Add active class to clicked button
    event.target.classList.remove('border-transparent', 'bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
    event.target.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20', 'text-indigo-700', 'dark:text-indigo-300');

    let inicio, fim = hoje;

    switch(periodo) {
        case 'hoje':
            inicio = hoje;
            break;
        case 'ontem':
            inicio = new Date(hoje);
            inicio.setDate(hoje.getDate() - 1);
            fim = new Date(inicio);
            break;
        case 'ultimos7':
            inicio = new Date(hoje);
            inicio.setDate(hoje.getDate() - 7);
            break;
        case 'ultimos30':
            inicio = new Date(hoje);
            inicio.setDate(hoje.getDate() - 30);
            break;
        case 'esteano':
            inicio = new Date(hoje.getFullYear(), 0, 1);
            break;
    }

    periodoInicio.value = formatDate(inicio);
    periodoFim.value = formatDate(fim);

    // Submit form
    form.submit();
}
</script>

<?= $this->endSection() ?>
