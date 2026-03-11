# Conventions

## Language & Formatting
- PHP code adheres to standard Laravel conventions with PSR-4 autoloading (composer.json).
- Editor defaults: 4-space indentation, LF endings, trim trailing whitespace ( .editorconfig ).

## Naming & Structure
- Controllers under app/Http/Controllers with *Controller.php suffix (app/Http/Controllers/*).
- Models under app/Models with singular names (app/Models/*).
- Form requests under app/Http/Requests (app/Http/Requests/*).
- Blade templates under resources/views with folder-based grouping (resources/views/*).
- Migrations named with timestamps and descriptive suffixes (database/migrations/*).

## Tooling Standards
- Laravel Pint included for PHP formatting (composer.json).
- Vite and Tailwind config live at repo root (vite.config.js, tailwind.config.js).
