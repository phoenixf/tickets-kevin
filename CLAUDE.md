# 🤖 Guia do Projeto para Claude Code

Este documento serve como **guia de contexto** para retomar o desenvolvimento do projeto após pausas ou crashes.

---

## 📍 Estado Atual do Projeto

**Versão**: v0.1.0
**Última Atualização**: 2025-11-17
**Status**: ✅ Setup completo, pronto para desenvolvimento

---

## 🎯 Objetivo do Projeto

Sistema moderno de gestão de tickets (help desk) em PHP com CodeIgniter 4, focado em:
- **Simplicidade**: Fácil de usar e manter
- **Performance**: CodeIgniter 4 é extremamente rápido
- **Visual Moderno**: Tailwind CSS + Flowbite + Alpine.js + HTMX
- **Boas Práticas**: MVC, testes, documentação completa

---

## 📊 O que Já Foi Feito (v0.1.0)

### ✅ Ambiente Configurado
- PHP 8.4.14 instalado
- MySQL 8.0.43 instalado e rodando
- Composer 2.9.1 instalado
- CodeIgniter 4.6.3 instalado
- Todas as extensões PHP necessárias instaladas

### ✅ Banco de Dados
- **Database**: `tickets_db`
- **Usuário**: `tickets_user`
- **Senha**: `tickets_pass_2024`
- **7 tabelas criadas** em PT-BR:
  1. `usuarios` - Admin, agentes, clientes
  2. `categorias` - 6 categorias cadastradas
  3. `prioridades` - 4 níveis com cores
  4. `tickets` - Tabela principal
  5. `comentarios` - Comentários e notas internas
  6. `anexos` - Arquivos anexados
  7. `historico_tickets` - Auditoria

### ✅ Migrations e Seeders
- 7 migrations criadas e executadas
- 3 seeders criados e executados:
  - 4 prioridades
  - 6 categorias
  - 7 usuários (1 admin, 3 agentes, 3 clientes)

### ✅ Documentação
- `docs/PLANEJAMENTO.md` - Arquitetura completa (800+ linhas)
- `docs/BANCO_DE_DADOS.md` - Schema, queries, diagramas
- `docs/tickets.md` - Requisitos originais
- `README.md` - Documentação principal
- `CHANGELOG.md` - Histórico de mudanças
- `CLAUDE.md` - Este arquivo

### ✅ Versionamento
- Git inicializado
- Repositório no GitHub: https://github.com/phoenixf/tickets-kevin
- Branch principal: `main`
- 2 commits realizados

---

## 🗺️ Roadmap de Desenvolvimento

### Próxima Versão: v0.2.0 (Autenticação)
**Prioridade:** ALTA

**Tarefas:**
1. ✅ Instalar CodeIgniter Shield via Composer
2. ✅ Configurar Shield no projeto
3. ✅ Executar migrations do Shield
4. ✅ Criar views de login/registro
5. ✅ Configurar redirecionamentos pós-login
6. ✅ Testar sistema de login

**Arquivos a Criar:**
- `app/Config/Auth.php` (configuração do Shield)
- `app/Views/auth/login.php`
- `app/Views/auth/register.php`
- `app/Filters/AuthFilter.php`

**Comandos:**
```bash
composer require codeigniter4/shield
php spark shield:setup
php spark migrate --all
```

---

### Versão: v0.3.0 (Models e Structure)
**Prioridade:** ALTA

**Tarefas:**
1. ✅ Criar Models com relacionamentos
2. ✅ Criar Validation Rules
3. ✅ Criar Entities (opcional)

**Arquivos a Criar:**
- `app/Models/TicketModel.php`
- `app/Models/UserModel.php`
- `app/Models/CategoryModel.php`
- `app/Models/PriorityModel.php`
- `app/Models/CommentModel.php`
- `app/Models/AttachmentModel.php`

