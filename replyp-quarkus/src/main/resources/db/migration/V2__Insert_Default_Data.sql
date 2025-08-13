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
