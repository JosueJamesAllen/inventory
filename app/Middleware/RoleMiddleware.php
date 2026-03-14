<?php

namespace App\Middleware;

use Core\Response;
use Core\Session;

class RoleMiddleware
{
    public static function require(string ...$roles): void
    {
        AuthMiddleware::handle();

        if (!Session::hasRole(...$roles)) {
            Session::flash('error', 'You do not have permission to access that page.');
            Response::redirect('/dashboard');
        }
    }
}
