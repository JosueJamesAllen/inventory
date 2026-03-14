<?php

namespace App\Controllers;

use Core\Request;
use Core\Session;

abstract class BaseController
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // ── View rendering ────────────────────────────────────

    protected function view(string $template, array $data = [], string $layout = 'app'): void
    {
        // Make data available as variables
        extract($data);

        $flash    = Session::getFlash();
        $user     = Session::user();
        $csrf     = Session::csrfField();
        $appName  = (require BASE_PATH . '/config/app.php')['name'];

        // Capture the inner view
        ob_start();
        require VIEW_PATH . '/' . str_replace('.', '/', $template) . '.php';
        $content = ob_get_clean();

        // Wrap in layout
        require VIEW_PATH . '/layouts/' . $layout . '.php';
    }

    // ── Helpers ───────────────────────────────────────────

    protected function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    protected function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/dashboard';
        header('Location: ' . $ref);
        exit;
    }
}
