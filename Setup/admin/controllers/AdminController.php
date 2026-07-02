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
    private string $pagesFile;
    private string $postsFile;
    private string $teamFile;

    public function __construct()
    {
        $this->leadsFile = __DIR__ . '/../../../Vault/cache/leads.json';
        $this->servicesFile = __DIR__ . '/../../../Vault/content/services.json';
        $this->portfolioFile = __DIR__ . '/../../../Vault/content/portfolio.json';
        $this->settingsFile = __DIR__ . '/../../../Vault/content/settings.json';
        $this->pagesFile = __DIR__ . '/../../../Vault/content/pages.json';
        $this->postsFile = __DIR__ . '/../../../Vault/content/posts.json';
        $this->teamFile = __DIR__ . '/../../../Vault/content/team.json';
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

        // Load existing settings
        $existing = [];
        if (file_exists($this->settingsFile)) {
            $existing = json_decode((string)file_get_contents($this->settingsFile), true) ?: [];
        }

        // Process SMTP pass (encrypt if changed)
        $smtpPass = trim($_POST['smtp_pass'] ?? '');
        if ($smtpPass === '') {
            $smtpPass = $existing['smtp_pass'] ?? '';
        } else {
            $smtpPass = \ConfigEncryptor::encrypt($smtpPass);
        }

        // Process DB pass (encrypt if changed)
        $dbPass = trim($_POST['db_pass'] ?? '');
        if ($dbPass === '') {
            $dbPass = $existing['db_pass'] ?? '';
        } else {
            $dbPass = \ConfigEncryptor::encrypt($dbPass);
        }

        // Process Admin pass (encrypt if changed)
        $adminPass = trim($_POST['admin_pass'] ?? '');
        if ($adminPass === '') {
            $adminPass = $existing['admin_pass'] ?? '';
        } else {
            $adminPass = \ConfigEncryptor::encrypt($adminPass);
        }

        $config = [
            'app_name' => trim($_POST['app_name'] ?? 'Great Endured Technology'),
            'app_url' => trim($_POST['app_url'] ?? 'https://greatentech.com'),
            'app_desc' => trim($_POST['app_desc'] ?? ''),
            'app_keywords' => trim($_POST['app_keywords'] ?? ''),
            'smtp_host' => trim($_POST['smtp_host'] ?? ''),
            'smtp_port' => trim($_POST['smtp_port'] ?? '587'),
            'smtp_user' => trim($_POST['smtp_user'] ?? ''),
            'smtp_pass' => $smtpPass,
            'smtp_from' => trim($_POST['smtp_from'] ?? 'contact@greatentech.com'),
            'smtp_from_name' => trim($_POST['smtp_from_name'] ?? 'Great Endured Technology'),
            'db_host' => trim($_POST['db_host'] ?? 'localhost'),
            'db_port' => trim($_POST['db_port'] ?? '3306'),
            'db_name' => trim($_POST['db_name'] ?? 'great_endured_db'),
            'db_user' => trim($_POST['db_user'] ?? 'root'),
            'db_pass' => $dbPass,
            'admin_user' => trim($_POST['admin_user'] ?? 'admin'),
            'admin_pass' => $adminPass,
            'header_code' => $_POST['header_code'] ?? '',
            'footer_code' => $_POST['footer_code'] ?? ''
        ];

        file_put_contents($this->settingsFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Site, SMTP, Database, and Admin credentials updated successfully!']);
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

    public function pages(): void
    {
        $pages = [];
        if (file_exists($this->pagesFile)) {
            $pages = json_decode((string)file_get_contents($this->pagesFile), true) ?: [];
        }
        $this->renderView('pages', [
            'title' => 'Page CMS Manager',
            'pages' => $pages
        ]);
    }

    public function createPage(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $meta_desc = trim($_POST['meta_desc'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($slug) || empty($content)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Title, Slug, and Content are required fields.']);
            return;
        }

        // Clean slug: lowercase, replace non-alphanumeric with hyphen
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug));

        $pages = [];
        if (file_exists($this->pagesFile)) {
            $pages = json_decode((string)file_get_contents($this->pagesFile), true) ?: [];
        }

        // Check if slug exists
        foreach ($pages as $p) {
            if ($p['slug'] === $slug) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'A page with this URL slug already exists.']);
                return;
            }
        }

        $newPage = [
            'slug' => $slug,
            'title' => $title,
            'meta_desc' => $meta_desc,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $pages[] = $newPage;

        file_put_contents($this->pagesFile, json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Page created successfully!']);
    }

    public function deletePage(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Page Slug is required.']);
            return;
        }

        $pages = [];
        if (file_exists($this->pagesFile)) {
            $pages = json_decode((string)file_get_contents($this->pagesFile), true) ?: [];
        }

        $updated = array_filter($pages, fn($p) => $p['slug'] !== $slug);
        if (count($pages) === count($updated)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Page not found.']);
            return;
        }

        $updated = array_values($updated);
        if (file_put_contents($this->pagesFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write data changes. Check file permissions on pages.json.']);
            return;
        }
        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Page deleted successfully.']);
    }

    public function posts(): void
    {
        $posts = [];
        if (file_exists($this->postsFile)) {
            $posts = json_decode((string)file_get_contents($this->postsFile), true) ?: [];
        }
        $this->renderView('posts', [
            'title' => 'Blog CMS Manager',
            'posts' => $posts
        ]);
    }

    public function createPost(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $meta_desc = trim($_POST['meta_desc'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($title) || empty($slug) || empty($content)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Title, Slug, and Content are required fields.']);
            return;
        }

        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug));

        $posts = [];
        if (file_exists($this->postsFile)) {
            $posts = json_decode((string)file_get_contents($this->postsFile), true) ?: [];
        }

        foreach ($posts as $p) {
            if ($p['slug'] === $slug) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'A blog post with this URL slug already exists.']);
                return;
            }
        }

        $newPost = [
            'slug' => $slug,
            'title' => $title,
            'meta_desc' => $meta_desc,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $posts[] = $newPost;

        file_put_contents($this->postsFile, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Blog post published successfully!']);
    }

    public function deletePost(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $slug = trim($_POST['slug'] ?? '');
        if (empty($slug)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Post Slug is required.']);
            return;
        }

        $posts = [];
        if (file_exists($this->postsFile)) {
            $posts = json_decode((string)file_get_contents($this->postsFile), true) ?: [];
        }

        $updated = array_filter($posts, fn($p) => $p['slug'] !== $slug);
        if (count($posts) === count($updated)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Blog post not found.']);
            return;
        }

        $updated = array_values($updated);
        if (file_put_contents($this->postsFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write data changes. Check file permissions on posts.json.']);
            return;
        }
        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Blog post deleted successfully.']);
    }

    public function showBlogPost(string $slug): void
    {
        $posts = [];
        if (file_exists($this->postsFile)) {
            $posts = json_decode((string)file_get_contents($this->postsFile), true) ?: [];
        }

        $post = null;
        foreach ($posts as $p) {
            if ($p['slug'] === $slug) {
                $post = $p;
                break;
            }
        }

        if ($post === null) {
            http_response_code(404);
            echo view('404', ['title' => 'Post Not Found']);
            return;
        }

        echo view('blog_post', [
            'title' => $post['title'],
            'post' => $post
        ]);
    }

    public function showDynamicPage(string $slug): void
    {
        $pages = [];
        if (file_exists($this->pagesFile)) {
            $pages = json_decode((string)file_get_contents($this->pagesFile), true) ?: [];
        }

        $page = null;
        foreach ($pages as $p) {
            if ($p['slug'] === $slug) {
                $page = $p;
                break;
            }
        }

        if ($page === null) {
            http_response_code(404);
            echo view('404', ['title' => 'Page Not Found']);
            return;
        }

        echo view('dynamic_page', [
            'title' => $page['title'],
            'page' => $page
        ]);
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

    public function team(): void
    {
        $team = [];
        if (file_exists($this->teamFile)) {
            $team = json_decode((string)file_get_contents($this->teamFile), true) ?: [];
        }
        $this->renderView('team', [
            'title' => 'Team Manager',
            'team' => $team
        ]);
    }

    public function createTeamMember(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $skills = trim($_POST['skills'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $theme = trim($_POST['theme'] ?? 'linear-gradient(135deg, #090d16 0%, #0a2540 100%)');

        if (empty($name) || empty($role) || empty($bio)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name, Role, and Biography are required.']);
            return;
        }

        $imagePath = '/Front/static/img/logo.png'; // default placeholder
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($fileExtension, $allowedExtensions, true)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/../../../Vault/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $destPath = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagePath = '/Vault/uploads/' . $newFileName;
                }
            }
        }

        $team = [];
        if (file_exists($this->teamFile)) {
            $team = json_decode((string)file_get_contents($this->teamFile), true) ?: [];
        }

        $id = uniqid();
        $newItem = [
            'id' => $id,
            'name' => $name,
            'role' => $role,
            'bio' => $bio,
            'skills' => $skills,
            'image' => $imagePath,
            'phone' => $phone,
            'theme' => $theme
        ];

        $team[] = $newItem;

        file_put_contents($this->teamFile, json_encode($team, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Team member added successfully!']);
    }

    public function deleteTeamMember(): void
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
            echo json_encode(['success' => false, 'message' => 'Team member ID is required.']);
            return;
        }

        $team = [];
        if (file_exists($this->teamFile)) {
            $team = json_decode((string)file_get_contents($this->teamFile), true) ?: [];
        }

        $updated = array_filter($team, fn($item) => $item['id'] !== $id);

        if (count($team) === count($updated)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Team member not found.']);
            return;
        }

        file_put_contents($this->teamFile, json_encode(array_values($updated), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        echo json_encode(['success' => true, 'message' => 'Team member deleted successfully!']);
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

    public function updatePage(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $original_slug = trim($_POST['original_slug'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $meta_desc = trim($_POST['meta_desc'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($original_slug) || empty($title) || empty($slug) || empty($content)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        $pages = [];
        if (file_exists($this->pagesFile)) {
            $pages = json_decode((string)file_get_contents($this->pagesFile), true) ?: [];
        }

        $foundIndex = -1;
        foreach ($pages as $index => $p) {
            if ($p['slug'] === $original_slug) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === -1) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Original page not found.']);
            return;
        }

        if ($slug !== $original_slug) {
            foreach ($pages as $p) {
                if ($p['slug'] === $slug) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'A page with the new URL slug already exists.']);
                    return;
                }
            }
        }

        $pages[$foundIndex]['title'] = $title;
        $pages[$foundIndex]['slug'] = $slug;
        $pages[$foundIndex]['meta_desc'] = $meta_desc;
        $pages[$foundIndex]['content'] = $content;

        if (file_put_contents($this->pagesFile, json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write page changes. Check file permissions.']);
            return;
        }

        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Page updated successfully!']);
    }

    public function updatePost(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $original_slug = trim($_POST['original_slug'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $meta_desc = trim($_POST['meta_desc'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (empty($original_slug) || empty($title) || empty($slug) || empty($content)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        $posts = [];
        if (file_exists($this->postsFile)) {
            $posts = json_decode((string)file_get_contents($this->postsFile), true) ?: [];
        }

        $foundIndex = -1;
        foreach ($posts as $index => $p) {
            if ($p['slug'] === $original_slug) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === -1) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Original post not found.']);
            return;
        }

        if ($slug !== $original_slug) {
            foreach ($posts as $p) {
                if ($p['slug'] === $slug) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'A blog post with the new URL slug already exists.']);
                    return;
                }
            }
        }

        $posts[$foundIndex]['title'] = $title;
        $posts[$foundIndex]['slug'] = $slug;
        $posts[$foundIndex]['meta_desc'] = $meta_desc;
        $posts[$foundIndex]['content'] = $content;

        if (file_put_contents($this->postsFile, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write blog changes. Check file permissions.']);
            return;
        }

        $this->triggerSeoUpdate();

        echo json_encode(['success' => true, 'message' => 'Blog post updated successfully!']);
    }

    public function updateTeamMember(): void
    {
        header('Content-Type: application/json');

        if (!CSRF::verify()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'CSRF verification failed.']);
            return;
        }

        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $skills = trim($_POST['skills'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $theme = trim($_POST['theme'] ?? '');

        if (empty($id) || empty($name) || empty($role) || empty($bio)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID, Name, Role, and Bio are required.']);
            return;
        }

        $team = [];
        if (file_exists($this->teamFile)) {
            $team = json_decode((string)file_get_contents($this->teamFile), true) ?: [];
        }

        $foundIndex = -1;
        foreach ($team as $index => $m) {
            if ($m['id'] === $id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === -1) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Team member not found.']);
            return;
        }

        $imagePath = $team[$foundIndex]['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (in_array($fileExtension, $allowedExtensions, true)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = __DIR__ . '/../../../Vault/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $destPath = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagePath = '/Vault/uploads/' . $newFileName;
                }
            }
        }

        $team[$foundIndex]['name'] = $name;
        $team[$foundIndex]['role'] = $role;
        $team[$foundIndex]['bio'] = $bio;
        $team[$foundIndex]['skills'] = $skills;
        $team[$foundIndex]['phone'] = $phone;
        $team[$foundIndex]['theme'] = $theme;
        $team[$foundIndex]['image'] = $imagePath;

        if (file_put_contents($this->teamFile, json_encode($team, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to write team changes. Check file permissions.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Team member updated successfully!']);
    }

    private function triggerSeoUpdate(): void
    {
        $seoScript = dirname(__DIR__, 2) . '/scripts/seo_generator.php';
        if (file_exists($seoScript)) {
            require_once $seoScript;
            if (function_exists('generateSeoFiles')) {
                generateSeoFiles();
            }
        }
    }
}
