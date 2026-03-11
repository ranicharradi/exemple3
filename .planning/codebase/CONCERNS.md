# Concerns

## Operational Risks
- Uploaded payment proofs and course documents are stored on the local disk (`Storage::disk('local')`). This can be fragile in containerized or multi-instance deployments if no persistent volume is configured. Evidence: app/Http/Controllers/PaymentController.php, app/Http/Controllers/DocumentController.php, app/Http/Controllers/StudentDocumentController.php, app/Http/Controllers/AdminController.php; render.yaml has no persistent disk definition.

## Environment Parity
- Production uses PostgreSQL (docker-compose.yml, render.yaml) while tests use SQLite in-memory (phpunit.xml). Differences between SQLite and PostgreSQL could mask SQL or schema edge cases.

## Authorization Model
- Admin access is enforced via a role string on the users table and a custom middleware check (app/Models/User.php, app/Http/Middleware/AdminMiddleware.php). This is simple and effective but has no explicit database constraint or policy layer shown here; role misconfiguration would bypass admin protections.
