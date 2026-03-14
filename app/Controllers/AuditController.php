<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Reconciliation;
use App\Models\Transaction;
use Core\Response;

class AuditController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $tab         = $this->request->get('tab', 'equipment');
        $txModel     = new Transaction();
        $reconModel  = new Reconciliation();

        $this->view('audit.index', [
            'tab'          => $tab,
            'equipmentLog' => $txModel->equipmentAudit(),
            'employeeLog'  => $txModel->employeeAudit(),
            'reconLog'     => $reconModel->all(),
        ]);
    }

    public function exportCsv(): void
    {
        AuthMiddleware::handle();

        $type    = $this->request->get('type', 'equipment');
        $txModel = new Transaction();

        ob_start();
        $out = fopen('php://output', 'w');

        if ($type === 'equipment') {
            fputcsv($out, ['Device', 'Asset Tag', 'Type', 'Borrower', 'Department', 'Borrowed At', 'Returned At', 'Facilitated By', 'Returned By', 'Notes']);
            foreach ($txModel->equipmentCsvRows() as $r) {
                fputcsv($out, [
                    $r['device_name'],
                    $r['asset_tag'],
                    $r['device_type'],
                    $r['borrower_name'],
                    $r['department'],
                    $r['borrowed_at'],
                    $r['returned_at'] ?? 'Active',
                    $r['facilitated_by_name'] ?? 'Self',
                    $r['returned_by_name'] ?? ($r['returned_at'] ? 'Self' : '—'),
                    $r['notes'] ?? '',
                ]);
            }
        } else {
            fputcsv($out, ['Employee', 'Department', 'QR Code', 'Device', 'Asset Tag', 'Type', 'Borrowed At', 'Returned At']);
            foreach ($txModel->employeeCsvRows() as $r) {
                fputcsv($out, [
                    $r['employee_name'],
                    $r['department'],
                    $r['qr_code'],
                    $r['device_name'],
                    $r['asset_tag'],
                    $r['device_type'],
                    $r['borrowed_at'],
                    $r['returned_at'] ?? 'Active',
                ]);
            }
        }

        fclose($out);
        $csv      = ob_get_clean();
        $filename = $type . '_audit_' . date('Y-m-d') . '.csv';

        Response::download($filename, $csv);
    }
}
