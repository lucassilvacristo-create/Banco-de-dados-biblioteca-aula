
DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

-- Tabela de livros
CREATE TABLE livros (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  titulo      VARCHAR(150) NOT NULL,
  autor       VARCHAR(100) NOT NULL,
  ano         INT,
  genero      VARCHAR(50),
  quantidade  INT NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- Tabela de alunos
CREATE TABLE alunos (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nome   VARCHAR(100) NOT NULL,
  turma  VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

-- Tabela de empréstimos: liga um aluno a um livro
CREATE TABLE emprestimos (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  livro_id         INT NOT NULL,
  aluno_id         INT NOT NULL,
  data_emprestimo  DATE NOT NULL,
  data_devolucao   DATE NULL,
  FOREIGN KEY (livro_id) REFERENCES livros(id),
  FOREIGN KEY (aluno_id) REFERENCES alunos(id)
) ENGINE=InnoDB;

ALTER TABLE livros ADD COLUMN capa VARCHAR(255) DEFAULT 'sem-capa.jpg';

-- Dados de exemplo
INSERT INTO livros (titulo, autor, ano, genero, quantidade) VALUES
('Dom Casmurro', 'Machado de Assis', 1899, 'Romance', 3),
('Memórias Póstumas de Brás Cubas', 'Machado de Assis', 1881, 'Romance', 2),
('O Alienista', 'Machado de Assis', 1882, 'Conto', 2),
('O Cortiço', 'Aluísio Azevedo', 1890, 'Romance', 2),
('Iracema', 'José de Alencar', 1865, 'Romance', 1),
('Vidas Secas', 'Graciliano Ramos', 1938, 'Romance', 2),
('Capitães da Areia', 'Jorge Amado', 1937, 'Romance', 3),
('A Hora da Estrela', 'Clarice Lispector', 1977, 'Romance', 1),
('Quarto de Despejo', 'Carolina Maria de Jesus', 1960, 'Diário', 2),
('Grande Sertão: Veredas', 'João Guimarães Rosa', 1956, 'Romance', 1),
('Auto da Compadecida', 'Ariano Suassuna', 1955, 'Teatro', 2),
('A Moreninha', 'Joaquim Manuel de Macedo', 1844, 'Romance', 1),
('Noites Brancas', 'Fiódor Dostoiévski', 1848, 'Ficção', 4),
('A Metamorfose', 'Franz Kafka', 1915, 'Ficção', 5);

INSERT INTO alunos (nome, turma) VALUES
('Ana Souza', '2A'), ('Bruno Lima', '2A'), ('Carla Mendes', '2B'), ('Diego Rocha', '2B'),
('Eduarda Alves', '3A'), ('Felipe Costa', '3A'), ('Gabriela Nunes', '3B'), ('Henrique Dias', '3B');

INSERT INTO emprestimos (livro_id, aluno_id, data_emprestimo, data_devolucao) VALUES
(1, 1, '2026-09-01', '2026-09-15'),
(6, 2, '2026-09-10', NULL),
(1, 3, '2026-09-20', NULL),
(9, 5, '2026-09-22', NULL),
(7, 4, '2026-09-05', '2026-09-19'),
(11, 7, '2026-10-01', NULL);

UPDATE livros SET capa = 'metamorfose.jpg' WHERE titulo = 'A Metamorfose';
UPDATE livros SET capa = 'capitaes-da-areia.jpg' WHERE titulo = 'Capitães da Areia';
UPDATE livros SET capa = 'dom-casmurro.jpg' WHERE titulo = 'Dom Casmurro';
UPDATE livros SET capa = 'bras-cubas.jpg' WHERE titulo = 'Memórias Póstumas de Brás Cubas';
UPDATE livros SET capa = 'auto-da-compadecida.jpg' WHERE titulo = 'Auto da Compadecida';
UPDATE livros SET capa = 'cortico.jpg' WHERE titulo = 'O Cortiço';
UPDATE livros SET capa = 'grande-sertao.jpg' WHERE titulo = 'Grande Sertão: Veredas';
UPDATE livros SET capa = 'hora-da-estrela.jfif' WHERE titulo = 'A Hora da Estrela';
UPDATE livros SET capa = 'iracema.jpg' WHERE titulo = 'Iracema';
UPDATE livros SET capa = 'moreninha.jpg' WHERE titulo = 'A Moreninha';
UPDATE livros SET capa = 'o-alienista.jpg' WHERE titulo = 'O Alienista';
UPDATE livros SET capa = 'quarto-de-despejo.jfif' WHERE titulo = 'Quarto de Despejo';
UPDATE livros SET capa = 'vidas-secas.jfif' WHERE titulo = 'Vidas Secas';
UPDATE livros SET capa = 'noites-brancas.jfif' WHERE titulo = 'Noites Brancas'