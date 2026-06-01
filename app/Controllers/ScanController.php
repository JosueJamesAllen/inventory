<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\ActivityLog;
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

        $continueBorrower = $_SESSION['continue_borrower'] ?? null;
        unset($_SESSION['continue_borrower']);

        $this->view('scan.index', [
            'employees'       => (new Employee())->all(),
            'availDevices'    => (new Device())->available(),
            'borrowedDevices' => (new Device())->borrowed(),
            'continueBorrower' => $continueBorrower,
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

        $currentUser   = Session::user();
        $facilitatedBy = ($currentUser['id'] !== $borrower['id']) ? (int)$currentUser['id'] : null;
        $purpose       = trim($this->request->post('purpose') ?? '');
        $expectedReturn = $this->request->post('expected_return_at') ?: null;

        (new Transaction())->borrow((int)$device['id'], (int)$borrower['id'], $facilitatedBy, $purpose ?: null, $expectedReturn);
        $deviceModel->setStatus((int)$device['id'], 'borrowed');

        $borrowDesc = $facilitatedBy
            ? "Borrowed {$device['name']} for {$borrower['name']}"
            : "Borrowed {$device['name']}";
        ActivityLog::record('scan.borrow', $borrowDesc);

        Session::flash('success', "&#10003; <strong>{$this->e($borrower['name'])}</strong> has borrowed <strong>{$this->e($device['name'])}</strong>.");
        $_SESSION['continue_borrower'] = [
            'name' => $borrower['name'],
            'qr'   => $borrower['qr_code'],
        ];
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

        $returnDesc = $returnedBy
            ? "Returned {$device['name']} on behalf of {$borrower['name']}"
            : "Returned {$device['name']}";
        ActivityLog::record('scan.return', $returnDesc);

        Session::flash('success', "&#10003; <strong>{$this->e($device['name'])}</strong> returned. Place it at <strong>{$this->e($device['cabinet'])}, {$this->e($device['shelf'])}</strong>.");
        Response::redirect('/scan');
    }

    public function checkEmployee(): void
    {
        AuthMiddleware::handle();
        $qr       = $this->request->get('qr') ?? '';
        $employee = (new Employee())->findByQr($qr);
        Response::json([
            'valid' => (bool) $employee,
            'name'  => $employee['name'] ?? null,
            'error' => $employee ? null : "QR \"{$this->e($qr)}\" was not found as an employee.",
        ]);
    }

    public function checkDevice(): void
    {
        AuthMiddleware::handle();
        $qr     = $this->request->get('qr') ?? '';
        $device = (new Device())->findByQr($qr);
        Response::json([
            'valid'     => (bool) $device,
            'name'      => $device['name']      ?? null,
            'asset_tag' => $device['asset_tag'] ?? null,
            'error'     => $device ? null : "QR \"{$this->e($qr)}\" was not found as a device.",
        ]);
    }
}
