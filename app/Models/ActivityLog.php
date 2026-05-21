<?php

namespace App\Models;

use Core\Session;

class ActivityLog extends BaseModel
{
    protected string $table = 'activity_log';

    public static function record(string $action, string $description): void
    {
        $user = Session::user();
        if (!$user) return;
        (new static())->execute(
            "INSERT INTO activity_log (user_id, user_name, user_role, action, description) VALUES (?, ?, ?, ?, ?)",
            [(int)$user['id'], $user['name'], $user['role'], $action, $description]
        );
    }

    public function recent(int $limit = 500): array
    {
        return $this->query("SELECT * FROM activity_log ORDER BY id DESC LIMIT $limit");
    }

    public function distinctUsers(): array
    {
        return $this->query(
            "SELECT DISTINCT user_id, user_name, user_role FROM activity_log ORDER BY user_name"
        );
    }
}
