<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Models\Device;
use App\Models\Reconciliation;
use App\Models\Transaction;
use Core\Response;
use Core\Session;

class DeviceController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $this->view('devices.index', [
            'devices' => (new Device())->all(),
        ]);
    }

    public function store(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $assetTag = $this->request->post('asset_tag');

        // Check for duplicate asset tag
        if ((new Device())->findByAssetTag($assetTag)) {
            Session::flash('error', "Asset tag <strong>{$this->e($assetTag)}</strong> already exists.");
            Response::redirect('/devices');
        }

        (new Device())->create([
            'name'      => $this->request->post('name'),
            'type'      => $this->request->post('type'),
            'asset_tag' => $assetTag,
            'cabinet'   => $this->request->post('cabinet'),
            'shelf'     => $this->request->post('shelf'),
            'notes'     => $this->request->post('notes'),
        ]);

        Session::flash('success', 'Device added successfully.');
        Response::redirect('/devices');
    }

    public function update(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $id = (int)$this->request->post('device_id');

        (new Device())->update($id, [
            'name'    => $this->request->post('name'),
            'type'    => $this->request->post('type'),
            'cabinet' => $this->request->post('cabinet'),
            'shelf'   => $this->request->post('shelf'),
            'status'  => $this->request->post('status'),
            'notes'   => $this->request->post('notes'),
        ]);

        Session::flash('success', 'Device updated successfully.');
        Response::redirect('/devices');
    }

    public function reconcile(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $id        = (int)$this->request->post('device_id');
        $newStatus = $this->request->post('new_status');
        $reason    = $this->request->post('reason');

        $deviceModel = new Device();
        $device      = $deviceModel->findById($id);

        if (!$device) {
            Session::flash('error', 'Device not found.');
            Response::redirect('/devices');
        }

        // If reconciling to available, close any open transactions
        if ($newStatus === 'available') {
            (new Transaction())->closeByDevice($id, 'Reconciled: ' . $reason);
        }

        $deviceModel->setStatus($id, $newStatus);

        (new Reconciliation())->log(
            $id,
            (int)Session::user()['id'],
            $device['status'],
            $newStatus,
            $reason
        );

        Session::flash('success', 'Reconciliation logged successfully.');
        Response::redirect('/devices');
    }
}