**Exemplo de Model:**
```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'titulo', 'descricao', 'usuario_id', 'responsavel_id',
        'categoria_id', 'prioridade_id', 'status', 'data_vencimento'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    // Validation
    protected $validationRules = [
        'titulo'        => 'required|min_length[10]|max_length[255]',
        'descricao'     => 'required|min_length[20]',
        'usuario_id'    => 'required|integer',
        'prioridade_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'titulo' => [
            'required'   => 'O título é obrigatório',
            'min_length' => 'O título deve ter no mínimo 10 caracteres',
        ],
    ];

    // Relationships
    public function getWithRelations($id)
    {
        return $this->select('tickets.*,
                            usuarios.nome as usuario_nome,
                            responsavel.nome as responsavel_nome,
                            categorias.nome as categoria_nome,
                            prioridades.nome as prioridade_nome,
                            prioridades.cor as prioridade_cor')
                    ->join('usuarios', 'usuarios.id = tickets.usuario_id')
                    ->join('usuarios as responsavel', 'responsavel.id = tickets.responsavel_id', 'left')
                    ->join('categorias', 'categorias.id = tickets.categoria_id', 'left')
                    ->join('prioridades', 'prioridades.id = tickets.prioridade_id')
                    ->find($id);
    }
}
```

---

### Versão: v0.4.0 (CRUD de Tickets)
**Prioridade:** ALTA

**Tarefas:**
1. ✅ Criar Controller de Tickets
2. ✅ Criar Views de listagem
3. ✅ Criar Views de criação/edição
4. ✅ Implementar validações
5. ✅ Adicionar HTMX para interatividade

**Arquivos a Criar:**
- `app/Controllers/Tickets.php`
- `app/Views/tickets/index.php`
- `app/Views/tickets/create.php`
- `app/Views/tickets/edit.php`
- `app/Views/tickets/show.php`
- `app/Views/layouts/main.php`

---

### Versão: v0.5.0 (Frontend Moderno)
**Prioridade:** MÉDIA

**Tarefas:**
1. ✅ Integrar Tailwind CSS (via CDN ou build)
2. ✅ Integrar Flowbite components
3. ✅ Integrar Alpine.js
4. ✅ Integrar HTMX
5. ✅ Criar layout responsivo

---

### Versão: v0.6.0 (Comentários e Anexos)
**Prioridade:** MÉDIA

### Versão: v0.7.0 (Dashboard e Métricas)
**Prioridade:** MÉDIA

### Versão: v0.8.0 (Notificações)
**Prioridade:** BAIXA

### Versão: v1.0.0 (Primeira Release Estável)
**Prioridade:** OBJETIVO FINAL

---

## 📝 Convenções do Projeto

### Nomenclatura

**Tabelas do Banco** (PT-BR, plural, snake_case):
- ✅ `usuarios`, `tickets`, `categorias`, `prioridades`

**Models** (PT-BR ou EN, singular, PascalCase):
- ✅ `TicketModel`, `UserModel`, `CategoryModel`

**Controllers** (Plural, PascalCase):
- ✅ `Tickets`, `Users`, `Dashboard`

**Views** (snake_case, organizado por controller):
- ✅ `views/tickets/index.php`
- ✅ `views/tickets/create.php`

**Rotas** (kebab-case, RESTful):
- ✅ `GET /tickets` → lista
- ✅ `GET /tickets/create` → formulário
- ✅ `POST /tickets` → salvar
- ✅ `GET /tickets/(:num)` → visualizar
- ✅ `GET /tickets/(:num)/edit` → editar
- ✅ `PUT /tickets/(:num)` → atualizar
- ✅ `DELETE /tickets/(:num)` → deletar

---

## 🧪 Convenção de Testes

### Estrutura de Testes
```
tests/
├── database/           # Testes de banco de dados
│   ├── TicketModelTest.php
│   ├── UserModelTest.php
│   └── MigrationTest.php
├── unit/               # Testes unitários
│   ├── ValidationTest.php
│   └── HelperTest.php
└── feature/            # Testes de feature (E2E)
    ├── AuthTest.php
    ├── TicketCRUDTest.php
    └── DashboardTest.php
```

### Como Criar Testes

**1. Teste de Model (Database)**
```php
<?php
namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\TicketModel;

class TicketModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testCanCreateTicket()
    {
        $model = new TicketModel();

        $data = [
            'titulo'        => 'Teste de Ticket',
            'descricao'     => 'Descrição do teste com mais de 20 caracteres',
            'usuario_id'    => 1,
            'prioridade_id' => 2,
            'status'        => 'novo',
        ];

        $ticketId = $model->insert($data);

        $this->assertIsNumeric($ticketId);
        $this->seeInDatabase('tickets', ['id' => $ticketId]);
    }

    public function testValidationRules()
    {
        $model = new TicketModel();

        $data = [
            'titulo' => 'Curto', // Menos de 10 caracteres
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertNotEmpty($model->errors());
    }
}
```

