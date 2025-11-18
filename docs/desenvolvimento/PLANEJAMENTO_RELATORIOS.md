# 📊 Planejamento: Página de Relatórios e Métricas

**Status**: 🟡 Planejamento (Brainstorm)
**Data**: 2025-11-18
**Objetivo**: Criar página de relatórios visuais completos com foco em performance de agentes

---

## 🎯 Objetivo Geral

Criar uma página de relatórios que permita:
- **Gestores**: Avaliar performance da equipe e identificar gargalos
- **Agentes**: Acompanhar sua própria produtividade
- **Tomada de decisão**: Baseada em dados reais e visuais

---

## 📋 Estrutura Proposta da Página

### 1️⃣ **Filtros Globais** (Topo da Página)

```
┌────────────────────────────────────────────────────────┐
│ 📊 Relatórios e Métricas                               │
│                                                         │
│ [Período ▼] [Agente ▼] [Categoria ▼] [Prioridade ▼]   │
│                                        [🔄] [📥 Export]│
└────────────────────────────────────────────────────────┘
```

**Filtros:**
- **Período**:
  - Hoje
  - Últimos 7 dias ⭐ (padrão)
  - Últimos 30 dias
  - Últimos 90 dias
  - Este mês
  - Mês passado
  - Intervalo personalizado (date picker)

- **Agente**:
  - Todos ⭐ (padrão para admin)
  - João Silva
  - Maria Santos
  - Pedro Costa

- **Categoria**: Multi-select (todas por padrão)
- **Prioridade**: Multi-select (todas por padrão)

---

## 📊 Seções e Métricas

### **SEÇÃO 1: Visão Geral - KPIs Principais**

**Layout**: 5 cards na horizontal

```
┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│  Total   │ │ Resolv.  │ │  Tempo   │ │   Taxa   │ │  Abertos │
│ Tickets  │ │  Hoje    │ │  Médio   │ │ Resolução│ │   Agora  │
│   142    │ │    23    │ │  4h 32m  │ │   87%    │ │    18    │
│ +12% ↗   │ │  +5  ↗   │ │  -15m ↘  │ │  +2% ↗   │ │   -3  ↘  │
└──────────┘ └──────────┘ └──────────┘ └──────────┘ └──────────┘
```

**Métricas dos Cards:**

1. **Total de Tickets** (período selecionado)
   - Valor absoluto
   - Comparação com período anterior (%)
   - Indicador visual: ↗ ↘ →

2. **Tickets Resolvidos** (período)
   - Quantidade
   - Comparação período anterior
   - Mini-gráfico sparkline (últimos 7 dias)

3. **Tempo Médio de Resolução**
   - Tempo em horas/minutos
   - Comparação período anterior
   - Código de cores: verde (<4h), amarelo (4-8h), vermelho (>8h)

4. **Taxa de Resolução**
   - Percentual (resolvidos/total)
   - Meta: 85%
   - Barra de progresso

5. **Tickets Abertos Agora**
   - Quantidade atual
   - Por status: novo, em progresso, pendente
   - Mini indicador de urgência (quantos são críticos)

---

### **SEÇÃO 2: Performance dos Agentes**

**Layout**: Tabela + Gráfico de barras

```
┌────────────────────────────────────────────────────────────────┐
│ 👥 Performance por Agente                        [Ver Detalhes]│
├───────┬──────┬──────┬──────┬──────┬──────┬──────┬─────────────┤
│Agente │Total │Resolv│Pend. │Tempo │Taxa  │Reopn.│ Ações       │
├───────┼──────┼──────┼──────┼──────┼──────┼──────┼─────────────┤
│João   │  45  │  38  │  7   │4h32m │ 84%  │ 2.1% │ [Detalhes]  │
│Maria  │  52  │  44  │  8   │3h15m │ 85%  │ 1.8% │ [Detalhes]  │
│Pedro  │  38  │  30  │  8   │5h10m │ 79%  │ 3.2% │ [Detalhes]  │
├───────┴──────┴──────┴──────┴──────┴──────┴──────┴─────────────┤
│ 📊 Gráfico de Barras: Tickets Atribuídos vs Resolvidos        │
│ [Gráfico visual lado a lado por agente]                        │
└────────────────────────────────────────────────────────────────┘
```

