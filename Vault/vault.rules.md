# Vault Security & Access Rules
Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)

---

## 1. Directory Structure & Permissions

| Folder | Recommended Permissions | Git Status | Description |
|---|---|---|---|
| `/Vault/assets` | `0755` (read/execute) | Tracked | Static media, icons, brand source graphics |
| `/Vault/content` | `0755` (read/execute) | Tracked | Structured JSON/Markdown databases |
| `/Vault/uploads` | `0755` (never `0777`) | Ignored | User-submitted files |
| `/Vault/backups` | `0700` (admin read only) | Ignored | SQL and directory snapshots |
| `/Vault/cache` | `0700` (system read/write) | Ignored | Rate-limit logs and flat-file caching |

---

## 2. Security Restrictions

> [!CAUTION]
> **No Executable Code Allowed in Vault**:
> - Never store PHP, JS, executable scripts, or htaccess files inside `/Vault`.
> - If user uploads are enabled in the future, file upload endpoints must strictly validate file mime types and extensions (e.g. only allow png, jpg, pdf).
> - Prevent script execution by uploading a `.htaccess` to `/Vault/uploads` containing:
>   ```apache
>   # Disable PHP engine in uploads directory
>   <FilesMatch "\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$">
>       ForceType text/plain
>       Deny from all
>   </FilesMatch>
>   ```

---

## 3. Flat File Cache Policies

- Caches in `/Vault/cache` are written by the PHP application as JSON strings.
- Files should be named with hashes: `rate_limit_[hash].json` or `cache_[key].json`.
- Cached items must contain expiration timestamps. In-request garbage collection must periodically prune expired files.
