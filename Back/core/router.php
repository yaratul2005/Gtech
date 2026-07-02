<?php
/**
 * Great Endured Technology — Router Class
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

namespace Back\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|string|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable|string|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string from URI
        $position = strpos($uri, '?');
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }

        // Clean URI trail slashes
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        $callback = $this->routes[$method][$uri] ?? null;

        if ($callback === null) {
            $this->abort404();
            return;
        }

        if (is_callable($callback)) {
            call_user_func($callback);
            return;
        }

        if (is_array($callback)) {
            $controllerName = $callback[0];
            $action = $callback[1];

            $controller = new $controllerName();
            $controller->$action();
            return;
        }
    }

    private function abort404(): void
    {
        http_response_code(404);
        echo view('404', ['title' => 'Page Not Found']);
    }
}