**Métricas da Tabela:**

1. **Total Atribuído** - Tickets sob responsabilidade do agente
2. **Resolvidos** - Tickets fechados com sucesso
3. **Pendentes** - Soma de: novo + em progresso + pendente
4. **Tempo Médio** - Da atribuição até resolução
5. **Taxa de Resolução** - Resolvidos/Total (%)
6. **Taxa de Reabertura** - Tickets reabertos após resolver (indicador de qualidade)

**Gráfico de Barras Agrupadas:**
- Eixo X: Agentes
- Eixo Y: Quantidade de tickets
- Barras: Atribuídos (azul) vs Resolvidos (verde)

---

### **SEÇÃO 3: Análise Temporal**

**Layout**: Grid 2 colunas

```
┌─────────────────────────────┬──────────────────────────────┐
│ 📈 Tickets Criados vs       │ 📅 Distribuição Semanal      │
│    Resolvidos (30 dias)     │                              │
│ [Gráfico de Linha/Área]     │ [Gráfico Barras por dia]     │
│                             │                              │
│ ― Criados  ― Resolvidos     │ Seg Ter Qua Qui Sex Sab Dom  │
│                             │  12  18  15  20  16   5   3  │
└─────────────────────────────┴──────────────────────────────┘
```

**3.1 Tickets Criados vs Resolvidos (Linha do Tempo)**
- Gráfico de linha ou área
- 2 séries: criados (azul) e resolvidos (verde)
- Período configurável pelos filtros
- Identifica se equipe está "em dia" ou acumulando backlog

**3.2 Distribuição Semanal**
- Barras verticais por dia da semana
- Identifica padrões (ex: segunda-feira sempre tem pico)
- Útil para dimensionar equipe

**Métricas Adicionais Temporais:**

3.3 **Tickets por Mês (Últimos 12 meses)**
- Gráfico de barras empilhadas
- Cores por status final (resolvido, fechado, etc)
- Identifica sazonalidade

3.4 **Heatmap: Dia da Semana vs Hora do Dia**
```
        00 02 04 06 08 10 12 14 16 18 20 22
Seg     ░░ ░░ ░░ ▓▓ ██ ██ ██ ██ ██ ▓▓ ░░ ░░
Ter     ░░ ░░ ░░ ▓▓ ██ ██ ██ ██ ██ ▓▓ ░░ ░░
Qua     ░░ ░░ ░░ ▓▓ ██ ██ ██ ██ ██ ▓▓ ░░ ░░
Qui     ░░ ░░ ░░ ▓▓ ██ ██ ██ ██ ██ ▓▓ ░░ ░░
Sex     ░░ ░░ ░░ ▓▓ ██ ██ ██ ▓▓ ▓▓ ░░ ░░ ░░
Sab     ░░ ░░ ░░ ░░ ░░ ▓▓ ▓▓ ░░ ░░ ░░ ░░ ░░
Dom     ░░ ░░ ░░ ░░ ░░ ░░ ░░ ░░ ░░ ░░ ░░ ░░

██ = Muito Alto  ▓▓ = Alto  ▒▒ = Médio  ░░ = Baixo
```
- Identifica horários de pico
- Útil para escalar plantões

---

### **SEÇÃO 4: Distribuição por Categoria e Prioridade**

**Layout**: Grid 2 colunas

```
┌─────────────────────────────┬──────────────────────────────┐
│ 📂 Tickets por Categoria    │ ⚡ Tickets por Prioridade    │
│                             │                              │
│ Suporte Técnico    ████ 45% │      [Gráfico Pizza]         │
│ Financeiro         ██   18% │                              │
│ Comercial          ██   15% │  Crítica  12%  🔴           │
│ RH                 █    12% │  Alta     28%  🟠           │
│ Infraestrutura     █    10% │  Normal   45%  🟡           │
│                             │  Baixa    15%  🟢           │
└─────────────────────────────┴──────────────────────────────┘
```

