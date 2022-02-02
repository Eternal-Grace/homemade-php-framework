<?php

class Routes
{
    protected array $path_split;
    protected array $queries;
    protected array $routes;
    protected string $action;
    protected array $params;

    public string|null $redirect = null;
    public string|null $error = null;
    public string $method;
    public string $view = '';
    public array $data = [];

    public function __construct()
    {
        // header('HTTP/1.1 404 Not Found');
        // die('404 - Page not found');

        $this->method = $_SERVER['REQUEST_METHOD'];
        if (!in_array($this->method, ['GET', 'POST'])) {
            // BLA-BLA NO Time to complete this code (Not important for now)
        }
        if (isset($_SERVER['PATH_INFO'])) {
            $this->path_split = explode('/', ltrim($_SERVER['PATH_INFO']));
            $this->queries = explode('&', ltrim($_SERVER['QUERY_STRING']));
        } else {
            $this->path_split = ['/'];
            $this->queries = [];
        }
        $this->routes = include(__DIR__.'/config.php');
        if ($this->isValidRoute()) {
            $this->execute();
        }
    }

    private function validateParams(array $route): bool
    {
        $isValid = true;
        if (strtolower($this->method) !== strtolower($route['method'])) {
            $isValid = false;
        }
        if ($route['uri'] == '/') {
            $routeUri = ['/'];
        } else {
            $routeUri = explode('/', ltrim($route['uri']));
        }
        if (count($this->path_split) !== count($routeUri)) {
            $isValid = false;
        } else {
            $this->params = [];
            foreach($this->path_split as $x => $px) {
                foreach($routeUri as $y => $py) {
                    $preg_matched = preg_match('/^\{[a-z0-9]+\}$/i', $py) === 1;
                    if ($x === $y) {
                        if ($px === $py || $preg_matched) {
                            if ($preg_matched) {
                                $this->params[substr($py, 1, -1)] = $px;
                            }
                        } else {
                            $isValid = false;
                        }
                    }
                }
            }
        }
        return $isValid;
    }

    public function execute(): void
    {
        call_user_func([$this, $this->action], $this->params);
    }

    public function isValidRoute(): bool
    {
        $isValid = false;
        foreach ($this->routes as $route) {
            if ($isValid) {
                continue;
            }
            if (array_diff(['method','uri','controller'], array_keys($route))) {
                $isValid = false;
            }
            if ($this->validateParams($route)) {
                $this->action = $route['controller'];
                $isValid = true;
            }
        }
        return $isValid;
    }

    public function __sleep(): array
    {
        return [
            'view',
            'data',
            'method',
            'redirect',
            'error',
        ];
    }
}
