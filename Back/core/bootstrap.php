<?php
/**
 * Great Endured Technology — Bootstrap Core
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Helper function to load env variables from .env file
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Split by first equals sign
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);

            // Strip quotes if any
            if (preg_match('/^"(.+)"$/', $val, $matches) || preg_match("/^'(.+)'$/", $val, $matches)) {
                $val = $matches[1];
            }

            $_ENV[$key] = $val;
            putenv("{$key}={$val}");
        }
    }
}

// Load env configurations
loadEnv(__DIR__ . '/../../.env');

// Set PHP execution parameters (Resource.md: Shared Hosting limits check)
$appEnv = $_ENV['APP_ENV'] ?? 'production';
ini_set('display_errors', $appEnv === 'development' ? '1' : '0');
ini_set('display_startup_errors', $appEnv === 'development' ? '1' : '0');
error_reporting($appEnv === 'development' ? E_ALL : 0);

// Set default timezone
date_default_timezone_set('Asia/Dhaka');

// 2. Global Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    // e.g. Back\Controllers\ContactController -> Back/controllers/ContactController.php
    $classPath = str_replace('\\', '/', $class);
    
    // Check if path starts with Back/
    if (strpos($classPath, 'Back/') === 0) {
        // Namespace parts: Back/Core, Back/Controllers, Back/Models, Back/Services
        // Fix folder case according to protocol (e.g. Back/core, Back/controllers, Back/models, Back/services)
        $parts = explode('/', $classPath);
        if (count($parts) >= 3) {
            $parts[1] = strtolower($parts[1]); // Make subdirectory lowercase (core, controllers, models, services)
        }
        
        // Check 1: Exact casing (e.g. Back/core/Router.php)
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }

        // Check 2: Lowercase filename (e.g. Back/core/router.php)
        $lastIdx = count($parts) - 1;
        $originalLast = $parts[$lastIdx];
        
        $parts[$lastIdx] = strtolower($originalLast);
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }

        // Check 3: Lowercase first letter of filename (e.g. Back/middleware/rateLimit.php)
        $parts[$lastIdx] = lcfirst($originalLast);
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Check if path starts with Setup/
    if (strpos($classPath, 'Setup/') === 0) {
        $parts = explode('/', $classPath);
        if (isset($parts[1])) {
            $parts[1] = strtolower($parts[1]); // e.g. Setup/admin
        }
        if (isset($parts[2])) {
            $parts[2] = strtolower($parts[2]); // e.g. Setup/admin/controllers
        }

        // Check 1: Exact casing (e.g. Setup/admin/controllers/AdminController.php)
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }

        // Check 2: Lowercase filename
        $lastIdx = count($parts) - 1;
        $originalLast = $parts[$lastIdx];
        $parts[$lastIdx] = strtolower($originalLast);
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 3. View Helper Function
if (!function_exists('view')) {
    function view(string $page, array $data = []): string
    {
        $renderer = new \Back\Core\View();
        return $renderer->render($page, $data);
    }
}

// 4. Config Helper Function
if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

// 5. CSRF helpers
if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}
