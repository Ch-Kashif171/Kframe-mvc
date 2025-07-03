<?php

namespace Core\Database\connection;

use Core\Exception\Handlers\DBException;

/**
 * Class database written by @kashif sohail
 * it is written in PDO
 */
class database
{
    private  $driver;
    private  $db;
    private  $user;
    private  $host;
    private  $pass;
    private  $dsn;

    public function __construct()
    {
        $this->driver   =   config('database.db_connection');
        $this->db       =   config('database.db_database');
        $this->user     =   config('database.db_username');
        $this->host     =   config('database.db_host');
        $this->pass     =   config('database.db_password');
        $this->dsn = "{$this->driver}:host={$this->host};dbname={$this->db}";
    }

    private $options  = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,);

    protected $con;

    public function connection()
    {
        try
        {
            $this->con = new \PDO($this->dsn, $this->user,$this->pass,$this->options);
            return $this->con;
        }
        catch (\PDOException $e)
        {
            throw new DBException("Connection Error: " .  $e->getMessage());
        }
    }

    public function closeConnection() {
        $this->con = null;
    }
}