<?php

namespace App\Controllers;

use App\Middleware\RoleMiddleware;
use App\Models\Employee;
use Core\Response;
use Core\Session;

class EmployeeController extends BaseController
{
    public function index(): void
    {
        RoleMiddleware::require('admin', 'it_staff');

        $this->view('employees.index', [
            'employees' => (new Employee())->all(),
        ]);
    }

    public function store(): void
    {
        RoleMiddleware::require('admin');
        $this->request->verifyCsrf();

        $qrCode = $this->request->post('qr_code');

        if ((new Employee())->findByQr($qrCode)) {
            Session::flash('error', "QR code <strong>{$this->e($qrCode)}</strong> is already in use.");
            Response::redirect('/employees');
        }

        (new Employee())->create([
            'name'       => $this->request->post('name'),
            'department' => $this->request->post('department'),
            'qr_code'    => $qrCode,
            'role'       => $this->request->post('role'),
        ]);

        Session::flash('success', 'Employee added successfully.');
        Response::redirect('/employees');
    }

    public function update(): void
    {
        RoleMiddleware::require('admin');
        $this->request->verifyCsrf();

        $id = (int)$this->request->post('employee_id');

        (new Employee())->update($id, [
            'name'       => $this->request->post('name'),
            'department' => $this->request->post('department'),
            'role'       => $this->request->post('role'),
        ]);

        Session::flash('success', 'Employee updated successfully.');
        Response::redirect('/employees');
    }
}
