<?php
/**
 * get @ENV path, and load it for using in whole framework
 */
$env_path = str_replace("\config","",__DIR__);
/**
 * sent second @param, file name if load file other then .env
 */
$dotenv = new Dotenv($env_path);
$dotenv->load();
/***********************************************************/

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
    'helpers' =>  array('helper'),

];