<?php

require_once 'Error.php';
class ErrorsHandler extends Exception
{
    public function errorException($error){

        set_error_handler('myErrorsHandler');
        trigger_error($error,E_USER_ERROR);

    }
}