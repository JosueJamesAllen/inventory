<?php

namespace Core;

class Request
{
    // ── URI / Method ───────────────────────────────────────

    public function uri(): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $uri  = substr($_SERVER['REQUEST_URI'], strlen($base));
        $uri  = strtok($uri, '?');   // strip query string
        return '/' . trim($uri, '/') ?: '/';
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    // ── Input ─────────────────────────────────────────────

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        $val = $_POST[$key] ?? $default;
        return is_string($val) ? trim($val) : $val;
    }

    public function all(): array
    {
        return $_POST;
    }

    // ── CSRF ──────────────────────────────────────────────

    public function verifyCsrf(): void
    {
        $token = $this->post('_csrf');
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request. Please try again.');
            Response::redirect('/');
        }
    }
}
