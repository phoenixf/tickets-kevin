# Documentação de Desenvolvimento

Esta pasta contém guias técnicos, instruções de setup, e documentação de testes.

## Arquivos

### Instalação e Configuração
- **[SETUP_INSTRUCTIONS.md](./SETUP_INSTRUCTIONS.md)** - Guia passo-a-passo de instalação
- **[SETUP_COMPLETE.md](./SETUP_COMPLETE.md)** - Checklist e confirmação de setup

### Banco de Dados
- **[BANCO_DE_DADOS.md](./BANCO_DE_DADOS.md)** - Documentação completa do banco
  - Estrutura de tabelas
  - Relacionamentos
  - Diagramas ER
  - Queries úteis

### Testes
- **[TESTING.md](./TESTING.md)** - Guia de testes automatizados
- **[TESTE_VISUAL.md](./TESTE_VISUAL.md)** - Testes visuais com Playwright
- **[TESTS_REPORT.md](./TESTS_REPORT.md)** - Relatórios de execução de testes

## 🛠️ Fluxo Típico de Desenvolvimento

1. **Primeira vez?** → [SETUP_INSTRUCTIONS.md](./SETUP_INSTRUCTIONS.md)
2. **Entender o banco?** → [BANCO_DE_DADOS.md](./BANCO_DE_DADOS.md)
3. **Fazer testes?** → [TESTING.md](./TESTING.md)
4. **Testar interface?** → [TESTE_VISUAL.md](./TESTE_VISUAL.md)

## 🔧 Comandos Rápidos

```bash
# Setup inicial
composer install
php spark migrate
php spark db:seed PrioridadesSeeder

# Desenvolvimento
php spark serve --port 8081

# Testes
php spark test

# Testes visuais
npx playwright test --headed
```

## 📊 Informações Importantes

- **Backend:** http://localhost:8081
- **Database:** tickets_db (porta 3310 em dev)
- **Usuário DB:** tickets_user
- **Framework:** CodeIgniter 4.6.3
- **PHP:** 8.4.14

---

[← Voltar para documentação](../README.md)
