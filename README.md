# Codex Learning Platform

A Laravel-based e-learning platform where students purchase and access online courses via manual bank transfer (RIB) payment, validated by an administrator.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Database Schema](#database-schema)
- [Roles & Permissions](#roles--permissions)
- [Payment Workflow](#payment-workflow)
- [Routes](#routes)
- [Security](#security)
- [Build Plan](#build-plan)
- [Getting Started](#getting-started)

---

## Overview

Codex Learning Platform connects students with online courses. Unlike platforms with automated payment gateways, Codex uses a **manual bank transfer (RIB)** model:

1. Student selects a course and receives bank transfer details (IBAN, account holder, amount, reference).
2. Student transfers the funds and uploads proof of payment.
3. Admin verifies the transfer and approves/rejects the payment.
4. Upon approval, the student is enrolled and gains access to the course content.

---

## Tech Stack

| Layer     | Technology              |
|-----------|-------------------------|
| Backend   | Laravel (PHP)           |
| Frontend  | Blade / HTML / CSS / JS |
| Database  | PostgreSQL              |
| Auth      | Laravel built-in auth   |

---

## Architecture

```
app/
├── Models/
│   ├── User.php
│   ├── Course.php
│   ├── Payment.php
│   └── Enrollment.php
├── Http/
│   ├── Controllers/
│   │   ├── CourseController.php
│   │   ├── PaymentController.php
│   │   └── AdminController.php
│   └── Middleware/
│       └── AdminMiddleware.php
resources/
└── views/
    ├── courses/
    ├── payments/
    └── admin/
```

---

## Database Schema

### users
| Column     | Type      | Notes               |
|------------|-----------|----------------------|
| id         | PK        |                      |
| name       | string    |                      |
| email      | string    | unique               |
| password   | string    | hashed               |
| role       | string    | `admin` or `student` |
| created_at | timestamp |                      |

### courses
| Column      | Type      | Notes |
|-------------|-----------|-------|
| id          | PK        |       |
| title       | string    |       |
| description | text      |       |
| price       | decimal   |       |
| video_url   | string    |       |
| created_at  | timestamp |       |

### payments
| Column     | Type      | Notes                              |
|------------|-----------|------------------------------------|
| id         | PK        |                                    |
| user_id    | FK→users  |                                    |
| course_id  | FK→courses|                                    |
| amount     | decimal   |                                    |
| reference  | string    | unique transfer reference           |
| proof      | string    | file path to uploaded proof image   |
| status     | string    | `pending`, `approved`, `rejected`  |
| created_at | timestamp |                                    |

### enrollments
| Column         | Type      | Notes        |
|----------------|-----------|--------------|
| id             | PK        |              |
| user_id        | FK→users  |              |
| course_id      | FK→courses|              |
| access_granted | boolean   | default false|
| created_at     | timestamp |              |

---

## Roles & Permissions

### Admin
- Create / update / delete courses
- View all students
- View payment requests
- Approve or reject payments
- Grant course access

### Student
- Register and login
- Browse courses
- Request course purchase
- Upload payment proof (bank transfer receipt)
- Access purchased courses after admin approval

---

## Payment Workflow

```
┌─────────┐   select course   ┌─────────────────┐
│ Student │ ───────────────►  │ View bank info   │
└─────────┘                   │ (IBAN, amount,   │
                              │  reference)      │
                              └────────┬─────────┘
                                       │ transfer funds
                                       ▼
                              ┌─────────────────┐
                              │ Upload proof     │
                              └────────┬─────────┘
                                       │ status = pending
                                       ▼
                              ┌─────────────────┐
                              │ Admin reviews    │
                              └───┬─────────┬────┘
                          approve │         │ reject
                                  ▼         ▼
                          ┌──────────┐  ┌──────────┐
                          │ Enrolled │  │ Rejected │
                          │ + access │  │ notified │
                          └──────────┘  └──────────┘
```

---

## Routes

### Student Routes
| Method | URI               | Action                        |
|--------|-------------------|-------------------------------|
| GET    | `/courses`        | List all courses              |
| GET    | `/course/{id}`    | View course details           |
| POST   | `/payment`        | Submit payment proof          |
| GET    | `/my-courses`     | View enrolled courses         |

### Admin Routes (protected by admin middleware)
| Method | URI                | Action                       |
|--------|--------------------|------------------------------|
| GET    | `/admin/dashboard` | Admin dashboard overview     |
| GET    | `/admin/courses`   | Manage courses (CRUD)        |
| GET    | `/admin/payments`  | Review pending payments      |
| GET    | `/admin/students`  | View registered students     |

---

## Security

### Authentication & Authorization
- **Laravel built-in authentication** (registration, login, password hashing via bcrypt).
- **Role-based access control**: `role` column on users table distinguishes `admin` from `student`.
- **Admin middleware** (`AdminMiddleware`) protects all `/admin/*` routes — rejects non-admin users with 403.

### Input Validation & Injection Prevention
- **CSRF protection**: All POST/PUT/DELETE forms include `@csrf` token. Laravel middleware rejects requests without valid tokens.
- **Server-side validation**: Every form submission is validated in the controller using Laravel's `validate()` — required fields, types, max lengths, allowed values (`in:pending,approved,rejected`).
- **Eloquent ORM & parameterized queries**: All database interaction uses Eloquent or query builder with bound parameters — no raw SQL concatenation. This prevents SQL injection.
- **Blade auto-escaping**: All output uses `{{ }}` (auto-escaped). Raw `{!! !!}` is never used for user-supplied data. This prevents XSS.

### File Upload Security
- **Payment proof uploads** are validated for file type (image/pdf only), max size, and stored in a non-public directory or via Laravel's storage disk.
- Files are served through a controller route that checks authorization — students can only view their own proofs, admins can view all.
- Original filenames are never preserved; files are renamed to random hashes.

### Access Control
- Students can only view their own payments and enrolled courses.
- Course video URLs are only accessible to enrolled students (checked via `enrollments` table).
- All admin actions verify the authenticated user's role before executing.

### Rate Limiting & Session Security
- Laravel's built-in rate limiting on login routes prevents brute-force attacks.
- Sessions use encrypted cookies with `HttpOnly` and `Secure` flags in production.
- `.env` file is excluded from version control and contains all secrets.

---

## Build Plan

The project is built in **7 phases**, each delivering a working increment:

### Phase 1 — Project Setup
- `laravel new codex-learning`
- Configure PostgreSQL in `.env`
- Run initial migration
- Set up Git repository

### Phase 2 — Authentication
- `php artisan make:auth` or Laravel Breeze/Fortify
- Add `role` column to users table (migration)
- Seed an admin user
- Create `AdminMiddleware` to gate `/admin/*` routes

### Phase 3 — Course Management (Admin)
- `Course` model + migration
- `CourseController` with CRUD actions
- Admin Blade views: course list, create/edit form
- Route registration under `/admin/courses`

### Phase 4 — Student Course Browsing
- Public course listing page (`/courses`)
- Course detail page (`/course/{id}`)
- Blade views with course info and price

### Phase 5 — Payment System
- `Payment` model + migration
- `PaymentController`: show bank info, handle proof upload, list student payments
- Payment form with file upload (proof)
- Display payment status to student

### Phase 6 — Admin Payment Validation
- Admin payments list (pending/approved/rejected filters)
- Approve/reject actions in `AdminController`
- On approval: create `Enrollment` record with `access_granted = true`
- Notify student of status change (optional: email or dashboard flash)

### Phase 7 — Enrollment & Course Access
- `Enrollment` model + migration
- `/my-courses` page listing enrolled courses
- Video access gate: middleware or policy check on enrollment status
- Admin can view all enrollments

### Current Implementation Status

- Phase 1: implemented
- Phase 2: implemented
- Phase 3: implemented
- Phase 4: implemented
- Phase 5: implemented
- Phase 6: implemented
- Phase 7: implemented

---

## Getting Started

```bash
# Clone the repository
git clone <repo-url> codex-learning
cd codex-learning

# Install dependencies
composer install
npm install && npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Start PostgreSQL in Docker
docker compose up -d postgres

# The app connects to the Docker database with:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=codex_learning
# DB_USERNAME=postgres
# DB_PASSWORD=postgres
#
# Optional bank transfer settings:
# CODEX_BANK_ACCOUNT_HOLDER="Codex Learning Platform"
# CODEX_BANK_NAME="Codex Bank"
# CODEX_BANK_IBAN=TN59040012345678901234
# CODEX_PAYMENT_REFERENCE_PREFIX=CDX
# CODEX_PAYMENT_PROOF_MAX_KB=5120

# Run migrations and seed
php artisan migrate
php artisan db:seed

# Start the server
php artisan serve
```

Visit `http://localhost:8000` — register as a student or login as admin (seeded credentials in `DatabaseSeeder`).

### Docker Database

- PostgreSQL runs in Docker via `docker-compose.yml`.
- The main `.env` is configured for Dockerized PostgreSQL.
- Tests use `.env.testing` with SQLite in memory, so `php artisan test` does not depend on Docker.
- If you run the app with XAMPP PHP, make sure `pdo_pgsql` and `pgsql` are enabled in `C:\xampp\php\php.ini`.
