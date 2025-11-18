# 🚀 Instruções de Setup - Sistema de Gestão de Tickets

## ✅ O que já foi configurado automaticamente:

1. ✅ Dependências do Composer instaladas
2. ✅ Arquivo `.env` configurado
3. ✅ Chave de encriptação gerada
4. ✅ Porta MySQL ajustada para 3310
5. ✅ Scripts de setup criados

---

## 📋 Próximos Passos (Manual)

### Passo 1: Criar o banco de dados e usuário no MySQL

Execute os seguintes comandos no MySQL (porta 3310):

```bash
mysql -h localhost -P 3310 -u root -p
```

Dentro do MySQL, execute:

```sql
-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS tickets_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar usuário
CREATE USER IF NOT EXISTS 'tickets_user'@'localhost' IDENTIFIED BY 'tickets_pass_2024';

-- Conceder permissões
GRANT ALL PRIVILEGES ON tickets_db.* TO 'tickets_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar
SHOW DATABASES LIKE 'tickets_db';
SELECT user, host FROM mysql.user WHERE user = 'tickets_user';

-- Sair
EXIT;
```

**OU** execute o script SQL diretamente:

```bash
mysql -h localhost -P 3310 -u root -p < setup-database.sql
```

---

### Passo 2: Executar as Migrations (criar tabelas)

```bash
php spark migrate
```

Isso criará as **7 tabelas** do sistema:
- `usuarios`
- `categorias`
- `prioridades`
- `tickets`
- `comentarios`
- `anexos`
- `historico_tickets`

---

### Passo 3: Popular o banco com dados iniciais (Seeders)

```bash
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder
```

Isso criará:
- **4 prioridades**: Baixa, Normal, Alta, Crítica
- **6 categorias**: Suporte Técnico, Financeiro, Comercial, RH, TI, Geral
- **7 usuários**: 1 admin, 3 agentes, 3 clientes

---

### Passo 4: Iniciar o servidor de desenvolvimento

```bash
php spark serve
```

Acesse: **http://localhost:8080**

---

## 🎯 Script Automático (Opcional)

Se preferir, pode executar o script automático que faz tudo de uma vez (exceto criar o usuário MySQL):

```bash
./setup.sh
```

---

## 👤 Credenciais de Acesso

Após executar os seeders, você terá os seguintes usuários:

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

## 🔧 Comandos Úteis

### Verificar status das migrations
```bash
php spark migrate:status
```

### Reverter migrations
```bash
php spark migrate:rollback
```

### Ver rotas disponíveis
```bash
php spark routes
```

### Limpar cache
```bash
php spark cache:clear
```

---

## 🐛 Troubleshooting

### Erro de conexão com MySQL
- Verifique se o MySQL está rodando: `mysql -h localhost -P 3310 -u root -p`
- Verifique a porta no `.env`: deve ser `3310`

### Migrations falham
```bash
php spark migrate:status  # Ver status
php spark migrate:rollback  # Reverter
php spark migrate  # Executar novamente
```

### Erro de permissão em writable/
```bash
chmod -R 777 writable/
```

---

## 📚 Documentação

- **[README.md](../../README.md)** - Documentação principal do projeto
- **[CLAUDE.md](../projeto/CLAUDE.md)** - Guia para desenvolvimento com Claude Code
- **[CHANGELOG.md](../projeto/CHANGELOG.md)** - Histórico de mudanças
- **[PLANEJAMENTO.md](../features/PLANEJAMENTO.md)** - Planejamento completo
- **[BANCO_DE_DADOS.md](./BANCO_DE_DADOS.md)** - Documentação do banco

---

## ✅ Checklist de Verificação

Após concluir o setup, verifique:

- [ ] MySQL está rodando na porta 3310
- [ ] Banco `tickets_db` foi criado
- [ ] Usuário `tickets_user` foi criado e tem permissões
- [ ] Migrations executadas com sucesso (7 tabelas criadas)
- [ ] Seeders executados (dados iniciais carregados)
- [ ] Servidor PHP rodando em http://localhost:8080
- [ ] É possível fazer login com as credenciais padrão

---

**Setup preparado por:** Claude Code
**Data:** 2025-11-18
**Versão do Projeto:** v0.1.0
