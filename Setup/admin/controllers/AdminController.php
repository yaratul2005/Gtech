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

    public function __construct()
    {
        $this->leadsFile = __DIR__ . '/../../../Vault/cache/leads.json';
        $this->servicesFile = __DIR__ . '/../../../Vault/content/services.json';
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
