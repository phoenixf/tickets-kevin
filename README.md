# 🎫 Sistema de Gestão de Tickets

Sistema moderno de gestão de tickets desenvolvido em **PHP** com **CodeIgniter 4**, **Tailwind CSS**, **MySQL** e ferramentas modernas de frontend.

[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.6.3-orange.svg)](https://codeigniter.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

---

## 📋 Sobre o Projeto

Sistema completo de help desk para gerenciamento de tickets de suporte, desenvolvido com foco em **simplicidade**, **performance** e **visual moderno**.

### ✨ Características

- 🎨 **Interface Moderna** - Design responsivo com Tailwind CSS + Flowbite
- ⚡ **Performance** - CodeIgniter 4 é um dos frameworks PHP mais rápidos
- 🔐 **Seguro** - Proteção contra CSRF, XSS, SQL Injection
- 🌍 **PT-BR** - Totalmente em português do Brasil
- 📱 **Responsivo** - Funciona perfeitamente em desktop, tablet e mobile
- 🎯 **MVC** - Arquitetura Model-View-Controller bem estruturada

---

## 🚀 Stack Tecnológica

### Backend
- **Framework:** CodeIgniter 4.6.3
- **Linguagem:** PHP 8.4+
- **Banco de Dados:** MySQL 8.0+
- **ORM:** CodeIgniter Query Builder

### Frontend (Planejado)
- **CSS Framework:** Tailwind CSS 3.x
- **Componentes:** Flowbite (600+ componentes prontos)
- **Interatividade:** Alpine.js (leve, ~15kb)
- **AJAX:** HTMX 1.9+
- **Tabelas:** DataTables
- **Ícones:** Heroicons

---

## 📊 Estrutura do Banco de Dados

O sistema possui **7 tabelas** em PT-BR:

| Tabela | Descrição |
|--------|-----------|
| `usuarios` | Administradores, agentes e clientes |
| `categorias` | Categorias dos tickets (Suporte, Financeiro, etc.) |
| `prioridades` | Níveis de prioridade (Baixa, Normal, Alta, Crítica) |
| `tickets` | Tabela principal de tickets |
| `comentarios` | Comentários e notas nos tickets |
| `anexos` | Arquivos anexados aos tickets |
| `historico_tickets` | Auditoria de todas as mudanças |

### Sistema de Cores das Prioridades

```
🟢 Baixa    → #10B981 (Verde)
🟡 Normal   → #EAB308 (Amarelo)
🟠 Alta     → #F97316 (Laranja)
🔴 Crítica  → #EF4444 (Vermelho)
```

Veja documentação completa em [BANCO_DE_DADOS.md](BANCO_DE_DADOS.md)

---

## 🛠️ Instalação

### Pré-requisitos

- PHP 8.1 ou superior
- MySQL 8.0 ou superior
- Composer
- Extensões PHP: `mysqli`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `intl`

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/phoenixf/tickets-kevin.git
cd tickets-kevin
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o banco de dados**
```bash
# Crie o banco de dados
mysql -u root -p
CREATE DATABASE tickets_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tickets_user'@'localhost' IDENTIFIED BY 'sua_senha_aqui';
GRANT ALL PRIVILEGES ON tickets_db.* TO 'tickets_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

4. **Configure o .env**
```bash
cp env .env
```

Edite o `.env` e configure:
```env
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080'

database.default.hostname = localhost
database.default.database = tickets_db
database.default.username = tickets_user
database.default.password = sua_senha_aqui
database.default.DBDriver = MySQLi
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
```

5. **Gere a chave de encriptação**
```bash
php spark key:generate --show
```

Copie a chave gerada e adicione no `.env`:
```env
encryption.key = hex2bin:sua_chave_aqui
```

6. **Execute as migrations**
```bash
php spark migrate
```

7. **Popule o banco com dados iniciais**
```bash
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder
```

8. **Inicie o servidor de desenvolvimento**
```bash
php spark serve
```

Acesse: http://localhost:8080

---

## 👤 Credenciais de Acesso

### Usuários Padrão

| Função | Email | Senha |
|--------|-------|-------|
| **Admin** | admin@tickets.com | 123456 |
| **Agente** | joao.silva@tickets.com | 123456 |
| **Agente** | maria.santos@tickets.com | 123456 |
| **Agente** | carlos.oliveira@tickets.com | 123456 |
| **Cliente** | ana.costa@cliente.com | 123456 |
| **Cliente** | pedro.almeida@cliente.com | 123456 |
| **Cliente** | juliana.ferreira@cliente.com | 123456 |

⚠️ **IMPORTANTE:** Altere as senhas em produção!

---

## 📝 Funcionalidades

### Implementadas ✅
- [x] Estrutura do banco de dados completa
- [x] Migrations em PT-BR
- [x] Seeders com dados iniciais
- [x] Sistema de prioridades com cores
- [x] Sistema de categorias
- [x] Usuários (Admin, Agente, Cliente)

### Em Desenvolvimento 🚧
- [ ] Autenticação com CodeIgniter Shield
- [ ] CRUD de Tickets
- [ ] Sistema de comentários
- [ ] Upload de anexos
- [ ] Dashboard com métricas
- [ ] Busca e filtros avançados
- [ ] Notificações por email
- [ ] Histórico de atividades
- [ ] Sistema de permissões (RBAC)

### Planejadas 📅
- [ ] SLA Management
- [ ] Respostas prontas (Macros)
- [ ] Atribuição automática de tickets
- [ ] Pesquisa de satisfação (CSAT)
- [ ] Relatórios avançados
- [ ] Portal do cliente
- [ ] Base de conhecimento (FAQ)
- [ ] Integração com Slack/Teams

---

## 📖 Documentação

Toda documentação está organizada em [`docs/`](./docs/):

### 📚 Documentação do Projeto
- **[CLAUDE.md](./docs/projeto/CLAUDE.md)** - Guia para desenvolvimento com Claude Code
- **[CHANGELOG.md](./docs/projeto/CHANGELOG.md)** - Histórico de mudanças e versionamento

### 🛠️ Documentação de Desenvolvimento
- **[SETUP_INSTRUCTIONS.md](./docs/desenvolvimento/SETUP_INSTRUCTIONS.md)** - Guia de instalação e configuração
- **[SETUP_COMPLETE.md](./docs/desenvolvimento/SETUP_COMPLETE.md)** - Status de setup completo
- **[BANCO_DE_DADOS.md](./docs/desenvolvimento/BANCO_DE_DADOS.md)** - Documentação detalhada do banco, queries úteis
- **[TESTING.md](./docs/desenvolvimento/TESTING.md)** - Guia completo de testes
- **[TESTE_VISUAL.md](./docs/desenvolvimento/TESTE_VISUAL.md)** - Guia de testes visuais e Playwright
- **[TESTS_REPORT.md](./docs/desenvolvimento/TESTS_REPORT.md)** - Relatórios de testes

### 🎯 Documentação de Features
- **[PLANEJAMENTO.md](./docs/features/PLANEJAMENTO.md)** - Planejamento completo do sistema e roadmap
- **[TICKETS.md](./docs/features/TICKETS.md)** - Requisitos originais do sistema
- **[RELATORIOS.md](./docs/features/RELATORIOS.md)** - Documentação de relatórios e correções implementadas

---

## 🗂️ Estrutura de Diretórios

```
tickets-kevin/
├── app/
│   ├── Controllers/        # Controladores (Dashboard, Tickets, etc.)
│   ├── Models/             # Modelos (TicketModel, UserModel, etc.)
│   ├── Views/              # Templates HTML
│   ├── Database/
│   │   ├── Migrations/     # Migrations do banco (7 tabelas)
│   │   └── Seeds/          # Seeders de dados iniciais
│   ├── Config/             # Configurações do CodeIgniter
│   └── Helpers/            # Funções auxiliares
├── public/                 # Arquivos públicos (CSS, JS, imagens)
│   └── uploads/            # Anexos de tickets
├── writable/               # Logs, cache, sessões
├── vendor/                 # Dependências do Composer
├── .env                    # Configurações (não versionado)
├── composer.json           # Dependências PHP
└── spark                   # CLI do CodeIgniter
```

---

## 🧪 Comandos Úteis

### Migrations
```bash
# Executar migrations
php spark migrate

# Rollback
php spark migrate:rollback

# Status
php spark migrate:status
```

### Seeders
```bash
# Executar seeder específico
php spark db:seed NomeSeeder

# Executar todos
php spark db:seed PrioridadesSeeder && php spark db:seed CategoriasSeeder && php spark db:seed UsuariosSeeder
```

### Desenvolvimento
```bash
# Servidor de desenvolvimento
php spark serve

# Criar migration
php spark make:migration NomeMigration

# Criar model
php spark make:model NomeModel

# Criar controller
php spark make:controller NomeController

# Criar seeder
php spark make:seeder NomeSeeder
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para:

1. Fazer fork do projeto
2. Criar uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abrir um Pull Request

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 🙏 Agradecimentos

- **CodeIgniter Team** - Framework PHP incrível
- **Tailwind Labs** - Tailwind CSS
- **Flowbite** - Componentes UI lindos
- **Alpine.js** - Interatividade simples e leve
- **HTMX** - AJAX moderno sem JavaScript complexo

---

## 📞 Contato

Para dúvidas ou sugestões, abra uma [issue](https://github.com/phoenixf/tickets-kevin/issues).

---

<p align="center">
  Feito com ❤️ usando CodeIgniter 4
</p>

<p align="center">
  <sub>🤖 Desenvolvido com ajuda do <a href="https://claude.com/claude-code">Claude Code</a></sub>
</p>
