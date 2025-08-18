
# replyvp

ReplyVP is a solution to customer support with asynchronous ticket support system designed to streamline customer service operations. The solution is containerized with Docker Compose for easy deployment and management.

The architecture is built for scalability and performance, featuring Quarkus, PostgreSQL and Kong API Gateway.

---

## Table of Contents

- [Requirements](#requirements)
- [Setup Instructions](#setup-instructions)
- [Database and passwords](#database-and-passwords)
- [Endpoints](#endpoints)
- [Features](#features)
- [License](#license)

---

## Requirements

### Backend

- **Language**: Java 21
- **Framework**: Quarkus 3.25
- **Migrations Tool**: Flyway
- **Tests** : Junit + Rest Assured
- **Authentication & RBAC** : Elytron Smallrey JWT
- **RSA Key Pair Tool** : Openssl
- **Password Hash** : Bcrypt
- **Logging** : JBoss
- **API Documentation** : OpenAPI Swagger

### Database
- **Database**: PostgreSQL

---

## Setup Instructions

### Prerequisites
The only prerequirement is to have Docker Desktop or Podman Desktop:
- Docker Desktop: For Windows, Mac and Linux.   
    1 - Installation Guide: [https://docs.docker.com/desktop/](https://docs.docker.com/desktop/)     

- Podman Desktop - For Windows, Mac and Linux. Is optimized for RPM-based Linux distributions.    
    1 - Installation Guide: [https://podman-desktop.io/docs/installation](https://podman-desktop.io/docs/installation)   
    2 - Setting up Compose: https://podman-desktop.io/docs/compose/setting-up-compose

### Step 1: Clone the Repository
Clone the repository to your local machine:

```bash
git clone <repository-url>
cd <project-directory>
```
### Step 2: Edit Passwords and Environment Variables
Edit the environment variable values in the following files:

- `/.env.example`
- `/web/app/.env.example`

### Step 3: Rename `.env.example` Files to `.env`


```bash
mv .env.example .env
mv web/app/.env.example web/app/.env
```

### Step 4: Build and Start the Containers
To start the project with Docker, run the following in your terminal:

```bash
docker-compose up --build -d
```
OR
```bash
podman-compose up -d
```

This will:
- Build the images for PHP, MySQL, and other services.
- Expose the services on port `8080` (PHP), `5174` (VUE ) `3306` (MySQL).
- Automatically load MySQL data from the provided SQL script for initialization.

> To stop all containers for this project, use:

```bash
docker-compose down
```
OR
```bash
podman-compose down
```

### Step 5: Access the Application
Once the containers are up and running, you can access the application:

The Vue App (client) is available on [http://localhost:5174/](http://localhost:5174/).
To consult the API go to [Endpoints](#endpoits)

------

## Database and passwords
The MySQL database configuration is automatically handled by `docker-compose.yml`. The SQL initialization script ( [/db/init/script.sql](/db/init/script.sql) ) creates necessary database tables and inserts initial sample data such as tickets, messages and users.

> Info: All default users have the password: `test@test.com`

------

## Endpoints:

### GET Requests  
> Retrieve user tickets.
### `GET http://localhost:8080/users/ticket`

Request model:
```curl
curl --location 'http://localhost:8080/users/tickets' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:


### `GET http://localhost:8080/tickets/{id}`
> Retrieve a ticket by id.

Request model:
```curl
curl --location 'http://localhost:8080/tickets/8' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:

### `GET http://localhost:8080/tickets/open`
> Retrieve pending tickets or not answered.

Request model:

```curl
curl --location 'http://localhost:8080/tickets/open' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:

### `GET http://localhost:8080/tickets/interactions`
> Retrieve unique tickets with at least one message sent by the requesting user.

Request model:

```curl
curl --location 'http://localhost:8080/tickets/open' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3NDc3ODQyMDksImV4cCI6MTc0Nzg3MDYwOSwiZGF0YSI6eyJpZCI6MSwicm9sZXMiOlsiYWRtaW4iXX19.L8mN1B4sXae7M7kOjyXabEjZWLngAKO34Ee7gvI2U2g'
```

Sample response:



### `GET http://localhost:8080/tickets/{id}/messages`
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

### POST Requests  

### `POST http://localhost:8080/users/tickets`
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

### `POST http://localhost:8080/auth/login`

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

### `POST http://localhost:8080/auth/register`
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

### `POST http://localhost:8080/auth/authenticate`
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


### `POST http://localhost:8080/tickets/{id}/messages` 
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


---

## License

This project is licensed under the MIT License. See the LICENSE file for details.