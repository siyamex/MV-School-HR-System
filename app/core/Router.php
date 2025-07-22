<?php
/**
 * Simple Router Class
 */
class Router {
    private $routes = [];
    
    public function add($route, $action) {
        $this->routes[$route] = $action;
    }
    
    public function dispatch($request) {
        // Debug output (remove in production)
        error_log("Router: Dispatching request: '{$request}'");
        error_log("Router: Available routes: " . implode(', ', array_keys($this->routes)));
        
        // Try exact match first
        if (isset($this->routes[$request])) {
            error_log("Router: Exact match found for '{$request}' -> {$this->routes[$request]}");
            return $this->callAction($this->routes[$request]);
        }
        
        // Try pattern matching for routes with parameters
        foreach ($this->routes as $route => $action) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $request, $matches)) {
                error_log("Router: Pattern match found for '{$request}' with route '{$route}' -> {$action}");
                array_shift($matches); // Remove full match
                return $this->callAction($action, $matches);
            }
        }
        
        // Route not found
        error_log("Router: No route found for '{$request}'");
        throw new Exception('Route not found: ' . $request);
    }
    
    private function convertRouteToRegex($route) {
        // Convert {id} to capture group
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
    
    private function callAction($action, $params = []) {
        list($controllerName, $methodName) = explode('@', $action);
        
        $controllerFile = APP_PATH . "/controllers/{$controllerName}.php";
        
        error_log("Router: Calling {$controllerName}@{$methodName}");
        error_log("Router: Controller file: {$controllerFile}");
        
        if (!file_exists($controllerFile)) {
            error_log("Router: Controller file not found: {$controllerFile}");
            throw new Exception("Controller not found: {$controllerName}");
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            error_log("Router: Controller class not found: {$controllerName}");
            throw new Exception("Controller class not found: {$controllerName}");
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            error_log("Router: Method {$methodName} not found in {$controllerName}");
            throw new Exception("Method not found: {$controllerName}@{$methodName}");
        }
        
        error_log("Router: About to call {$controllerName}@{$methodName}");
        $result = call_user_func_array([$controller, $methodName], $params);
        error_log("Router: Successfully called {$controllerName}@{$methodName}");
        
        return $result;
    }
}
