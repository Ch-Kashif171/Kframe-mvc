<?php
/**
 *this class is written by @Kashif Sohail
 * Simple Validator class for show validation messages,
 * @validate() static function call to check the validation rules
 */
namespace Core\Facades\Validation;

class Validator
{
    public static $msg = array();
    public function __construct()
    {
        //
    }

    public static function validate($fields,$validate){

        if ( count($validate)> 0) {
            foreach ($validate as $name => $valid) {

                if (array_key_exists($name, $fields)) {
                    self::valid($name, $fields[$name], $valid);
                }
            }
            return new Validator();
        } else {
            return "please provide the validation rules";
        }
    }

    private static function valid($name,$field,$rule){
        if($field == '' && strpos($rule,'required') !== false){
            self::$msg[$name] =  "The ".self::splitField($name)." is required";
        }

        if($field != '' && strpos($rule,'mail') !== false) {
            if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
                self::$msg[$name] = "The ".self::splitField($name)." field is not a valid email";
            }
        }
        if($field != '' && strpos($rule,'date') !== false) {
            $is_valid = self::validate_date($field);
            if (!$is_valid) {
                self::$msg[$name] = "The ".self::splitField($name)." field is not a valid date";
            }
        }

        if($field != '' && strpos($rule,'min') !== false) {
            $r = self::getRule($rule,'min:');
            if ($r > 0 && strlen($field) < $r) {
                self::$msg[$name] = "The minimum length of ".self::splitField($name)." should be {$r}";
            }
        }

        if($field != '' && strpos($rule,'max') !== false) {
            $r = self::getRule($rule,'max:');
            if ($r > 0 && strlen($field) > $r) {
                self::$msg[$name] = "The maximum lenght of ".self::splitField($name)." should be {$r}";
            }
        }

        return self::$msg;
    }

    private static function validate_date($date){
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }

    private static function splitField($name){
        return str_replace('_',' ',$name);
    }

    private static function getRule($rule,$check){
        $length = 0;
        $array = explode('|',$rule);
        foreach ($array as $ar){
            if(strpos($ar,$check) !== false){
                $length = str_replace($check,'',$ar);
            }
        }
        return $length;
    }

    public function fails(){
        if(count(self::$msg) > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function messages(){
        if(count(self::$msg) > 0 ){
            $errors = '';
            foreach (self::$msg as $ms){
                $errors .= $ms."<br>";
            }
            return $errors;
        }else{
            return true;
        }
    }

    public function errors(){
        if(count(self::$msg) > 0 ){
            return self::$msg;
        }else{
            return true;
        }
    }

    public function error($name){

        if(count(self::$msg) > 0 ){
            return self::$msg[$name];
        }else{
            return true;
        }
    }

    public function first(){
        if(count(self::$msg) > 0 ) {
            $msg = array_reverse(self::$msg);
            return array_pop($msg);
        }else{
            return true;
        }
    }

}