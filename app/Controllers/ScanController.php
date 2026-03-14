<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Device;
use App\Models\Employee;
use App\Models\Transaction;
use Core\Response;
use Core\Session;

class ScanController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $this->view('scan.index', [
            'employees'     => (new Employee())->all(),
            'availDevices'  => (new Device())->available(),
            'borrowedDevices' => (new Device())->borrowed(),
        ]);
    }

    public function borrow(): void
    {
        AuthMiddleware::handle();
        $this->request->verifyCsrf();

        $empQr  = $this->request->post('emp_qr');
        $devQr  = $this->request->post('dev_qr');

        $employeeModel = new Employee();
        $deviceModel   = new Device();

        $borrower = $employeeModel->findByQr($empQr);
        $device   = $deviceModel->findByQr($devQr);

        if (!$borrower) {
            Session::flash('error', "Employee QR not found: <strong>{$this->e($empQr)}</strong>");
            Response::redirect('/scan');
        }

        if (!$device) {
            Session::flash('error', "Device QR not found: <strong>{$this->e($devQr)}</strong>");
            Response::redirect('/scan');
        }

        if ($device['status'] !== 'available') {
            Session::flash('error', "Device <strong>{$this->e($device['name'])}</strong> is not available (status: {$device['status']}).");
            Response::redirect('/scan');
        }

        $currentUser  = Session::user();
        $facilitatedBy = ($currentUser['id'] !== $borrower['id']) ? (int)$currentUser['id'] : null;

        (new Transaction())->borrow((int)$device['id'], (int)$borrower['id'], $facilitatedBy);
        $deviceModel->setStatus((int)$device['id'], 'borrowed');

        Session::flash('success', "&#10003; <strong>{$this->e($borrower['name'])}</strong> has borrowed <strong>{$this->e($device['name'])}</strong>.");
        Response::redirect('/scan');
    }

    public function return(): void
    {
        AuthMiddleware::handle();
        $this->request->verifyCsrf();

        $empQr = $this->request->post('emp_qr');
        $devQr = $this->request->post('dev_qr');

        $employeeModel = new Employee();
        $deviceModel   = new Device();
        $txModel       = new Transaction();

        $borrower = $employeeModel->findByQr($empQr);
        $device   = $deviceModel->findByQr($devQr);

        if (!$borrower) {
            Session::flash('error', "Employee QR not found: <strong>{$this->e($empQr)}</strong>");
            Response::redirect('/scan');
        }

        if (!$device) {
            Session::flash('error', "Device QR not found: <strong>{$this->e($devQr)}</strong>");
            Response::redirect('/scan');
        }

        if ($device['status'] !== 'borrowed') {
            Session::flash('error', "Device <strong>{$this->e($device['name'])}</strong> is not currently borrowed.");
            Response::redirect('/scan');
        }

        $tx = $txModel->activeByDevice((int)$device['id']);

        if (!$tx) {
            Session::flash('error', 'No active borrow record found for this device.');
            Response::redirect('/scan');
        }

        $currentUser = Session::user();
        $returnedBy  = ((int)$currentUser['id'] !== (int)$tx['borrower_id']) ? (int)$currentUser['id'] : null;

        $txModel->return((int)$tx['id'], $returnedBy);
        $deviceModel->setStatus((int)$device['id'], 'available');

        Session::flash('success', "&#10003; <strong>{$this->e($device['name'])}</strong> returned. Place it at <strong>{$this->e($device['cabinet'])}, {$this->e($device['shelf'])}</strong>.");
        Response::redirect('/scan');
    }
}
