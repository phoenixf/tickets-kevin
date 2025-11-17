# Planejamento do Sistema de Tickets 🎫

## 📋 Índice
1. [Visão Geral](#visão-geral)
2. [Stack Tecnológica](#stack-tecnológica)
3. [Análise dos Requisitos Originais](#análise-dos-requisitos-originais)
4. [Melhorias Propostas](#melhorias-propostas)
5. [Arquitetura do Sistema](#arquitetura-do-sistema)
6. [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
7. [Features Detalhadas](#features-detalhadas)
8. [Design e Interface](#design-e-interface)
9. [Roadmap de Desenvolvimento](#roadmap-de-desenvolvimento)

---

## 🎯 Visão Geral

Sistema moderno de gestão de tickets desenvolvido em PHP, focado em **simplicidade, performance e visual atraente**. O sistema permite abertura, acompanhamento e resolução de tickets de suporte com interface intuitiva e código de cores para prioridades.

### Objetivos Principais:
✅ Interface moderna e responsiva
✅ Simplicidade no uso (baixa curva de aprendizado)
✅ Performance otimizada
✅ Código limpo e manutenível (padrão MVC)
✅ Visual profissional com cores para prioridades

---

## 🛠️ Stack Tecnológica

### Backend
- **Framework:** CodeIgniter 4
- **Linguagem:** PHP 8.1+
- **Autenticação:** CodeIgniter Shield
- **Validação:** CodeIgniter Validation Library

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **Biblioteca de Componentes:** Flowbite (600+ componentes prontos)
- **Interatividade:** Alpine.js 3.x (leve, ~15kb)
- **AJAX:** HTMX 1.9+ (comunicação sem JavaScript complexo)
- **Tabelas:** DataTables com tema Tailwind
- **Ícones:** Heroicons

### Banco de Dados
- **SGBD:** MySQL 8.0+
- **Engine:** InnoDB (suporte a transações)
- **ORM:** CodeIgniter Query Builder

### Ferramentas de Desenvolvimento
- **Gerenciador de Dependências:** Composer
- **Migrations:** CodeIgniter Migrations
- **Version Control:** Git

### Por que essa stack?

| Critério | Justificativa |
|----------|---------------|
| **Simplicidade** | Zero build steps, configuração mínima |
| **Performance** | CodeIgniter é extremamente rápido e leve |
| **Visual Moderno** | Tailwind + Flowbite = design contemporâneo sem esforço |
| **Produtividade** | Desenvolvimento rápido, menos boilerplate |
| **Custo** | 100% open-source e gratuito |
| **Manutenibilidade** | MVC nativo, código organizado |

---

## 📄 Análise dos Requisitos Originais

### Requisitos do arquivo `tickets.md`:

| # | Requisito Original | Status | Observações |
|---|-------------------|--------|-------------|
| 1 | Sistema em PHP | ✅ Mantido | CodeIgniter 4 |
| 2 | Abertura via modal | ✅ Mantido | Modal moderno com Alpine.js |
| 3 | Lista de tickets abertos | ✅ Expandido | Adicionado filtros e busca |
| 4 | Campos: ID, Usuário, Data, Responsável, Prioridade, Situação | ✅ Mantido | + campos adicionais |
| 5 | Prioridades: baixa, normal, alta, urgente, PRIORIDADE | ✅ Ajustado | 4 níveis: Baixa, Normal, Alta, Crítica |
| 6 | Cores por prioridade | ✅ Implementado | Sistema de badges coloridos |

### Ajustes nas Prioridades:
**Original:** baixa, normal, alta, urgente, PRIORIDADE (5 níveis)
**Proposta:** Baixa, Normal, Alta, Crítica (4 níveis)

**Justificativa:**
- 4 níveis são suficientes e mais claros
- "PRIORIDADE" não é um nome descritivo
- "Crítica" substitui "urgente/PRIORIDADE" com nome mais profissional
- Alinhado com padrões de mercado (Zendesk, Freshdesk, Jira)

### Sistema de Cores Proposto:

```
🔴 Crítica  → #EF4444 (Vermelho)  - Requer ação imediata
🟠 Alta     → #F97316 (Laranja)   - Atenção prioritária
🟡 Normal   → #EAB308 (Amarelo)   - Fluxo normal
🟢 Baixa    → #10B981 (Verde)     - Quando possível
```

---

## ✨ Melhorias Propostas

### 🎯 Features Essenciais (MUST HAVE)

Funcionalidades que agregam muito valor sem adicionar complexidade:

#### 1. **Sistema de Status (Workflow)**
Além da prioridade, os tickets precisam de status:
- **Novo** → Ticket criado, aguardando atribuição
- **Aberto** → Atribuído a um responsável
- **Em Progresso** → Trabalho ativo no ticket
- **Pendente** → Aguardando cliente/terceiros
- **Resolvido** → Solução fornecida
- **Fechado** → Finalizado

**Benefício:** Visão clara do ciclo de vida do ticket

#### 2. **Categorias de Tickets**
Classificação por tipo de problema:
- Suporte Técnico
- Financeiro
- Vendas
- Comercial
- Outros

**Benefício:** Melhor organização e métricas

#### 3. **Comentários e Histórico**
- Timeline de todas as interações
- Comentários públicos (visíveis para cliente)
- Notas internas (apenas equipe)
- Histórico de mudanças (quem alterou o quê e quando)

**Benefício:** Rastreabilidade completa

#### 4. **Anexos**
- Upload de arquivos (screenshots, documentos, logs)
- Limite: 5MB por arquivo
- Formatos: imagens, PDF, documentos

**Benefício:** Melhor comunicação de problemas

#### 5. **Dashboard com Métricas**
Cards na página inicial:
- Total de tickets
- Tickets abertos
- Tickets em progresso
- Tickets resolvidos hoje
- Gráfico de tickets por período

**Benefício:** Visão gerencial imediata

#### 6. **Busca e Filtros Avançados**
- Busca por palavra-chave (título, descrição)
- Filtros por:
  - Status
  - Prioridade
  - Categoria
  - Responsável
  - Data (range)
- Filtros salvos (views customizadas)

**Benefício:** Encontrar tickets rapidamente

#### 7. **Notificações por Email**
Emails automáticos em:
- Criação de ticket (para cliente e responsável)
- Mudança de status
- Novo comentário
- Resolução do ticket

**Benefício:** Todos ficam informados

#### 8. **Sistema de Permissões**
Níveis de acesso:
- **Admin** → Acesso total, configurações
- **Agente** → Gerenciar tickets atribuídos
- **Cliente** → Criar tickets, visualizar os próprios

**Benefício:** Segurança e organização

### 🚀 Features Recomendadas (SHOULD HAVE)

Funcionalidades que agregam valor médio-alto:

#### 9. **Atribuição Automática**
- Round-robin: distribuição igual entre agentes
- Por categoria: agente especialista por tipo

**Benefício:** Agiliza o processo

#### 10. **Respostas Prontas (Macros)**
Biblioteca de respostas para problemas comuns:
- "Como resetar senha"
- "Instruções de primeiro acesso"
- etc.

**Benefício:** Aumenta produtividade

#### 11. **SLA (Service Level Agreement)**
- Definir tempo de resposta esperado por prioridade
- Alertas visuais quando próximo do prazo
- Timer que pausa em status "Pendente"

**Benefício:** Controle de qualidade

#### 12. **Pesquisa de Satisfação**
Após fechar ticket, enviar email com:
- "Como avalia o atendimento?" (1-5 estrelas)
- Campo de comentário opcional

**Benefício:** Medir qualidade do suporte

### 🌟 Features Opcionais (NICE TO HAVE)

Funcionalidades para versões futuras:

- Portal do cliente (área separada para clientes)
- Base de conhecimento (FAQ)
- Relatórios avançados (exportar CSV/PDF)
- Integração com Slack/Teams
- Chat em tempo real
- Mobile app

---

## 🏗️ Arquitetura do Sistema

### Estrutura de Diretórios (CodeIgniter 4)

```
/var/www/tickets-kevin/
├── app/
│   ├── Controllers/
│   │   ├── Dashboard.php         # Página inicial com métricas
│   │   ├── Tickets.php            # CRUD de tickets
│   │   ├── Comments.php           # Comentários em tickets
│   │   ├── Users.php              # Gerenciamento de usuários
│   │   └── Settings.php           # Configurações do sistema
│   │
│   ├── Models/
│   │   ├── TicketModel.php        # Entidade Ticket
│   │   ├── UserModel.php          # Entidade User
│   │   ├── CategoryModel.php      # Entidade Category
│   │   ├── CommentModel.php       # Entidade Comment
│   │   ├── AttachmentModel.php    # Entidade Attachment
│   │   └── PriorityModel.php      # Entidade Priority
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── header.php         # Header global
│   │   │   ├── sidebar.php        # Sidebar de navegação
│   │   │   └── footer.php         # Footer global
│   │   │
│   │   ├── dashboard/
│   │   │   └── index.php          # Dashboard principal
│   │   │
│   │   ├── tickets/
│   │   │   ├── index.php          # Listagem de tickets
│   │   │   ├── view.php           # Visualizar ticket individual
│   │   │   ├── create_modal.php   # Modal de criação
│   │   │   └── edit_modal.php     # Modal de edição
│   │   │
│   │   └── users/
│   │       ├── index.php          # Gerenciar usuários
│   │       └── profile.php        # Perfil do usuário
│   │
│   ├── Config/
│   │   ├── Routes.php             # Rotas da aplicação
│   │   ├── Database.php           # Configuração do MySQL
│   │   └── Email.php              # Configuração de emails
│   │
│   └── Helpers/
│       ├── ticket_helper.php      # Funções auxiliares
│       └── notification_helper.php
│
├── public/
│   ├── css/
│   │   └── custom.css             # CSS customizado
│   ├── js/
│   │   └── app.js                 # JavaScript customizado
│   └── uploads/                   # Anexos de tickets
│       └── attachments/
│
├── database/
│   ├── migrations/                # Migrações do banco
│   │   ├── 001_create_users_table.php
│   │   ├── 002_create_tickets_table.php
│   │   ├── 003_create_categories_table.php
│   │   ├── 004_create_comments_table.php
│   │   └── 005_create_attachments_table.php
│   │
│   └── seeds/                     # Dados iniciais
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── PrioritySeeder.php
│
├── .env                           # Variáveis de ambiente
├── composer.json                  # Dependências PHP
└── README.md                      # Documentação
```

### Padrão MVC

```
┌──────────┐      ┌──────────────┐      ┌─────────┐      ┌──────────┐
│  USER    │─────▶│ CONTROLLER   │─────▶│  MODEL  │─────▶│ DATABASE │
│ (Browser)│      │ (Lógica)     │      │ (Dados) │      │  (MySQL) │
└──────────┘      └──────────────┘      └─────────┘      └──────────┘
     ▲                   │
     │                   ▼
     │            ┌──────────────┐
     └────────────│     VIEW     │
                  │  (Template)  │
                  └──────────────┘
```

---

## 🗄️ Estrutura do Banco de Dados

### Diagrama ER (Entidade-Relacionamento)

```
┌─────────────────┐         ┌─────────────────┐         ┌─────────────────┐
│     USERS       │         │    TICKETS      │         │   CATEGORIES    │
├─────────────────┤         ├─────────────────┤         ├─────────────────┤
│ id (PK)         │────┐    │ id (PK)         │    ┌────│ id (PK)         │
│ name            │    │    │ title           │    │    │ name            │
│ email           │    │    │ description     │    │    │ color           │
│ password        │    │    │ user_id (FK)────┼────┘    │ icon            │
│ role            │    │    │ assigned_to(FK)─┼────┐    │ active          │
│ avatar          │    │    │ category_id(FK)─┼────┘    │ created_at      │
│ active          │    │    │ priority_id(FK)─┼──┐      └─────────────────┘
│ created_at      │    │    │ status          │  │
│ updated_at      │    │    │ due_date        │  │      ┌─────────────────┐
└─────────────────┘    │    │ resolved_at     │  └─────▶│   PRIORITIES    │
                       │    │ closed_at       │         ├─────────────────┤
                       │    │ created_at      │         │ id (PK)         │
                       │    │ updated_at      │         │ name            │
                       │    └─────────────────┘         │ level           │
                       │            │                   │ color           │
                       │            │                   │ created_at      │
                       │            │                   └─────────────────┘
                       │            │
                       │            │    ┌─────────────────┐
                       │            └───▶│    COMMENTS     │
                       │                 ├─────────────────┤
                       │                 │ id (PK)         │
                       │                 │ ticket_id (FK)  │
                       │                 │ user_id (FK)────┼──┘
                       │                 │ content         │
                       │                 │ is_internal     │
                       │                 │ created_at      │
                       │                 └─────────────────┘
                       │
                       │                 ┌─────────────────┐
                       └────────────────▶│  ATTACHMENTS    │
                                         ├─────────────────┤
                                         │ id (PK)         │
                                         │ ticket_id (FK)  │
                                         │ filename        │
                                         │ filepath        │
                                         │ filesize        │
                                         │ mimetype        │
                                         │ uploaded_by(FK) │
                                         │ created_at      │
                                         └─────────────────┘
```

### Tabelas Detalhadas

#### 1. `users` (Usuários do sistema)
```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent', 'client') DEFAULT 'client',
    avatar VARCHAR(255) NULL,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 2. `categories` (Categorias de tickets)
```sql
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#6366F1',
    icon VARCHAR(50) NULL,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 3. `priorities` (Prioridades)
```sql
CREATE TABLE priorities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT NOT NULL,
    color VARCHAR(7) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados iniciais
INSERT INTO priorities (name, level, color) VALUES
('Baixa', 1, '#10B981'),
('Normal', 2, '#EAB308'),
('Alta', 3, '#F97316'),
('Crítica', 4, '#EF4444');
```

#### 4. `tickets` (Tickets principais)
```sql
CREATE TABLE tickets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    assigned_to INT UNSIGNED NULL,
    category_id INT UNSIGNED NULL,
    priority_id INT UNSIGNED DEFAULT 2,
    status ENUM('new', 'open', 'in_progress', 'pending', 'resolved', 'closed') DEFAULT 'new',
    due_date DATETIME NULL,
    resolved_at DATETIME NULL,
    closed_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (priority_id) REFERENCES priorities(id) ON DELETE SET NULL,

    INDEX idx_status (status),
    INDEX idx_priority (priority_id),
    INDEX idx_assigned (assigned_to),
    INDEX idx_created (created_at),
    FULLTEXT KEY ft_search (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 5. `comments` (Comentários nos tickets)
```sql
CREATE TABLE comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    is_internal TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_ticket (ticket_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 6. `attachments` (Anexos)
```sql
CREATE TABLE attachments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    filesize INT UNSIGNED NOT NULL,
    mimetype VARCHAR(100) NOT NULL,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_ticket (ticket_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 7. `ticket_history` (Histórico de mudanças)
```sql
CREATE TABLE ticket_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    action VARCHAR(50) NOT NULL,
    field VARCHAR(50) NULL,
    old_value VARCHAR(255) NULL,
    new_value VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_ticket (ticket_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🎨 Design e Interface

### Paleta de Cores

```css
/* Cores Principais */
--primary: #6366F1;        /* Indigo - Botões principais */
--secondary: #8E32E9;      /* Roxo - Acentos */
--background: #F8F9FA;     /* Cinza claro - Fundo */
--surface: #FFFFFF;        /* Branco - Cards */
--text: #1F2937;           /* Cinza escuro - Texto */

/* Cores de Status */
--status-new: #3B82F6;     /* Azul - Novo */
--status-open: #06B6D4;    /* Ciano - Aberto */
--status-progress: #8B5CF6; /* Roxo - Em progresso */
--status-pending: #F59E0B; /* Âmbar - Pendente */
--status-resolved: #10B981; /* Verde - Resolvido */
--status-closed: #6B7280;  /* Cinza - Fechado */

/* Cores de Prioridade */
--priority-low: #10B981;    /* Verde - Baixa */
--priority-normal: #EAB308; /* Amarelo - Normal */
--priority-high: #F97316;   /* Laranja - Alta */
--priority-critical: #EF4444; /* Vermelho - Crítica */

/* Cores de Feedback */
--success: #10B981;
--warning: #F59E0B;
--error: #EF4444;
--info: #3B82F6;
```

### Layout Principal

```
┌─────────────────────────────────────────────────────────────┐
│  NAVBAR - Logo | Busca | Notificações | Avatar             │
└─────────────────────────────────────────────────────────────┘
┌───────────┬─────────────────────────────────────────────────┐
│           │  HEADER - Título da Página | Botão Novo Ticket │
│ SIDEBAR   ├─────────────────────────────────────────────────┤
│           │                                                 │
│ Dashboard │  ┌───────┐ ┌───────┐ ┌───────┐ ┌───────┐      │
│ Tickets   │  │ Total │ │ Abert │ │ Progr │ │ Resolv│      │
│ Relat.    │  │  145  │ │  23   │ │  45   │ │  77   │      │
│ Config.   │  └───────┘ └───────┘ └───────┘ └───────┘      │
│ Sair      │                                                 │
│           │  ┌─────────────────────────────────────────┐   │
│           │  │ FILTROS E BUSCA                        │   │
│           │  │ 🔍 [Buscar...] [Status▼] [Prioridade▼]│   │
│           │  └─────────────────────────────────────────┘   │
│           │                                                 │
│           │  ┌─────────────────────────────────────────┐   │
│           │  │ TABELA DE TICKETS                       │   │
│           │  │ ID | Título | Status | Prioridade | ... │   │
│           │  │ ─────────────────────────────────────── │   │
│           │  │ #1 | Login  | 🔵 Novo | 🔴 Crítica  | ...│   │
│           │  │ #2 | Bug    | 🟢 Resol| 🟡 Normal   | ...│   │
│           │  │                                         │   │
│           │  │          [1] [2] [3] ... [10]          │   │
│           │  └─────────────────────────────────────────┘   │
└───────────┴─────────────────────────────────────────────────┘
```

### Componentes Visuais

#### Badges de Status
```html
<!-- Novo -->
<span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
    Novo
</span>

<!-- Aberto -->
<span class="bg-cyan-100 text-cyan-800 text-xs font-semibold px-3 py-1 rounded-full">
    Aberto
</span>

<!-- Em Progresso -->
<span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
    Em Progresso
</span>

<!-- Pendente -->
<span class="bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">
    Pendente
</span>

<!-- Resolvido -->
<span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
    Resolvido
</span>

<!-- Fechado -->
<span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
    Fechado
</span>
```

#### Badges de Prioridade
```html
<!-- Crítica -->
<span class="inline-flex items-center gap-1.5 bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
    <svg class="w-1.5 h-1.5 fill-red-500" viewBox="0 0 6 6">
        <circle cx="3" cy="3" r="3" />
    </svg>
    Crítica
</span>

<!-- Alta -->
<span class="inline-flex items-center gap-1.5 bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full">
    <svg class="w-1.5 h-1.5 fill-orange-500" viewBox="0 0 6 6">
        <circle cx="3" cy="3" r="3" />
    </svg>
    Alta
</span>

<!-- Normal -->
<span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
    <svg class="w-1.5 h-1.5 fill-yellow-500" viewBox="0 0 6 6">
        <circle cx="3" cy="3" r="3" />
    </svg>
    Normal
</span>

<!-- Baixa -->
<span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
    <svg class="w-1.5 h-1.5 fill-green-500" viewBox="0 0 6 6">
        <circle cx="3" cy="3" r="3" />
    </svg>
    Baixa
</span>
```

### Modal de Criação de Ticket

```
┌─────────────────────────────────────────┐
│  Criar Novo Ticket                   ✕  │
├─────────────────────────────────────────┤
│                                         │
│  Título *                               │
│  [___________________________________]  │
│                                         │
│  Descrição *                            │
│  [___________________________________]  │
│  [___________________________________]  │
│  [___________________________________]  │
│                                         │
│  Categoria *        Prioridade *        │
│  [Suporte Técnico▼] [Normal ▼]          │
│                                         │
│  Anexos                                 │
│  📎 Clique ou arraste arquivos          │
│                                         │
├─────────────────────────────────────────┤
│                    [Cancelar] [Criar]   │
└─────────────────────────────────────────┘
```

---

## 📝 Features Detalhadas

### Feature 1: Dashboard

**Descrição:** Página inicial com visão geral do sistema

**Componentes:**
- 4 Cards de métricas principais
- Gráfico de tickets por período (últimos 30 dias)
- Lista de tickets recentes (últimos 10)
- Lista de tickets atribuídos ao usuário logado
- Indicadores de SLA (se implementado)

**Wireframe:**
```
┌──────────┬──────────┬──────────┬──────────┐
│  Total   │  Novos   │  Progr.  │  Resolv. │
│   145    │    23    │    45    │    77    │
└──────────┴──────────┴──────────┴──────────┘

┌─────────────────────────────────────────┐
│  Tickets por Período                    │
│  [Gráfico de Linha/Barra]               │
└─────────────────────────────────────────┘

┌────────────────────┬────────────────────┐
│  Tickets Recentes  │  Meus Tickets      │
│  #1234 - Login...  │  #5678 - Bug...    │
│  #1233 - Bug...    │  #5677 - Config... │
└────────────────────┴────────────────────┘
```

### Feature 2: Listagem de Tickets

**Descrição:** Tabela com todos os tickets e filtros

**Funcionalidades:**
- Paginação (10, 25, 50, 100 por página)
- Ordenação por coluna (clique no header)
- Busca por palavra-chave (full-text search)
- Filtros múltiplos:
  - Status (checkboxes múltiplos)
  - Prioridade (checkboxes múltiplos)
  - Categoria (dropdown)
  - Responsável (dropdown com busca)
  - Data (range picker)
- Ações em massa (opcional):
  - Atribuir múltiplos tickets
  - Mudar prioridade em lote
  - Fechar múltiplos tickets

**Colunas da Tabela:**
| Coluna | Tipo | Largura | Ordenável |
|--------|------|---------|-----------|
| ID | Numérico | 80px | Sim |
| Título | Texto | 300px | Sim |
| Solicitante | User | 150px | Sim |
| Responsável | User | 150px | Sim |
| Categoria | Badge | 120px | Sim |
| Prioridade | Badge | 100px | Sim |
| Status | Badge | 120px | Sim |
| Criado em | Data | 120px | Sim |
| Ações | Botões | 100px | Não |

### Feature 3: Visualizar Ticket

**Descrição:** Página detalhada de um ticket individual

**Seções:**
1. **Header**
   - Título do ticket
   - Badges de status e prioridade
   - Botões de ação (Editar, Fechar, Atribuir)

2. **Informações Principais** (Grid 2 colunas)
   - ID do ticket
   - Solicitante (avatar + nome)
   - Responsável (avatar + nome ou "Não atribuído")
   - Categoria
   - Prioridade
   - Status
   - Criado em
   - Atualizado em
   - Resolvido em (se aplicável)
   - Fechado em (se aplicável)

3. **Descrição**
   - Texto completo da descrição
   - Formatação preservada

4. **Anexos**
   - Lista de arquivos anexados
   - Download individual
   - Preview de imagens

5. **Timeline de Atividades**
   - Comentários públicos
   - Notas internas (apenas para agentes/admin)
   - Histórico de mudanças
   - Ordenação cronológica inversa (mais recente primeiro)

6. **Adicionar Comentário**
   - Editor de texto
   - Checkbox "Nota interna" (apenas agentes/admin)
   - Upload de anexos
   - Botão "Enviar"

### Feature 4: Criar/Editar Ticket

**Campos do Formulário:**

| Campo | Tipo | Obrigatório | Validação |
|-------|------|-------------|-----------|
| Título | Text | Sim | Min 10, Max 255 caracteres |
| Descrição | Textarea | Sim | Min 20 caracteres |
| Categoria | Select | Sim | Deve existir no banco |
| Prioridade | Select | Sim | Deve existir no banco |
| Atribuir a | Select | Não | Apenas agentes/admin |
| Anexos | File Upload | Não | Max 5MB, tipos permitidos |

**Validações:**
- Client-side (Alpine.js)
- Server-side (CodeIgniter Validation)
- Mensagens de erro claras e específicas

**Comportamento:**
- Modal ou página dedicada (configurável)
- Loading state no botão de submit
- Confirmação visual após sucesso
- Redirecionamento para o ticket criado/editado

### Feature 5: Sistema de Comentários

**Tipos de Comentário:**
1. **Comentário Público**
   - Visível para cliente e equipe
   - Notificação por email para todos os envolvidos
   - Ícone: 💬

2. **Nota Interna**
   - Visível apenas para agentes e admin
   - Não notifica o cliente
   - Ícone: 🔒

**Layout de Comentário:**
```
┌──────────────────────────────────────────┐
│ 👤 João Silva (Agente)    17/11 às 14:30│
├──────────────────────────────────────────┤
│ Olá! Verifiquei o problema e identifiquei│
│ que é necessário resetar a senha.        │
│                                          │
│ Enviei as instruções por email.          │
└──────────────────────────────────────────┘
```

### Feature 6: Sistema de Notificações

**Eventos que Geram Notificações:**

| Evento | Destinatários | Tipo |
|--------|---------------|------|
| Ticket criado | Cliente, Admin | Email |
| Ticket atribuído | Responsável | Email + In-app |
| Status alterado | Cliente, Responsável | Email |
| Novo comentário | Cliente, Responsável | Email + In-app |
| Ticket resolvido | Cliente | Email |
| Ticket fechado | Cliente, Responsável | Email |
| SLA próximo ao vencimento | Responsável, Admin | Email + In-app |

**Template de Email:**
```
Assunto: [Ticket #1234] Novo comentário adicionado

Olá João,

Um novo comentário foi adicionado ao ticket #1234 - "Problema com login"

Comentário de Maria Silva:
"Já resetei sua senha. Verifique seu email."

Status: Em Progresso
Prioridade: Alta

Ver ticket completo: https://tickets.app/view/1234

---
Sistema de Tickets
```

### Feature 7: Busca e Filtros

**Busca Global:**
- Full-text search em: título + descrição
- Busca em tempo real (debounce de 500ms)
- Highlight dos termos encontrados
- Sugestões autocomplete (opcional)

**Filtros Disponíveis:**
```
┌─────────────────────────────────────────┐
│ 🔍 [Buscar tickets...]                  │
├─────────────────────────────────────────┤
│ Status:                                 │
│ ☐ Novo  ☐ Aberto  ☐ Em Progresso       │
│ ☐ Pendente  ☐ Resolvido  ☐ Fechado     │
├─────────────────────────────────────────┤
│ Prioridade:                             │
│ ☐ Crítica  ☐ Alta  ☐ Normal  ☐ Baixa   │
├─────────────────────────────────────────┤
│ Categoria: [Todas ▼]                    │
│ Responsável: [Todos ▼]                  │
│ Data: [Último mês ▼]                    │
├─────────────────────────────────────────┤
│         [Limpar] [Aplicar Filtros]      │
└─────────────────────────────────────────┘
```

**Filtros Salvos (opcional):**
- "Meus tickets abertos"
- "Prioridade alta não atribuídos"
- "Tickets vencidos"
- Criar filtro customizado

### Feature 8: Permissões e Roles

**Níveis de Acesso:**

#### Admin
- ✅ Criar/editar/excluir tickets
- ✅ Atribuir tickets para qualquer agente
- ✅ Ver todos os tickets
- ✅ Gerenciar usuários
- ✅ Gerenciar categorias e configurações
- ✅ Ver relatórios completos
- ✅ Excluir comentários

#### Agente
- ✅ Criar/editar tickets
- ✅ Ver todos os tickets
- ✅ Atribuir tickets para si mesmo
- ✅ Adicionar comentários públicos e notas internas
- ✅ Mudar status e prioridade
- ✅ Ver relatórios básicos
- ❌ Gerenciar usuários
- ❌ Gerenciar configurações

#### Cliente
- ✅ Criar tickets
- ✅ Ver apenas seus próprios tickets
- ✅ Adicionar comentários públicos
- ✅ Anexar arquivos
- ❌ Ver tickets de outros clientes
- ❌ Ver notas internas
- ❌ Atribuir tickets
- ❌ Mudar status (exceto reabrir)
- ❌ Acessar configurações

**Implementação:**
```php
// app/Filters/RoleFilter.php
if (!in_array(session('role'), ['admin', 'agent'])) {
    return redirect()->to('/unauthorized');
}
```

---

## 🚀 Roadmap de Desenvolvimento

### Fase 1: Setup e Fundação (Semana 1)
- [ ] Instalar CodeIgniter 4 via Composer
- [ ] Configurar MySQL e criar database
- [ ] Configurar `.env` (database, email)
- [ ] Instalar CodeIgniter Shield (autenticação)
- [ ] Criar migrations para todas as tabelas
- [ ] Criar seeders para dados iniciais
- [ ] Integrar Tailwind CSS + Flowbite
- [ ] Criar layout base (header, sidebar, footer)
- [ ] Implementar sistema de autenticação (login/logout)

**Entregável:** Sistema básico rodando com login funcional

### Fase 2: CRUD de Tickets (Semana 2)
- [ ] Criar Model `TicketModel` com relações
- [ ] Criar Controller `Tickets.php`
- [ ] Implementar listagem de tickets
- [ ] Implementar criação de ticket (modal)
- [ ] Implementar visualização de ticket individual
- [ ] Implementar edição de ticket
- [ ] Implementar sistema de status
- [ ] Adicionar validações server-side
- [ ] Implementar badges coloridos (prioridade/status)

**Entregável:** CRUD completo de tickets funcionando

### Fase 3: Features Essenciais (Semana 3)
- [ ] Implementar sistema de categorias
- [ ] Implementar atribuição de tickets
- [ ] Implementar sistema de comentários
- [ ] Criar histórico de atividades
- [ ] Implementar upload de anexos
- [ ] Criar dashboard com métricas
- [ ] Implementar busca full-text
- [ ] Implementar filtros avançados

**Entregável:** Sistema funcional com features principais

### Fase 4: DataTables e HTMX (Semana 4)
- [ ] Integrar DataTables na listagem
- [ ] Configurar paginação server-side
- [ ] Implementar ordenação por colunas
- [ ] Adicionar HTMX para ações dinâmicas
- [ ] Implementar Alpine.js nos modais
- [ ] Criar filtros com HTMX (live reload)
- [ ] Otimizar queries do banco
- [ ] Adicionar loading states

**Entregável:** Interface moderna e responsiva

### Fase 5: Notificações e Emails (Semana 5)
- [ ] Configurar envio de emails (SMTP)
- [ ] Criar templates de email
- [ ] Implementar notificações de criação
- [ ] Implementar notificações de comentários
- [ ] Implementar notificações de mudança de status
- [ ] Criar sistema de preferências de notificação
- [ ] Implementar notificações in-app (badge no sino)

**Entregável:** Sistema completo de notificações

### Fase 6: Polimento e Testes (Semana 6)
- [ ] Revisar responsividade (mobile/tablet)
- [ ] Adicionar validações client-side (Alpine.js)
- [ ] Implementar mensagens de feedback (toasts)
- [ ] Otimizar performance (caching, índices)
- [ ] Criar página de perfil de usuário
- [ ] Implementar dark mode (opcional)
- [ ] Testes manuais completos
- [ ] Correção de bugs

**Entregável:** Sistema pronto para produção (MVP)

### Fase 7: Features Avançadas (Futuro)
- [ ] Implementar SLA management
- [ ] Criar respostas prontas (macros)
- [ ] Implementar atribuição automática
- [ ] Criar relatórios avançados
- [ ] Implementar pesquisa de satisfação
- [ ] Criar portal do cliente
- [ ] Implementar base de conhecimento
- [ ] Integração com Slack/Teams

---

## 📊 Métricas de Sucesso

### KPIs do Sistema:
- **Performance:** Tempo de carregamento < 2s
- **Usabilidade:** Criar ticket em < 1 minuto
- **Confiabilidade:** Uptime > 99%
- **Satisfação:** CSAT > 4.5/5

### Métricas de Negócio:
- Total de tickets criados
- Tempo médio de resolução
- Taxa de tickets resolvidos no primeiro contato
- Tickets por categoria
- Tickets por agente
- SLA compliance rate

---

## 🔐 Segurança

### Medidas Implementadas:
- ✅ Proteção CSRF (CodeIgniter built-in)
- ✅ Prepared Statements (SQL Injection)
- ✅ Password Hashing (bcrypt via Shield)
- ✅ XSS Protection (CodeIgniter escaping)
- ✅ HTTPS obrigatório (produção)
- ✅ Rate Limiting (login attempts)
- ✅ File Upload Validation (tipo, tamanho)
- ✅ Role-Based Access Control (RBAC)

---

## 📚 Referências e Recursos

### Documentação:
- CodeIgniter 4: https://codeigniter.com/user_guide/
- Tailwind CSS: https://tailwindcss.com/docs
- Flowbite: https://flowbite.com
- Alpine.js: https://alpinejs.dev
- HTMX: https://htmx.org/docs/
- DataTables: https://datatables.net

### Inspirações de Design:
- Zendesk: https://www.zendesk.com
- Freshdesk: https://freshdesk.com
- Linear: https://linear.app
- Notion: https://notion.so

---

## 🎯 Próximos Passos

1. **Aprovação do Planejamento**
   - Revisar este documento
   - Ajustar prioridades se necessário
   - Confirmar stack tecnológica

2. **Setup do Ambiente**
   - Instalar dependências
   - Configurar database
   - Criar estrutura base

3. **Iniciar Desenvolvimento**
   - Seguir roadmap da Fase 1
   - Commits frequentes
   - Code review contínuo

---

**Documento criado em:** 17/11/2025
**Versão:** 1.0
**Autor:** Claude Code (com pesquisa de mercado atualizada)
