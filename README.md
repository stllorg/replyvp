
# ReplyVP - Fullstack Solution with Docker Compose

This project is a modern fullstack application with a PHP backend, MySQL database, and a Vue.js frontend. Designed with scalability, security, and best practices in mind, it's an ideal starting point for building robust applications.

---

## Table of Contents

- [Requirements](#requirements)
- [Setup Instructions](#setup-instructions)
- [Features](#features)
- [Vue Client Setup](#vue-client-setup)
- [Database Configuration](#mysql-database-access)
- [Contributing](#contributing)
- [License](#license)

---

## Requirements

### Backend

- **Language**: PHP 8.4.3
- **Web Server**: Apache 2.4.62 (serving the PHP application)
- **API Endpoints**: routes defined on [/web/app/index.php](/web/app/index.php)
  - **Authentication**: `localhost:8080/auth/authenticate` (For token for validation)    
  - **Registration/Sign up**: `localhost:8080/auth/register` (For user registration)    
  - **Login/Sign up**: `localhost:8080/auth/login` (For login)    
  - **Users tickets**: `localhost:8080/users/tickets` (For users create or fetch their tickets)
- **Database Driver**: MySQLi (for communication with the MySQL database)
- **PHP Dependency Manager**: Composer ( installed from [/web/Dockerfile](/web/Dockerfile) )
- **Authentication**: Using Firebase `php-jwt` v6.11 for authentication and JWT token generation.
- **Containers**: Docker with Compose in compatibility with Podman Compose.
- **PHP Testing**: PHPUnit ( installed from [/web/app/composer.json](/web/app/composer.json) ). Tests cases executed from [/web/app/tests/Auth](/web/app/tests/Auth).  
  - Currently, tests are executed manually from terminal:
    ```bash
    docker exec -it php-container vendor/bin/phpunit
    ```
### Database
- **Database**: MySQL

### Frontend
- **Framework**: Vue.js (Client-side)
- **Build Tool**: npm for dependency management and script automation

---

## Setup Instructions

### Step 1: Clone the Repository
Clone the repository to your local machine:

```bash
git clone <repository-url>
cd <project-directory>
```
### Step 2: Edit Passwords and Environment Variables
Edit the environment variable values in the following files:

- `/.env`
- `/web/app/.env.example`

### Step 3: Rename `.env.example` Files to `.env`


```bash
mv .env.example .env
mv web/app/.env.example web/app/.env
```

### Step 4: Build and Start the Containers
To start the project with Docker, run the following in your terminal:

```bash
docker-compose up --build
```

This will:
- Build the images for PHP, MySQL, and other services.
- Expose the services on port `8080` (PHP) and `3306` (MySQL).
- Automatically load MySQL data from the provided SQL script for initialization.

> To stop the containers, use:

```bash
docker-compose down
```

### Step 5: Access the Application
Once the containers are up and running, you can access the application:

#### Launch Vue
For the Vue client (development mode):

```bash
cd client/app
npm install
npm run serve
```

This will launch the client on [http://localhost:5174/](http://localhost:5174/).

------

## Sample data, default credentials and passwords
When database image is being built, a SQL script is loaded from db/init/script.sql to insert initial sample data such as tickets, messages and users.

> Info: All default users have the password: `test@testmail.com`

Default users 
- Admin   
  id: 1
  username: admin   
  email: adm@testmail.com   
  password: test@testmail.com   
  roles: admin  

- Manager   
  id: 2   
  username: manager   
  email : manager@email.com   
  password: test@testmail.com   
  roles: manager  (no rights)

- Support
  id: 3
  username: support
  email : support@email.com
  password: test@testmail.com
  roles: support (no rights)

### script.sql Line 4 - Create users table
> The users table stores information to users login and be correctly identified to the system provide services.   

```sql
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### script.sql Line 12 - Create roles table
> The roles table stores roles to possibility rules to improve user experience providing only needed resources and services applicable to a specific role.
```sql
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE
);
```

### script.sql Line 17 - Create user_roles
> The user_roles table stores user_id and role_id to provide the atrribution of roles to users. Currently, each user may have n roles.
```sql
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
```

### script.sql Line 45 - Populate users table with sample users
> The samples users inserted will have the same default password to login, the passowrd in plain text withouth encryption is: `test@testmail.com`  

```sql
INSERT INTO users (username, email, password) VALUES
('admin', 'adm@testmail.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('manager', 'manager@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('support', 'support@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera1', 'usuario4@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera2', 'usuario5@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera3', 'usuario6@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO'),
('usera4', 'usuario7@email.com', '$2y$12$7WxeGPCKKc/w5ZrR4I/YjeoJ0p2AyjbjCsYt/Y6ygHo9phOWd0ZsO');
```
### script.sql Line 54 - Create table roles
> Insert admin, manager, support and user roles. Currently, manager and support have no access.

```sql
INSERT INTO roles (name) VALUES
('admin'),
('manager'),
('support'),
('user');
```

### script.sql Line 60 - Populate user_roles
> Insert entries in user_roles with (user_id, role_id) to assign roles to users. Currently, role_id 2 (manager) and role_id(3) will grant no acess.

```sql
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 4),
(6, 4),
(7, 4);
```
------

### Endpoints:

### GET  
> Retrieve user tickets.
### http://localhost:8080/users/tickets

Request model:
```curl
curl --location 'http://localhost:8080/users/tickets' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:


### http://localhost:8080/tickets/{id}
> Retrieve a ticket by id.

Request model:
```curl
curl --location 'http://localhost:8080/tickets/8' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:

### http://localhost:8080/tickets/open
> Retrieve pending tickets or not answered.

Request model:

```curl
curl --location 'http://localhost:8080/tickets/open' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:



### http://localhost:8080/tickets/{id}/messages
> Retrieve ticket messages.

Resquest model:

```curl
curl --location 'http://localhost:8080/tickets/{id}/messages' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'   
```
Sample response:

```json
[
    {
        "id": 65,
        "ticketId": "14",
        "userId": "1",
        "content": "Sample message content on ticket 14",
        "createdAt": "2025-05-23T12:57:19+00:00",
        "roles": [
            "admin"
        ]
    }
]
```

## POST  

### http://localhost:8080/users/tickets 
> Create a new ticket.

Request model: 
```curl
curl --location 'http://localhost:8080/users/tickets' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g' \
--header 'Content-Type: application/json' \
--data '{
    "subject":"Sample text"
}
```
Sample response:

STATUS CODE 201
```json
{
    "id": 123,
    "subject": "Sample text",
    "userId": 1
}
```

### http://localhost:8080/auth/login

Request model:
>  Description: Sign in with user credentials.
```curl
curl --location 'http://localhost:8080/auth/login' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "username": "admin",
    "password": "test@test.com"
}'
```

Sample response: 

```json
{
    "success": true,
    "message": "Login successful.",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g",
}
```

### http://localhost:8080/auth/register
> Description : Register a new user.
Request model:
```curl
curl --location 'http://localhost:8080/auth/login' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "username": "admin",
    "email": "admin@test.com"
    "password": "test@test.com"
}'
```


Status Code 201 (Created)

```json
{
    "message": "User registered successfully"
}
```

### http://localhost:8080/auth/authenticate
> Verify if JWT Token is invalid, if not sends user id and user role.

Request model: 
```curl
curl --location 'http://localhost:8080/auth/authenticate' \
--header 'Authorization: Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g' \
--header 'Content-Type: text/plain'
```

Sample response:
```json
{
    "userId": 1,
    "roles": [
        "admin"
    ]
}
```


### http://localhost:8080/tickets/{id}/messages 
> Create a new message on ticket {id}. 


Request model:
```curl
curl --location 'http://localhost:8080/tickets/{id}/messages' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g' \
--data '{
    "content": "A sample message."
  }'
```

Sample response:
```json
{
    "messageId": 65,
    "ticketId": "14",
    "userId": "1",
    "content": "A sample message."
}
```

---

## Feature

This application is designed with the following key features:

### 1. **Account Management**
- User registration and authentication.
- Role-based access control (RBAC) for different types of users.
- Profile management with CRUD operations.
- Secure account deletion or scheduled termination.

### 2. **Tickets & Support**
- Users can create and manage support tickets.
- In-app messaging between users and support staff.

### 3. **Security**
- JWT-based authentication for secure user login.
- CSRF protection to prevent cross-site request forgery.
- Input validation and prepared SQL statements to prevent SQL injection.
- CORS headers to enable safe cross-origin requests.
- Vue.js route guards to protect sensitive pages based on roles.

---

## Vue Client Setup

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

Your Vue client should now be running at [http://localhost:5174/](http://localhost:5174/).

---

## MySQL Database Access

The MySQL database configuration is automatically handled by `docker-compose.yml`. The SQL initialization script (`script.sql`) is included to help you get started with the necessary database tables and data.

---

## Contributing

We welcome contributions from developers of all skill levels. If you have ideas for new features, optimizations, or bug fixes, feel free to submit an issue or pull request.

### Code Style
This project follows the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0-beta.4/) specification for commit messages. The code is formatted as follows:
- **Vue.js files**: 2 spaces indentation.
- **PHP files**: 4 spaces indentation.

A [Visual Studio Code configuration](./vscode) file is included to ensure consistency across the development environment.

---

## License

This project is licensed under the MIT License. See the LICENSE file for details.

---

## Details

- **Frontend Expertise**: Proficiency in Vue.js for building interactive, scalable, and responsive user interfaces.
- **Backend Development**: Solid foundation in PHP and MySQL for building API-driven applications with security and scalability in mind.
- **DevOps Skills**: Docker for containerization and Docker Compose for orchestration, ensuring a seamless development and deployment workflow.
- **Security Focus**: Best practices for securing the application, including JWT, CSRF protection, input validation, and more.