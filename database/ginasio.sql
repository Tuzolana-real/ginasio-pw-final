CREATE DATABASE IF NOT EXISTS ginasio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ginasio_db;

-- 1. Perfis de acesso
CREATE TABLE perfis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL UNIQUE
);

-- 2. Utilizadores (login)
CREATE TABLE utilizadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  perfil_id INT NOT NULL,
  ativo TINYINT(1) DEFAULT 1,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (perfil_id) REFERENCES perfis(id)
);

-- 3. Clientes (membros do ginásio)
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT NULL,
  nome VARCHAR(150) NOT NULL,
  bi VARCHAR(20) UNIQUE,
  data_nascimento DATE,
  genero ENUM('M','F','Outro'),
  telefone VARCHAR(20),
  email VARCHAR(150),
  endereco VARCHAR(200),
  foto VARCHAR(255),
  data_registo DATE DEFAULT (CURRENT_DATE),
  estado ENUM('ativo','inativo') DEFAULT 'ativo',
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);

-- 4. Modalidades (categorias de aula/atividade)
CREATE TABLE modalidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  categoria VARCHAR(50),
  instrutor VARCHAR(100),
  vagas INT DEFAULT 0
);

-- 5. Planos (mensalidades)
CREATE TABLE planos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nome VARCHAR(100) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  duracao_meses INT NOT NULL,
  descricao TEXT
);

-- 6. Inscrições
CREATE TABLE inscricoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  plano_id INT NOT NULL,
  modalidade_id INT NULL,
  data_inicio DATE NOT NULL,
  data_fim DATE NOT NULL,
  estado ENUM('ativa','expirada','cancelada') DEFAULT 'ativa',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  FOREIGN KEY (plano_id) REFERENCES planos(id),
  FOREIGN KEY (modalidade_id) REFERENCES modalidades(id)
);

-- 7. Pagamentos
CREATE TABLE pagamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  inscricao_id INT NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  data_pagamento DATE NOT NULL,
  forma_pagamento ENUM('Dinheiro','Transferencia','Multicaixa','Cartao') DEFAULT 'Dinheiro',
  estado ENUM('pago','pendente') DEFAULT 'pago',
  FOREIGN KEY (inscricao_id) REFERENCES inscricoes(id) ON DELETE CASCADE
);

-- 8. Frequência (check-in / check-out)
CREATE TABLE frequencia (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  modalidade_id INT NULL,
  data_hora_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
  data_hora_saida DATETIME NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  FOREIGN KEY (modalidade_id) REFERENCES modalidades(id)
);

-- 9. Logs (auditoria)
CREATE TABLE logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT NULL,
  acao VARCHAR(255) NOT NULL,
  data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  ip VARCHAR(45),
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);

-- Dados iniciais obrigatórios
INSERT INTO perfis (nome) VALUES ('Administrador'), ('Recepcionista'), ('Cliente');