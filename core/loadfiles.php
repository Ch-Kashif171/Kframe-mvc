<?php

$base = __DIR__ . '/../';
if (!empty($autoload['libraries'])){
    foreach (array_filter($autoload['libraries']) as $library){
        require_once $base.'application/libraries/'.$library.'.php';
    }
}

if (!empty($autoload['helpers'])){
    foreach (array_filter($autoload['helpers']) as $helper){
        require_once $base.'application/helpers/'.$helper.'.php';
    }
}