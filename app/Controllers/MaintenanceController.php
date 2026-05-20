<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Models\Location;
use Core\Response;
use Core\Session;

class MaintenanceController extends BaseController
{
    public function index(): void
    {
        RoleMiddleware::require('admin', 'it_staff');

        $this->view('maintenance.index', [
            'locations' => (new Location())->all(),
        ]);
    }

    public function storeLocation(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $cabinet = trim($this->request->post('cabinet') ?? '');
        $shelf   = trim($this->request->post('shelf') ?? '');

        if (!$cabinet || !$shelf) {
            Session::flash('error', 'Cabinet and shelf are both required.');
            Response::redirect('/maintenance');
        }

        $model = new Location();

        if ($model->exists($cabinet, $shelf)) {
            Session::flash('error', "Location <strong>{$this->e($cabinet)} — {$this->e($shelf)}</strong> already exists.");
            Response::redirect('/maintenance');
        }

        $model->create(['cabinet' => $cabinet, 'shelf' => $shelf]);
        Session::flash('success', "Location <strong>{$this->e($cabinet)} — {$this->e($shelf)}</strong> added.");
        Response::redirect('/maintenance');
    }

    public function locationsJson(): void
    {
        AuthMiddleware::handle();
        Response::json((new Location())->grouped());
    }

    public function deleteLocation(): void
    {
        RoleMiddleware::require('admin', 'it_staff');
        $this->request->verifyCsrf();

        $id    = (int)($this->request->post('location_id') ?? 0);
        $model = new Location();

        $inUse = $model->inUseCount($id);
        if ($inUse > 0) {
            Session::flash('error', "Cannot remove — {$inUse} device(s) are still assigned to this location.");
            Response::redirect('/maintenance');
        }

        $model->delete($id);
        Session::flash('success', 'Location removed.');
        Response::redirect('/maintenance');
    }
}
