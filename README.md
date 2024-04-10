# Cryptocurrency-Api

## About

## Getting started

1. Copy .env.example -> .env
2. Start & build container

```console
docker compose up -d --build
```

3. After booting the container inter environment console

```console
docker exec -it fa_app bash
```

4. Migrate tables

```console
php artisan migrate
```

5. Seed tables

```console
php artisan db:seed
```

6. Update packages

```console
composer update
```

7. Generate jwt-rsa certs

```console
php artisan jwt:generate-certs
```

## Available endpoints

Base url (if anything isn't changed) - http://fileapi.local:8080

### Authentication

| HTTP Verbs | Endpoints          | Action                                        | Form params           |
|------------|--------------------|-----------------------------------------------|-----------------------|
| POST       | /api/auth/register | To sign up a new user                         | name, email, password |
| POST       | /api/auth/login    | To login an existing user account and get jwt | email, password       |
| POST       | /api/auth/logout   | To logout                                     |                       |
| POST       | /api/auth/me       | To retrieve info about authenticated user     |                       | 
| POST       | /api/auth/refresh  | To refresh token                              |                       |

### File handling

| HTTP Verbs | Endpoints                   | Action                            | Params               |
|------------|-----------------------------|-----------------------------------|----------------------|
| POST       | /api/files                  | To upload file                    | file: pdf, doc, docx |
| GET        | /api/files                  | To get all files uploaded by user | email, password      |
| GET        | /api/files/:fileId          | To get specific file              | fileId - id of file  |
| PUT        | /api/files/:fileId          | To update specific file           | fileId - id of file  | 
| GET        | /api/files/:fileId/download | To download specific file         | fileId - id of file  |
| DELETE     | /api/files/:fileId          | To delete specific file           | fileId - id of file  |

## Usage

1. Login into acc
    1. email: *kosty@test.com*
    2. password: *password*
2. Get the token from the endpoint above, then set var **'auth_token'** in the Postman collection to the received token
3. Test API

## Test

Application test - [Postman](https://documenter.getpostman.com/view/31420373/2sA3BgBvsq)
> Go to the page above and click "Run in Postman", then export the collection to your Postman

## Third-part library

- [PHP-Open-Source-Saver/jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth)
