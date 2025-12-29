# Ticketing System API (Laravel)

This repository contains a **practice-oriented backend project** built with Laravel.

The goal of this project is not to deliver a production-ready system,  
but to **demonstrate real-world backend concepts** through a clean, step-by-step implementation,
similar to how features are developed in a professional team environment.

---

## Project Purpose

This project is designed as a **learning and portfolio project** to practice:

- API-first development with Laravel
- Clean Git workflow (feature branches, meaningful commits)
- Request validation using FormRequest classes
- Authorization using Policies (no inline permission checks)
- Explicit business rules (ticket lifecycle, access rules)
- Writing code that can be explained clearly in technical interviews

Each feature is implemented incrementally and committed separately to reflect
real development workflow rather than a single large dump of code.

---

## Tech Stack

- Laravel (API-only approach)
- Laravel Sanctum (token-based authentication)
- PHPUnit (feature-level testing)
- MySQL / SQLite (depending on environment)

---

## Running the Project Locally

1. Copy environment file  
   `cp .env.example .env`

2. Install dependencies  
   `composer install`

3. Generate application key  
   `php artisan key:generate`

4. Configure database credentials in `.env`

5. Run migrations  
   `php artisan migrate`

6. Start development server  
   `php artisan serve`

---

## API Structure

Base URL:
