<?php
/**
 * Great Endured Technology — CSRF Middleware
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

namespace Back\Middleware;

class CSRF
{
    public static function verify(): bool
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Only verify modifying methods
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $_POST['csrf_token'] ?? '';
            
            // Check headers if not found in POST (for JSON requests)
            if (empty($token)) {
                $headers = getallheaders();
                $token = $headers['X-CSRF-Token'] ?? '';
            }

            if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
                return false;
            }
        }
        return true;
    }
}
