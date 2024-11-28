CREATE DATABASE controle_financas;
USE controle_financas;

CREATE TABLE categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(20),
    descricao VARCHAR(400)
);

CREATE TABLE financas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(20) NOT NULL,
    data DATE,
    descricao VARCHAR(400),
    valor DECIMAL(10, 2),
    fk_categoria_id INT,
    FOREIGN KEY (fk_categoria_id) REFERENCES categoria(id) 
    ON DELETE CASCADE
);

CREATE TABLE mes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(20) NOT NULL
);

INSERT INTO mes(nome) VALUES 
("janeiro"),
("fevereiro"),
("março"),
("abril"),
("maio"),
("junho"),
("julho"),
("agosto"),
("setembro"),
("outubro"),
("novembro"),
("dezembro");

CREATE TABLE meses (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome INT NOT NULL,
    ano CHAR(4),
    saldo DECIMAL(10, 2),
    fk_id_financas INT,
    FOREIGN KEY (fk_id_financas) REFERENCES financas(id) 
    ON DELETE CASCADE,
    FOREIGN KEY (nome) REFERENCES mes(id) 
    ON DELETE RESTRICT
);

ALTER TABLE financas
ADD COLUMN fk_mes_id INT NOT NULL,
ADD CONSTRAINT fk_mes_id FOREIGN KEY (fk_mes_id) REFERENCES meses(id) 
ON DELETE CASCADE;
