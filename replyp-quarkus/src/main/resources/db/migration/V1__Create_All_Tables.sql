CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 -- Roles, user_roles and user.password are hadnled by Keycloack

CREATE TYPE ticket_status AS ENUM ('open', 'in_progress', 'closed');

CREATE TABLE tickets (
    id SERIAL PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    status ticket_status DEFAULT 'open',
    is_repeat BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE ticket_messages (
    id SERIAL PRIMARY KEY,
    ticket_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);