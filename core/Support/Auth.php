<?php
namespace Core\Support;

use Core\Database\Doctrine;
use MongoDB\Driver\Exception\AuthenticationException;

class Auth
{
    public $db;

    public function __construct()
    {
        if(env('AUTH_TABLE') == '' || env('AUTH_TABLE') == 'null'){
            die("Please define auth table name in .ENV file for authentication");
        }

        $this->db = new Doctrine(env('AUTH_TABLE'));

    }

    /**
     * @return bool|stdClass
     */
    public static function user(){
        $result = (new self)->get();
        return $result;
    }

    public static function id(){
        $result = (new self)->get();
        if ($result){
            return $result->id;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function check(){
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
     */
    public static function attempt($credentials){
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
        if(isset($_SESSION['user'])){
            /*$result = $this->db->where_array($_SESSION['user'])->first();*/
            $keys = array_keys((array)$_SESSION['user']);
            $skiped_result = new \stdClass();
            foreach ($keys as $key){
                if (strpos($key, 'password') === false) {
                    $skiped_result->$key = $_SESSION['user']->$key;
                }
            }
            $result = $skiped_result;

        }else{
            $result = false;
        }
        return $result;
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
     */
    private function getAuthTableFieldsSkipPassword($credentials)
    {

        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".env('AUTH_TABLE')."' ";
        $fields = $this->db->rawQuery($query);

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
            throw new \Exception("Please enter valid auth table name in .ENV file");
        }
    }

}