## [2026-07-04] v1.2.0
**Agent:** Antigravity (Gemini)
**Scope:** Back → Admin views, layout scaffolding, and dedicated CMS workspaces

### Added
- Created dedicated editor workspaces: `post_write.php`, `page_write.php`, `portfolio_write.php`, `team_write.php`, and `email_write.php`.
- Integrated a clean 2-column layout (Left: giant title & WYSIWYG Canvas; Right: Publish box & meta-widgets) resembling classic blogging engines.
- Excluded admin sidebar navigation menu and headers when `$hide_sidebar` is active in `layout.php`.
- Added sent email outbox logging to `Vault/content/emails.json`.

## [2026-07-04] v1.1.0
**Agent:** Antigravity (Gemini)
**Scope:** Back → Services, Controllers, and SMTP Mail Integration

### Added
- Created `EmailService.php` providing branded HTML layouts and custom SMTP socket + mail() fallback transport channels.
- Added `/admin/emails` broadcast composer views, rich CKEditor fields, and CSRF-protected AJAX submit endpoints.
- Integrated fully editable workspaces and case study update controllers in `/admin/portfolio`.

### Changed
- Refactored `ContactController.php` to leverage SMTP-supported notifications via `EmailService`.

## [2026-07-02] v1.0.0
**Agent:** Antigravity (Gemini)
**Scope:** Back → Core bootstrapping, routers, controllers, and middlewares

### Added
- Created core MVC classes (`bootstrap.php`, regex `router.php`, layout `view.php`, and `db.php` PDO singleton).
- Added `ContactController.php` validating contact requests, rate limits, CSRF, and emails.
- Added flat-file `rateLimit.php` and `csrf.php` middlewares.
- Added database configuration loading and `Lead.php` ORM model.
