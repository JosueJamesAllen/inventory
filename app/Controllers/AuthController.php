<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Middleware\AuthMiddleware;
use Core\Response;
use Core\Session;

class AuthController extends BaseController
{
    public function loginForm(): void
    {
        if (Session::check()) {
            Response::redirect('/dashboard');
        }
        $this->view('auth.login', [], 'auth');
    }

    public function login(): void
    {
        $this->request->verifyCsrf();

        $qrCode = $this->request->post('qr_code');

        if (!$qrCode) {
            Session::flash('error', 'Please enter your QR code or Employee ID.');
            Response::redirect('/login');
        }

        $employee = (new Employee())->findByQr($qrCode);

        if (!$employee) {
            Session::flash('error', "No employee found with QR code: <strong>{$this->e($qrCode)}</strong>. Try EMP-001 to EMP-008.");
            Response::redirect('/login');
        }

        Session::login($employee);
        Session::flash('success', "Welcome back, {$employee['name']}!");
        Response::redirect('/dashboard');
    }

    public function logout(): void
    {
        $this->request->verifyCsrf();
        Session::logout();
        Response::redirect('/login');
    }
}
