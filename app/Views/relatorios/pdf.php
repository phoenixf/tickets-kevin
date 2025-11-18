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

        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            background: white;
            color: #1f2937;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4f46e5;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 8px;
            color: white;
        }

        .header h1 {
            color: white;
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: bold;
        }

        .header p {
            color: #e0e7ff;
            font-size: 13px;
            margin: 3px 0;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .kpi-card {
            border-left: 4px solid;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            background: #f9fafb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .kpi-card.indigo { border-left-color: #6366f1; }
        .kpi-card.green { border-left-color: #10b981; }
        .kpi-card.blue { border-left-color: #3b82f6; }
        .kpi-card.purple { border-left-color: #8b5cf6; }
        .kpi-card.yellow { border-left-color: #f59e0b; }

        .kpi-card h3 {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .kpi-card p {
            font-size: 22px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
            background: white;
        }

        .section h2 {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 12px;
            padding: 10px 12px;
            background: linear-gradient(to right, #f3f4f6, white);
            border-left: 4px solid #4f46e5;
            border-radius: 4px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        table th {
            background: linear-gradient(to bottom, #6366f1, #4f46e5);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
        }

        table td {
            padding: 8px;
            font-size: 11px;
            border: 1px solid #e5e7eb;
            background: white;
        }

        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        table tbody tr:hover {
            background: #f3f4f6;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            z-index: 1000;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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

            .header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .metric-box {
            border-left: 4px solid;
            padding: 12px;
            border-radius: 6px;
            background: #f9fafb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .metric-box.sla-green { border-left-color: #10b981; }
        .metric-box.sla-yellow { border-left-color: #f59e0b; }
        .metric-box.sla-red { border-left-color: #ef4444; }
        .metric-box.sla-purple { border-left-color: #8b5cf6; }

        .metric-box h4 {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .metric-box p {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .metric-box small {
            font-size: 9px;
            color: #6b7280;
        }

        .green { color: #10b981; }
        .yellow { color: #f59e0b; }
        .red { color: #ef4444; }
        .blue { color: #3b82f6; }
        .purple { color: #8b5cf6; }
        .indigo { color: #6366f1; }

        /* SVG Icons inline */
        .icon-ticket {
            display: inline-block;
            width: 14px;
            height: 14px;
            vertical-align: middle;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Imprimir / Salvar PDF</button>

    <div class="header">
        <h1>📊 Relatório de Tickets</h1>
        <p>Período: <?= date('d/m/Y', strtotime($periodo_inicio)) ?> até <?= date('d/m/Y', strtotime($periodo_fim)) ?></p>
        <p>Gerado em: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <!-- KPIs Principais -->
    <div class="kpis">
        <div class="kpi-card indigo">
            <h3>Total de Tickets</h3>
            <p class="indigo"><?= $kpis['total_tickets'] ?></p>
        </div>
        <div class="kpi-card green">
            <h3>Resolvidos</h3>
            <p class="green"><?= $kpis['tickets_resolvidos'] ?></p>
        </div>
        <div class="kpi-card blue">
            <h3>Tempo Médio</h3>
            <p class="blue">
                <?php
                $horas = floor($kpis['tempo_medio_resolucao'] / 60);
                $minutos = $kpis['tempo_medio_resolucao'] % 60;
                echo $horas . 'h ' . $minutos . 'm';
                ?>
            </p>
        </div>
        <div class="kpi-card purple">
            <h3>Taxa Resolução</h3>
            <p class="purple"><?= number_format($kpis['taxa_resolucao'], 1) ?>%</p>
        </div>
        <div class="kpi-card yellow">
            <h3>Abertos Agora</h3>
            <p class="yellow"><?= $kpis['tickets_abertos_agora'] ?></p>
        </div>
    </div>

    <!-- Métricas de SLA -->
    <div class="section">
        <h2>⏱️ Service Level Agreement (SLA)</h2>
        <div class="metric-grid">
            <div class="metric-box <?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'sla-green' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'sla-yellow' : 'sla-red') ?>">
                <h4>SLA Primeira Resposta</h4>
                <p class="<?= $slaMetrics['sla_compliance_primeira_resposta'] >= 90 ? 'green' : ($slaMetrics['sla_compliance_primeira_resposta'] >= 75 ? 'yellow' : 'red') ?>">
                    <?= $slaMetrics['sla_compliance_primeira_resposta'] ?>%
                </p>
                <small><?= $slaMetrics['primeira_resposta_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?> tickets</small>
            </div>
            <div class="metric-box <?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'sla-green' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'sla-yellow' : 'sla-red') ?>">
                <h4>SLA Resolução</h4>
                <p class="<?= $slaMetrics['sla_compliance_resolucao'] >= 85 ? 'green' : ($slaMetrics['sla_compliance_resolucao'] >= 70 ? 'yellow' : 'red') ?>">
                    <?= $slaMetrics['sla_compliance_resolucao'] ?>%
                </p>
                <small><?= $slaMetrics['resolucao_dentro_sla'] ?>/<?= $slaMetrics['total_tickets'] ?> tickets</small>
            </div>
            <div class="metric-box sla-purple">
                <h4>FCR Rate</h4>
                <p class="purple"><?= $fcrMetrics['fcr_rate'] ?>%</p>
                <small><?= $fcrMetrics['resolvidos_primeiro_contato'] ?> no 1º contato</small>
            </div>
        </div>
    </div>

    <!-- Performance por Agente -->
    <?php if (!empty($performanceAgentes)) : ?>
    <div class="section">
        <h2>👥 Performance por Agente</h2>
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
        <h2>📊 Distribuição por Status</h2>
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
        <h2>⚡ Distribuição por Prioridade</h2>
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
        <h2>🏷️ Distribuição por Categoria</h2>
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
