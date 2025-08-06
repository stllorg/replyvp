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
(1, 1), -- admin (id=1)
(2, 2), -- manager (id=2)
(3, 3), -- support (id=3)
(4, 4), -- user (id=4)
(5, 4),
(6, 4),
(7, 4);

INSERT INTO tickets (subject, status, is_repeat, user_id) VALUES
('Login', 'in_progress', FALSE, 4),
('Atualiza??o de email', 'open', FALSE, 5),
('Verifica??o de email', 'open', FALSE, 6);

-- Insere as mensagens de exemplo para os tickets.
-- Nota: 'user_id' aqui se refere a quem enviou a mensagem.
INSERT INTO ticket_messages (ticket_id, message, user_id) VALUES
(1, 'N?o consigo fazer login.', 4),
(1, 'Ol?! Meu nome ? John Doe, especialista em suporte. Voc? poderia tentar usar a op??o redefinir sua senha?', 3),
(1, 'J? tentei, mas apareceu um erro dizendo que meu email n?o existe, embora meu email j? existe.', 4),
(2, 'Quero atualizar meu email.', 5),
(3, 'N?o consigo verificar o meu email.', 6);
