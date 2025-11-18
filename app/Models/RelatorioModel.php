<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatorioModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    /**
     * Retorna KPIs principais do sistema
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getKPIs($filtros = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tickets');

        // Aplicar filtros de período
        if (!empty($filtros['periodo_inicio'])) {
            $builder->where('tickets.criado_em >=', $filtros['periodo_inicio']);
        }
        if (!empty($filtros['periodo_fim'])) {
            $builder->where('tickets.criado_em <=', $filtros['periodo_fim']);
        }

        // Aplicar filtros opcionais
        if (!empty($filtros['agente_id'])) {
            $builder->where('tickets.responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $builder->where('tickets.categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $builder->where('tickets.prioridade_id', $filtros['prioridade_id']);
        }

        // Total de tickets
        $total_tickets = $builder->countAllResults(false);

        // Tickets resolvidos
        $tickets_resolvidos = $builder->whereIn('status', ['resolvido', 'fechado'])
            ->countAllResults(false);

        // Tempo médio de resolução (em minutos)
        $tempo_medio_query = $db->table('tickets')
            ->select('AVG(TIMESTAMPDIFF(MINUTE, criado_em, resolvido_em)) as tempo_medio');

        if (!empty($filtros['periodo_inicio'])) {
            $tempo_medio_query->where('criado_em >=', $filtros['periodo_inicio']);
        }
        if (!empty($filtros['periodo_fim'])) {
            $tempo_medio_query->where('criado_em <=', $filtros['periodo_fim']);
        }
        if (!empty($filtros['agente_id'])) {
            $tempo_medio_query->where('responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $tempo_medio_query->where('categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $tempo_medio_query->where('prioridade_id', $filtros['prioridade_id']);
        }

        $tempo_medio_query->where('resolvido_em IS NOT NULL');
        $tempo_medio_result = $tempo_medio_query->get()->getRowArray();
        $tempo_medio_resolucao = $tempo_medio_result['tempo_medio'] ? round($tempo_medio_result['tempo_medio'], 2) : 0;

        // Taxa de resolução (%)
        $taxa_resolucao = $total_tickets > 0
            ? round(($tickets_resolvidos / $total_tickets) * 100, 2)
            : 0;

        // Tickets abertos agora (status != resolvido e fechado)
        $builder_abertos = $db->table('tickets')
            ->whereNotIn('status', ['resolvido', 'fechado']);

        if (!empty($filtros['agente_id'])) {
            $builder_abertos->where('responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $builder_abertos->where('categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $builder_abertos->where('prioridade_id', $filtros['prioridade_id']);
        }

        $tickets_abertos_agora = $builder_abertos->countAllResults();

        return [
            'total_tickets' => $total_tickets,
            'tickets_resolvidos' => $tickets_resolvidos,
            'tempo_medio_resolucao' => $tempo_medio_resolucao,
            'taxa_resolucao' => $taxa_resolucao,
            'tickets_abertos_agora' => $tickets_abertos_agora
        ];
    }

    /**
     * Retorna performance de cada agente
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getPerformanceAgentes($filtros = [])
    {
        $db = \Config\Database::connect();

        // Query principal com subqueries para reabertura
        $sql = "
            SELECT
                usuarios.id as agente_id,
                usuarios.nome as agente_nome,
                COUNT(tickets.id) as total_atribuido,
                SUM(CASE WHEN tickets.status IN ('resolvido', 'fechado') THEN 1 ELSE 0 END) as resolvidos,
                SUM(CASE WHEN tickets.status NOT IN ('resolvido', 'fechado') THEN 1 ELSE 0 END) as pendentes,
                AVG(
                    CASE
                        WHEN tickets.resolvido_em IS NOT NULL
                        THEN TIMESTAMPDIFF(MINUTE, tickets.criado_em, tickets.resolvido_em)
                        ELSE NULL
                    END
                ) as tempo_medio_minutos,
                ROUND(
                    (SUM(CASE WHEN tickets.status IN ('resolvido', 'fechado') THEN 1 ELSE 0 END) / COUNT(tickets.id)) * 100,
                    2
                ) as taxa_resolucao,
                (
                    SELECT COUNT(DISTINCT h1.ticket_id)
                    FROM historico_tickets h1
                    WHERE h1.campo = 'status'
                    AND h1.valor_antigo IN ('resolvido', 'fechado')
                    AND h1.valor_novo NOT IN ('resolvido', 'fechado')
                    AND h1.ticket_id IN (
                        SELECT id FROM tickets WHERE responsavel_id = usuarios.id
                    )
                ) as total_reaberturas
            FROM usuarios
            LEFT JOIN tickets ON tickets.responsavel_id = usuarios.id
        ";

        $where_conditions = [];
        $params = [];

        // Filtros de período
        if (!empty($filtros['periodo_inicio'])) {
            $where_conditions[] = "tickets.criado_em >= ?";
            $params[] = $filtros['periodo_inicio'];
        }
        if (!empty($filtros['periodo_fim'])) {
            $where_conditions[] = "tickets.criado_em <= ?";
            $params[] = $filtros['periodo_fim'];
        }

        // Filtro de agente específico
        if (!empty($filtros['agente_id'])) {
            $where_conditions[] = "usuarios.id = ?";
            $params[] = $filtros['agente_id'];
        }

        // Filtros opcionais
        if (!empty($filtros['categoria_id'])) {
            $where_conditions[] = "tickets.categoria_id = ?";
            $params[] = $filtros['categoria_id'];
        }
        if (!empty($filtros['prioridade_id'])) {
            $where_conditions[] = "tickets.prioridade_id = ?";
            $params[] = $filtros['prioridade_id'];
        }

        // Somente agentes (usuários com perfil agente ou admin)
        $where_conditions[] = "usuarios.id IN (
            SELECT user_id FROM auth_groups_users
            WHERE `group` IN ('admin', 'agente')
        )";

        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }

        $sql .= " GROUP BY usuarios.id, usuarios.nome";
        $sql .= " HAVING total_atribuido > 0";
        $sql .= " ORDER BY resolvidos DESC, tempo_medio_minutos ASC";

        $query = $db->query($sql, $params);
        $resultados = $query->getResultArray();

        // Calcular taxa de reabertura
        foreach ($resultados as &$resultado) {
            $resultado['tempo_medio_minutos'] = $resultado['tempo_medio_minutos']
                ? round($resultado['tempo_medio_minutos'], 2)
                : 0;

            $resultado['taxa_reabertura'] = $resultado['resolvidos'] > 0
                ? round(($resultado['total_reaberturas'] / $resultado['resolvidos']) * 100, 2)
                : 0;

            // Converter para int
            $resultado['agente_id'] = (int) $resultado['agente_id'];
            $resultado['total_atribuido'] = (int) $resultado['total_atribuido'];
            $resultado['resolvidos'] = (int) $resultado['resolvidos'];
            $resultado['pendentes'] = (int) $resultado['pendentes'];
            $resultado['total_reaberturas'] = (int) $resultado['total_reaberturas'];
        }

        return $resultados;
    }

    /**
     * Retorna tickets criados e resolvidos por período
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @param string $agrupamento 'day', 'week', 'month'
     * @return array
     */
    public function getTicketsPorPeriodo($filtros = [], $agrupamento = 'day')
    {
        $db = \Config\Database::connect();

        // Definir formato de data baseado no agrupamento
        $date_format = match($agrupamento) {
            'week' => '%Y-%u',  // Ano-Semana
            'month' => '%Y-%m', // Ano-Mês
            default => '%Y-%m-%d' // Dia (padrão)
        };

        // Query para tickets criados
        $sql_criados = "
            SELECT
                DATE_FORMAT(criado_em, '{$date_format}') as data,
                COUNT(*) as criados
            FROM tickets
        ";

        // Query para tickets resolvidos
        $sql_resolvidos = "
            SELECT
                DATE_FORMAT(resolvido_em, '{$date_format}') as data,
                COUNT(*) as resolvidos
            FROM tickets
            WHERE resolvido_em IS NOT NULL
        ";

        $where_conditions = [];
        $params_criados = [];
        $params_resolvidos = [];

        // Filtros de período para criados
        if (!empty($filtros['periodo_inicio'])) {
            $where_conditions[] = "criado_em >= ?";
            $params_criados[] = $filtros['periodo_inicio'];
        }
        if (!empty($filtros['periodo_fim'])) {
            $where_conditions[] = "criado_em <= ?";
            $params_criados[] = $filtros['periodo_fim'];
        }

        // Filtros opcionais
        $optional_conditions = [];
        $optional_params = [];

        if (!empty($filtros['agente_id'])) {
            $optional_conditions[] = "responsavel_id = ?";
            $optional_params[] = $filtros['agente_id'];
        }
        if (!empty($filtros['categoria_id'])) {
            $optional_conditions[] = "categoria_id = ?";
            $optional_params[] = $filtros['categoria_id'];
        }
        if (!empty($filtros['prioridade_id'])) {
            $optional_conditions[] = "prioridade_id = ?";
            $optional_params[] = $filtros['prioridade_id'];
        }

        // Aplicar condições aos criados
        $all_conditions_criados = array_merge($where_conditions, $optional_conditions);
        if (!empty($all_conditions_criados)) {
            $sql_criados .= " WHERE " . implode(' AND ', $all_conditions_criados);
        }
        $sql_criados .= " GROUP BY data ORDER BY data ASC";

        // Filtros de período para resolvidos
        $where_conditions_resolvidos = [];
        if (!empty($filtros['periodo_inicio'])) {
            $where_conditions_resolvidos[] = "resolvido_em >= ?";
            $params_resolvidos[] = $filtros['periodo_inicio'];
        }
        if (!empty($filtros['periodo_fim'])) {
            $where_conditions_resolvidos[] = "resolvido_em <= ?";
            $params_resolvidos[] = $filtros['periodo_fim'];
        }

        // Aplicar condições aos resolvidos
        $all_conditions_resolvidos = array_merge($where_conditions_resolvidos, $optional_conditions);
        if (!empty($all_conditions_resolvidos)) {
            $sql_resolvidos .= " AND " . implode(' AND ', $all_conditions_resolvidos);
        }
        $sql_resolvidos .= " GROUP BY data ORDER BY data ASC";

        // Executar queries
        $params_criados = array_merge($params_criados, $optional_params);
        $params_resolvidos = array_merge($params_resolvidos, $optional_params);

        $criados = $db->query($sql_criados, $params_criados)->getResultArray();
        $resolvidos = $db->query($sql_resolvidos, $params_resolvidos)->getResultArray();

        // Combinar resultados
        $resultado = [];
        $datas = [];

        // Adicionar todas as datas
        foreach ($criados as $item) {
            $datas[$item['data']] = ['data' => $item['data'], 'criados' => (int) $item['criados'], 'resolvidos' => 0];
        }
        foreach ($resolvidos as $item) {
            if (isset($datas[$item['data']])) {
                $datas[$item['data']]['resolvidos'] = (int) $item['resolvidos'];
            } else {
                $datas[$item['data']] = ['data' => $item['data'], 'criados' => 0, 'resolvidos' => (int) $item['resolvidos']];
            }
        }

        // Ordenar por data
        ksort($datas);

        return array_values($datas);
    }

    /**
     * Retorna distribuição de tickets por status
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getDistribuicaoPorStatus($filtros = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tickets')
            ->select('status, COUNT(*) as total')
            ->groupBy('status');

        // Aplicar filtros
        if (!empty($filtros['periodo_inicio'])) {
            $builder->where('criado_em >=', $filtros['periodo_inicio']);
        }
        if (!empty($filtros['periodo_fim'])) {
            $builder->where('criado_em <=', $filtros['periodo_fim']);
        }
        if (!empty($filtros['agente_id'])) {
            $builder->where('responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $builder->where('categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $builder->where('prioridade_id', $filtros['prioridade_id']);
        }

        $resultados = $builder->get()->getResultArray();

        // Converter total para int
        foreach ($resultados as &$item) {
            $item['total'] = (int) $item['total'];
        }

        return $resultados;
    }

    /**
     * Retorna distribuição de tickets por prioridade
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getDistribuicaoPorPrioridade($filtros = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tickets')
            ->select('prioridades.id, prioridades.nome, prioridades.cor, COUNT(tickets.id) as total')
            ->join('prioridades', 'prioridades.id = tickets.prioridade_id', 'left')
            ->groupBy('prioridades.id, prioridades.nome, prioridades.cor')
            ->orderBy('prioridades.nivel', 'DESC');

        // Aplicar filtros
        if (!empty($filtros['periodo_inicio'])) {
            $builder->where('tickets.criado_em >=', $filtros['periodo_inicio']);
        }
        if (!empty($filtros['periodo_fim'])) {
            $builder->where('tickets.criado_em <=', $filtros['periodo_fim']);
        }
        if (!empty($filtros['agente_id'])) {
            $builder->where('tickets.responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $builder->where('tickets.categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $builder->where('tickets.prioridade_id', $filtros['prioridade_id']);
        }

        $resultados = $builder->get()->getResultArray();

        // Converter total para int
        foreach ($resultados as &$item) {
            $item['id'] = (int) $item['id'];
            $item['total'] = (int) $item['total'];
        }

        return $resultados;
    }

    /**
     * Retorna distribuição de tickets por categoria
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getDistribuicaoPorCategoria($filtros = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tickets')
            ->select('categorias.id, categorias.nome, categorias.cor, COUNT(tickets.id) as total')
            ->join('categorias', 'categorias.id = tickets.categoria_id', 'left')
            ->groupBy('categorias.id, categorias.nome, categorias.cor')
            ->orderBy('total', 'DESC');

        // Aplicar filtros
        if (!empty($filtros['periodo_inicio'])) {
            $builder->where('tickets.criado_em >=', $filtros['periodo_inicio']);
        }
        if (!empty($filtros['periodo_fim'])) {
            $builder->where('tickets.criado_em <=', $filtros['periodo_fim']);
        }
        if (!empty($filtros['agente_id'])) {
            $builder->where('tickets.responsavel_id', $filtros['agente_id']);
        }
        if (!empty($filtros['categoria_id'])) {
            $builder->where('tickets.categoria_id', $filtros['categoria_id']);
        }
        if (!empty($filtros['prioridade_id'])) {
            $builder->where('tickets.prioridade_id', $filtros['prioridade_id']);
        }

        $resultados = $builder->get()->getResultArray();

        // Converter valores e tratar categorias nulas
        foreach ($resultados as &$item) {
            if ($item['id'] === null) {
                $item['id'] = 0;
                $item['nome'] = 'Sem Categoria';
                $item['cor'] = '#999999';
            } else {
                $item['id'] = (int) $item['id'];
            }
            $item['total'] = (int) $item['total'];
        }

        return $resultados;
    }

    /**
     * Retorna métricas de SLA
     *
     * @param array $filtros [periodo_inicio, periodo_fim, agente_id, categoria_id, prioridade_id]
     * @return array
     */
    public function getSLAMetrics($filtros = [])
    {
        $db = \Config\Database::connect();

        // Query para calcular compliance de SLA
        $sql = "
            SELECT
                COUNT(tickets.id) as total_tickets,
                SUM(CASE
                    WHEN tickets.primeira_resposta_em IS NOT NULL
                    AND TIMESTAMPDIFF(MINUTE, tickets.criado_em, tickets.primeira_resposta_em) <= sla.tempo_primeira_resposta_minutos
                    THEN 1 ELSE 0
                END) as primeira_resposta_dentro_sla,
                SUM(CASE
                    WHEN tickets.resolvido_em IS NOT NULL
                    AND TIMESTAMPDIFF(MINUTE, tickets.criado_em, tickets.resolvido_em) <= sla.tempo_resolucao_minutos
                    THEN 1 ELSE 0
                END) as resolucao_dentro_sla,
                AVG(CASE
                    WHEN tickets.primeira_resposta_em IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, tickets.criado_em, tickets.primeira_resposta_em)
                    ELSE NULL
                END) as tempo_medio_primeira_resposta,
                COUNT(CASE WHEN tickets.primeira_resposta_em IS NULL AND tickets.status NOT IN ('resolvido', 'fechado') THEN 1 END) as sem_primeira_resposta
            FROM tickets
            LEFT JOIN sla_configuracoes sla ON sla.prioridade_id = tickets.prioridade_id
        ";

        $where_conditions = [];
        $params = [];

        if (!empty($filtros['periodo_inicio'])) {
            $where_conditions[] = "tickets.criado_em >= ?";
            $params[] = $filtros['periodo_inicio'];
        }
        if (!empty($filtros['periodo_fim'])) {
            $where_conditions[] = "tickets.criado_em <= ?";
            $params[] = $filtros['periodo_fim'];
        }
        if (!empty($filtros['agente_id'])) {
            $where_conditions[] = "tickets.responsavel_id = ?";
            $params[] = $filtros['agente_id'];
        }
        if (!empty($filtros['categoria_id'])) {
            $where_conditions[] = "tickets.categoria_id = ?";
            $params[] = $filtros['categoria_id'];
        }
        if (!empty($filtros['prioridade_id'])) {
            $where_conditions[] = "tickets.prioridade_id = ?";
            $params[] = $filtros['prioridade_id'];
        }

        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(' AND ', $where_conditions);
        }

        $result = $db->query($sql, $params)->getRowArray();

        // Calcular percentuais
        $total = $result['total_tickets'] ?: 1;
        $sla_compliance_primeira_resposta = round(($result['primeira_resposta_dentro_sla'] / $total) * 100, 1);
        $sla_compliance_resolucao = round(($result['resolucao_dentro_sla'] / $total) * 100, 1);

        return [
            'total_tickets' => (int) $result['total_tickets'],
            'primeira_resposta_dentro_sla' => (int) $result['primeira_resposta_dentro_sla'],
            'resolucao_dentro_sla' => (int) $result['resolucao_dentro_sla'],
            'sla_compliance_primeira_resposta' => $sla_compliance_primeira_resposta,
            'sla_compliance_resolucao' => $sla_compliance_resolucao,
            'tempo_medio_primeira_resposta' => round($result['tempo_medio_primeira_resposta'] ?: 0, 2),
            'sem_primeira_resposta' => (int) $result['sem_primeira_resposta']
        ];
    }

    /**
     * Retorna tickets próximos ao vencimento do SLA (crítico para ação imediata)
     *
     * @param int $limit
     * @return array
     */
    public function getTicketsProximosVencimento($limit = 10)
    {
        $db = \Config\Database::connect();

        $sql = "
            SELECT
                tickets.id,
                tickets.titulo,
                tickets.status,
                tickets.criado_em,
                tickets.primeira_resposta_em,
                prioridades.nome as prioridade_nome,
                prioridades.cor as prioridade_cor,
                categorias.nome as categoria_nome,
                usuarios.nome as responsavel_nome,
                sla.tempo_primeira_resposta_minutos,
                sla.tempo_resolucao_minutos,
                CASE
                    WHEN tickets.primeira_resposta_em IS NULL
                    THEN TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW())
                    ELSE NULL
                END as minutos_sem_resposta,
                CASE
                    WHEN tickets.resolvido_em IS NULL AND tickets.status NOT IN ('resolvido', 'fechado')
                    THEN TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW())
                    ELSE NULL
                END as minutos_aberto,
                CASE
                    WHEN tickets.primeira_resposta_em IS NULL
                    THEN GREATEST(0, CAST(sla.tempo_primeira_resposta_minutos AS SIGNED) - TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()))
                    ELSE NULL
                END as minutos_ate_vencer_primeira_resposta,
                CASE
                    WHEN tickets.resolvido_em IS NULL AND tickets.status NOT IN ('resolvido', 'fechado')
                    THEN GREATEST(0, CAST(sla.tempo_resolucao_minutos AS SIGNED) - TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()))
                    ELSE NULL
                END as minutos_ate_vencer_resolucao
            FROM tickets
            LEFT JOIN prioridades ON prioridades.id = tickets.prioridade_id
            LEFT JOIN categorias ON categorias.id = tickets.categoria_id
            LEFT JOIN usuarios ON usuarios.id = tickets.responsavel_id
            LEFT JOIN sla_configuracoes sla ON sla.prioridade_id = tickets.prioridade_id
            WHERE tickets.status NOT IN ('resolvido', 'fechado')
            AND (
                (tickets.primeira_resposta_em IS NULL AND TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()) > CAST(sla.tempo_primeira_resposta_minutos * 0.7 AS SIGNED))
                OR
                (tickets.resolvido_em IS NULL AND TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()) > CAST(sla.tempo_resolucao_minutos * 0.7 AS SIGNED))
            )
            ORDER BY
                CASE
                    WHEN tickets.primeira_resposta_em IS NULL
                    THEN GREATEST(0, CAST(sla.tempo_primeira_resposta_minutos AS SIGNED) - TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()))
                    ELSE GREATEST(0, CAST(sla.tempo_resolucao_minutos AS SIGNED) - TIMESTAMPDIFF(MINUTE, tickets.criado_em, NOW()))
                END ASC
            LIMIT ?
        ";

        $result = $db->query($sql, [$limit])->getResultArray();

        // Processar resultados
        foreach ($result as &$ticket) {
            $ticket['id'] = (int) $ticket['id'];
            $ticket['minutos_sem_resposta'] = (int) $ticket['minutos_sem_resposta'];
            $ticket['minutos_aberto'] = (int) $ticket['minutos_aberto'];
            $ticket['minutos_ate_vencer_primeira_resposta'] = (int) $ticket['minutos_ate_vencer_primeira_resposta'];
            $ticket['minutos_ate_vencer_resolucao'] = (int) $ticket['minutos_ate_vencer_resolucao'];

            // Determinar urgência (vermelho se < 0, amarelo se < 30% do tempo)
            $ticket['urgencia'] = 'normal';
            if ($ticket['minutos_ate_vencer_primeira_resposta'] < 0 || $ticket['minutos_ate_vencer_resolucao'] < 0) {
                $ticket['urgencia'] = 'vencido';
            } elseif ($ticket['minutos_ate_vencer_primeira_resposta'] < ($ticket['tempo_primeira_resposta_minutos'] * 0.3)) {
                $ticket['urgencia'] = 'critico';
            }
        }

        return $result;
    }

    /**
     * Retorna First Contact Resolution (FCR)
     *
     * @param array $filtros
     * @return array
     */
    public function getFirstContactResolution($filtros = [])
    {
        $db = \Config\Database::connect();

        // Tickets resolvidos com apenas 1 comentário (do agente) = FCR
        $sql = "
            SELECT
                COUNT(DISTINCT tickets.id) as total_resolvidos,
                COUNT(DISTINCT CASE
                    WHEN comentarios_count.total <= 1 THEN tickets.id
                END) as resolvidos_primeiro_contato
            FROM tickets
            LEFT JOIN (
                SELECT ticket_id, COUNT(*) as total
                FROM comentarios
                WHERE usuario_id != (SELECT usuario_id FROM tickets WHERE id = comentarios.ticket_id)
                GROUP BY ticket_id
            ) comentarios_count ON comentarios_count.ticket_id = tickets.id
            WHERE tickets.status IN ('resolvido', 'fechado')
        ";

        $where_conditions = [];
        $params = [];

        if (!empty($filtros['periodo_inicio'])) {
            $where_conditions[] = "tickets.criado_em >= ?";
            $params[] = $filtros['periodo_inicio'];
        }
        if (!empty($filtros['periodo_fim'])) {
            $where_conditions[] = "tickets.criado_em <= ?";
            $params[] = $filtros['periodo_fim'];
        }
        if (!empty($filtros['agente_id'])) {
            $where_conditions[] = "tickets.responsavel_id = ?";
            $params[] = $filtros['agente_id'];
        }

        if (!empty($where_conditions)) {
            $sql .= " AND " . implode(' AND ', $where_conditions);
        }

        $result = $db->query($sql, $params)->getRowArray();

        $total = $result['total_resolvidos'] ?: 1;
        $fcr_rate = round(($result['resolvidos_primeiro_contato'] / $total) * 100, 1);

        return [
            'total_resolvidos' => (int) $result['total_resolvidos'],
            'resolvidos_primeiro_contato' => (int) $result['resolvidos_primeiro_contato'],
            'fcr_rate' => $fcr_rate
        ];
    }
}
