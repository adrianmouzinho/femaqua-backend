# FEMAQUA (Ferramentas Maravilhosas Que Adoro)

FEMAQUA is a simple application for managing tools with their respective names, links, descriptions, and tags. The application allows users to interact with a collection of tools via a RESTful API.

## Technologies

- Laravel (PHP)
- MySQL
- Docker
- Sanctum (for authentication)
- Swagger (for API documentation)

## Functional Requirements

- [x] Users must be able to view a list of tools with their names, links, descriptions, and tags;
- [x] Users must be able to filter tools based on tags;
- [x] Users must be able to create a tool;
- [x] Users must be able to update a tool;
- [x] Users must be able to delete a tool;
- [x] Authentication should be required for listing, creating, updating, and deleting tools.

## Getting Started

### Prerequisites

- [Docker](https://www.docker.com/)

### Cloning the project

```bash
git clone https://github.com/adrianmouzinho/femaqua-backend.git
```

### Starting API

> Before running the application, rename the `.env.example` file to `.env` and configure the database settings.

> Run migrations to create database tables.

```sh
cd femaqua-backend

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs   

sail up -d

sail artisan key:generate

sail artisan migrate
```

### Running tests

```bash
sail artisan test
```

## API Documentation (Swagger)

For API documentation, access the link: [http://localhost/api/v1/documentation](http://localhost/api/v1/documentation)
