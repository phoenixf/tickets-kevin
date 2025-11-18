# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [Unreleased]

### Planejado
- Autenticação com CodeIgniter Shield
- CRUD completo de Tickets
- Sistema de comentários
- Upload de anexos
- Dashboard com métricas
- Busca e filtros avançados
- Notificações por email
- Sistema de permissões (RBAC)

---

## [0.1.0] - 2025-11-17

### 🎉 Versão Inicial - Setup Completo

#### Added
- **Ambiente de Desenvolvimento**
  - Instalação do PHP 8.4.14
  - Instalação do MySQL 8.0.43
  - Instalação do Composer 2.9.1
  - Instalação do CodeIgniter 4.6.3
  - Configuração de todas as extensões PHP necessárias

- **Estrutura do Banco de Dados (7 tabelas em PT-BR)**
  - Tabela `usuarios` - Administradores, agentes e clientes
  - Tabela `categorias` - Categorias dos tickets
  - Tabela `prioridades` - Níveis de prioridade (Baixa, Normal, Alta, Crítica)
  - Tabela `tickets` - Tabela principal de tickets
  - Tabela `comentarios` - Comentários e notas internas
  - Tabela `anexos` - Arquivos anexados
  - Tabela `historico_tickets` - Auditoria de mudanças

- **Migrations**
  - Migration de usuarios com campos: id, nome, email, senha, funcao, avatar, ativo
  - Migration de categorias com campos: id, nome, descricao, cor, icone, ativo
  - Migration de prioridades com campos: id, nome, nivel, cor
  - Migration de tickets com campos: id, titulo, descricao, usuario_id, responsavel_id, categoria_id, prioridade_id, status, datas
  - Migration de comentarios com campos: id, ticket_id, usuario_id, conteudo, eh_interno
  - Migration de anexos com campos: id, ticket_id, nome_arquivo, caminho_arquivo, tamanho_arquivo, tipo_mime, enviado_por
  - Migration de historico_tickets com campos: id, ticket_id, usuario_id, acao, campo, valor_antigo, valor_novo
  - Todas as foreign keys configuradas com CASCADE e SET NULL apropriados

