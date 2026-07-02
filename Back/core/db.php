<?php
/**
 * Great Endured Technology — Database Connection Wrapper
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

namespace Back\Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $instance = null;

    private function __construct() {} // Prevent direct instantiation

    public static function getConnection(): ?PDO
    {
        if (self::$instance === null) {
            // Load credentials from environment config
            $host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
            $port = getenv('DB_PORT') !== false ? getenv('DB_PORT') : '3306';
            $dbName = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'greatentech_db';
            $user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'greatentech_user';
            $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'greatentech_password';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // If in production, do not leak connection credentials
                $errorMsg = getenv('APP_ENV') === 'development' 
                    ? "Connection failed: " . $e->getMessage() 
                    : "Database connection could not be established.";
                
                error_log("PDO Connection Error: " . $e->getMessage());
                
                // Return null instead of throwing uncaught exception to fail gracefully
                return null;
            }
        }

        return self::$instance;
    }
}
