<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Models\Device;
use App\Models\Location;
use App\Models\Reconciliation;
use App\Models\Transaction;
use Core\Response;
use Core\Session;

class DeviceController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $loc = new Location();
        $this->view('devices.index', [
            'devices'          => (new Device())->all(),
            'locationsByGroup' => $loc->grouped(),
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

        $newStatus = $this->request->post('status');

        if ($newStatus === 'available') {
            (new Transaction())->closeByDevice($id, 'Status set to available via device edit.');
        }

        (new Device())->update($id, [
            'name'    => $this->request->post('name'),
            'type'    => $this->request->post('type'),
            'cabinet' => $this->request->post('cabinet'),
            'shelf'   => $this->request->post('shelf'),
            'status'  => $newStatus,
            'notes'   => $this->request->post('notes'),
        ]);

        Session::flash('success', 'Device updated successfully.');
        Response::redirect('/devices');
    }

    public function bulkUpdate(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $ids       = array_filter(array_map('intval', (array)($_POST['device_ids'] ?? [])));
        $newStatus = $this->request->post('bulk_status');
        $allowed   = ['available', 'borrowed', 'out_of_service'];

        if (empty($ids) || !in_array($newStatus, $allowed)) {
            Session::flash('error', 'Invalid bulk update request.');
            Response::redirect('/devices');
        }

        $deviceModel = new Device();
        $txModel     = new Transaction();

        foreach ($ids as $id) {
            if ($newStatus === 'available') {
                $txModel->closeByDevice($id, 'Bulk status update to available.');
            }
            $deviceModel->setStatus($id, $newStatus);
        }

        $label = str_replace('_', ' ', $newStatus);
        Session::flash('success', count($ids) . ' device(s) set to <strong>' . $label . '</strong>.');
        Response::redirect('/devices');
    }

    public function history(): void
    {
        AuthMiddleware::handle();

        $id      = (int)($_GET['id'] ?? 0);
        $device  = (new Device())->findById($id);

        if (!$device) {
            Response::json(['error' => 'Device not found'], 404);
        }

        $rows = (new Transaction())->historyByDevice($id);

        Response::json([
            'device' => [
                'name'       => $device['name'],
                'asset_tag'  => $device['asset_tag'],
                'type'       => $device['type'],
                'status'     => $device['status'],
            ],
            'history' => $rows,
        ]);
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
