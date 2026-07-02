<?php
/**
 * Great Endured Technology — Admin Panel Controller
 * Governed by RBP (AGENTS.md) & Stack Profile (Resource.md)
 */

declare(strict_types=1);

namespace Setup\Admin\Controllers;

use Back\Middleware\CSRF;

class AdminController
{
    private string $leadsFile;
    private string $servicesFile;
    private string $portfolioFile;
    private string $settingsFile;

    public function __construct()
    {
        $this->leadsFile = __DIR__ . '/../../../Vault/cache/leads.json';
        $this->servicesFile = __DIR__ . '/../../../Vault/content/services.json';
        $this->portfolioFile = __DIR__ . '/../../../Vault/content/portfolio.json';
        $this->settingsFile = __DIR__ . '/../../../Vault/content/settings.json';
    }

    public function showLoginForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If already logged in, redirect
        if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: /admin/dashboard');
            exit();
        }

        $this->renderView('login', ['title' => 'Admin Login']);
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');

        // Fetch credentials from environment
        $adminUser = getenv('ADMIN_USER') !== false ? getenv('ADMIN_USER') : 'admin';
        $adminPass = getenv('ADMIN_PASS') !== false ? getenv('ADMIN_PASS') : 'admin1234';

        if ($user === $adminUser && $pass === $adminPass) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: /admin/dashboard');
            exit();
        }

        $this->renderView('login', [
            'title' => 'Admin Login',
            'error' => 'Invalid username or password configuration.'
        ]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['admin_logged_in'] = false;
        session_destroy();
        header('Location: /admin/login');
        exit();
    }

    public function dashboard(): void
    {
        $leads = $this->getLeads();
        $services = $this->getServices();

        $this->renderView('dashboard', [
            'title' => 'Admin Dashboard',
            'leadsCount' => count($leads),
            'servicesCount' => count($services),
            'recentLeads' => array_slice(array_reverse($leads), 0, 5)
        ]);
    }

    public function leads(): void
    {
        $leads = $this->getLeads();
        $this->renderView('leads', [
            'title' => 'Manage Leads',
            'leads' => array_reverse($leads)
        ]);
    }

    public function deleteLead(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Inquiry ID is required.']);
            return;
        }

        $leads = $this->getLeads();
        $updatedLeads = array_filter($leads, fn($lead) => $lead['id'] !== $id);

        if (count($leads) === count($updatedLeads)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Inquiry not found.']);
            return;
        }

        // Re-index array
        $updatedLeads = array_values($updatedLeads);

        // Write back with lock
        $this->saveLeads($updatedLeads);

        echo json_encode(['success' => true, 'message' => 'Lead inquiry deleted successfully.']);
    }

    public function services(): void
    {
        $services = $this->getServices();
        $this->renderView('services', [
            'title' => 'Services CMS Control',
            'services' => $services
        ]);
    }

    public function updateService(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $focus = trim($_POST['focus'] ?? '');

        if (empty($id) || empty($name) || empty($description) || empty($focus)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Service ID, Name, Description, and Focus details are required.']);
            return;
        }

        $data = json_decode((string)file_get_contents($this->servicesFile), true) ?: [];
        $services = $data['services'] ?? [];
        $found = false;

        foreach ($services as &$service) {
            if ($service['id'] === $id) {
                $service['name'] = $name;
                $service['description'] = $description;
                $service['focus'] = $focus;
                $found = true;
                break;
            }
        }

        if (!$found) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Service ID not found in database catalog.']);
            return;
        }

        $data['services'] = $services;

        // Save back
        file_put_contents($this->servicesFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Service CMS info updated successfully.']);
    }

    public function settings(): void
    {
        $settings = [];
        if (file_exists($this->settingsFile)) {
            $settings = json_decode((string)file_get_contents($this->settingsFile), true) ?: [];
        }
        $this->renderView('settings', [
            'title' => 'Settings & SMTP CP',
            'settings' => $settings
        ]);
    }

    public function updateSettings(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $config = [
            'app_name' => trim($_POST['app_name'] ?? 'Great Endured Technology'),
            'app_url' => trim($_POST['app_url'] ?? 'https://greatentech.com'),
            'app_desc' => trim($_POST['app_desc'] ?? ''),
            'app_keywords' => trim($_POST['app_keywords'] ?? ''),
            'smtp_host' => trim($_POST['smtp_host'] ?? ''),
            'smtp_port' => trim($_POST['smtp_port'] ?? '587'),
            'smtp_user' => trim($_POST['smtp_user'] ?? ''),
            'smtp_pass' => trim($_POST['smtp_pass'] ?? ''),
            'smtp_from' => trim($_POST['smtp_from'] ?? 'contact@greatentech.com'),
            'smtp_from_name' => trim($_POST['smtp_from_name'] ?? 'Great Endured Technology')
        ];

        file_put_contents($this->settingsFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Site and SMTP configuration saved successfully!']);
    }

    public function portfolio(): void
    {
        $portfolio = [];
        if (file_exists($this->portfolioFile)) {
            $portfolio = json_decode((string)file_get_contents($this->portfolioFile), true) ?: [];
        }
        $this->renderView('portfolio', [
            'title' => 'Portfolio Manager',
            'portfolio' => $portfolio
        ]);
    }

    public function createPortfolioItem(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $theme = trim($_POST['theme'] ?? 'linear-gradient(135deg, #090d16 0%, #0a2540 100%)');
        $icon = trim($_POST['icon'] ?? 'globe');

        if (empty($title) || empty($category) || empty($description)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Title, Category, and Description are required.']);
            return;
        }

        $portfolio = [];
        if (file_exists($this->portfolioFile)) {
            $portfolio = json_decode((string)file_get_contents($this->portfolioFile), true) ?: [];
        }

        // Generate ID from slug
        $id = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));

        $newItem = [
            'id' => $id,
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'theme' => $theme,
            'icon' => $icon,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $portfolio[] = $newItem;

        file_put_contents($this->portfolioFile, json_encode($portfolio, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Portfolio item added successfully!']);
    }

    public function deletePortfolioItem(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID is required.']);
            return;
        }

        $portfolio = [];
        if (file_exists($this->portfolioFile)) {
            $portfolio = json_decode((string)file_get_contents($this->portfolioFile), true) ?: [];
        }

        $updated = array_filter($portfolio, fn($item) => $item['id'] !== $id);
        if (count($portfolio) === count($updated)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Portfolio item not found.']);
            return;
        }

        $updated = array_values($updated);
        file_put_contents($this->portfolioFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Portfolio item deleted successfully.']);
    }

    public function database(): void
    {
        $dbConnected = false;
        $dbError = '';
        $leadsTableCount = 0;
        $latency = 0;

        $start = microtime(true);
        $db = \Back\Core\DB::getConnection();
        $end = microtime(true);
        
        if ($db !== null) {
            $dbConnected = true;
            $latency = round(($end - $start) * 1000, 2); // latency in ms
            try {
                $stmt = $db->query("SELECT COUNT(*) FROM leads");
                $leadsTableCount = (int)$stmt->fetchColumn();
            } catch (\Exception $e) {
                $dbError = "Table error: " . $e->getMessage();
            }
        } else {
            $dbError = "Could not establish a connection to host server.";
        }

        $this->renderView('database', [
            'title' => 'Database Tools',
            'dbConnected' => $dbConnected,
            'dbError' => $dbError,
            'leadsTableCount' => $leadsTableCount,
            'latency' => $latency
        ]);
    }

    public function migrateDatabase(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $db = \Back\Core\DB::getConnection();
        if ($db === null) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database connection failed. Verify XAMPP/MySQL settings.']);
            return;
        }

        $schemaFile = __DIR__ . '/../../db/schema.sql';
        if (!file_exists($schemaFile)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Migration schema.sql file not found.']);
            return;
        }

        try {
            $sql = file_get_contents($schemaFile);
            $db->exec($sql);

            // Log action to setup.log.md (RBP idempotent check rule)
            $logFile = __DIR__ . '/../../setup.log.md';
            $logEntry = "\n## [" . date('Y-m-d H:i:s') . "] On-Demand Database Migration Run\n";
            $logEntry .= "- **Action**: Re-scaffolded leads table via admin CP.\n";
            $logEntry .= "- **Status**: SUCCESS\n";
            file_put_contents($logFile, $logEntry, FILE_APPEND);

            echo json_encode(['success' => true, 'message' => 'Database schema successfully migrated!']);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Migration execution failed: ' . $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------
    // Helper Data Accessors
    // -------------------------------------------------------------

    private function getLeads(): array
    {
        if (!file_exists($this->leadsFile)) {
            return [];
        }
        $content = file_get_contents($this->leadsFile);
        if (empty($content)) {
            return [];
        }
        return json_decode($content, true) ?: [];
    }

    private function saveLeads(array $leads): void
    {
        $fp = fopen($this->leadsFile, 'c');
        if ($fp) {
            if (flock($fp, LOCK_EX)) {
                ftruncate($fp, 0);
                fwrite($fp, json_encode($leads, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                fflush($fp);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
    }

    private function getServices(): array
    {
        if (!file_exists($this->servicesFile)) {
            return [];
        }
        $content = file_get_contents($this->servicesFile);
        if (empty($content)) {
            return [];
        }
        $data = json_decode($content, true) ?: [];
        return $data['services'] ?? [];
    }

    private function renderView(string $viewName, array $data = []): void
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $viewName . '.php';
        
        ob_start();
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<h1>View template '{$viewName}' not found.</h1>";
        }
        $content = ob_get_clean();

        // Render layout
        $layoutFile = __DIR__ . '/../views/layout.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
