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
        // -------------------------------------------------------------
        // Option A: File-Based Database (Active - Zero Config)
        // Stores contact leads in /Vault/cache/leads.json (gitignored)
        // -------------------------------------------------------------
        $filePath = __DIR__ . '/../../Vault/cache/leads.json';
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $leads = [];
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            if (!empty($content)) {
                $leads = json_decode($content, true) ?: [];
            }
        }

        $newLead = [
            'id' => uniqid('lead_', true),
            'name' => $data['name'],
            'email' => $data['email'],
            'service' => $data['service'] ?? 'General Inquiry',
            'message' => $data['message'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $leads[] = $newLead;

        // Secure write with file-locking to prevent concurrency issues
        $fp = fopen($filePath, 'c');
        if ($fp) {
            if (flock($fp, LOCK_EX)) {
                ftruncate($fp, 0); // Clear file
                fwrite($fp, json_encode($leads, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                fflush($fp);
                flock($fp, LOCK_UN);
                fclose($fp);
                return true;
            }
            fclose($fp);
        }
        return false;

        // -------------------------------------------------------------
        // Option B: MySQL Database (Uncomment this and comment out Option A to migrate)
        // -------------------------------------------------------------
        /*
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
        */
    }
}
