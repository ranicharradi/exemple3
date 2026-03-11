# Testing

## Framework
- PHPUnit configured via phpunit.xml
- Feature and Unit suites (tests/Feature, tests/Unit)

## Test Environment
- Uses SQLite in-memory DB for tests (phpunit.xml)
- Laravel test bootstrap from vendor/autoload.php (phpunit.xml)

## Test Coverage Areas (Examples)
- Authentication and verification flows (tests/Feature/Auth/*)
- Admin access and course management (tests/Feature/AdminAccessTest.php, tests/Feature/AdminCourseManagementTest.php)
- Payments and profile flows (tests/Feature/PaymentWorkflowTest.php, tests/Feature/ProfileTest.php)

## Common Commands
- `composer test` runs the Laravel test suite (composer.json)
