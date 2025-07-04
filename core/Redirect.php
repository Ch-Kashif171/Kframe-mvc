<?php

use Core\Support\General;
use Core\Support\Session;

use Core\Response;

class Redirect
{
    public function __construct()
    {
        //
    }

    public function withInput()
    {
        General::setOldData();
        return new Redirect();
    }

    /**
     * @param null $with
     * @return Redirect|void
     */
    public function back($with = null){
        if(is_null($with)){
            return header('Location: ' . $_SERVER['HTTP_REFERER']);
        }else{
            return new Redirect();
        }
    }

    /**
     * @param $data
     */
    public function backWithErrors($data){
        Session::push('errors',$data);
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function backWith($key,$message){
        Session::flash($key,$message);
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function json($data){
        echo json_encode($data);
    }
}