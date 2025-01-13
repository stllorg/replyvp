CREATE DATABASE IF NOT EXISTS api;
USE api;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO messages (content)
VALUES
    ('Hello World!'),
    ('Hello!'),
    ('Hello World!!');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password) VALUES
('usuario1', 'usuario1@email.com', '$2y$10$muO7.qg/8k9x.a8/1234567890abcdefghijklmnopq'),
('usuario2', 'usuario2@email.com', '$2y$10$anotherHash1234567890abcdefghijklmnopqrs'),
('usuario3', 'usuario3@email.com', '$2y$10$yetAnotherHash1234567890abcdefghijklmn');