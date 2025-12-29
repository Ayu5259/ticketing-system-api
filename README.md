# Ticketing System API (Laravel)

API-first ticketing system built with Laravel for portfolio and interview demonstration.

## Goals

-   Clean, step-by-step development with professional Git workflow
-   FormRequest validation
-   Policy-based authorization
-   Clear business rules (ticket lifecycle)

## Tech

-   Laravel (API-first)
-   Sanctum (token auth)
-   PHPUnit (feature tests)

## Run locally

1. `cp .env.example .env`
2. `composer install`
3. `php artisan key:generate`
4. Configure DB in `.env`
5. `php artisan migrate`
6. `php artisan serve`

## API

Base URL: `/api/v1`

### Health

`GET /api/v1/health`

Response:

```json
{
    "success": true,
    "message": "Healthy",
    "data": {
        "service": "ticketing-system-api"
    }
}
```
