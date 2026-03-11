# Structure

## Top-Level
- app/ - Controllers, models, middleware, view components (app/Http, app/Models, app/View)
- bootstrap/ - Laravel bootstrap/cache setup
- config/ - Application and integration configuration
- database/ - Migrations, factories, seeders
- public/ - Public web root
- resources/ - Blade views, JS, CSS
- routes/ - Route definitions
- storage/ - Logs, compiled views, and uploaded files (local disk)
- tests/ - PHPUnit Feature and Unit tests

## Notable Files
- composer.json / composer.lock - PHP dependencies and scripts
- package.json / package-lock.json - Frontend tooling dependencies
- docker-compose.yml - PostgreSQL service for local dev
- Dockerfile - Production container build
- render.yaml - Render deployment configuration
- phpunit.xml - Test configuration
- vite.config.js / tailwind.config.js / postcss.config.js - Frontend build config
