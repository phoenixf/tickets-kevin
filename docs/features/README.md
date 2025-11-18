# Documentação de Features

Esta pasta contém documentação sobre funcionalidades específicas do sistema, requisitos e planejamento.

## Arquivos

### Planejamento e Requisitos
- **[PLANEJAMENTO.md](./PLANEJAMENTO.md)** - Planejamento completo do projeto
  - Roadmap geral
  - Features planejadas
  - Prioridades
  - Arquitetura de solução

- **[TICKETS.md](./TICKETS.md)** - Requisitos originais
  - Especificações iniciais
  - Casos de uso
  - Requisitos funcionais
  - Requisitos não-funcionais

### Features Específicas
- **[RELATORIOS.md](./RELATORIOS.md)** - Documentação da feature de Relatórios
  - Funcionamento
  - Exportação em PDF
  - Como usar
  - Correções implementadas

## 🗺️ Mapa de Features

### Implementadas ✅
- Autenticação básica
- CRUD de Tickets
- Sistema de comentários
- Upload de anexos
- Dashboard com métricas
- Relatórios com exportação PDF

### Em Desenvolvimento 🚧
- Notificações por email
- Sistema de permissões (RBAC)
- Histórico de atividades

### Planejadas 📅
- SLA Management
- Respostas prontas (Macros)
- Atribuição automática
- Pesquisa de satisfação (CSAT)
- Portal do cliente
- Base de conhecimento (FAQ)
- Integrações (Slack/Teams)

## 🚀 Como Adicionar uma Nova Feature

1. Abra [PLANEJAMENTO.md](./PLANEJAMENTO.md) e marque a feature
2. Atualize o [CHANGELOG.md](../projeto/CHANGELOG.md) com a nova funcionalidade
3. Se necessário, crie um novo arquivo `.md` nesta pasta
4. Documente o comportamento, API, e como testar

## 📝 Estrutura de Documentação de Feature

Cada feature deve ter:

```markdown
# Feature: Nome da Feature

## Descrição
Breve explicação do que faz

## Status
- Em desenvolvimento / Implementada / Planejada

## Como Usar
Instruções para usuários finais

## Implementação Técnica
Detalhes de como foi implementada

## Testes
Como testar a feature

## Relacionadas
Links para outras features correlatas
```

---

[← Voltar para documentação](../README.md)
