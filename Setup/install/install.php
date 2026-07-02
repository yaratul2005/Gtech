<?php
/**
 * Great Endured Technology — Installation Wizard
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

require_once __DIR__ . '/../../Back/core/bootstrap.php';

$lockFile = __DIR__ . '/install.lock';
$schemaFile = __DIR__ . '/../db/schema.sql';
$logFile = __DIR__ . '/../setup.log.md';

// 1. Check if already installed
if (file_exists($lockFile)) {
    http_response_code(403);
    die("Installation Wizard is locked. To re-install, delete `/Setup/install/install.lock`.");
}

$status = [];
$step = $_GET['step'] ?? 'check';

if ($step === 'check') {
    // Check PHP version (requires 8.x)
    $phpVersion = phpversion();
    $versionOk = version_compare($phpVersion, '8.0.0', '>=');
    
    $status[] = [
        'param' => 'PHP Version',
        'current' => $phpVersion,
        'required' => '>= 8.0.0',
        'status' => $versionOk ? 'PASS' : 'FAIL'
    ];

    // Check directory writable permissions
    $directories = [
        '/Vault/uploads' => __DIR__ . '/../../Vault/uploads',
        '/Vault/cache' => __DIR__ . '/../../Vault/cache',
        '/Vault/backups' => __DIR__ . '/../../Vault/backups',
        '/Back/config' => __DIR__ . '/../../Back/config',
    ];

    foreach ($directories as $name => $path) {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $writable = is_writable($path);
        $status[] = [
            'param' => "Folder Writable: {$name}",
            'current' => $writable ? 'Writable' : 'Blocked',
            'required' => 'Writable',
            'status' => $writable ? 'PASS' : 'FAIL'
        ];
    }
}

// 2. Perform DB Connection and Scaffolding
$dbStatus = 'Not Checked';
$dbMessage = '';
if ($step === 'run') {
    // Attempt to auto-create database if it does not exist (Resource.md stack helper)
    $host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
    $port = getenv('DB_PORT') !== false ? getenv('DB_PORT') : '3306';
    $dbName = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'greatentech_db';
    $user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
    $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

    try {
        $tempDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $tempPdo = new PDO($tempDsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $tempPdo = null;
    } catch (\PDOException $e) {
        error_log("Database auto-creation failed: " . $e->getMessage());
    }

    $db = \Back\Core\DB::getConnection();
    if ($db === null) {
        $dbStatus = 'FAIL';
        $dbMessage = 'Could not connect to database. Please check credentials in `.env` and verify MySQL is running in XAMPP.';
    } else {
        try {
            // Read and run schema sql
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $db->exec($sql);
                $dbStatus = 'SUCCESS';
                $dbMessage = 'Database tables successfully scaffolded.';

                // Self-lock (Resource.md Section 3)
                file_put_contents($lockFile, date('Y-m-d H:i:s'));

                // Log to setup.log.md (Resource.md Section 3)
                $logEntry = "\n## [" . date('Y-m-d H:i:s') . "] First Run Bootstrap\n";
                $logEntry .= "- **Status**: SUCCESS\n";
                $logEntry .= "- **PHP Version**: " . phpversion() . "\n";
                $logEntry .= "- **Action**: Scaffolded `leads` table and created lock file.\n";
                file_put_contents($logFile, $logEntry, FILE_APPEND);
            } else {
                $dbStatus = 'FAIL';
                $dbMessage = 'Schema file `schema.sql` is missing.';
            }
        } catch (\PDOException $e) {
            $dbStatus = 'FAIL';
            $dbMessage = 'Migration execution failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GET — Setup Wizard</title>
    <style>
        body { font-family: sans-serif; background: #05060a; color: #cbd5e1; padding: 40px; }
        .box { max-width: 600px; margin: 0 auto; background: #12141c; padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); }
        h1 { color: #fff; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .PASS { color: #34d399; font-weight: bold; }
        .FAIL { color: #f87171; font-weight: bold; }
        .btn { display: inline-block; background: #3b82f6; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn:hover { background: #2563eb; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: rgba(52, 211, 153, 0.1); border: 1px solid #34d399; color: #34d399; }
        .alert-error { background: rgba(248, 113, 113, 0.1); border: 1px solid #f87171; color: #f87171; }
    </style>
</head>
<body>
    <div class="box">
        <h1>GET. System Scaffold Wizard</h1>
        
        <?php if ($step === 'check'): ?>
            <h3>Environment Checks</h3>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Required</th>
                        <th>Current</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($status as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['param']); ?></td>
                            <td><?php echo htmlspecialchars($s['required']); ?></td>
                            <td><?php echo htmlspecialchars($s['current']); ?></td>
                            <td class="<?php echo $s['status']; ?>"><?php echo $s['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <a href="?step=run" class="btn">Run Database Migrations</a>
        <?php elseif ($step === 'run'): ?>
            <h3>Database Setup Status</h3>
            <?php if ($dbStatus === 'SUCCESS'): ?>
                <div class="alert alert-success">
                    <strong>Success!</strong> <?php echo htmlspecialchars($dbMessage); ?>
                </div>
                <p>The installation is complete and has been locked for security.</p>
                <a href="/" class="btn">Proceed to Website</a>
            <?php else: ?>
                <div class="alert alert-error">
                    <strong>Failed!</strong> <?php echo htmlspecialchars($dbMessage); ?>
                </div>
                <a href="?step=check" class="btn" style="background:#64748b;">Retry Checks</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
