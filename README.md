# Running Migrations

To set up the database schema, run the following Artisan command:

```bash
composer install
php artisan migrate

```

# This API provides authentication endpoints for user registration, login, and logout.

## Register User

### Endpoint

- **POST** `http://localhost:8000/api/register`

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

- **POST** `http://localhost:8000/api/login`

### Request Body

```json
{
    "email": "tuan123@gmail.com",
    "password": "12345678"
}
```
## Logout (require token)
- **POST** `http://localhost:8000/api/logout`
