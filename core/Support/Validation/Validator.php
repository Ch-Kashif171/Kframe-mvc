<?php
/**
 *this class is written by @Kashif Sohail
 * Simple Validator class for show validation messages,
 * @validate() static function call to check the validation rules
 */

namespace Core\Support\Validation;

use Core\Support\DB;

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

    public static function escape($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }


    private static function valid($name,$field,$rule)
    {
        if($field == '' && str_contains($rule, 'required')){
            self::$msg[$name] =  "The ".self::splitField($name)." field is required.";
        }

        if($field != '' && str_contains($rule, 'mail')) {
            if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
                self::$msg[$name] = "The ".self::splitField($name)." field is not a valid email.";
            }
        }

        if($field != '' && str_contains($rule, 'unique')) {
            $response = self::isUnique($field, $rule);
            if (!$response['status']) {
                self:: $msg[$name] = $response['message'];
            }
        }
        if($field != '' && str_contains($rule, 'date')) {
            $is_valid = self::validate_date($field);
            if (!$is_valid) {
                self::$msg[$name] = "The ".self::splitField($name)." field is not a valid date.";
            }
        }

        if($field != '' && str_contains($rule, 'min')) {
            $r = self::getRule($rule,'min:');
            if ($r > 0 && strlen($field) < $r) {
                self::$msg[$name] = "The minimum length of ".self::splitField($name)." should be {$r}";
            }
        }

        if($field != '' && str_contains($rule, 'max')) {
            $r = self::getRule($rule,'max:');
            if ($r > 0 && strlen($field) > $r) {
                self::$msg[$name] = "The maximum length of ".self::splitField($name)." should be {$r}";
            }
        }

        if($field != '' && str_contains($rule, 'numeric')) {
            if (!is_numeric($field)) {
                self::$msg[$name] = "The ".self::splitField($name)." field must be numeric.";
            }
        }

        if($field != '' && str_contains($rule, 'regex:')) {
            $pattern = self::getRule($rule, 'regex:');
            if (!preg_match($pattern, $field)) {
                self::$msg[$name] = "The ".self::splitField($name)." field format is invalid.";
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

    /**
     * @param string $field
     * @param string $rule
     * @return array|bool[]
     */
    private static function isUnique(string $field, string $rule )
    {
        $allRules = explode('|', $rule);

        foreach ($allRules as $rulePart) {
            if (str_starts_with($rulePart, 'unique:')) {
                $params = substr($rulePart, 7);
                $segments = explode(',', $params);

                $table      = trim($segments[0]);
                $column     = trim($segments[1]);
                $exceptId   = $segments[2] ?? null;
                $idColumn   = $segments[3] ?? 'id';

                if (count($segments) < 2) {
                    return [
                        'status' => false,
                        'message' => "Invalid unique rule format for $column. Use unique:table,column[,exceptId[,idColumn]]"
                    ];
                }

                $exists = self::checkUnique($table, $column, $field, $exceptId, $idColumn);

                if ($exists) {
                    return [
                        'status' => false,
                        'message' => "The $field has already been taken."
                    ];
                }
            }
        }

        return [
            'status' => true
        ];
    }

    private static function checkUnique(string $table, string $column, string $value, $exceptId = null, string $idColumn = 'id'): bool
    {
        $query = DB::table($table)->where($column, '=', $value);

        if ($exceptId !== null) {
            $query->where($idColumn, '!=', $exceptId);
        }

        return $query->exists();
    }



}