**2. Teste de Feature (Integração)**
```php
<?php
namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class TicketCRUDTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed    = 'TestSeeder';

    public function testCanViewTicketsList()
    {
        $result = $this->withSession(['logged_in' => true])
                       ->get('tickets');

        $result->assertOK();
        $result->assertSee('Lista de Tickets');
    }

    public function testCanCreateTicket()
    {
        $result = $this->withSession(['logged_in' => true])
                       ->post('tickets', [
                           'titulo'        => 'Novo Ticket de Teste',
                           'descricao'     => 'Descrição completa do ticket de teste',
                           'prioridade_id' => 2,
                       ]);

        $result->assertRedirect();
        $this->seeInDatabase('tickets', ['titulo' => 'Novo Ticket de Teste']);
    }
}
```

### Executar Testes

```bash
# Todos os testes
./vendor/bin/phpunit

# Teste específico
./vendor/bin/phpunit tests/database/TicketModelTest.php

# Com coverage
./vendor/bin/phpunit --coverage-html coverage/

# Apenas testes de database
./vendor/bin/phpunit --group Database

# Apenas testes unitários
./vendor/bin/phpunit tests/unit/
```

### Sempre Testar

**Para cada nova funcionalidade:**
1. ✅ **Teste de Model** - Valida CRUD e validações
2. ✅ **Teste de Database** - Verifica se dados estão sendo gravados corretamente
3. ✅ **Teste de Controller** - Valida lógica de negócio
4. ✅ **Teste de Feature** - Valida fluxo completo (E2E)

**Verificações Obrigatórias:**
- ✅ Dados são inseridos no banco corretamente?
- ✅ Foreign keys estão funcionando?
- ✅ Validações estão bloqueando dados inválidos?
- ✅ Timestamps (criado_em, atualizado_em) estão sendo populados?
- ✅ Soft deletes (se aplicável) estão funcionando?
- ✅ Relacionamentos estão retornando dados corretos?

---

## 🔄 Workflow de Desenvolvimento

### Ao Iniciar uma Nova Feature

1. **Atualizar do repositório**
```bash
git pull origin main
```

2. **Criar branch da feature**
```bash
git checkout -b feature/nome-da-feature
```

3. **Desenvolver a feature**
- Criar migrations se necessário
- Criar/atualizar models
- Criar controllers
- Criar views
- **Criar testes** (OBRIGATÓRIO!)

4. **Testar localmente**
```bash
php spark serve
./vendor/bin/phpunit
```

5. **Commit com convenção**
```bash
git add .
git commit -m "feat: adiciona CRUD de tickets

- Cria TicketModel com validações
- Cria TicketController com métodos CRUD
- Cria views de listagem e criação
- Adiciona testes de model e feature

Testes: ✅ Todos passando
Database: ✅ Dados gravando corretamente

🤖 Generated with Claude Code
Co-Authored-By: Claude <noreply@anthropic.com>"
```

6. **Atualizar CHANGELOG.md**
```markdown
## [Unreleased]
### Added
- CRUD completo de tickets
- Testes de TicketModel
- Testes de feature para tickets
```

7. **Push para o repositório**
```bash
git push origin feature/nome-da-feature
```

8. **Merge para main** (após testes)
```bash
git checkout main
git merge feature/nome-da-feature
git push origin main
```

9. **Criar tag de versão** (se for release)
```bash
git tag -a v0.2.0 -m "Release v0.2.0 - Autenticação"
git push origin v0.2.0
```

---

## 🗄️ Comandos Importantes do Projeto

### MySQL
```bash
# Iniciar MySQL
sudo service mysql start

# Status
sudo service mysql status

# Acessar banco
sudo mysql -u tickets_user -ptickets_pass_2024 tickets_db

# Backup
mysqldump -u tickets_user -ptickets_pass_2024 tickets_db > backup_$(date +%Y%m%d).sql
```

### CodeIgniter
```bash
# Servidor de desenvolvimento
php spark serve

# Migrations
php spark migrate
php spark migrate:rollback
php spark migrate:status

# Seeders
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder

# Criar arquivos
php spark make:migration NomeMigration
php spark make:model NomeModel
php spark make:controller NomeController
php spark make:seeder NomeSeeder
php spark make:filter NomeFilter

# Limpar cache
php spark cache:clear

# Rotas
php spark routes
```