**4.1 Por Categoria**
- Gráfico de barras horizontais
- Ordenado por quantidade (decrescente)
- Mostra % do total
- Clicável para filtrar

**4.2 Por Prioridade**
- Gráfico de pizza/donut
- Cores padrão: 🔴 crítica, 🟠 alta, 🟡 normal, 🟢 baixa
- Mostra quantidade + percentual
- Destaque para crítica/alta

---

### **SEÇÃO 5: Tempo de Resposta e Resolução**

**Layout**: Grid 2 colunas

```
┌─────────────────────────────┬──────────────────────────────┐
│ ⏱️ Tempo Médio de Resposta  │ ⏱️ Tempo Médio de Resolução │
│   (Primeira interação)      │   (Completa)                 │
│                             │                              │
│ Crítica   ████ 1h 23m  ✅   │ Crítica   ████ 4h 15m   ✅  │
│ Alta      ████ 2h 45m  ✅   │ Alta      ████ 12h 30m  ⚠️  │
│ Normal    ████ 5h 12m  ⚠️   │ Normal    ████ 28h 45m  ✅  │
│ Baixa     ████ 8h 30m  ✅   │ Baixa     ████ 45h 20m  ✅  │
│                             │                              │
│ Meta: Crítica < 1h          │ Meta: Crítica < 4h          │
└─────────────────────────────┴──────────────────────────────┘
```

**Métricas:**
- Tempo médio de primeira resposta por prioridade
- Tempo médio de resolução completa por prioridade
- Indicadores de SLA: ✅ dentro / ⚠️ limite / ❌ fora
- Barras de progresso coloridas

**SLA Sugerido:**

| Prioridade | 1ª Resposta | Resolução |
|------------|-------------|-----------|
| Crítica    | < 1h        | < 4h      |
| Alta       | < 4h        | < 24h     |
| Normal     | < 8h        | < 48h     |
| Baixa      | < 24h       | < 72h     |

---

### **SEÇÃO 6: Qualidade e Satisfação**

```
┌────────────────────────────────────────────────────────────┐
│ ✅ Indicadores de Qualidade                                │
├────────────────────────────┬───────────────────────────────┤
│ Taxa de Reabertura         │  Tickets com Mais Interações  │
│                            │                               │
│ Geral:       2.3%  ✅      │ #45 - Bug no checkout   (23)  │
│ João Silva:  2.1%  ✅      │ #32 - Integração API    (18)  │
│ Maria Santos: 1.8% ✅      │ #67 - Erro no relatório (15)  │
│ Pedro Costa: 3.2%  ⚠️      │ #12 - Lentidão sistema  (14)  │
│                            │ #89 - Falha no backup   (12)  │
│ Meta: < 3%                 │                               │
└────────────────────────────┴───────────────────────────────┘
```

**Métricas de Qualidade:**

1. **Taxa de Reabertura**
   - % de tickets resolvidos que foram reabertos
   - Por agente (identifica quem precisa de treinamento)
   - Meta: < 3%

2. **Tickets Complexos**
   - Top 5 tickets com mais comentários
   - Indica problemas recorrentes ou complexos
   - Pode virar FAQ ou documentação

3. **Tempo em Status "Pendente"**
   - Tickets que ficam muito tempo aguardando cliente
   - Pode indicar falta de clareza na comunicação

4. **First Contact Resolution (FCR)**
   - % de tickets resolvidos na primeira interação
   - Meta: > 70%

---

### **SEÇÃO 7: Detalhamento Individual por Agente**

**Layout**: Accordion/Tabs clicáveis

