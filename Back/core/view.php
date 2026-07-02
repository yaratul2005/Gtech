<?php
/**
 * Great Endured Technology — View Renderer
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

namespace Back\Core;

class View
{
    public function render(string $page, array $data = []): string
    {
        // Extract variables to page scope
        extract($data);

        // Buffer outputs
        ob_start();

        // 1. Locate the page template
        $pagePath = __DIR__ . '/../../Front/dyn/pages/' . $page . '.php';

        if (!file_exists($pagePath)) {
            // Fallback for 404 page if not found
            if ($page === '404') {
                return '<h1>404 Not Found</h1><p>Page could not be found.</p>';
            }
            $pagePath = __DIR__ . '/../../Front/dyn/pages/404.php';
        }

        // 2. Load header component (unless it is an API request or partial response)
        $isPartial = $data['is_partial'] ?? false;

        if (!$isPartial) {
            require_once __DIR__ . '/../../Front/dyn/components/header.php';
        }

        // 3. Load actual page template content
        require $pagePath;

        // 4. Load footer component
        if (!$isPartial) {
            require_once __DIR__ . '/../../Front/dyn/components/footer.php';
        }

        return ob_get_clean();
    }
}
