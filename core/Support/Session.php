<?php
/**
 * this class is written by @Kashif Sohail
 * Simple Session class for save and get sessions with specific keys,
 * @flash() function to set flash session
 * @put() function to set session
 * @push() function to set multi arrays into session
 * @get() function to get session values by key
 * @has() function to check if session with specific keyis exist or not
 */
namespace Core\Support;

class Session
{
    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function flash($key,$value){
        $_SESSION[$key]['flash'] = $value;
        return true;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function put($key,$value){
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * @param $key
     * @param $array
     * @return mixed
     */
    public static function push($key,$array){
        if(!isset($_SESSION[$key])){
            $_SESSION[$key] = array();
        }else{
            $_SESSION[$key] = $_SESSION[$key];
        }
        array_push($_SESSION[$key],$array);

        return $_SESSION[$key];
    }

    /**
     * @param null $key
     * @return mixed
     */
    public static function get($key=null){
        if(is_null($key)){
          return $_SESSION;
        }else {
            if (isset($_SESSION[$key]['flash'])) {
                $session = $_SESSION[$key]['flash'];
                unset($_SESSION[$key]['flash']);
                unset($_SESSION[$key]);
            } else {
                $session = $_SESSION[$key];
            }
            return $session;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key){

        if(isset($_SESSION[$key])){
            $session = true;
        }else{
            $session = false;
        }
        return $session;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function forget($key){
        unset($_SESSION[$key]);
        return true;
    }

    public static function forget_array($key,$assoc,$index){
        unset($_SESSION[$key][$assoc][$index]);
        return true;
    }

}