```
┌────────────────────────────────────────────────────────────┐
│ 👤 Detalhamento por Agente                                 │
├────────────────────────────────────────────────────────────┤
│ ▼ João Silva                                    [Expandir] │
│   ├─ Total atribuído: 45 tickets                          │
│   ├─ Resolvidos: 38 (84%)                                 │
│   ├─ Tempo médio: 4h 32m                                  │
│   ├─ Taxa reabertura: 2.1%                                │
│   │                                                        │
│   ├─ 📊 Distribuição por Status      [Gráfico Pizza]      │
│   ├─ 📊 Tickets por Categoria        [Barras Horizontais] │
│   ├─ 📈 Produtividade (30 dias)      [Linha do Tempo]     │
│   └─ 📋 Últimos 10 tickets resolvidos [Tabela]            │
├────────────────────────────────────────────────────────────┤
│ ▶ Maria Santos                                  [Expandir] │
├────────────────────────────────────────────────────────────┤
│ ▶ Pedro Costa                                   [Expandir] │
└────────────────────────────────────────────────────────────┘
```

**Para cada agente:**
- Resumo de métricas principais
- Gráfico de distribuição por status
- Gráfico de tickets por categoria
- Linha do tempo de produtividade (tickets resolvidos/dia)
- Lista dos últimos tickets resolvidos
- Comparação com média da equipe

---

## 🎨 Visualizações e Tecnologias

### Bibliotecas de Gráficos (Escolher uma)

**Opção 1: Chart.js** ⭐ (Recomendado)
- ✅ Gratuita e open-source
- ✅ Leve (11kb gzipped)
- ✅ Simples de implementar
- ✅ Responsiva
- ✅ 8 tipos de gráficos
- ❌ Menos recursos avançados

**Opção 2: ApexCharts**
- ✅ Moderna e interativa
- ✅ Animações suaves
- ✅ Mais tipos de gráficos
- ✅ Zoom, pan, tooltips avançados
- ❌ Mais pesada (144kb)
- ❌ Curva de aprendizado maior

**Opção 3: D3.js**
- ✅ Máxima flexibilidade
- ✅ Gráficos customizados
- ❌ Muito complexa
- ❌ Requer conhecimento avançado

**Recomendação: Chart.js** para começar, migrar para ApexCharts se precisar de mais recursos

---

### Tipos de Gráficos a Usar

1. **Line Chart** (Linha do Tempo)
   - Tickets criados vs resolvidos
   - Tendências temporais

2. **Bar Chart** (Barras Verticais)
   - Tickets por agente
   - Tickets por dia da semana
   - Comparações

3. **Horizontal Bar Chart** (Barras Horizontais)
   - Tickets por categoria (quando há muitas categorias)
   - Rankings

4. **Pie/Doughnut Chart** (Pizza/Rosca)
   - Distribuição por status
   - Distribuição por prioridade

5. **Area Chart** (Área)
   - Evolução acumulada
   - Comparação criados vs resolvidos

6. **Heatmap** (Mapa de Calor)
   - Dia da semana vs hora do dia
   - Identifica padrões temporais

7. **Gauge Chart** (Indicador/Velocímetro)
   - Taxa de resolução vs meta
   - SLA compliance

---

## 📥 Exportação de Dados

**Botão "Exportar" no topo da página**

Formatos:
1. **PDF** - Relatório completo formatado
   - Biblioteca: TCPDF ou DomPDF
   - Inclui todos os gráficos como imagens
   - Cabeçalho com filtros aplicados
   - Rodapé com data/hora geração

2. **Excel** - Dados brutos em planilhas
   - Biblioteca: PhpSpreadsheet
   - Múltiplas abas (por seção)
   - Formatação condicional
   - Gráficos nativos do Excel

3. **CSV** - Dados simples
   - Para importação em outras ferramentas
   - Uma linha por ticket

---

## 🔐 Permissões de Acesso

| Papel   | Permissão                                    |
|---------|----------------------------------------------|
| Admin   | Ver todos agentes e métricas gerais          |
| Agente  | Ver apenas suas próprias métricas            |
| Cliente | Não tem acesso à página de relatórios        |

**Implementação:**
- Middleware de verificação de role
- Query automática filtra por `user_id` se agente
- Admin vê dropdown de agentes, agente não vê

---

