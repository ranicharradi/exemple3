# Architecture

## High-Level Flow
- HTTP requests enter through route definitions (routes/web.php, routes/auth.php).
- Controllers handle request logic (app/Http/Controllers/*).
- Models encapsulate data access via Eloquent (app/Models/*).
- Views render Blade templates (resources/views/*).

## Key Domain Areas
- Courses: listing, detail, and access checks (app/Http/Controllers/CourseController.php, app/Models/Course.php).
- Payments: manual proof upload and admin review (app/Http/Controllers/PaymentController.php, app/Http/Controllers/AdminController.php, app/Models/Payment.php).
- Enrollments: access gating for course content and documents (app/Models/Enrollment.php, app/Http/Controllers/StudentDocumentController.php).
- Admin: protected routes for course and payment management (routes/web.php, app/Http/Middleware/AdminMiddleware.php).

## Request Routing Structure
- Public routes for course browsing and auth (routes/web.php, routes/auth.php).
- Authenticated student routes for dashboard, cart, and payments (routes/web.php).
- Admin routes grouped with `auth`, `verified`, and `admin` middleware (routes/web.php).

## Data Access Patterns
- Controllers use Eloquent queries and relationships (app/Http/Controllers/*, app/Models/*).
- Transactions used for payment status updates (app/Http/Controllers/AdminController.php) and payment creation (app/Http/Controllers/PaymentController.php).
