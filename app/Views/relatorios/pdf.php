<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Tickets - <?= date('d/m/Y') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1f2937;
            margin-bottom: 10px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .kpi-card {
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .kpi-card h3 {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .kpi-card p {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section h2 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th {
            background: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        table td {
            padding: 8px 10px;
            font-size: 12px;
            border: 1px solid #e5e7eb;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background: #4338ca;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                padding: 0;
            }

            .page-break {
                page-break-before: always;
            }
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .metric-box {
            border: 1px solid #e5e7eb;
            padding: 12px;
            border-radius: 6px;
        }

        .metric-box h4 {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .metric-box p {
            font-size: 20px;
            font-weight: bold;
        }

        .green { color: #10b981; }
        .yellow { color: #f59e0b; }
        .red { color: #ef4444; }
        .blue { color: #3b82f6; }
        .purple { color: #8b5cf6; }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Imprimir / Salvar PDF</button>

    <div class="header">
        <h1>Relatório de Tickets</h1>
        <p>Período: <?= date('d/m/Y', strtotime($periodo_inicio)) ?> até <?= date('d/m/Y', strtotime($periodo_fim)) ?></p>
        <p>Gerado em: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <!-- KPIs Principais -->
    <div class="kpis">
        <div class="kpi-card">
            <h3>Total de Tickets</h3>
            <p><?= $kpis['total_tickets'] ?></p>
        </div>
        <div class="kpi-card">
            <h3>Resolvidos</h3>
            <p class="green"><?= $kpis['tickets_resolvidos'] ?></p>
        </div>
        <div class="kpi-card">
            <h3>Tempo Médio</h3>
            <p class="blue">
                <?php
                $horas = floor($kpis['tempo_medio_resolucao'] / 60);
                $minutos = $kpis['tempo_medio_resolucao'] % 60;
                echo $horas . 'h ' . $minutos . 'm';
                ?>
            </p>
        </div>
        <div class="kpi-card">
            <h3>Taxa Resolução</h3>
            <p class="purple"><?= number_format($kpis['taxa_resolucao'], 1) ?>%</p>
        </div>
        <div class="kpi-card">
            <h3>Abertos Agora</h3>
            <p class="yellow"><?= $kpis['tickets_abertos_agora'] ?></p>
        </div>
    </div>

    <!-- Métricas de SLA -->
    <div class="section">
        <h2>Service Level Agreement (SLA)</h2>
        <div class="metric-grid">
            <div class="metric-box">
                <h4>SLA Primeira Resposta</h4>
                <p class="<?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'green' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'yellow' : 'red') ?>">
                    <?= $slaMetrics['sla_compliance_primeira_resposta'] ?>%
                </p>
                <small><?= $slaMetrics['primeira_resposta_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?> tickets</small>
            </div>
            <div class="metric-box">
                <h4>SLA Resolução</h4>
                <p class="<?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'green' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'yellow' : 'red') ?>">
                    <?= $slaMetrics['sla_compliance_resolucao'] ?>%
                </p>
                <small><?= $slaMetrics['resolucao_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?> tickets</small>
            </div>
            <div class="metric-box">
                <h4>FCR Rate</h4>
                <p class="purple"><?= $fcrMetrics['fcr_rate'] ?>%</p>
                <small><?= $fcrMetrics['resolvidos_primeiro_contato'] ?> no 1º contato</small>
            </div>
        </div>
    </div>

    <!-- Performance por Agente -->
    <?php if (!empty($performanceAgentes)) : ?>
    <div class="section">
        <h2>Performance por Agente</h2>
        <table>
            <thead>
                <tr>
                    <th>Agente</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;">Resolvidos</th>
                    <th style="text-align: center;">Pendentes</th>
                    <th style="text-align: center;">Taxa Resolução</th>
                    <th style="text-align: center;">Tempo Médio</th>
                    <th style="text-align: center;">Reaberturas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($performanceAgentes as $agente) : ?>
                <tr>
                    <td><?= esc($agente['agente_nome']) ?></td>
                    <td style="text-align: center;"><?= $agente['total_atribuido'] ?></td>
                    <td style="text-align: center; color: #10b981; font-weight: bold;"><?= $agente['resolvidos'] ?></td>
                    <td style="text-align: center; color: #f59e0b;"><?= $agente['pendentes'] ?></td>
                    <td style="text-align: center; color: #8b5cf6; font-weight: bold;"><?= $agente['taxa_resolucao'] ?>%</td>
                    <td style="text-align: center; color: #3b82f6;">
                        <?php
                        $horas = floor($agente['tempo_medio_minutos'] / 60);
                        $minutos = $agente['tempo_medio_minutos'] % 60;
                        echo $horas . 'h ' . $minutos . 'm';
                        ?>
                    </td>
                    <td style="text-align: center;"><?= $agente['total_reaberturas'] ?> (<?= $agente['taxa_reabertura'] ?>%)</td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>

    <!-- Distribuição por Status -->
    <div class="section">
        <h2>Distribuição por Status</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: right;">Percentual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_status = array_sum(array_column($distribuicaoStatus, 'total'));
                foreach ($distribuicaoStatus as $status) :
                    $labels = [
                        'novo' => 'Novo',
                        'em_andamento' => 'Em Andamento',
                        'em_progresso' => 'Em Progresso',
                        'aguardando_resposta' => 'Aguardando',
                        'pendente' => 'Pendente',
                        'resolvido' => 'Resolvido',
                        'fechado' => 'Fechado'
                    ];
                    $percentual = $total_status > 0 ? round(($status['total'] / $total_status) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?= $labels[$status['status']] ?? $status['status'] ?></td>
                    <td style="text-align: center;"><?= $status['total'] ?></td>
                    <td style="text-align: right;"><?= $percentual ?>%</td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Distribuição por Prioridade -->
    <div class="section">
        <h2>Distribuição por Prioridade</h2>
        <table>
            <thead>
                <tr>
                    <th>Prioridade</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: right;">Percentual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_prioridade = array_sum(array_column($distribuicaoPrioridade, 'total'));
                foreach ($distribuicaoPrioridade as $prioridade) :
                    $percentual = $total_prioridade > 0 ? round(($prioridade['total'] / $total_prioridade) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?= esc($prioridade['nome']) ?></td>
                    <td style="text-align: center;"><?= $prioridade['total'] ?></td>
                    <td style="text-align: right;"><?= $percentual ?>%</td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Distribuição por Categoria -->
    <div class="section">
        <h2>Distribuição por Categoria</h2>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: right;">Percentual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_categoria = array_sum(array_column($distribuicaoCategoria, 'total'));
                foreach ($distribuicaoCategoria as $categoria) :
                    $percentual = $total_categoria > 0 ? round(($categoria['total'] / $total_categoria) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?= esc($categoria['nome']) ?></td>
                    <td style="text-align: center;"><?= $categoria['total'] ?></td>
                    <td style="text-align: right;"><?= $percentual ?>%</td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Sistema de Tickets - Relatório Automático</p>
        <p>Documento gerado automaticamente. Para salvar como PDF, use Ctrl+P ou Cmd+P e escolha "Salvar como PDF".</p>
    </div>

    <script>
        // Auto-print quando carregar a página (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
