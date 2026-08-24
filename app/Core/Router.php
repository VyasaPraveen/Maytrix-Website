<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Minimal regex router supporting {param} placeholders and GET/POST verbs.
 */
final class Router
{
    /** @var array<int,array{method:string,pattern:string,handler:callable|array}> */
    private array $routes = [];
    private $notFound = null;

    public function get(string $pattern, $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function any(string $pattern, $handler): void
    {
        $this->add('GET', $pattern, $handler);
        $this->add('POST', $pattern, $handler);
    }

    public function notFound(callable $handler): void
    {
        $this->notFound = $handler;
    }

    private function add(string $method, string $pattern, $handler): void
    {
        $this->routes[] = ['method' => $method, 'pattern' => $pattern, 'handler' => $handler];
    }

    public function dispatch(Request $request)
    {
        $path = $request->path();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $regex = $this->compile($route['pattern']);
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->invoke($route['handler'], array_values($params), $request);
            }
        }

        http_response_code(404);
        if ($this->notFound) {
            return ($this->notFound)($request);
        }
        echo '404 Not Found';
        return null;
    }

    private function compile(string $pattern): string
    {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    private function invoke($handler, array $params, Request $request)
    {
        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();
            return $controller->$action($request, ...$params);
        }
        if (is_callable($handler)) {
            return $handler($request, ...$params);
        }
        throw new \RuntimeException('Invalid route handler.');
    }
}
