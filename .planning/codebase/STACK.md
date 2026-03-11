# Stack

## Backend
- PHP 8.2 runtime (Dockerfile, composer.json)
- Laravel 12 framework (composer.json)
- Laravel Breeze auth scaffolding (composer.json)
- Eloquent ORM / Blade templating (app/, resources/views)

## Frontend
- Blade views with Tailwind CSS (resources/views, tailwind.config.js)
- Vite asset pipeline (vite.config.js)
- Alpine.js + Axios (package.json)

## Data & Storage
- PostgreSQL in local dev via Docker Compose (docker-compose.yml)
- PostgreSQL in production via Render database (render.yaml)
- SQLite in-memory for tests (phpunit.xml)

## Tooling
- Composer + npm (composer.json, package.json)
- Laravel Pint formatter (composer.json)
- Docker build/runtime (Dockerfile)
- Render deployment config (render.yaml)
