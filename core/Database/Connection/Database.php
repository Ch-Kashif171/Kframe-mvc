<?php

namespace Core\Database\Connection;

use PDO;
use PDOException;
use Core\Exception\Handlers\DBException;

/**
 * Class Database
 * PDO-based DB connection handler by @kashif sohail
 */
class Database
{
    protected PDO|null $connection = null;

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

    public function __construct()
    {
        $this->driver = config('database.db_connection');
        $this->host   = config('database.db_host');
        $this->db     = config('database.db_database');
        $this->user   = config('database.db_username');
        $this->pass   = config('database.db_password');

        $this->dsn = "{$this->driver}:host={$this->host};dbname={$this->db};charset=utf8mb4";
    }

    /**
     * Establish a PDO connection (lazy-loaded).
     */
    public function connection(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO($this->dsn, $this->user, $this->pass, $this->options);
            } catch (PDOException $e) {
                throw new DBException("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->connection;
    }

    /**
     * Close the active PDO connection.
     */
    public function closeConnection(): void
    {
        $this->connection = null;
    }
}
