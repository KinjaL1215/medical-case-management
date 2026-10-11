# Medical Case Management System

A Laravel-based web application for managing medical cases, with role-based access control and REST API support.

## Features

- **Role-based access:** Admin and Viewer roles.
- **Dashboard:** View medical case counts and case records.
- **Case management:** Create, view, edit, and delete cases according to user permissions.
- **Search and filters:** Search by case number or surgeon name and filter by status.
- **Pagination:** Display 10 cases per page.
- **REST API:** Token-based authentication using Laravel Sanctum.
- **Validation:** Validate case details and prevent duplicate case numbers.
- **Automated tests:** Tests for authentication, case CRUD operations, validation, API access, and web authorization.

## Technology Stack

- PHP 8.4
- Laravel 13
- MySQL
- Blade Templates
- Laravel Sanctum
- PHPUnit / Pest testing

## Requirements

- PHP and Composer
- MySQL
- Node.js and npm, if frontend assets need to be built

## Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/KinjaL1215/medical-case-management.git
cd medical-case-management
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create your environment file

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

On macOS or Linux:

```bash
cp .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure the database

Create a MySQL database named `medical_case_management`.

Configure your local database credentials in the `.env` file.

### 6. Run migrations and seed sample data

```bash
php artisan migrate --seed
```

### 7. Start the application

```bash
php artisan serve
```

### 8. Open the application

Visit [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

## Development Login Accounts

The database seeder provides these development-only accounts:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `Admin@12345` |
| Viewer | `viewer@example.com` | `Viewer@12345` |

These credentials are intended for local development and demonstration only. Do not use them in production.

## API Endpoints

All case endpoints except login require authentication using a valid Sanctum bearer token.

| Method | Endpoint | Access |
|---|---|---|
| POST | `/api/login` | Public |
| POST | `/api/logout` | Authenticated |
| GET | `/api/cases` | Admin and Viewer |
| GET | `/api/cases/{id}` | Admin and Viewer |
| POST | `/api/cases` | Admin only |
| PUT | `/api/cases/{id}` | Admin only |
| DELETE | `/api/cases/{id}` | Admin only |

### API Authentication

Send a POST request to `/api/login` with JSON credentials:

```json
{
    "email": "admin@example.com",
    "password": "Admin@12345"
}
```

Use the returned token in subsequent requests:

```text
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

Use only dummy patient data when testing this project.

## Running Tests

Run the automated test suite:

```bash
php artisan test
```

The current test suite contains 16 passing tests with 39 assertions, covering authentication, case management, validation, and role-based access control.

## Documentation

- [Database Documentation](docs/database.md)
- [Requirements Traceability](docs/traceability.md)

## Security Notes

- Admin-only operations are protected by role middleware and authorization checks.
- Passwords are stored using Laravel's password hashing.
- API access uses Laravel Sanctum tokens.
- The `.env` file contains local configuration and must not be committed.
- The `.env.example` file should contain placeholder values, never real credentials.
- Use dummy patient data for development and testing.
- Change the example development passwords before using any deployed environment.
