<?php
/**
 * Great Endured Technology — Rate Limiting Middleware (Flat-file based)
 * Spec from Resource.md (Section 3 & 4)
 */

declare(strict_types=1);

namespace Back\Middleware;

class RateLimit
{
    private static int $limit = 5; // Max 5 requests
    private static int $window = 3600; // per hour (in seconds)

    public static function check(string $endpoint): bool
    {
        // Get client IP address
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ipHash = md5($ip . '_' . $endpoint);

        $cacheDir = __DIR__ . '/../../Vault/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $file = $cacheDir . '/rate_limit_' . $ipHash . '.json';
        $now = time();

        if (file_exists($file)) {
            $data = json_decode((string)file_get_contents($file), true);
            
            // Check if window has expired
            if ($now - $data['start_time'] > self::$window) {
                // Reset window
                $data = [
                    'start_time' => $now,
                    'count' => 1
                ];
            } else {
                // Increment count
                $data['count']++;
                if ($data['count'] > self::$limit) {
                    return false; // Limit exceeded
                }
            }
        } else {
            // First request in window
            $data = [
                'start_time' => $now,
                'count' => 1
            ];
        }

        file_put_contents($file, json_encode($data));
        return true;
    }
}
