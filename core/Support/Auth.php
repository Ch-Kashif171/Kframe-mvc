<?php
namespace Core\Support;

use Core\Database\Doctrine;
use stdClass;
use Whoops\Exception\ErrorException;

class Auth
{
    public $db;
    public $table;

    public function __construct()
    {
        $this->table = function_exists('config') ? config('auth.table', 'users') : (env('AUTH_TABLE') ?: 'users');
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
        if($result){
            $verify = (new self)->verify($credentials,$result);
            if($verify){
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['user'] = $result;
                return true;
            }else{
                self::logout();
                return false;
            }
        }else{
            self::logout();
            return false;
        }
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
    private function checkUser($credentials)
    {

        $result = $this->db->where_array($credentials)->first();
        return $result;
    }

    /**
     * @param $credentials
     * @param $output
     * @return bool
     */
    public function verify($credentials,$output)
    {
        $verified = array();
        foreach ($credentials as $field=> $credential){
            $verified[] = password_verify($credential, $output->$field );
        }
        if(in_array(true,$verified)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $credentials
     * @return array
     * @throws ErrorException
     */
    private function getAuthTableFieldsSkipPassword($credentials)
    {
        $table = function_exists('config') ? config('auth.table', 'users') : (env('AUTH_TABLE') ?: 'users');
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$table."' ";
        $fields = $this->db->rawQuery($query);
        if ($fields === false) {
            $fields = [];
        }

        if(count($fields) > 0) {

            $auth_fields = array();
            $key = 0;
            foreach ($fields as $field) {
                if (strpos($field->COLUMN_NAME, 'password') === false) {
                    if (isset($credentials[$field->COLUMN_NAME])) {
                        $auth_fields[$field->COLUMN_NAME] = $credentials[$field->COLUMN_NAME];
                    }

                }
                $key++;
            }

            return $auth_fields;
        }else{
            throw new ErrorException("The ". $this->table ." table for authentication is not exists.");
        }
    }

}