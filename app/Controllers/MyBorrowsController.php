<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Transaction;
use Core\Session;

class MyBorrowsController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $user = Session::user();
        $rows = (new Transaction())->borrowsByEmployee((int)$user['id']);

        $active  = array_values(array_filter($rows, fn($r) => $r['returned_at'] === null));
        $history = array_values(array_filter($rows, fn($r) => $r['returned_at'] !== null));

        $this->view('my_borrows.index', [
            'active'  => $active,
            'history' => $history,
        ]);
    }
}
