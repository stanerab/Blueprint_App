<?php
namespace App\Core;

class Router
{
    private $routes = [];
    
    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch($url)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = '/' . trim($url, '/');
        
        foreach ($this->routes as $route) {
            // Check if this route matches with parameters
            $params = $this->matchRoute($route['path'], $path);
            
            if ($route['method'] === $method && $params !== false) {
                return $this->callHandler($route['handler'], $params);
            }
        }
        
        // 404 - Route not found
        $this->handle404();
    }
    
    private function matchRoute($routePath, $requestPath)
    {
        // Convert route path to regex pattern
        $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        if (preg_match($pattern, $requestPath, $matches)) {
            array_shift($matches); // Remove the full match
            return $matches; // Return captured parameters
        }
        
        return false;
    }
    
    private function callHandler($handler, $params = [])
    {
        if ($handler instanceof \Closure) {
            return call_user_func_array($handler, $params);
        }
        
        list($controllerName, $method) = explode('@', $handler);
        $controllerClass = "App\\Controllers\\{$controllerName}";
        
        if (!class_exists($controllerClass)) {
            die("Controller {$controllerClass} not found");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            die("Method {$method} not found in controller {$controllerClass}");
        }
        
        // Call the method with parameters
        return call_user_func_array([$controller, $method], $params);
    }
    
    private function handle404()
    {
        header("HTTP/1.0 404 Not Found");
        
        $viewPath = APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . '404.view.php';
        
        if (file_exists($viewPath)) {
            require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'header.php';
            require $viewPath;
            require APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'footer.php';
        } else {
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The page you requested could not be found.</p>";
        }
        exit;
    }
}