# Integrations

## Database
- PostgreSQL service for local dev (docker-compose.yml)
- PostgreSQL on Render (render.yaml)

## File Storage
- Local disk storage for payment proofs and course documents via Storage::disk('local') (app/Http/Controllers/PaymentController.php, app/Http/Controllers/DocumentController.php, app/Http/Controllers/StudentDocumentController.php, app/Http/Controllers/AdminController.php)

## Auth & User Management
- Laravel built-in auth routes and controllers (routes/auth.php, app/Http/Controllers/Auth/*)

## Payments (Manual Bank Transfer)
- Bank transfer metadata and limits via config/codex.php

## Asset Pipeline
- Vite build with Tailwind and PostCSS (vite.config.js, tailwind.config.js, postcss.config.js)
