<?php

namespace App\Middleware;

use Core\Response;
use Core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Session::check()) {
            Response::redirect('/login');
        }
    }
}
