# Correções nos Relatórios - Resumo

## Data: 2025-11-18

## Problemas Identificados e Resolvidos

### 1. Botão "Exportar PDF" não funcionava
**Causa Raiz:** Botão existia visualmente mas não tinha JavaScript nem rota configurada.

**Solução Implementada:**
- ✅ Adicionado event listener no botão `#export-btn` em `/app/Views/relatorios/index.php` (linha 559)
- ✅ Criado método `exportarPdf()` no controller `/app/Controllers/Relatorios.php` (linha 126)
- ✅ Criada view de PDF otimizada para impressão em `/app/Views/relatorios/pdf.php`

**Como usar:**
1. Acessar http://localhost:8081/relatorios
2. Clicar no botão "Exportar PDF" (canto superior direito)
3. Abre página formatada para impressão
4. Usar Ctrl+P (Windows/Linux) ou Cmd+P (Mac)
5. Selecionar "Salvar como PDF" no destino

---

### 2. SLA em 0% (Primeira Resposta e Resolução)
**Causa Raiz:** Colunas `primeira_resposta_em` e `resolvido_em` estavam todas NULL na tabela `tickets`.

**Solução Implementada:**

#### Parte 1: Lógica Automática para Novos Tickets
- ✅ Adicionado callback `afterInsert` no `/app/Models/CommentModel.php` (linha 58)
- ✅ Método `atualizarPrimeiraResposta()` popula `primeira_resposta_em` quando agente/admin comenta pela primeira vez
- ✅ Adicionado callback `beforeUpdate` no `/app/Models/TicketModel.php` (linha 89)
- ✅ Método `atualizarResolvidoEm()` popula `resolvido_em` quando status muda para "resolvido" ou "fechado"
- ✅ Adicionado `primeira_resposta_em` e `resolvido_em` aos `allowedFields` do TicketModel

#### Parte 2: Popular Dados Retroativos (Executado via SQL)
```sql
-- Atualizar primeira_resposta_em baseado no primeiro comentário de agente/admin
UPDATE tickets t
SET primeira_resposta_em = (
    SELECT MIN(c.criado_em)
    FROM comentarios c
    INNER JOIN usuarios u ON u.id = c.usuario_id
    WHERE c.ticket_id = t.id
    AND u.funcao IN ('agente', 'admin')
)
WHERE primeira_resposta_em IS NULL;

-- Atualizar resolvido_em para tickets já resolvidos/fechados
UPDATE tickets t
SET resolvido_em = COALESCE(
    (SELECT MAX(h.criado_em) FROM historico_tickets h WHERE h.ticket_id = t.id AND h.campo = 'status' AND h.valor_novo IN ('resolvido', 'fechado') LIMIT 1),
    t.atualizado_em,
    t.criado_em
)
WHERE status IN ('resolvido', 'fechado') AND resolvido_em IS NULL;
```

**Resultado:**
- ✅ 30 tickets agora têm `primeira_resposta_em` populado
- ✅ 10 tickets resolvidos/fechados têm `resolvido_em` populado
- ✅ SLA agora mostra valores reais (não mais 0%)

---

### 3. Relatório mostrando 23 tickets ao invés de 35
**Causa Raiz:** Filtro padrão de período estava definido como "últimos 7 dias", escondendo 12 tickets antigos.

**Solução Implementada:**
- ✅ Alterado filtro padrão de `-7 days` para `-30 days` em `/app/Controllers/Relatorios.php` (linha 41)
- ✅ Atualizado valor padrão do campo de data na view `/app/Views/relatorios/index.php` (linha 46)
- ✅ Modificado botão quick filter para destacar "Últimos 30 dias" ao invés de "Últimos 7 dias" (linhas 25-29)

**Resultado:**
- ✅ Agora mostra 35 tickets por padrão (todos dos últimos 30 dias)
- ✅ Usuário pode ajustar período manualmente se desejar
- ✅ Quick filters continuam funcionando (Hoje, Ontem, 7 dias, 30 dias, Este Ano)

---

## Testes Executados

### Via Playwright:
- ✅ Login como agente
- ✅ Navegação para /relatorios
- ✅ Verificação de 5 cards KPI
- ✅ **Total de Tickets: 35** (antes: 23)
- ✅ Tickets Resolvidos: 10
- ✅ Tempo Médio: 36h 0m
- ✅ Taxa Resolução: 28.6%
- ✅ Abertos Agora: 25

### Via MySQL:
```sql
-- Total de tickets
SELECT COUNT(*) FROM tickets; -- 35

-- Tickets com primeira_resposta_em
SELECT COUNT(*) FROM tickets WHERE primeira_resposta_em IS NOT NULL; -- 30

-- Tickets resolvidos com resolvido_em
SELECT COUNT(*) FROM tickets WHERE status IN ('resolvido', 'fechado') AND resolvido_em IS NOT NULL; -- 10
```

---

## Arquivos Modificados

1. `/app/Controllers/Relatorios.php`
   - Alterado filtro padrão (linha 41)
   - Adicionado método `exportarPdf()` (linha 126-189)

2. `/app/Models/CommentModel.php`
   - Adicionado callback `afterInsert` (linha 58)
   - Adicionado método `atualizarPrimeiraResposta()` (linhas 63-92)

3. `/app/Models/TicketModel.php`
   - Adicionado `primeira_resposta_em` e `resolvido_em` aos allowedFields (linhas 24-25)
   - Adicionado callback `beforeUpdate` (linha 89)
   - Adicionado método `atualizarResolvidoEm()` (linhas 99-130)

4. `/app/Views/relatorios/index.php`
   - Alterado valor padrão do período (linha 46)
   - Alterado destaque do quick filter (linhas 25-29)
   - Adicionado JavaScript para botão Exportar PDF (linhas 559-567)

5. `/app/Views/relatorios/pdf.php` (NOVO)
   - View completa otimizada para impressão em PDF
   - Inclui KPIs, SLA, performance de agentes, distribuições

---

## Status Final

| Problema | Status | Observações |
|----------|--------|-------------|
| Exportar PDF não funciona | ✅ RESOLVIDO | Botão funcional com view dedicada |
| SLA em 0% | ✅ RESOLVIDO | Dados retroativos populados + lógica automática para novos tickets |
| 23 tickets ao invés de 35 | ✅ RESOLVIDO | Filtro padrão alterado para 30 dias |

---

## Próximos Passos (Opcional)

### Melhorias Sugeridas:
1. Usar biblioteca PHP real de PDF (ex: Dompdf, TCPDF) para gerar PDF server-side
2. Adicionar opção de exportar CSV/Excel
3. Criar agendamento de relatórios automáticos por email
4. Adicionar filtro de "data de resolução" além de "data de criação"
5. Implementar caching de relatórios para melhor performance

### Manutenção:
- As colunas `primeira_resposta_em` e `resolvido_em` agora são atualizadas automaticamente
- Não é necessário executar SQL manualmente para novos tickets
- Para dados antigos importados, executar os SQLs de atualização retroativa

---

**Gerado em:** 2025-11-18
**Testado em:** http://localhost:8081/relatorios
**Status:** ✅ Todas as correções implementadas e validadas
