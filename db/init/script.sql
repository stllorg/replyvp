CREATE DATABASE IF NOT EXISTS api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE api;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS tickets ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    status ENUM('open', 'in_progress', 'closed') DEFAULT 'open',
    is_repeat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ticket_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password) VALUES
('admin', 'adm@testmail.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('manager', 'manager@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('support', 'support@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera1', 'usuario4@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera2', 'usuario5@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera3', 'usuario6@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera4', 'usuario7@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO');

INSERT INTO roles (name) VALUES
('admin'),
('manager'),
('support'),
('user');

INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 4),
(6, 4),
(7, 4);

INSERT INTO tickets (subject, status, is_repeat, user_id) VALUES
('Login', 'in_progress', FALSE, 4),
('Atualização de email', 'open', FALSE, 5),
('Verificação de email', 'open', FALSE, 6);

INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES
(1, 'Não consigo fazer login.', 4),
(1, 'Olá! Meu nome é John Doe, especialista em suporte. Você poderia tentar usar a opção redefinir sua senha?', 3),
(1, 'Já tentei, mas apareceu um erro dizendo que meu email não existe, embora meu email já existe.', 4),
(2, 'Quero atualizar meu email.', 5),
(3, 'Não consigo verificar o meu email.', 6);