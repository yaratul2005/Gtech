<?php
/**
 * Great Endured Technology — DB configurations loader
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'dbname' => getenv('DB_NAME') ?: 'greatentech_db',
    'user' => getenv('DB_USER') ?: 'greatentech_user',
    'password' => getenv('DB_PASS') ?: 'greatentech_password',
];
