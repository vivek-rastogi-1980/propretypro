<?php
namespace App\Routes;

/**
 * Custom Core MVC Router
 */
class Router {
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $route, string $handler): void {
        $this->addRoute('GET', $route, $handler);
    }

    /**
     * Register a POST route
     */
    public function post(string $route, string $handler): void {
        $this->addRoute('POST', $route, $handler);
    }

    /**
     * Add route to collection
     */
    private function addRoute(string $method, string $route, string $handler): void {
        $route = trim($route, '/');
        // Convert placeholders like {slug} or {id} into regex patterns
        // {slug} or any alphanumeric/dash name -> ([^/]+)
        // {id} -> (\d+)
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Resolve the current request
     */
    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

        // Strip query parameters
        $requestUri = explode('?', $requestUri)[0];

        // Strip base path subdirectory (e.g. /real-estate-version-one-antigravity/)
        $basePath = BASE_PATH;
        if (str_starts_with($requestUri, $basePath)) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        $requestUri = trim($requestUri, '/');

        // Look for matching route
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['pattern'], $requestUri, $matches)) {
                array_shift($matches); // Remove first match which is the full URI
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        // Route not found - show 404 page
        $this->show404();
    }

    /**
     * Execute target controller and method
     */
    private function executeHandler(string $handler, array $params): void {
        list($controllerClass, $method) = explode('@', $handler);
        $fullControllerClass = "App\\Controllers\\" . $controllerClass;

        if (class_exists($fullControllerClass)) {
            $controller = new $fullControllerClass();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                throw new \Exception("Method {$method} not found in controller {$controllerClass}.");
            }
        } else {
            throw new \Exception("Controller class {$fullControllerClass} not found.");
        }
    }

    /**
     * Render a standard, styled 404 page
     */
    private function show404(): void {
        header("HTTP/1.0 404 Not Found");
        // We'll require base style for nice looking error
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Page Not Found | Vigtez Reality Estates</title>
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background-color: #F8FAFC;
                    font-family: 'Outfit', sans-serif;
                    color: #0F172A;
                    height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .error-card {
                    background: rgba(255, 255, 255, 0.7);
                    backdrop-filter: blur(12px);
                    border: 1px solid rgba(255, 255, 255, 0.4);
                    border-radius: 24px;
                    padding: 48px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                    text-align: center;
                    max-width: 500px;
                    width: 100%;
                }
                .error-code {
                    font-size: 72px;
                    font-weight: 700;
                    color: #2563EB;
                    background: linear-gradient(135deg, #2563EB, #0F172A);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
                .btn-home {
                    background: linear-gradient(135deg, #2563EB, #1D4ED8);
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 12px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                .btn-home:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(37,99,235,0.4);
                    color: white;
                }
            </style>
        </head>
        <body>
            <div class="error-card">
                <div class="error-code">404</div>
                <h2 class="fw-bold mb-3">Property Not Found</h2>
                <p class="text-muted mb-4">The listing or page you are looking for has been moved, archived, or does not exist.</p>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-home">Go Back Home</a>
            </div>
        </body>
        </html>
        <?php
    }
}
