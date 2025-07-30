<?php

namespace Core\Database\Connection;

use PDO;
use PDOException;
use Core\Exception\Handlers\DBException;

class Database
{
    protected static ?PDO $connection = null;

    private static ?self $instance = null;

    private string $driver;
    private string $host;
    private string $db;
    private string $user;
    private string $pass;
    private string $dsn;

    private array $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    private function __construct()
    {
        $this->driver = config('database.db_connection');
        $this->host   = config('database.db_host');
        $this->db     = config('database.db_database');
        $this->user   = config('database.db_username');
        $this->pass   = config('database.db_password');

        $this->dsn = "{$this->driver}:host={$this->host};dbname={$this->db};charset=utf8mb4";
    }

    /**
     * Get the singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get the PDO connection
     */
    public function connection(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO($this->dsn, $this->user, $this->pass, $this->options);
            } catch (PDOException $e) {
                throw new DBException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    /**
     * Close the connection
     */
    public function closeConnection(): void
    {
        self::$connection = null;
    }

}
