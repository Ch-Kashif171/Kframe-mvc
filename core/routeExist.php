<?php

use Core\Facades\RouteNotFount;

    if (isset($_SESSION['exist']) && $_SESSION['exist']) {
        /*Okay do nothing*/
    } else {

        RouteNotFount::check();
    }
    unset($_SESSION['exist']);