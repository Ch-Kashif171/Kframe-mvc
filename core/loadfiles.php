<?php

use Core\Facades\NotFound;

if (!empty($autoload['libraries'])){
    foreach (array_filter($autoload['libraries']) as $library){
        require_once root_path.'/app/'.$library.'.php';
    }
}

if (!empty($autoload['helpers'])){
    foreach (array_filter($autoload['helpers']) as $helper){
        require_once root_path.'/app/'.$helper.'.php';
    }
}

/**
 * get home url here for 404
 */
NotFound::home_url($home_page);