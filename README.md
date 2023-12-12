

# Running Migrations

copy .env.example to .env 

### 
```bash
composer require "darkaonline/l5-swagger"
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
composer require doctrine/annotations

```
### Swagger for front-end dev

-  `http://localhost:8000/api/documentation`

## To set up the database schema, run the following Artisan command:
```bash
composer install
php artisan migrate

```
run laravel application : 
```bash
php artisan serve
```

### Swagger for front-end dev

-  `http://localhost:8000/api/documentation`


# This API provides authentication endpoints for user registration, login, and logout.

## Register User

### Endpoint

- **POST** `http://localhost:8000/api/auth/register`

### Request Body

```json
{
    "display_name": "Phan Tuấn",
    "email": "tuan123@gmail.com",
    "password": "Ptuan123@"
}
```

## Login User

### Endpoint

- **POST** `http://localhost:8000/api/auth/login`

### Request Body

```json
{
    "email": "tuan123@gmail.com",
    "password": "Ptuan123@"
}
```
## user-profile (require bearer token)
- **GET** `http://localhost:8000/api/user/me`


## refresh-token (require bearer token)
- **POST** `http://localhost:8000/api/user/refresh`

## change-password (require bearer token)
- **POST** `http://localhost:8000/api/user/change-password`
```json
{
    "old_password" : "",
    "new_password" : ""
}
```


