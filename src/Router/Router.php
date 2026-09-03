<?php

declare(strict_types=1);

namespace App\Router;

use Closure;

final class Router
{
    /** @var array<int, array{method:string,path:string,handler:callable}> */
    private array $routes = [];
    private readonly Closure $notFoundHandler;

    public function __construct(callable $notFoundHandler)
    {
        $this->notFoundHandler = Closure::fromCallable($notFoundHandler);
    }

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            $parameters = $this->match($route['path'], $path);
            if ($parameters === null || $route['method'] !== $method) {
                continue;
            }

            ($route['handler'])(...$parameters);
            return;
        }

        http_response_code(404);
        ($this->notFoundHandler)('Page introuvable.');
    }

    private function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
        ];
    }

    /** @return list<int|string>|null */
    private function match(string $route, string $path): ?array
    {
        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static fn(array $m): string => '(?P<' . $m[1] . '>[^/]+)',
            $route
        );

        if ($pattern === null || !preg_match('#^' . $pattern . '/?$#', $path, $matches)) {
            return null;
        }

        $parameters = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[] = ctype_digit($value) ? (int) $value : $value;
            }
        }

        return $parameters;
    }
}
