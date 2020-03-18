<?php

/**********remove errors************/

    switch (env('APP_ENV')) {
        case 'development':
            error_reporting(E_ALL);
            break;
        case 'testing':
        case 'production':
            error_reporting(0);
            break;

        default:
            exit('The application environment is not set correctly.');
    }


/***********************************************************************/

/*load configuration files*/
$autoload = [

    /**
    * here @libraries , @helpers and other files can
    * be included as an array these all will
    * be autoload once load framework
    */
    /*load libraries files here as array*/
    'libraries' =>  array(''),


    /*load helpers*/
    'helpers' =>  array(''),

];

/**
 * Define go back page path here to return user to home page in 404 error case.
 */
$go_back = url();