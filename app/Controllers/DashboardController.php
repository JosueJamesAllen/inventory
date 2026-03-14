<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Transaction;

class DashboardController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $device      = new Device();
        $transaction = new Transaction();
        $employee    = new Employee();

        $this->view('dashboard.index', [
            'total'         => $device->countAll(),
            'available'     => $device->countAvailable(),
            'borrowed'      => $device->countBorrowed(),
            'oos'           => $device->countOos(),
            'employeeCount' => $employee->total(),
            'todayCount'    => $transaction->countToday(),
            'activeBorrows' => $transaction->activeBorrows(),
        ]);
    }
}
