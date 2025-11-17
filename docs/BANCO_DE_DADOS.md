# Documentação do Banco de Dados - Sistema de Tickets

## 📊 Informações Gerais

- **Banco:** tickets_db
- **Usuário:** tickets_user
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci
- **SGBD:** MySQL 8.0+

---

## 🗄️ Estrutura das Tabelas

### 1. usuarios

Armazena todos os usuários do sistema (administradores, agentes e clientes).

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único do usuário |
| `nome` | VARCHAR(100) | Nome completo do usuário |
| `email` | VARCHAR(255) UNIQUE | Email para login (único) |
| `senha` | VARCHAR(255) | Senha criptografada (bcrypt) |
| `funcao` | ENUM | Função do usuário: 'admin', 'agente', 'cliente' |
| `avatar` | VARCHAR(255) | Caminho para foto do usuário (opcional) |
| `ativo` | TINYINT | Status do usuário (1=ativo, 0=inativo) |
| `criado_em` | DATETIME | Data de criação do registro |
| `atualizado_em` | DATETIME | Data da última atualização |

**Índices:**
- PRIMARY KEY: `id`
- INDEX: `funcao`
- UNIQUE INDEX: `email` (automático)

**Dados Iniciais:**
- 1 Administrador: admin@tickets.com
- 3 Agentes
- 3 Clientes
- **Senha padrão:** 123456

---

### 2. categorias

Categorias para classificação dos tickets.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único da categoria |
| `nome` | VARCHAR(100) | Nome da categoria |
| `descricao` | TEXT | Descrição detalhada da categoria |
| `cor` | VARCHAR(7) | Cor em hexadecimal para identificação visual |
| `icone` | VARCHAR(50) | Nome do ícone (Heroicons) |
| `ativo` | TINYINT | Status da categoria (1=ativo, 0=inativo) |
| `criado_em` | DATETIME | Data de criação do registro |
| `atualizado_em` | DATETIME | Data da última atualização |

**Índices:**
- PRIMARY KEY: `id`

