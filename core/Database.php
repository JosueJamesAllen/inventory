<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = require BASE_PATH . '/config/database.php';

            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $cfg['driver'],
                $cfg['host'],
                $cfg['port'],
                $cfg['database'],
                $cfg['charset']
            );

            try {
                self::$instance = new PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);
            } catch (PDOException $e) {
                // Show a clean error instead of a stack trace
                http_response_code(500);
                die('<h2 style="font-family:sans-serif;color:#b91c1c;padding:2rem">
                    Database connection failed.<br>
                    <small style="font-size:.875rem;color:#6b7280">
                        Make sure MySQL is running in XAMPP and <code>inventory_db</code> exists.<br>
                        Error: ' . htmlspecialchars($e->getMessage()) . '
                    </small>
                </h2>');
            }
        }

        return self::$instance;
    }

    // Prevent instantiation
    private function __construct() {}
    private function __clone() {}
}
