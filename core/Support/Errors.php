<?php

namespace Core\Support;

class Errors
{
    public static  function withErrors($field)
    {
        $error = Session::get();
        if (array_key_exists('error_key', $error)) {
            if (isset($error)) {
                return Session::get('error_key')[$field];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has_error($key): bool
    {
        if (!Session::has('errors')) {
            return false;
        }

        $errors = Session::get('errors');
        foreach ($errors as $error) {
            if (isset($error[$key])) {
                return true;
            }
        }

        return false;
    }


    /**
     * @param $key
     * @return mixed
     */
    public static function errors($key): mixed
    {
        if (!Session::has('errors')) {
            return false;
        }

        $errors = Session::get('errors');
        if (empty($errors)) {
            return false;
        }

        foreach ($errors as $k => $error) {
            if (isset($error[$key])) {
                $message = $error[$key];
                Session::forget_array('errors', $k, $key);
                return $message;
            }
        }

        return false;
    }

}