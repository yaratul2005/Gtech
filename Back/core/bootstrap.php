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

// 1. Config Encryption & Secure Settings Engine
class ConfigEncryptor
{
    private static ?string $key = null;

    private static function getKey(): string
    {
        if (self::$key !== null) {
            return self::$key;
        }

        $keyFile = __DIR__ . '/../../Vault/content/.key';
        if (!file_exists($keyFile)) {
            // Generate a secure random key
            $randomKey = bin2hex(random_bytes(16));
            file_put_contents($keyFile, $randomKey);
        }
        self::$key = trim((string)file_get_contents($keyFile));
        return self::$key;
    }

    public static function encrypt(string $value): string
    {
        if ($value === '') return '';
        $key = self::getKey();
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, 'AES-128-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $value): string
    {
        if ($value === '') return '';
        $data = base64_decode($value, true);
        if ($data === false || strlen($data) < 16) {
            return $value; // Fallback to raw if not encrypted
        }
        $key = self::getKey();
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, 0, $iv);
        return $decrypted !== false ? $decrypted : $value;
    }
}

// 2. Load and Bootstrap Configurations
$defaultConfigs = [
    'APP_ENV' => 'production',
    'APP_NAME' => 'Great Endured Technology',
    'APP_URL' => 'https://greatentech.com',
    'APP_DESC' => 'Great Endured Technology is a premier digital agency providing enterprise WordPress development, custom PHP solutions, SEO, digital marketing, Canva content, and 3D mockups.',
    'APP_KEYWORDS' => 'Great Endured Technology, digital agency, WordPress development, Elementor, PHP website, SEO ranking, Digital marketing, Canva design, 3D mockups, ecommerce startup',
    'DB_HOST' => 'localhost',
    'DB_PORT' => '3306',
    'DB_NAME' => 'great_endured_db',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'ADMIN_USER' => 'admin',
    'ADMIN_PASS' => 'admin1234',
    'SMTP_HOST' => '',
    'SMTP_PORT' => '587',
    'SMTP_USER' => '',
    'SMTP_PASS' => '',
    'SMTP_FROM' => 'contact@greatentech.com',
    'SMTP_FROM_NAME' => 'Great Endured Technology',
    'HEADER_CODE' => '',
    'FOOTER_CODE' => ''
];

$settingsFile = __DIR__ . '/../../Vault/content/settings.json';
$settingsData = [];
if (file_exists($settingsFile)) {
    $settingsData = json_decode((string)file_get_contents($settingsFile), true) ?: [];
}

// Populate system configurations (Decrypter triggers for sensitive fields)
$sensitiveKeys = ['db_pass', 'admin_pass', 'smtp_pass'];
foreach ($defaultConfigs as $key => $defaultVal) {
    $jsonKey = strtolower($key);
    $val = $settingsData[$jsonKey] ?? $defaultVal;

    // Decrypt if it is a sensitive field
    if (in_array($jsonKey, $sensitiveKeys, true) && !empty($val)) {
        $val = ConfigEncryptor::decrypt((string)$val);
    }

    $_ENV[$key] = $val;
    putenv("{$key}={$val}");
}

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
    
    // Check if path starts with Back/ or Setup/
    if (strpos($classPath, 'Back/') === 0 || strpos($classPath, 'Setup/') === 0) {
        $parts = explode('/', $classPath);
        $lastIdx = count($parts) - 1;
        $originalLast = $parts[$lastIdx];

        // Lowercase all directories, keeping the class name (last element) original
        for ($i = 1; $i < $lastIdx; $i++) {
            $parts[$i] = strtolower($parts[$i]);
        }
        
        // Check 1: Exact casing (e.g. Setup/auth/AuthMiddleware.php)
        $file = __DIR__ . '/../../' . implode('/', $parts) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }

        // Check 2: Lowercase filename (e.g. Setup/auth/authmiddleware.php)
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
