<?php

namespace App\Models;

class Reconciliation extends BaseModel
{
    protected string $table = 'reconciliations';

    public function log(int $deviceId, int $performedBy, string $oldStatus, string $newStatus, string $reason): int
    {
        return $this->insertGetId(
            "INSERT INTO reconciliations (device_id, performed_by, old_status, new_status, reason) VALUES (?,?,?,?,?)",
            [$deviceId, $performedBy, $oldStatus, $newStatus, $reason]
        );
    }

    public function all(int $limit = 200): array
    {
        return $this->query("
            SELECT r.*,
                   d.name AS device_name, d.asset_tag,
                   e.name AS staff_name
            FROM reconciliations r
            JOIN devices   d ON r.device_id    = d.id
            JOIN employees e ON r.performed_by = e.id
            ORDER BY r.id DESC
            LIMIT $limit
        ");
    }
}
