<?php

namespace App\Models;

class Location extends BaseModel
{
    protected string $table = 'locations';

    public function all(): array
    {
        return $this->query("
            SELECT l.*, COUNT(d.id) AS device_count
            FROM locations l
            LEFT JOIN devices d ON d.cabinet = l.cabinet AND d.shelf = l.shelf
            GROUP BY l.id
            ORDER BY l.cabinet, l.shelf
        ");
    }

    public function grouped(): array
    {
        $rows    = $this->query("SELECT cabinet, shelf FROM locations ORDER BY cabinet, shelf");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['cabinet']][] = $row['shelf'];
        }
        return $grouped;
    }

    public function create(array $data): int
    {
        return $this->insertGetId(
            "INSERT INTO locations (cabinet, shelf) VALUES (?, ?)",
            [$data['cabinet'], $data['shelf']]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->queryOne("SELECT * FROM locations WHERE id = ?", [$id]);
    }

    public function delete(int $id): bool
    {
        return $this->execute("DELETE FROM locations WHERE id = ?", [$id]);
    }

    public function exists(string $cabinet, string $shelf): bool
    {
        return (bool)$this->queryOne(
            "SELECT id FROM locations WHERE cabinet = ? AND shelf = ?",
            [$cabinet, $shelf]
        );
    }

    public function inUseCount(int $id): int
    {
        $loc = $this->queryOne("SELECT cabinet, shelf FROM locations WHERE id = ?", [$id]);
        if (!$loc) return 0;
        return $this->count(
            "SELECT COUNT(*) FROM devices WHERE cabinet = ? AND shelf = ?",
            [$loc['cabinet'], $loc['shelf']]
        );
    }
}
