
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
- **API Endpoints**:
  - **Authentication**: `localhost:8080/auth` (For user login and registration)
  - **API**: `localhost:8080/api` (For interactions with the application, protected by JWT)
- **Database Driver**: MySQLi (for communication with the MySQL database)
- **Authentication**: Using Firebase `php-jwt` v6.10.2 for authentication and JWT token generation.
- **Containers**: Docker Compose to simplify the development, build, and deployment process in isolated environments, with the API and authentication running in the `php-apache` container.

### Database
- **Database**: MySQL 9.1.0

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

### Step 2: Build and Start the Containers
To start the project with Docker, run the following in your terminal:

```bash
docker-compose up --build
```

This will:
- Build the images for PHP, MySQL, and other services.
- Expose the services on port `8080` (PHP) and `3306` (MySQL).
- Automatically load MySQL data from the provided SQL script for initialization.

### Step 3: Access the Application
Once the containers are up and running, you can access the application:

- **PHP Application**: `http://localhost:8080`
- **MySQL Database**: `localhost:3306` 

For the Vue client (development mode):

```bash
cd client/app
npm install
npm run serve
```

This will launch the client on [http://localhost:5174/](http://localhost:5174/).

### Step 4: Stopping Containers
To stop the containers, use:

```bash
docker-compose down
```

---

## Features

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