<?php
use Core\Exception\Handlers\RouteNotFoundException;

    if (isset($_SESSION['exist']) && $_SESSION['exist']) {
        /*Okay do nothing*/
    } else {
        throw new RouteNotFoundException("Your given route did not match");
    }
    unset($_SESSION['exist']);