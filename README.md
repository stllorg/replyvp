# ReplyVP

This repository contains a simple PHP API.

---
## Requirements

### API
- **Language**: PHP 8.2
- **Web Server**: Apache 2.4
- **Database Driver**: MySQLi

### Auth
- Firebase php-jwt v6.10.2 - https://github.com/firebase/php-jwt
- 

### Database
- **Database**: MySQL v9.1

### Containers
- **Tool**: Docker Compose

---
## Vue Client Setup

The Vue.js application is configured to run in development mode at:

[http://localhost:5174/](http://localhost:5174/)

### Steps to Run the Client:
1. Navigate to the client directory:
   ```bash
   cd client/app
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run serve
   ```

---
## MySQL Database Access

To interact with the database, use the following command to access the MySQL shell:

```bash
docker exec -it mysql-container mysql -u root -p
```
Enter the password for the root user when prompted.

### Example Queries:
1. Use the `api` database:
   ```sql
   USE api;
   ```

2. Fetch all messages:
   ```sql
   SELECT * FROM messages;
   ```

3. Fetch all users:
   ```sql
   SELECT * FROM users;
   ```

---
## Features

The following features are planned for implementation:

- [ ] **Account Management**
  - Users can register a new account.
  - Users can log in and log out.
  - Users can edit their account information.
  - Users can terminate their account (or schedule account deletion).

- [ ] **Messaging**
  - Users can post messages.
  - Users can delete or hide messages they have sent.

---
## Contributing

Contributions are always welcome! If you have any suggestions or improvements, feel free to submit issues or pull requests. This project follows the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0-beta.4/) specification for commit messages. 

This project has a configuration file for Visual Studio Code(VSCode) that automatically loads identation specifications from the `./vscode` folder to ensure consistency across the development environment.
- [ ] **Identation**
  - .vue Vue.js files: 2 spaces.
  - .php PHP files : 4 spaces.

---
## License

This project is licensed under the MIT License. See the LICENSE file for details.
