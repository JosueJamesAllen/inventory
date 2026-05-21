<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Models\ActivityLog;
use Core\Response;

class ActivityLogController extends BaseController
{
    public function index(): void
    {
        RoleMiddleware::require('admin');

        $model = new ActivityLog();
        $this->view('activity_log.index', [
            'entries' => $model->recent(500),
            'users'   => $model->distinctUsers(),
        ]);
    }

    // Called via fetch() from JS after client-side QR print/download
    public function logAction(): void
    {
        AuthMiddleware::handle();
        $this->request->verifyCsrf();

        $allowed = ['device.qr_printed', 'device.qr_pdf_downloaded'];
        $action  = $this->request->post('action');
        $desc    = $this->request->post('description');

        if (in_array($action, $allowed, true) && $desc) {
            ActivityLog::record($action, $desc);
        }

        Response::json(['ok' => true]);
    }
}
