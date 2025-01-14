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
('adm', 'Xtest@testmail.com', '$2y$12$WmsHcmLZh8uArpDIy8MeVOJmDFnpkO7wMdADfD0mfC8HcZ0y6C.iO'),
('usuario2', 'usuario2@email.com', '$2y$10$anotherHash1234567890abcdefghijklmnopqrs'),
('usuario3', 'usuario3@email.com', '$2y$10$yetAnotherHash1234567890abcdefghijklmn');

CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES
('admin'),
('manager'),
('support'),
('user');

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1),
(2, 2),
(3, 4);