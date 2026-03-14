<?php

namespace App\Models;

class Device extends BaseModel
{
    protected string $table = 'devices';

    public function all(): array
    {
        return $this->query("
            SELECT d.*,
                   (SELECT e.name FROM transactions t
                    JOIN employees e ON t.borrower_id = e.id
                    WHERE t.device_id = d.id AND t.returned_at IS NULL
                    ORDER BY t.id DESC LIMIT 1) AS current_borrower
            FROM devices d
            ORDER BY d.name
        ");
    }

    public function findById(int $id): ?array
    {
        return $this->queryOne("SELECT * FROM devices WHERE id = ?", [$id]);
    }

    public function findByQr(string $qrCode): ?array
    {
        return $this->queryOne("SELECT * FROM devices WHERE qr_code = ?", [$qrCode]);
    }

    public function findByAssetTag(string $tag): ?array
    {
        return $this->queryOne("SELECT * FROM devices WHERE asset_tag = ?", [$tag]);
    }

    public function create(array $data): int
    {
        return $this->insertGetId(
            "INSERT INTO devices (name, type, asset_tag, qr_code, cabinet, shelf, notes) VALUES (?,?,?,?,?,?,?)",
            [
                $data['name'], $data['type'], $data['asset_tag'],
                'QR-' . $data['asset_tag'],
                $data['cabinet'], $data['shelf'],
                $data['notes'] ?? null
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return $this->execute(
            "UPDATE devices SET name=?, type=?, cabinet=?, shelf=?, status=?, notes=? WHERE id=?",
            [$data['name'], $data['type'], $data['cabinet'], $data['shelf'], $data['status'], $data['notes'] ?? null, $id]
        );
    }

    public function setStatus(int $id, string $status): bool
    {
        return $this->execute("UPDATE devices SET status=? WHERE id=?", [$status, $id]);
    }

    // ── Stats ─────────────────────────────────────────────

    public function countAll(): int     { return $this->count("SELECT COUNT(*) FROM devices"); }
    public function countAvailable(): int { return $this->count("SELECT COUNT(*) FROM devices WHERE status='available'"); }
    public function countBorrowed(): int  { return $this->count("SELECT COUNT(*) FROM devices WHERE status='borrowed'"); }
    public function countOos(): int       { return $this->count("SELECT COUNT(*) FROM devices WHERE status='out_of_service'"); }

    public function available(): array
    {
        return $this->query("SELECT * FROM devices WHERE status='available' ORDER BY name");
    }

    public function borrowed(): array
    {
        return $this->query("SELECT * FROM devices WHERE status='borrowed' ORDER BY name");
    }
}
