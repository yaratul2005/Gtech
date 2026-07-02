<?php
/**
 * Great Endured Technology — Auth Middleware for Setup & Admin Layer
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

namespace Setup\Auth;

class AuthMiddleware
{
    public static function verify(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: /admin/login');
            exit();
        }
    }

    public static function redirectToDashboardOrLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /admin/login');
        }
        exit();
    }
}
