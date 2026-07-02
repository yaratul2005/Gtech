<?php
/**
 * Great Endured Technology — Lead Model
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

namespace Back\Models;

use Back\Core\DB;
use PDO;
use Exception;

class Lead
{
    public static function create(array $data): bool
    {
        $db = DB::getConnection();
        if ($db === null) {
            error_log("Database connection not available. Unable to save lead.");
            return false;
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO leads (name, email, service, message, created_at)
                VALUES (:name, :email, :service, :message, :created_at)
            ");

            return $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':service' => $data['service'] ?? 'General Inquiry',
                ':message' => $data['message'],
                ':created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            error_log("Failed to insert lead: " . $e->getMessage());
            return false;
        }
    }
}