- **Seeders**
  - PrioridadesSeeder: 4 prioridades com cores (#10B981, #EAB308, #F97316, #EF4444)
  - CategoriasSeeder: 6 categorias (Suporte Técnico, Financeiro, Comercial, RH, Infraestrutura, Outros)
  - UsuariosSeeder: 7 usuários (1 admin, 3 agentes, 3 clientes) com senha padrão "123456"

- **Configurações**
  - Arquivo `.env` configurado com credenciais do banco
  - Chave de encriptação gerada
  - Ambiente de desenvolvimento configurado
  - Base URL configurada para http://localhost:8080

- **Documentação**
  - `docs/features/PLANEJAMENTO.md` - Planejamento completo do sistema (800+ linhas)
    - Visão geral e objetivos
    - Stack tecnológica detalhada
    - Análise de requisitos originais
    - Melhorias propostas (features essenciais, recomendadas, opcionais)
    - Arquitetura do sistema completa
    - Estrutura do banco de dados
    - Features detalhadas
    - Design e interface
    - Roadmap de desenvolvimento em 6 semanas
    - Métricas de sucesso
    - Medidas de segurança

  - `docs/desenvolvimento/BANCO_DE_DADOS.md` - Documentação do banco de dados
    - Informações gerais (charset, collation)
    - Estrutura detalhada das 7 tabelas
    - Diagrama de relacionamentos (ER)
    - Queries úteis prontas para uso
    - Credenciais de acesso
    - Comandos úteis (migrations, seeders, backup)
    - Observações importantes sobre segurança

  - `docs/features/TICKETS.md` - Requisitos originais do sistema

  - `README.md` - Documentação principal
    - Badges do projeto
    - Sobre o projeto
    - Stack tecnológica
    - Estrutura do banco
    - Instruções de instalação
    - Credenciais de acesso
    - Lista de funcionalidades
    - Comandos úteis
    - Guia de contribuição

- **Versionamento**
  - Repositório Git inicializado
  - Repositório GitHub criado: https://github.com/phoenixf/tickets-kevin
  - `.gitignore` configurado (ignora .env, vendor, cache, etc.)
  - 2 commits iniciais realizados
  - Branch principal: `main`

#### Changed
- Sistema de prioridades ajustado de 5 níveis para 4 níveis (padrão de mercado)
  - Removido: "PRIORIDADE" (nome não descritivo)
  - Ajustado: "urgente" → "Crítica" (mais profissional)

#### Technical Details
- **Database**: tickets_db (utf8mb4_unicode_ci)
- **User**: tickets_user
- **Password**: tickets_pass_2024
- **Engine**: InnoDB
- **Foreign Keys**: Configuradas com integridade referencial
- **Índices**: Criados em campos mais consultados (email, funcao, status, prioridade_id, criado_em)

#### Sistema de Cores das Prioridades
```
🟢 Baixa    → #10B981 (Verde)
🟡 Normal   → #EAB308 (Amarelo)
🟠 Alta     → #F97316 (Laranja)
🔴 Crítica  → #EF4444 (Vermelho)
```

#### Dados Iniciais Inseridos
- **Prioridades**: 4 registros (Baixa, Normal, Alta, Crítica)
- **Categorias**: 6 registros (Suporte Técnico, Financeiro, Comercial, RH, Infraestrutura, Outros)
- **Usuários**: 7 registros
  - 1 Admin: admin@tickets.com
  - 3 Agentes: joao.silva@tickets.com, maria.santos@tickets.com, carlos.oliveira@tickets.com
  - 3 Clientes: ana.costa@cliente.com, pedro.almeida@cliente.com, juliana.ferreira@cliente.com

#### Stack Tecnológica Definida
- **Backend**: CodeIgniter 4.6.3
- **Frontend (Planejado)**: HTMX + Alpine.js + Tailwind CSS + Flowbite
- **Database**: MySQL 8.0+
- **Tabelas**: DataTables
- **Ícones**: Heroicons
- **Autenticação (Planejada)**: CodeIgniter Shield

#### Commits Realizados
1. `fdd140f` - feat: Setup inicial do sistema de tickets
2. `2ffb670` - docs: Atualiza README com documentação completa do projeto

---

## Informações de Versionamento

### Semantic Versioning (SemVer)

Este projeto utiliza [Semantic Versioning](https://semver.org/lang/pt-BR/):

- **MAJOR** (X.0.0): Mudanças incompatíveis na API
- **MINOR** (0.X.0): Novas funcionalidades compatíveis com versões anteriores
- **PATCH** (0.0.X): Correções de bugs compatíveis com versões anteriores

### Convenção de Commits

Seguimos [Conventional Commits](https://www.conventionalcommits.org/pt-br/):

- `feat:` - Nova funcionalidade
- `fix:` - Correção de bug
- `docs:` - Mudanças na documentação
- `style:` - Formatação, ponto e vírgula, etc (sem mudança de código)
- `refactor:` - Refatoração de código
- `test:` - Adição ou correção de testes
- `chore:` - Atualizações de dependências, configurações, etc
- `perf:` - Melhorias de performance
- `ci:` - Mudanças em CI/CD
- `build:` - Mudanças no sistema de build

### Tipos de Mudanças

- `Added` - Novas funcionalidades
- `Changed` - Mudanças em funcionalidades existentes
- `Deprecated` - Funcionalidades que serão removidas
- `Removed` - Funcionalidades removidas
- `Fixed` - Correções de bugs
- `Security` - Correções de vulnerabilidades

---

## Notas de Desenvolvimento

### Estado Atual do Projeto (v0.1.0)
✅ **Ambiente** completamente configurado
✅ **Banco de dados** criado e populado
✅ **Migrations** funcionando perfeitamente
✅ **Seeders** executados com sucesso
✅ **Documentação** completa e organizada
✅ **Versionamento** no GitHub configurado

### Próximos Passos (v0.2.0)
⏳ Instalar e configurar CodeIgniter Shield
⏳ Criar Models (TicketModel, UserModel, CategoryModel, etc.)
⏳ Criar Controllers (Auth, Dashboard, Tickets)
⏳ Criar Views básicas com layout
⏳ Implementar sistema de login

### Riscos e Observações
- ⚠️ Senhas padrão "123456" devem ser alteradas em produção
- ⚠️ Arquivo `.env` não está versionado (contém credenciais)
- ✅ Foreign keys configuradas para manter integridade referencial
- ✅ Índices criados para otimizar consultas
- ✅ Charset utf8mb4 para suportar emojis e caracteres especiais

---

## Links Úteis

- **Repositório**: https://github.com/phoenixf/tickets-kevin
- **CodeIgniter 4**: https://codeigniter.com/user_guide/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Flowbite**: https://flowbite.com
- **Alpine.js**: https://alpinejs.dev
- **HTMX**: https://htmx.org/docs/

---

**Mantido por**: phoenixf
**Desenvolvido com**: Claude Code
**Última atualização**: 2025-11-17
