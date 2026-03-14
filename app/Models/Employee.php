<?php

namespace App\Models;

class Employee extends BaseModel
{
    protected string $table = 'employees';

    public function all(): array
    {
        return $this->query("
            SELECT e.*,
                   COUNT(t.id)                                          AS total_borrows,
                   SUM(CASE WHEN t.returned_at IS NULL THEN 1 ELSE 0 END) AS active_borrows
            FROM employees e
            LEFT JOIN transactions t ON e.id = t.borrower_id
            GROUP BY e.id
            ORDER BY e.name
        ");
    }

    public function findById(int $id): ?array
    {
        return $this->queryOne("SELECT * FROM employees WHERE id = ?", [$id]);
    }

    public function findByQr(string $qrCode): ?array
    {
        return $this->queryOne("SELECT * FROM employees WHERE qr_code = ?", [$qrCode]);
    }

    public function create(array $data): int
    {
        return $this->insertGetId(
            "INSERT INTO employees (name, department, qr_code, role) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['department'], $data['qr_code'], $data['role']]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->execute(
            "UPDATE employees SET name=?, department=?, role=? WHERE id=?",
            [$data['name'], $data['department'], $data['role'], $id]
        );
    }

    public function total(): int
    {
        return $this->count("SELECT COUNT(*) FROM employees");
    }
}
