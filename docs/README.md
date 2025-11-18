# Documentação - Sistema de Tickets

Bem-vindo à documentação do Sistema de Gestão de Tickets. Aqui você encontrará guias, referências técnicas e informações sobre desenvolvimento.

---

## 📁 Estrutura de Documentação

### 📚 [projeto/](./projeto/) - Contexto Geral do Projeto
Informações sobre o projeto, histórico de mudanças e guias para desenvolvimento.

- **[CLAUDE.md](./projeto/CLAUDE.md)** - Guia de contexto para Claude Code
  - Estado atual do projeto
  - Arquitetura e stack tecnológico
  - Portas e configurações
  - Próximos passos de desenvolvimento

- **[CHANGELOG.md](./projeto/CHANGELOG.md)** - Histórico de Versões
  - Mudanças em cada release
  - Planejamento futuro
  - Segue padrão [Keep a Changelog](https://keepachangelog.com/pt-BR/)

---

### 🛠️ [desenvolvimento/](./desenvolvimento/) - Guias de Desenvolvimento
Documentação técnica para configuração, testes e desenvolvimento.

- **[SETUP_INSTRUCTIONS.md](./desenvolvimento/SETUP_INSTRUCTIONS.md)** - Guia de Instalação
  - Pré-requisitos do sistema
  - Passos de instalação
  - Configuração de ambiente

- **[SETUP_COMPLETE.md](./desenvolvimento/SETUP_COMPLETE.md)** - Status de Setup
  - Confirmação de instalação completa
  - Checklist de verificação
  - Próximas ações

- **[BANCO_DE_DADOS.md](./desenvolvimento/BANCO_DE_DADOS.md)** - Documentação do Banco
  - Estrutura de tabelas em PT-BR
  - Diagramas ER
  - Queries úteis
  - Relacionamentos

- **[TESTING.md](./desenvolvimento/TESTING.md)** - Guia de Testes
  - Testes unitários
  - Testes de integração
  - Testes do banco de dados
  - Configuração de ambiente de testes

- **[TESTE_VISUAL.md](./desenvolvimento/TESTE_VISUAL.md)** - Testes Visuais
  - Playwright e automação de testes
  - Testes end-to-end
  - Verificação visual

- **[TESTS_REPORT.md](./desenvolvimento/TESTS_REPORT.md)** - Relatórios de Testes
  - Resultados de testes
  - Cobertura de código
  - Análise de qualidade

---

### 🎯 [features/](./features/) - Documentação de Features
Detalhes sobre funcionalidades específicas do sistema.

- **[PLANEJAMENTO.md](./features/PLANEJAMENTO.md)** - Planejamento Completo
  - Roadmap do projeto
  - Features planejadas
  - Arquitetura de solução
  - Prioridades

- **[TICKETS.md](./features/TICKETS.md)** - Requisitos Originais
  - Especificações iniciais
  - Casos de uso
  - Requisitos funcionais
  - Requisitos não-funcionais

- **[RELATORIOS.md](./features/RELATORIOS.md)** - Feature: Relatórios
  - Exportação de PDF
  - Correções implementadas
  - Como utilizar

---

## 🚀 Quick Links

### Para Começar
1. **Primeira vez?** → Veja [SETUP_INSTRUCTIONS.md](./desenvolvimento/SETUP_INSTRUCTIONS.md)
2. **Desenvolvimento?** → Leia [CLAUDE.md](./projeto/CLAUDE.md)
3. **Precisa testar?** → Consulte [TESTING.md](./desenvolvimento/TESTING.md)

### Referência Rápida
- **Portas:** Backend (8081), MySQL (3310 em dev, 3306 em prod)
- **Database:** `tickets_db` com usuário `tickets_user`
- **Stack:** PHP 8.4 + CodeIgniter 4.6.3 + MySQL 8.0

### Comandos Úteis
```bash
# Iniciar servidor
php spark serve --port 8081

# Executar migrations
php spark migrate

# Rodar testes
php spark test

# Executar seeders
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder
```

---

## 📊 Status do Projeto

**Versão Atual:** v0.1.0
**Última Atualização:** 2025-11-18
**Status:** ✅ Setup completo, testes implementados

Para detalhes completos, veja [CHANGELOG.md](./projeto/CHANGELOG.md)

---

## 🔍 Navegação

Cada documento tem links internos para outras seções relevantes. Use os links acima para navegar pela documentação.

Para voltar a este índice de qualquer página, clique no link "Documentação" no topo.

---

## 💡 Dicas

- **Markdown:** Todos os arquivos usam Markdown com suporte a tabelas e blocos de código
- **Links:** Use caminhos relativos para navegar entre documentos
- **Atualizações:** Esta documentação é atualizada junto com o código
- **Português:** Toda documentação está em português do Brasil (PT-BR)

---

<p align="center">
  Documentação do <strong>Sistema de Gestão de Tickets</strong><br>
  <em>Construído com CodeIgniter 4 e ❤️</em>
</p>
