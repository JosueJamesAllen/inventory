<?php

namespace Core;

class Router
{
    private array $routes = [];

    // ── Registration ───────────────────────────────────────

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    // ── Dispatch ───────────────────────────────────────────

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri    = $request->uri();

        $handler = $this->routes[$method][$uri] ?? null;

        if ($handler === null) {
            http_response_code(404);
            die('<h2 style="font-family:sans-serif;padding:2rem">404 — Page not found.</h2>');
        }

        [$controllerClass, $action] = explode('@', $handler);

        $fullClass = 'App\\Controllers\\' . $controllerClass;

        if (!class_exists($fullClass)) {
            die("Controller not found: $fullClass");
        }

        $controller = new $fullClass($request);

        if (!method_exists($controller, $action)) {
            die("Method not found: $fullClass::$action");
        }

        $controller->$action();
    }
}
