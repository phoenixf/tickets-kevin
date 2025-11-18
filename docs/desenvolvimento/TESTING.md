# 🧪 Guia de Testes - Sistema de Tickets

Documentação completa sobre como testar o sistema de tickets, incluindo testes unitários, de integração e de banco de dados.

---

## 📋 Índice

1. [Visão Geral](#visão-geral)
2. [Configuração do Ambiente de Testes](#configuração-do-ambiente-de-testes)
3. [Tipos de Testes](#tipos-de-testes)
4. [Testes de Banco de Dados](#testes-de-banco-de-dados)
5. [Testes de Models](#testes-de-models)
6. [Testes de Controllers](#testes-de-controllers)
7. [Testes de Features](#testes-de-features)
8. [Executando Testes](#executando-testes)
9. [Boas Práticas](#boas-práticas)

---

## 🎯 Visão Geral

Este projeto utiliza **PHPUnit** para testes automatizados. Todos os testes estão no diretório `tests/`.

### Por que Testar?

- ✅ Garantir que novas features não quebrem funcionalidades existentes
- ✅ Validar que dados são gravados corretamente no banco
- ✅ Documentar o comportamento esperado do código
- ✅ Facilitar refactoring com confiança
- ✅ Detectar bugs antes de production

### Cobertura de Testes

**Objetivo**: 80%+ de cobertura

**Áreas Críticas (100% de cobertura obrigatória)**:
- Models e suas validações
- Controllers de CRUD
- Autenticação e autorização
- Operações de banco de dados

---

## ⚙️ Configuração do Ambiente de Testes

### 1. Banco de Dados de Testes

Crie um banco de dados separado para testes:

```sql
CREATE DATABASE tickets_db_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON tickets_db_test.* TO 'tickets_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configurar `.env` para Testes

O CodeIgniter usa automaticamente variáveis com prefixo `tests.` quando em ambiente de testes.

Adicione ao seu `.env`:

```env
# Database de Testes
tests.database.default.hostname = localhost
tests.database.default.database = tickets_db_test
tests.database.default.username = tickets_user
tests.database.default.password = tickets_pass_2024
tests.database.default.DBDriver = MySQLi
tests.database.default.charset = utf8mb4
tests.database.default.DBCollat = utf8mb4_unicode_ci
```

### 3. Configurar PHPUnit

O arquivo `phpunit.xml.dist` já está configurado. Para customizar, copie:

```bash
cp phpunit.xml.dist phpunit.xml
```

Edite `phpunit.xml` se necessário:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/codeigniter4/framework/system/Test/bootstrap.php"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true">
    <testsuites>
        <testsuite name="Database">
            <directory>tests/database</directory>
        </testsuite>
        <testsuite name="Unit">
            <directory>tests/unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

---

## 📦 Tipos de Testes

### 1. Testes Unitários (`tests/unit/`)

Testam **unidades isoladas** de código (funções, métodos).

**Exemplo**: Testar uma função helper

```php
<?php
namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class HelperTest extends CIUnitTestCase
{
    public function testFormatPriorityReturnsCorrectColor()
    {
        helper('ticket');

        $this->assertEquals('#EF4444', get_priority_color(4)); // Crítica
        $this->assertEquals('#F97316', get_priority_color(3)); // Alta
        $this->assertEquals('#EAB308', get_priority_color(2)); // Normal
        $this->assertEquals('#10B981', get_priority_color(1)); // Baixa
    }
}
```

### 2. Testes de Banco de Dados (`tests/database/`)

Testam **operações no banco de dados** e integridade dos dados.

**Características:**
- Usa banco de dados de teste
- Executa migrations automaticamente
- Limpa dados após cada teste

### 3. Testes de Features/Integração (`tests/feature/`)

Testam **fluxos completos** da aplicação (E2E - End to End).

**Exemplo**: Testar fluxo completo de criação de ticket

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

    public function testCompleteTicketCreationFlow()
    {
        // 1. Usuário acessa página de criação
        $result = $this->withSession(['logged_in' => true])
                       ->get('tickets/create');
        $result->assertOK();
        $result->assertSee('Criar Ticket');

        // 2. Usuário preenche formulário
        $result = $this->post('tickets', [
            'titulo'        => 'Problema no sistema de login',
            'descricao'     => 'Não consigo fazer login no sistema com minha senha',
            'categoria_id'  => 1,
            'prioridade_id' => 3,
        ]);

        // 3. Verifica redirecionamento
        $result->assertRedirect();

        // 4. Verifica se foi salvo no banco
        $this->seeInDatabase('tickets', [
            'titulo' => 'Problema no sistema de login',
            'status' => 'novo',
        ]);

        // 5. Verifica se criou histórico
        $this->seeInDatabase('historico_tickets', [
            'acao' => 'criado',
        ]);
    }
}
```

---

## 🗄️ Testes de Banco de Dados

### Estrutura Básica

```php
<?php
namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TicketDatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Configurações
    protected $migrate     = true;      // Executa migrations antes dos testes
    protected $migrateOnce = false;     // Executa migrations antes de cada teste
    protected $refresh     = true;      // Limpa dados após cada teste
    protected $namespace   = 'App';     // Namespace das migrations
    protected $seed        = 'TestSeeder'; // Seeder para dados de teste

    // Seus testes aqui
}
```

### Testes Essenciais de Banco de Dados

#### 1. Teste de Criação de Registro

```php
public function testCanInsertTicket()
{
    $data = [
        'titulo'        => 'Ticket de Teste',
        'descricao'     => 'Descrição completa do ticket de teste',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
        'status'        => 'novo',
    ];

    $this->db->table('tickets')->insert($data);

    // Verifica se foi inserido
    $this->seeInDatabase('tickets', [
        'titulo' => 'Ticket de Teste',
        'status' => 'novo',
    ]);
}
```

#### 2. Teste de Foreign Keys

```php
public function testForeignKeyIntegrity()
{
    // Tenta inserir ticket com usuario_id inexistente
    $this->expectException(\CodeIgniter\Database\Exceptions\DatabaseException::class);

    $data = [
        'titulo'        => 'Teste FK',
        'descricao'     => 'Teste de foreign key',
        'usuario_id'    => 999, // Não existe
        'prioridade_id' => 2,
        'status'        => 'novo',
    ];

    $this->db->table('tickets')->insert($data);
}
```

#### 3. Teste de CASCADE Delete

```php
public function testCascadeDeleteRemovesRelatedRecords()
{
    // Cria ticket
    $ticketId = $this->db->table('tickets')->insert([
        'titulo'        => 'Ticket para deletar',
        'descricao'     => 'Teste de cascade',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ]);

    // Cria comentário
    $this->db->table('comentarios')->insert([
        'ticket_id'  => $ticketId,
        'usuario_id' => 1,
        'conteudo'   => 'Comentário de teste',
        'eh_interno' => 0,
    ]);

    // Deleta ticket
    $this->db->table('tickets')->delete(['id' => $ticketId]);

    // Verifica se comentário também foi deletado (CASCADE)
    $this->dontSeeInDatabase('comentarios', ['ticket_id' => $ticketId]);
}
```

#### 4. Teste de Timestamps

```php
public function testTimestampsAreAutomaticallySet()
{
    $beforeInsert = date('Y-m-d H:i:s');
    sleep(1);

    $ticketId = $this->db->table('tickets')->insert([
        'titulo'        => 'Teste Timestamps',
        'descricao'     => 'Verifica se timestamps são criados automaticamente',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ]);

    $ticket = $this->db->table('tickets')->where('id', $ticketId)->get()->getRow();

    // Verifica se criado_em foi setado
    $this->assertNotNull($ticket->criado_em);
    $this->assertGreaterThan($beforeInsert, $ticket->criado_em);

    // atualizado_em deve ser igual a criado_em na criação
    $this->assertEquals($ticket->criado_em, $ticket->atualizado_em);
}
```

#### 5. Teste de Validação de Dados

```php
public function testRequiredFieldsValidation()
{
    // Título é obrigatório (definido no Model)
    $data = [
        'descricao'     => 'Sem título',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ];

    $model = new \App\Models\TicketModel();
    $result = $model->insert($data);

    // Insert deve falhar
    $this->assertFalse($result);
    $this->assertNotEmpty($model->errors());
}
```

---

## 📦 Testes de Models

### Estrutura Básica

```php
<?php
namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\TicketModel;

class TicketModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed    = 'TestSeeder';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TicketModel();
    }

    // Seus testes aqui
}
```

### Testes Essenciais de Models

#### 1. Teste de CRUD Completo

```php
public function testCanCreateReadUpdateDelete()
{
    $model = new TicketModel();

    // CREATE
    $data = [
        'titulo'        => 'Ticket CRUD Test',
        'descricao'     => 'Testando operações CRUD no model',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ];

    $ticketId = $model->insert($data);
    $this->assertIsNumeric($ticketId);

    // READ
    $ticket = $model->find($ticketId);
    $this->assertEquals('Ticket CRUD Test', $ticket['titulo']);
    $this->assertEquals('novo', $ticket['status']);

    // UPDATE
    $model->update($ticketId, ['status' => 'aberto']);
    $ticket = $model->find($ticketId);
    $this->assertEquals('aberto', $ticket['status']);

    // DELETE
    $model->delete($ticketId);
    $ticket = $model->find($ticketId);
    $this->assertNull($ticket);
}
```

#### 2. Teste de Validações

```php
public function testValidationRules()
{
    $model = new TicketModel();

    // Título muito curto
    $result = $model->insert([
        'titulo'        => 'Curto',
        'descricao'     => 'Descrição válida com mais de 20 caracteres',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ]);

    $this->assertFalse($result);
    $this->assertArrayHasKey('titulo', $model->errors());

    // Descrição muito curta
    $result = $model->insert([
        'titulo'        => 'Título válido com mais de 10 caracteres',
        'descricao'     => 'Curta',
        'usuario_id'    => 1,
        'prioridade_id' => 2,
    ]);

    $this->assertFalse($result);
    $this->assertArrayHasKey('descricao', $model->errors());
}
```

#### 3. Teste de Relacionamentos

```php
public function testGetWithRelationsReturnsCompleteData()
{
    $model = new TicketModel();

    // Cria ticket
    $ticketId = $model->insert([
        'titulo'        => 'Ticket com Relacionamentos',
        'descricao'     => 'Teste de joins',
        'usuario_id'    => 1,
        'prioridade_id' => 3,
        'categoria_id'  => 1,
    ]);

    // Busca com relacionamentos
    $ticket = $model->getWithRelations($ticketId);

    // Verifica se trouxe dados relacionados
    $this->assertNotNull($ticket);
    $this->assertArrayHasKey('usuario_nome', $ticket);
    $this->assertArrayHasKey('prioridade_nome', $ticket);
    $this->assertArrayHasKey('prioridade_cor', $ticket);
    $this->assertArrayHasKey('categoria_nome', $ticket);

    // Verifica valores
    $this->assertEquals('Alta', $ticket['prioridade_nome']);
    $this->assertEquals('#F97316', $ticket['prioridade_cor']);
}
```

#### 4. Teste de Filtros e Buscas

```php
public function testCanFilterTicketsByStatus()
{
    $model = new TicketModel();

    // Cria tickets com diferentes status
    $model->insert([
        'titulo' => 'Ticket Novo',
        'descricao' => 'Descrição',
        'usuario_id' => 1,
        'prioridade_id' => 2,
        'status' => 'novo',
    ]);

    $model->insert([
        'titulo' => 'Ticket Aberto',
        'descricao' => 'Descrição',
        'usuario_id' => 1,
        'prioridade_id' => 2,
        'status' => 'aberto',
    ]);

    // Busca apenas tickets novos
    $ticketsNovos = $model->where('status', 'novo')->findAll();
    $this->assertCount(1, $ticketsNovos);
    $this->assertEquals('Ticket Novo', $ticketsNovos[0]['titulo']);
}
```

---

## 🎮 Testes de Controllers

```php
<?php
namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class TicketsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed    = 'TestSeeder';

    public function testIndexDisplaysTicketsList()
    {
        $result = $this->withSession(['logged_in' => true])
                       ->get('tickets');

        $result->assertOK();
        $result->assertSee('Tickets');
    }

    public function testCreateRequiresAuthentication()
    {
        // Sem autenticação, deve redirecionar
        $result = $this->get('tickets/create');
        $result->assertRedirect();
    }

    public function testCanSubmitTicketForm()
    {
        $result = $this->withSession(['logged_in' => true, 'user_id' => 1])
                       ->post('tickets', [
                           'titulo'        => 'Novo Ticket via Controller',
                           'descricao'     => 'Descrição completa do ticket',
                           'prioridade_id' => 2,
                           'categoria_id'  => 1,
                       ]);

        $result->assertRedirect();
        $this->seeInDatabase('tickets', ['titulo' => 'Novo Ticket via Controller']);
    }

    public function testValidationFailsWithInvalidData()
    {
        $result = $this->withSession(['logged_in' => true])
                       ->post('tickets', [
                           'titulo' => 'Curto', // Menos de 10 caracteres
                       ]);

        $result->assertSessionHas('errors');
    }
}
```

---

## 🚀 Executando Testes

### Executar Todos os Testes

```bash
./vendor/bin/phpunit
```

### Executar Teste Específico

```bash
# Por arquivo
./vendor/bin/phpunit tests/database/TicketModelTest.php

# Por método
./vendor/bin/phpunit --filter testCanCreateTicket
```

### Executar por Suite

```bash
# Apenas testes de database
./vendor/bin/phpunit --testsuite Database

# Apenas testes unitários
./vendor/bin/phpunit --testsuite Unit

# Apenas testes de feature
./vendor/bin/phpunit --testsuite Feature
```

### Com Coverage (Cobertura de Código)

```bash
# Gerar HTML
./vendor/bin/phpunit --coverage-html coverage/

# Ver no navegador
open coverage/index.html
```

### Com Output Detalhado

```bash
./vendor/bin/phpunit --testdox
```

---

## ✅ Boas Práticas

### 1. Sempre Testar Gravação no Banco

```php
public function testTicketIsSavedInDatabase()
{
    $model = new TicketModel();
    $ticketId = $model->insert([...]);

    // SEMPRE verificar se salvou
    $this->seeInDatabase('tickets', ['id' => $ticketId]);

    // Verificar campos específicos
    $ticket = $this->db->table('tickets')->find($ticketId);
    $this->assertEquals('esperado', $ticket->campo);
}
```

### 2. Testar Foreign Keys

```php
public function testRelationshipsAreCorrect()
{
    // Criar registro relacionado
    $ticketId = ...;

    // Verificar se FK está correta
    $ticket = $this->db->table('tickets')->find($ticketId);
    $this->assertEquals(1, $ticket->usuario_id);

    // Verificar se relacionamento funciona
    $usuario = $this->db->table('usuarios')->find($ticket->usuario_id);
    $this->assertNotNull($usuario);
}
```

### 3. Testar Validações (Válido E Inválido)

```php
public function testValidation()
{
    $model = new TicketModel();

    // Teste com dados VÁLIDOS
    $result = $model->insert([...dados válidos...]);
    $this->assertIsNumeric($result);

    // Teste com dados INVÁLIDOS
    $result = $model->insert([...dados inválidos...]);
    $this->assertFalse($result);
    $this->assertNotEmpty($model->errors());
}
```

### 4. Usar Seeders de Teste

```php
// tests/_support/Database/Seeds/TestSeeder.php
class TestSeeder extends Seeder
{
    public function run()
    {
        // Dados mínimos para testes
        $this->call('PrioridadesSeeder');
        $this->call('CategoriasSeeder');

        // Usuário de teste
        $this->db->table('usuarios')->insert([
            'nome'  => 'Test User',
            'email' => 'test@test.com',
            'senha' => password_hash('password', PASSWORD_DEFAULT),
            'funcao' => 'agente',
        ]);
    }
}
```

### 5. Limpar Dados Após Testes

```php
protected $refresh = true; // Limpa banco após cada teste
```

### 6. Nomear Testes Descritivamente

```php
// ❌ Ruim
public function testTicket() { }

// ✅ Bom
public function testCanCreateTicketWithValidData() { }
public function testValidationFailsWhenTitleIsTooShort() { }
public function testForeignKeyPreventsInvalidUserId() { }
```

---

## 📊 Checklist de Testes

### Para Cada Model

- [ ] Teste de criação (insert)
- [ ] Teste de leitura (find, findAll)
- [ ] Teste de atualização (update)
- [ ] Teste de exclusão (delete)
- [ ] Teste de validações (dados válidos e inválidos)
- [ ] Teste de relacionamentos
- [ ] Teste de timestamps
- [ ] Teste de dados salvando corretamente no banco

### Para Cada Controller

- [ ] Teste de autenticação/autorização
- [ ] Teste de métodos GET
- [ ] Teste de métodos POST
- [ ] Teste de validação de formulários
- [ ] Teste de redirecionamentos
- [ ] Teste de mensagens flash

### Para Banco de Dados

- [ ] Teste de foreign keys
- [ ] Teste de CASCADE delete
- [ ] Teste de SET NULL
- [ ] Teste de índices (performance)
- [ ] Teste de constraints
- [ ] Teste de migrations (up e down)

---

**Documento mantido por**: phoenixf
**Última atualização**: 2025-11-17
**Versão**: 1.0
