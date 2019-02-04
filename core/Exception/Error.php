<?php

  function myErrorsHandler($errno, $errstr, $errfile, $errline)
    {

        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>ERROR</b> [$errno] $errstr<br />\n";
                echo "  Fatal error on line $errline in file $errfile";
                echo ", PHP " . PHP_VERSION . "<br />\n";

                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>WARNING: </b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>NOTICE: </b> [$errno] $errstr<br />\n";
                break;

            default:
                echo "Unknown error type: [$errno] $errstr<br />\n";
                break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }