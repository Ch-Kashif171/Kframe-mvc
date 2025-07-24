<?php
namespace Core\Support;

use Core\Database\Doctrine;
use stdClass;
use Whoops\Exception\ErrorException;

class Auth
{
    public $db;
    public $table;
    /**
     * @var array|mixed
     */
    private mixed $database;

    public function initDB()
    {
        $this->table = function_exists('config') ? config('app.table', 'users') : (env('AUTH_TABLE') ?: 'users');
        $this->database = config('database.db_database');
        $this->db = new Doctrine($this->table);
    }

    /**
     * @return bool|stdClass
     */
    public static function user()
    {
        return (new self)->get();
    }

    /**
     * @return false
     */
    public static function id()
    {
        $result = (new self)->get();
        if ($result) {
            return $result->id;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function check()
    {
        if(isset($_SESSION['user']) &&  $_SESSION['user'] != ''){
            $return = true;
        }else{
            $return = false;
        }
        return $return;
    }

    /**
     * @param $credentials
     * @return bool
     * @throws ErrorException
     */
    public static function attempt($credentials): bool
    {
        $auth_fields = (new self)->getAuthTableFieldsSkipPassword($credentials);
        $result = (new self)->checkUser($auth_fields);
        if($result) {
            $verify = (new self)->verify($credentials, $result);
            if($verify) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user'] = $result;
                return true;
            } else {
                self::logout();
                return false;
            }
        }

        self::logout();
        return false;
    }

    /**
     * @param $password
     * @return bool|string
     */
    public static function Hash($password): bool|string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        session_regenerate_id(true); // Prevent session fixation
        unset($_SESSION['user']);
        return true;
    }

    /**
     * @return bool|stdClass
     */
    private function get()
    {
        if (!isset($_SESSION['user']) || !is_object($_SESSION['user'])) {
            return false;
        }

        $user = $_SESSION['user'];
        $filteredUser = new stdClass();

        foreach ($user as $key => $value) {
            if (stripos($key, 'password') === false) {
                $filteredUser->$key = $value;
            }
        }

        return $filteredUser;
    }


    /**
     * @param $credentials
     * @return mixed
     * @throws \Whoops\Exception\ErrorException
     */
    private function checkUser($credentials): mixed
    {
        self::initDB();
        return $this->db->where_array($credentials)->userFound();
    }

    /**
     * @param $credentials
     * @param $output
     * @return bool
     */
    public function verify($credentials,$output): bool
    {
        $verified = [];
        foreach ($credentials as $field=> $credential){
            $verified[] = password_verify($credential, $output->$field );
        }
        if(in_array(true,$verified)){
            return true;
        }
        return false;
    }

    /**
     * @param $credentials
     * @return array
     * @throws ErrorException
     */
    private function getAuthTableFieldsSkipPassword($credentials)
    {
        self::initDB();
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME = '".$this->table."' ";
        $fields = $this->db->rawQuery($query);
        if ($fields === false) {
            $fields = [];
        }

        if(count($fields) > 0) {

            $auth_fields = [];
            $key = 0;
            foreach ($fields as $field) {
                if (!str_contains($field->COLUMN_NAME, 'password')) {
                    if (isset($credentials[$field->COLUMN_NAME])) {
                        $auth_fields[$field->COLUMN_NAME] = $credentials[$field->COLUMN_NAME];
                    }

                }
                $key++;
            }

            return $auth_fields;
        }

        throw new ErrorException("The ". $this->table ." table for authentication is not exists.");
    }

}