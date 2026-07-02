<?php
/**
 * Great Endured Technology — Entry Point
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Support local PHP built-in server router for static files
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (file_exists(__DIR__ . $path) && is_file(__DIR__ . $path)) {
        return false;
    }
}

// Boot the application configuration and services
require_once __DIR__ . '/Back/core/bootstrap.php';

// Instantiate and execute the router
use Back\Core\Router;

$router = new Router();

// Define routes
$router->get('/', function() {
    return view('home', ['title' => 'Home']);
});

$router->get('/about', function() {
    return view('about', ['title' => 'About Us']);
});

$router->get('/services', function() {
    return view('services', ['title' => 'Our Services']);
});

$router->get('/portfolio', function() {
    return view('portfolio', ['title' => 'Portfolio & Case Studies']);
});

$router->get('/contact', function() {
    return view('contact', ['title' => 'Contact Us']);
});

$router->get('/team', function() {
    return view('team', ['title' => 'Our Team']);
});

// Admin Panel routes (Setup & Governance layer mapped to /admin)
$router->get('/admin', function() {
    \Setup\Auth\AuthMiddleware::redirectToDashboardOrLogin();
});
$router->get('/admin/login', [ \Setup\Admin\Controllers\AdminController::class, 'showLoginForm' ]);
$router->post('/admin/login', [ \Setup\Admin\Controllers\AdminController::class, 'login' ]);
$router->get('/admin/logout', [ \Setup\Admin\Controllers\AdminController::class, 'logout' ]);

$router->get('/admin/dashboard', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->dashboard();
});
$router->get('/admin/leads', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->leads();
});
$router->post('/admin/leads/delete', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->deleteLead();
});
$router->get('/admin/services', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->services();
});
$router->post('/admin/services/update', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->updateService();
});
$router->get('/admin/settings', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->settings();
});
$router->post('/admin/settings/update', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->updateSettings();
});
$router->get('/admin/portfolio', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->portfolio();
});
$router->post('/admin/portfolio/create', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->createPortfolioItem();
});
$router->post('/admin/portfolio/delete', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->deletePortfolioItem();
});
$router->get('/admin/database', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->database();
});
$router->post('/admin/database/migrate', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->migrateDatabase();
});
$router->get('/admin/pages', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->pages();
});
$router->post('/admin/pages/create', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->createPage();
});
$router->post('/admin/pages/delete', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->deletePage();
});
$router->post('/admin/pages/update', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->updatePage();
});
$router->get('/admin/posts', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->posts();
});
$router->post('/admin/posts/create', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->createPost();
});
$router->post('/admin/posts/delete', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->deletePost();
});
$router->post('/admin/posts/update', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->updatePost();
});

$router->get('/admin/team', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->team();
});
$router->post('/admin/team/create', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->createTeamMember();
});
$router->post('/admin/team/delete', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->deleteTeamMember();
});
$router->post('/admin/team/update', function() {
    \Setup\Auth\AuthMiddleware::verify();
    $c = new \Setup\Admin\Controllers\AdminController();
    $c->updateTeamMember();
});

// Dynamic frontend routes
$router->get('/blog', function() {
    return view('blog', ['title' => 'Blog Updates']);
});
$router->get('/blog/*', [ \Setup\Admin\Controllers\AdminController::class, 'showBlogPost' ]);
$router->get('/p/*', [ \Setup\Admin\Controllers\AdminController::class, 'showDynamicPage' ]);

// Cron SEO update route
$router->get('/cron/seo', function() {
    require_once __DIR__ . '/Setup/scripts/seo_generator.php';
    if (function_exists('generateSeoFiles')) {
        generateSeoFiles();
    }
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Sitemap and Robots files auto-regenerated successfully.']);
    exit;
});

// Internal API routes
$router->post('/api/contact', function() {
    require_once __DIR__ . '/Tunnel/internal/api.php';
});

// Resolve the route
$router->resolve();
