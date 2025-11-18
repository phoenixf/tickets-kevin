-- Script de criação do banco de dados para o Sistema de Tickets
-- Execute este script como root do MySQL
-- Porta: 3310

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS tickets_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar usuário
CREATE USER IF NOT EXISTS 'tickets_user'@'localhost' IDENTIFIED BY 'tickets_pass_2024';

-- Conceder permissões
GRANT ALL PRIVILEGES ON tickets_db.* TO 'tickets_user'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Mostrar informações
SELECT 'Banco de dados criado com sucesso!' AS Status;
SHOW DATABASES LIKE 'tickets_db';