**Dados Iniciais:**
1. Suporte Técnico (#3B82F6)
2. Financeiro (#10B981)
3. Comercial (#8B5CF6)
4. Recursos Humanos (#F59E0B)
5. Infraestrutura (#EF4444)
6. Outros (#6B7280)

---

### 3. prioridades

Níveis de prioridade dos tickets.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único da prioridade |
| `nome` | VARCHAR(50) | Nome da prioridade |
| `nivel` | INT UNIQUE | Nível numérico (1-4) |
| `cor` | VARCHAR(7) | Cor em hexadecimal |
| `criado_em` | DATETIME | Data de criação do registro |

**Índices:**
- PRIMARY KEY: `id`
- UNIQUE INDEX: `nivel`

**Dados Iniciais:**
| Nível | Nome | Cor |
|-------|------|-----|
| 1 | Baixa | #10B981 (Verde) |
| 2 | Normal | #EAB308 (Amarelo) |
| 3 | Alta | #F97316 (Laranja) |
| 4 | Crítica | #EF4444 (Vermelho) |

---

### 4. tickets

Tabela principal do sistema - armazena todos os tickets.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único do ticket |
| `titulo` | VARCHAR(255) | Título do ticket |
| `descricao` | TEXT | Descrição detalhada do problema |
| `usuario_id` | INT UNSIGNED (FK) | ID do usuário que abriu o ticket |
| `responsavel_id` | INT UNSIGNED (FK) | ID do agente responsável (opcional) |
| `categoria_id` | INT UNSIGNED (FK) | ID da categoria (opcional) |
| `prioridade_id` | INT UNSIGNED (FK) | ID da prioridade (padrão: 2 - Normal) |
| `status` | ENUM | Status: 'novo', 'aberto', 'em_progresso', 'pendente', 'resolvido', 'fechado' |
| `data_vencimento` | DATETIME | Data limite para resolução (opcional) |
| `resolvido_em` | DATETIME | Data/hora que foi marcado como resolvido |
| `fechado_em` | DATETIME | Data/hora que foi fechado |
| `criado_em` | DATETIME | Data de criação do ticket |
| `atualizado_em` | DATETIME | Data da última atualização |

**Índices:**
- PRIMARY KEY: `id`
- INDEX: `status`, `prioridade_id`, `responsavel_id`, `criado_em`

**Foreign Keys:**
- `usuario_id` → `usuarios(id)` ON DELETE CASCADE
- `responsavel_id` → `usuarios(id)` ON DELETE SET NULL
- `categoria_id` → `categorias(id)` ON DELETE SET NULL
- `prioridade_id` → `prioridades(id)` ON DELETE RESTRICT

**Ciclo de Vida (Status):**
```
NOVO → ABERTO → EM_PROGRESSO → RESOLVIDO → FECHADO
         ↓
      PENDENTE
```

---

### 5. comentarios

Comentários e notas nos tickets.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único do comentário |
| `ticket_id` | INT UNSIGNED (FK) | ID do ticket |
| `usuario_id` | INT UNSIGNED (FK) | ID do usuário que comentou |
| `conteudo` | TEXT | Conteúdo do comentário |
| `eh_interno` | TINYINT | Se é nota interna (1) ou comentário público (0) |
| `criado_em` | DATETIME | Data do comentário |
| `atualizado_em` | DATETIME | Data da última edição |

**Índices:**
- PRIMARY KEY: `id`

**Foreign Keys:**
- `ticket_id` → `tickets(id)` ON DELETE CASCADE
- `usuario_id` → `usuarios(id)` ON DELETE CASCADE

**Tipos:**
- **Comentário Público (0):** Visível para cliente e equipe
- **Nota Interna (1):** Visível apenas para agentes e admin

---

### 6. anexos

Arquivos anexados aos tickets.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único do anexo |
| `ticket_id` | INT UNSIGNED (FK) | ID do ticket |
| `nome_arquivo` | VARCHAR(255) | Nome original do arquivo |
| `caminho_arquivo` | VARCHAR(500) | Caminho do arquivo no servidor |
| `tamanho_arquivo` | INT UNSIGNED | Tamanho em bytes |
| `tipo_mime` | VARCHAR(100) | Tipo MIME do arquivo |
| `enviado_por` | INT UNSIGNED (FK) | ID do usuário que enviou |
| `criado_em` | DATETIME | Data do upload |

**Índices:**
- PRIMARY KEY: `id`

**Foreign Keys:**
- `ticket_id` → `tickets(id)` ON DELETE CASCADE
- `enviado_por` → `usuarios(id)` ON DELETE CASCADE

**Limites:**
- Tamanho máximo por arquivo: 5MB
- Formatos permitidos: jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, txt, zip

---

### 7. historico_tickets

Registro de todas as mudanças nos tickets (auditoria).

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT UNSIGNED (PK) | Identificador único do histórico |
| `ticket_id` | INT UNSIGNED (FK) | ID do ticket |
| `usuario_id` | INT UNSIGNED (FK) | ID do usuário que fez a ação |
| `acao` | VARCHAR(50) | Tipo de ação realizada |
| `campo` | VARCHAR(50) | Campo que foi alterado (opcional) |
| `valor_antigo` | VARCHAR(255) | Valor anterior do campo |
| `valor_novo` | VARCHAR(255) | Novo valor do campo |
| `criado_em` | DATETIME | Data/hora da ação |

**Índices:**
- PRIMARY KEY: `id`

**Foreign Keys:**
- `ticket_id` → `tickets(id)` ON DELETE CASCADE
- `usuario_id` → `usuarios(id)` ON DELETE CASCADE

**Ações Registradas:**
- `criado`: Ticket criado
- `atualizado`: Ticket atualizado
- `status_alterado`: Mudança de status
- `prioridade_alterada`: Mudança de prioridade
- `atribuido`: Ticket atribuído a um agente
- `comentario_adicionado`: Novo comentário
- `anexo_adicionado`: Novo anexo
- `resolvido`: Ticket marcado como resolvido
- `fechado`: Ticket fechado
- `reaberto`: Ticket reaberto

---

## 🔗 Diagrama de Relacionamentos

```
┌─────────────┐         ┌─────────────┐         ┌─────────────┐
│  USUARIOS   │         │   TICKETS   │         │ CATEGORIAS  │
├─────────────┤         ├─────────────┤         ├─────────────┤
│ id (PK)     │────┐    │ id (PK)     │    ┌────│ id (PK)     │
│ nome        │    │    │ titulo      │    │    │ nome        │
│ email       │    │    │ descricao   │    │    │ descricao   │
│ senha       │    │    │ usuario_id  ├────┘    │ cor         │
│ funcao      │    │    │ responsavel ├────┐    │ icone       │
│ avatar      │    │    │ categoria   ├────┘    └─────────────┘
│ ativo       │    │    │ prioridade  ├───┐
└─────────────┘    │    │ status      │   │     ┌─────────────┐
                   │    │ data_venc.  │   └────▶│ PRIORIDADES │
                   │    │ resolvido_em│         ├─────────────┤
                   │    │ fechado_em  │         │ id (PK)     │
                   │    └─────────────┘         │ nome        │
                   │            │               │ nivel       │
                   │            │               │ cor         │
                   │            │               └─────────────┘
                   │            │
                   │            │    ┌─────────────────┐
                   │            └───▶│  COMENTARIOS    │
                   │                 ├─────────────────┤
                   │                 │ id (PK)         │
                   │                 │ ticket_id (FK)  │
                   │                 │ usuario_id (FK)─┼──┘
                   │                 │ conteudo        │
                   │                 │ eh_interno      │
                   │                 └─────────────────┘
                   │
                   │                 ┌─────────────────┐
                   │            ┌───▶│    ANEXOS       │
                   │            │    ├─────────────────┤
                   │            │    │ id (PK)         │
                   │            │    │ ticket_id (FK)  │
                   │            │    │ nome_arquivo    │
                   │            │    │ caminho_arquivo │
                   │            │    │ tamanho_arquivo │
                   │            │    │ tipo_mime       │
                   │            │    │ enviado_por(FK)─┼──┘
                   │            │    └─────────────────┘
                   │            │
                   │            │    ┌──────────────────────┐
                   │            └───▶│ HISTORICO_TICKETS    │
                   │                 ├──────────────────────┤
                   │                 │ id (PK)              │
                   │                 │ ticket_id (FK)       │
                   │                 │ usuario_id (FK)──────┼──┘
                   │                 │ acao                 │
                   │                 │ campo                │
                   │                 │ valor_antigo         │
                   │                 │ valor_novo           │
                   └─────────────────┴──────────────────────┘
```

---

## 📝 Queries Úteis

### Listar todos os tickets com detalhes
```sql
SELECT
    t.id,
    t.titulo,
    u.nome AS solicitante,
    r.nome AS responsavel,
    c.nome AS categoria,
    p.nome AS prioridade,
    t.status,
    t.criado_em
FROM tickets t
LEFT JOIN usuarios u ON t.usuario_id = u.id
LEFT JOIN usuarios r ON t.responsavel_id = r.id
LEFT JOIN categorias c ON t.categoria_id = c.id
LEFT JOIN prioridades p ON t.prioridade_id = p.id
ORDER BY t.criado_em DESC;
```

### Tickets abertos por prioridade
```sql
SELECT
    p.nome AS prioridade,
    COUNT(*) AS total
FROM tickets t
JOIN prioridades p ON t.prioridade_id = p.id
WHERE t.status IN ('novo', 'aberto', 'em_progresso')
GROUP BY p.nome, p.nivel
ORDER BY p.nivel DESC;
```

### Tickets por agente
```sql
SELECT
    u.nome AS agente,
    COUNT(*) AS total_tickets,
    SUM(CASE WHEN t.status = 'resolvido' THEN 1 ELSE 0 END) AS resolvidos,
    SUM(CASE WHEN t.status IN ('novo', 'aberto', 'em_progresso') THEN 1 ELSE 0 END) AS abertos
FROM tickets t
JOIN usuarios u ON t.responsavel_id = u.id
WHERE u.funcao = 'agente'
GROUP BY u.id, u.nome
ORDER BY total_tickets DESC;
```

### Histórico completo de um ticket
```sql
SELECT
    h.criado_em,
    u.nome AS usuario,
    h.acao,
    h.campo,
    h.valor_antigo,
    h.valor_novo
FROM historico_tickets h
JOIN usuarios u ON h.usuario_id = u.id
WHERE h.ticket_id = 1
ORDER BY h.criado_em ASC;
```

### Comentários de um ticket
```sql
SELECT
    c.id,
    u.nome AS autor,
    c.conteudo,
    c.eh_interno,
    c.criado_em
FROM comentarios c
JOIN usuarios u ON c.usuario_id = u.id
WHERE c.ticket_id = 1
ORDER BY c.criado_em ASC;
```

---

## 🔐 Credenciais de Acesso

### Banco de Dados
- **Host:** localhost
- **Porta:** 3306
- **Database:** tickets_db
- **Usuário:** tickets_user
- **Senha:** tickets_pass_2024

### Usuários do Sistema

#### Administrador
- **Email:** admin@tickets.com
- **Senha:** 123456
- **Função:** admin

#### Agentes
- joao.silva@tickets.com (Senha: 123456)
- maria.santos@tickets.com (Senha: 123456)
- carlos.oliveira@tickets.com (Senha: 123456)

#### Clientes
- ana.costa@cliente.com (Senha: 123456)
- pedro.almeida@cliente.com (Senha: 123456)
- juliana.ferreira@cliente.com (Senha: 123456)

---

## 🚀 Comandos Úteis

### Migrations
```bash
# Executar todas as migrations
php spark migrate

# Fazer rollback da última migration
php spark migrate:rollback

# Ver status das migrations
php spark migrate:status

# Resetar banco (rollback total)
php spark migrate:rollback -all
```

### Seeders
```bash
# Executar um seeder específico
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder

# Executar todos os seeders
php spark db:seed PrioridadesSeeder && php spark db:seed CategoriasSeeder && php spark db:seed UsuariosSeeder
```

### Backup do Banco
```bash
# Fazer backup
mysqldump -u tickets_user -p tickets_db > backup_tickets_$(date +%Y%m%d_%H%M%S).sql

# Restaurar backup
mysql -u tickets_user -p tickets_db < backup_tickets_20250117.sql
```

---

## 📚 Observações Importantes

1. **Senhas**: Sempre use `password_hash()` do PHP para criptografar senhas. Nunca armazene senhas em texto puro.

2. **Foreign Keys**: As foreign keys estão configuradas com:
   - `CASCADE`: Deleta registros relacionados automaticamente
   - `SET NULL`: Define como NULL quando o registro pai é deletado
   - `RESTRICT`: Impede deletar se houver registros relacionados

3. **Timestamps**: Todos os `criado_em` e `atualizado_em` são gerenciados automaticamente pelo CodeIgniter.

4. **Charset**: Utiliza `utf8mb4` para suportar emojis e caracteres especiais.

5. **Índices**: Índices criados nos campos mais consultados para otimizar performance.

6. **Histórico**: TODAS as ações importantes devem ser registradas na tabela `historico_tickets` para auditoria.

---

**Documento atualizado em:** 17/11/2025
**Versão do Banco:** 1.0
**CodeIgniter:** 4.6.3
**MySQL:** 8.0+
