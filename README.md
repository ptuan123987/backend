# Running Migrations

copy .env.example to .env 

To set up the database schema, run the following Artisan command:
```bash
composer install
php artisan migrate

```
run laravel application : 
```bash
php artisan serve
```

# This API provides authentication endpoints for user registration, login, and logout.

## Register User

### Endpoint

- **POST** `http://localhost:8000/api/auth/register`

### Request Body

```json
{
    "display_name": "Phan Tuấn",
    "email": "tuan123@gmail.com",
    "password": "12345678"
}
```

## Login User

### Endpoint

- **POST** `http://localhost:8000/api/auth/login`

### Request Body

```json
{
    "email": "tuan123@gmail.com",
    "password": "12345678"
}
```
## user-profile (require bearer token)
- **GET** `http://localhost:8000/api/auth/user-profile`


## refresh-token (require bearer token)
- **POST** `http://localhost:8000/api/auth/refresh`

## change-password (require bearer token)
- **POST** `http://localhost:8000/api/auth/change-password`
```json
{
    "old_password" : "12345678",
    "new_password" : "87654321"
}
```


