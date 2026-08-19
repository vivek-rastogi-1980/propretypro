<?php
namespace App\Controllers;

use App\Models\Setting;

/**
 * Abstract Base Controller implementing HTML View templates rendering and AJAX JSON dispatching
 */
abstract class Controller {
    
    /**
     * Render MVC view template wrapped in layouts
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'frontend'): void {
        // Automatically inject global settings and authentication state
        $data['globalSettings'] = Setting::getAll();
        $data['isAdminLoggedIn'] = \App\Helpers\AuthHelper::isLoggedIn();
        
        // Extract variables to local scope
        extract($data);

        // Resolve absolute view directory path
        $viewFile = ROOT_DIR . 'views/' . ltrim($viewPath, '/') . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View template file not found: {$viewFile}");
        }

        // Render appropriate layouts
        if ($layout === 'frontend') {
            require_once ROOT_DIR . 'views/layouts/header.php';
            require_once $viewFile;
            require_once ROOT_DIR . 'views/layouts/footer.php';
        } elseif ($layout === 'admin') {
            require_once ROOT_DIR . 'views/layouts/admin_header.php';
            require_once $viewFile;
            require_once ROOT_DIR . 'views/layouts/admin_footer.php';
        } else {
            // Raw view rendering (useful for login/forgot-password pages or isolated forms)
            require_once $viewFile;
        }
    }

    /**
     * Standardized AJAX Json Response
     */
    protected function json(array $data, int $statusCode = 200): void {
        // Clear buffers
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