## 🗄️ Queries SQL Importantes

### 1. Total de Tickets por Período
```sql
SELECT COUNT(*) as total
FROM tickets
WHERE criado_em BETWEEN ? AND ?
```

### 2. Tempo Médio de Resolução
```sql
SELECT
    AVG(TIMESTAMPDIFF(HOUR, criado_em, atualizado_em)) as tempo_medio_horas
FROM tickets
WHERE status IN ('resolvido', 'fechado')
  AND criado_em BETWEEN ? AND ?
```

### 3. Performance por Agente
```sql
SELECT
    u.nome as agente,
    COUNT(*) as total_atribuido,
    SUM(CASE WHEN t.status = 'resolvido' THEN 1 ELSE 0 END) as resolvidos,
    AVG(TIMESTAMPDIFF(MINUTE, t.criado_em, t.atualizado_em)) as tempo_medio_minutos,
    ROUND(SUM(CASE WHEN t.status = 'resolvido' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as taxa_resolucao
FROM tickets t
JOIN usuarios u ON t.responsavel_id = u.id
WHERE t.criado_em BETWEEN ? AND ?
GROUP BY u.id, u.nome
ORDER BY resolvidos DESC
```

### 4. Tickets por Dia (Últimos 30 dias)
```sql
SELECT
    DATE(criado_em) as data,
    COUNT(*) as criados,
    SUM(CASE WHEN status = 'resolvido' THEN 1 ELSE 0 END) as resolvidos
FROM tickets
WHERE criado_em >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(criado_em)
ORDER BY data
```

### 5. Heatmap (Dia da Semana + Hora)
```sql
SELECT
    DAYOFWEEK(criado_em) as dia_semana,
    HOUR(criado_em) as hora,
    COUNT(*) as quantidade
FROM tickets
WHERE criado_em BETWEEN ? AND ?
GROUP BY dia_semana, hora
ORDER BY dia_semana, hora
```

### 6. Taxa de Reabertura por Agente
```sql
SELECT
    u.nome,
    COUNT(DISTINCT t.id) as total_resolvidos,
    COUNT(DISTINCT CASE WHEN reabertura.ticket_id IS NOT NULL THEN t.id END) as reabertos,
    ROUND(COUNT(DISTINCT CASE WHEN reabertura.ticket_id IS NOT NULL THEN t.id END) * 100.0 / COUNT(DISTINCT t.id), 2) as taxa_reabertura
FROM tickets t
JOIN usuarios u ON t.responsavel_id = u.id
LEFT JOIN (
    SELECT ticket_id
    FROM historico_tickets
    WHERE status_novo IN ('aberto', 'novo')
      AND status_antigo = 'resolvido'
) reabertura ON reabertura.ticket_id = t.id
WHERE t.status = 'resolvido'
  AND t.criado_em BETWEEN ? AND ?
GROUP BY u.id, u.nome
```

---

## 🎯 Prioridades de Implementação

### **Fase 1 - MVP** (Essencial)
1. ✅ Filtros globais (período, agente)
2. ✅ Cards KPI principais (5 cards)
3. ✅ Tabela de performance por agente
4. ✅ Gráfico: Tickets criados vs resolvidos (linha)
5. ✅ Gráfico: Distribuição por prioridade (pizza)

### **Fase 2 - Análise** (Importante)
6. ✅ Gráfico: Tickets por categoria (barras horizontais)
7. ✅ Gráfico: Distribuição semanal (barras)
8. ✅ Tempo médio de resposta/resolução por prioridade
9. ✅ Taxa de reabertura por agente

### **Fase 3 - Avançado** (Diferencial)
10. ✅ Heatmap dia da semana vs hora
11. ✅ Detalhamento individual por agente (accordion)
12. ✅ Tickets por mês (últimos 12 meses)
13. ✅ Exportação PDF/Excel

### **Fase 4 - Futuro** (Nice to Have)
14. ⏳ Avaliação de satisfação (após implementar sistema de rating)
15. ⏳ Insights automáticos (IA/ML)
16. ⏳ Comparação entre períodos
17. ⏳ Previsão de demanda

