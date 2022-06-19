<?php

 namespace Core\connection;

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
        $this->driver   =   env('DB_CONNECTION','mysql');
        $this->db       =   env('DB_DATABASE','kframe');
        $this->user     =   env("DB_USERNAME","root");
        $this->host     =   env('DB_HOST','localhost');
        $this->pass     =   env('DB_PASSWORD','');
        $this->dsn = "" . $this->driver . ":host=" . $this->host . ";dbname=" . $this->db;
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