### Composer
```bash
# Instalar dependências
composer install

# Atualizar dependências
composer update

# Adicionar pacote
composer require vendor/package
```

### Testes
```bash
# Executar todos
./vendor/bin/phpunit

# Com coverage
./vendor/bin/phpunit --coverage-html coverage/

# Teste específico
./vendor/bin/phpunit tests/database/TicketModelTest.php
```

---

## 📂 Estrutura de Arquivos Importante

```
/var/www/tickets-kevin/
├── app/
│   ├── Controllers/         # CRIAR: Tickets, Dashboard, Users
│   ├── Models/              # CRIAR: TicketModel, UserModel, etc.
│   ├── Views/
│   │   ├── layouts/         # CRIAR: main.php, auth.php
│   │   ├── tickets/         # CRIAR: index, create, edit, show
│   │   └── dashboard/       # CRIAR: index.php
│   ├── Database/
│   │   ├── Migrations/      # ✅ 7 migrations criadas
│   │   └── Seeds/           # ✅ 3 seeders criados
│   ├── Config/
│   │   ├── Routes.php       # CONFIGURAR rotas
│   │   └── Filters.php      # CONFIGURAR auth filter
│   └── Filters/             # CRIAR: AuthFilter
├── public/
│   ├── css/                 # CRIAR: custom.css
│   ├── js/                  # CRIAR: app.js
│   └── uploads/             # Para anexos
├── docs/                    # ✅ Documentação organizada
│   ├── PLANEJAMENTO.md
│   ├── BANCO_DE_DADOS.md
│   └── tickets.md
├── tests/                   # CRIAR testes
│   ├── database/
│   ├── unit/
│   └── feature/
├── .env                     # ✅ Configurado
├── CHANGELOG.md             # ✅ Criado
├── CLAUDE.md                # ✅ Este arquivo
├── README.md                # ✅ Atualizado
└── TESTING.md               # CRIAR: Guia de testes
```

---

## 🚨 Pontos de Atenção

### Segurança
- ⚠️ **NUNCA** commitar arquivo `.env`
- ⚠️ Sempre usar `password_hash()` para senhas
- ⚠️ Validar TODOS os inputs do usuário
- ⚠️ Usar prepared statements (já feito pelo CodeIgniter)
- ⚠️ Configurar CSRF protection (já ativo no CodeIgniter)
- ⚠️ Sanitizar outputs com `esc()` nas views

### Performance
- ✅ Usar índices em colunas consultadas com frequência
- ✅ Fazer eager loading de relacionamentos quando possível
- ✅ Cachear queries pesadas
- ✅ Otimizar imagens antes do upload

### Banco de Dados
- ✅ SEMPRE criar migration para mudanças no schema
- ✅ NUNCA editar migrations já executadas (criar nova)
- ✅ Usar transações para operações críticas
- ✅ Testar rollback das migrations

### Testes
- ✅ **OBRIGATÓRIO** criar testes para novas features
- ✅ Verificar se dados estão sendo gravados corretamente no banco
- ✅ Testar validações (dados válidos e inválidos)
- ✅ Testar relacionamentos entre tabelas
- ✅ Executar testes antes de todo commit

---

## 🔧 Troubleshooting

### MySQL não inicia
```bash
sudo service mysql start
sudo service mysql status
```

### Migrations falham
```bash
# Ver status
php spark migrate:status

# Rollback e tentar novamente
php spark migrate:rollback
php spark migrate
```

### Erro de permissão em writable/
```bash
chmod -R 777 writable/
```

### Composer out of memory
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

---

## 📞 Contato e Recursos

- **Repositório**: https://github.com/phoenixf/tickets-kevin
- **CodeIgniter 4 Docs**: https://codeigniter.com/user_guide/
- **Shield Docs**: https://shield.codeigniter.com/
- **PHPUnit Docs**: https://phpunit.de/documentation.html

---

## ✅ Checklist Antes de Commit

- [ ] Código funciona localmente
- [ ] Migrations executam sem erro
- [ ] Testes criados e passando
- [ ] Dados gravando corretamente no banco
- [ ] CHANGELOG.md atualizado
- [ ] Sem código comentado desnecessário
- [ ] Sem `var_dump()` ou `echo` de debug
- [ ] Mensagem de commit segue convenção
- [ ] `.env` não foi incluído no commit

---

**Última atualização**: 2025-11-17
**Mantido por**: phoenixf
**Versão do projeto**: v0.1.0