---

## 🖼️ Wireframe da Página

```
┌─────────────────────────────────────────────────────────────┐
│ 📊 Relatórios e Métricas                                    │
│ [Período ▼] [Agente ▼] [Categoria ▼] [Prioridade ▼] [🔄] [📥]│
├─────────────────────────────────────────────────────────────┤
│ [Card KPI] [Card KPI] [Card KPI] [Card KPI] [Card KPI]     │
├─────────────────────────────────────────────────────────────┤
│ 📈 Tickets Criados vs Resolvidos (30 dias) [Gráfico Linha] │
├──────────────────────────┬──────────────────────────────────┤
│ 👥 Performance Agentes   │ ⚡ Distribuição Prioridade       │
│ [Tabela]                 │ [Gráfico Pizza]                  │
├──────────────────────────┼──────────────────────────────────┤
│ 📂 Tickets por Categoria │ 📅 Distribuição Semanal          │
│ [Barras Horizontais]     │ [Barras Verticais]               │
├──────────────────────────┴──────────────────────────────────┤
│ ⏱️ Tempo Médio Resposta/Resolução por Prioridade           │
│ [2 Gráficos de Barras Lado a Lado]                         │
├─────────────────────────────────────────────────────────────┤
│ ✅ Indicadores de Qualidade                                 │
│ [Taxa Reabertura] [Tickets Complexos] [FCR]                │
├─────────────────────────────────────────────────────────────┤
│ 🔥 Heatmap: Dia da Semana vs Hora do Dia                    │
│ [Calendário de Calor Colorido]                              │
├─────────────────────────────────────────────────────────────┤
│ 👤 Detalhamento por Agente                                  │
│ ▼ João Silva    [Gráficos e Métricas Individuais]          │
│ ▶ Maria Santos                                              │
│ ▶ Pedro Costa                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## 🤔 Decisões Pendentes

**1. Biblioteca de Gráficos**
- [ ] Chart.js (simples, leve)
- [ ] ApexCharts (moderna, rica)
- [ ] Outra?

**2. Período Padrão**
- [ ] Últimos 7 dias
- [ ] Últimos 30 dias
- [ ] Este mês

**3. SLA**
- [ ] Implementar tabela `sla_configuracoes` no banco?
- [ ] Deixar hardcoded no código por enquanto?

**4. Cache**
- [ ] Cachear resultados de queries pesadas?
- [ ] Redis ou arquivo?

**5. Permissões**
- [ ] Agente vê APENAS suas métricas?
- [ ] Agente vê métricas da equipe mas sem detalhes individuais?

---

## 📝 Próximos Passos

1. **Validar planejamento** com stakeholder
2. **Escolher biblioteca de gráficos**
3. **Criar migration** (se necessário para SLA ou cache)
4. **Criar Controller** `Relatorios.php`
5. **Criar Model** `RelatorioModel.php` com queries otimizadas
6. **Criar View** `relatorios/index.php`
7. **Implementar Fase 1** (MVP)
8. **Testar com Playwright**
9. **Iterar para Fases 2, 3, 4**

---

## 💡 Insights e Observações

1. **Performance**: Queries agregadas podem ser lentas com muitos dados
   - Solução: Índices no banco, cache, paginação

2. **Responsividade**: Muitos gráficos podem não caber bem em mobile
   - Solução: Grid adaptativo, scroll horizontal em tabelas

3. **Real-time**: Atualização automática pode ser útil para dashboards em TV
   - Solução: Polling a cada 5 minutos, ou websocket

4. **Gamificação**: Rankings e badges podem motivar agentes
   - Exemplo: "🏆 Agente do Mês", "⚡ Resposta Mais Rápida"

5. **Comparação**: Permitir comparar 2 períodos lado a lado
   - Exemplo: "Dezembro vs Novembro"

---

**Documento criado em**: 2025-11-18
**Última atualização**: 2025-11-18
**Versão**: 1.0 (Brainstorm Inicial)
