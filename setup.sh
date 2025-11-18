#!/bin/bash

# Script de setup do Sistema de Gestão de Tickets
# Autor: phoenixf
# Versão: 1.0

echo "=========================================="
echo "  Setup - Sistema de Gestão de Tickets"
echo "=========================================="
echo ""

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Verificar MySQL (porta 3310)
echo -e "${YELLOW}[1/5]${NC} Verificando MySQL na porta 3310..."
if mysql -h localhost -P 3310 -u root -e "SELECT VERSION();" > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} MySQL está rodando na porta 3310"
else
    echo -e "${RED}✗${NC} MySQL não está rodando na porta 3310"
    echo "Execute: sudo service mysql start"
    exit 1
fi

# 2. Criar banco de dados
echo -e "${YELLOW}[2/5]${NC} Criando banco de dados..."
echo "Nota: O usuário será criar manualmente pelo administrador"
mysql -h localhost -P 3310 -u root < setup-database.sql
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Banco de dados criado"
else
    echo -e "${RED}✗${NC} Erro ao criar banco de dados"
    exit 1
fi

# 3. Executar migrations
echo -e "${YELLOW}[3/5]${NC} Executando migrations..."
php spark migrate
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Migrations executadas"
else
    echo -e "${RED}✗${NC} Erro nas migrations"
    exit 1
fi

# 4. Executar seeders
echo -e "${YELLOW}[4/5]${NC} Populando banco com dados iniciais..."
php spark db:seed PrioridadesSeeder
php spark db:seed CategoriasSeeder
php spark db:seed UsuariosSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Seeders executados"
else
    echo -e "${RED}✗${NC} Erro nos seeders"
    exit 1
fi

# 5. Verificar instalação
echo -e "${YELLOW}[5/5]${NC} Verificando instalação..."
php spark migrate:status
echo ""

echo -e "${GREEN}=========================================="
echo "  ✓ Setup concluído com sucesso!"
echo "==========================================${NC}"
echo ""
echo "Para iniciar o servidor de desenvolvimento:"
echo "  php spark serve"
echo ""
echo "Acesse: http://localhost:8080"
echo ""
echo "Credenciais padrão:"
echo "  Admin:  admin@tickets.com / 123456"
echo "  Agente: joao.silva@tickets.com / 123456"
echo "  Cliente: ana.costa@cliente.com / 123456"
echo ""
