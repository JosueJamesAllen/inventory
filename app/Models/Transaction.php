<?php

namespace App\Models;

class Transaction extends BaseModel
{
    protected string $table = 'transactions';

    public function borrow(int $deviceId, int $borrowerId, ?int $facilitatedBy = null): int
    {
        return $this->insertGetId(
            "INSERT INTO transactions (device_id, borrower_id, facilitated_by) VALUES (?, ?, ?)",
            [$deviceId, $borrowerId, $facilitatedBy]
        );
    }

    public function return(int $transactionId, ?int $returnedBy = null): bool
    {
        return $this->execute(
            "UPDATE transactions SET returned_at = NOW(), returned_by = ? WHERE id = ?",
            [$returnedBy, $transactionId]
        );
    }

    public function activeByDevice(int $deviceId): ?array
    {
        return $this->queryOne(
            "SELECT * FROM transactions WHERE device_id = ? AND returned_at IS NULL ORDER BY id DESC LIMIT 1",
            [$deviceId]
        );
    }

    public function activeBorrows(): array
    {
        return $this->query("
            SELECT d.name AS device_name, d.asset_tag, d.type AS device_type,
                   d.cabinet, d.shelf,
                   t.id, t.borrowed_at, t.facilitated_by,
                   e1.name AS borrower_name, e1.department,
                   e2.name AS facilitated_by_name,
                   CASE WHEN t.borrowed_at IS NOT NULL
                        THEN ROUND(TIMESTAMPDIFF(MINUTE, t.borrowed_at, NOW()) / 60, 1)
                        ELSE NULL END AS hours_ago
            FROM devices d
            LEFT JOIN transactions t  ON t.device_id = d.id AND t.returned_at IS NULL
            LEFT JOIN employees    e1 ON t.borrower_id     = e1.id
            LEFT JOIN employees    e2 ON t.facilitated_by  = e2.id
            WHERE d.status = 'borrowed'
            ORDER BY t.borrowed_at IS NULL, t.borrowed_at DESC
        ");
    }

    public function equipmentAudit(int $limit = 300): array
    {
        return $this->query("
            SELECT t.*,
                   d.name AS device_name, d.asset_tag, d.type AS device_type,
                   e1.name AS borrower_name, e1.department,
                   e2.name AS facilitated_by_name,
                   e3.name AS returned_by_name
            FROM transactions t
            JOIN devices   d  ON t.device_id      = d.id
            JOIN employees e1 ON t.borrower_id    = e1.id
            LEFT JOIN employees e2 ON t.facilitated_by = e2.id
            LEFT JOIN employees e3 ON t.returned_by    = e3.id
            ORDER BY t.id DESC
            LIMIT $limit
        ");
    }

    public function employeeAudit(int $limit = 300): array
    {
        return $this->query("
            SELECT t.*,
                   e.name AS employee_name, e.department, e.qr_code,
                   d.name AS device_name, d.asset_tag, d.type AS device_type
            FROM transactions t
            JOIN employees e ON t.borrower_id = e.id
            JOIN devices   d ON t.device_id   = d.id
            ORDER BY e.name, t.borrowed_at DESC
            LIMIT $limit
        ");
    }

    public function countToday(): int
    {
        return $this->count("SELECT COUNT(*) FROM transactions WHERE DATE(borrowed_at) = CURDATE()");
    }

    // Mark open transactions for a device as reconciled
    public function closeByDevice(int $deviceId, string $note): void
    {
        $this->execute(
            "UPDATE transactions SET returned_at = NOW(), notes = ? WHERE device_id = ? AND returned_at IS NULL",
            [$note, $deviceId]
        );
    }

    public function historyByDevice(int $deviceId): array
    {
        return $this->query("
            SELECT t.id, t.borrowed_at, t.returned_at, t.notes,
                   e1.name AS borrower_name, e1.department,
                   e2.name AS facilitated_by_name,
                   e3.name AS returned_by_name
            FROM transactions t
            JOIN employees e1 ON t.borrower_id    = e1.id
            LEFT JOIN employees e2 ON t.facilitated_by = e2.id
            LEFT JOIN employees e3 ON t.returned_by    = e3.id
            WHERE t.device_id = ?
            ORDER BY t.id DESC
        ", [$deviceId]);
    }

    // CSV export rows
    public function equipmentCsvRows(): array { return $this->equipmentAudit(9999); }
    public function employeeCsvRows(): array  { return $this->employeeAudit(9999); }
}
