## [2026-07-02] v1.0.0
**Agent:** Antigravity (Gemini)
**Scope:** Back → Core bootstrapping, routers, controllers, and middlewares

### Added
- Created core MVC classes (`bootstrap.php`, regex `router.php`, layout `view.php`, and `db.php` PDO singleton).
- Added `ContactController.php` validating contact requests, rate limits, CSRF, and emails.
- Added flat-file `rateLimit.php` and `csrf.php` middlewares.
- Added database configuration loading and `Lead.php` ORM model.
