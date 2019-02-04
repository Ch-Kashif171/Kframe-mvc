<?php

include_once 'Exception/ErrorsHandler.php';
try {
    if (isset($_SESSION['exist']) && $_SESSION['exist']) {
        /*Okay do nothing*/
    } else {

        throw new ErrorsHandler();
    }

    unset($_SESSION['exist']);
} catch (ErrorsHandler $e){
    die($e->errorException('Your given route did not match'));
}