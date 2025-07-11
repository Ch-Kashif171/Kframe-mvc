<?php

namespace Core\Utils;

use Core\Support\General;
use Core\Support\Session;

class Redirect
{
    protected $url;

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
     * Redirect to a specific URL
     * @param string $url
     * @return $this
     */
    public function to($url)
    {
        $this->url = url($url);
        return $this;
    }

    /**
     * Perform the redirect
     * @return void
     */
    public function go()
    {
        header('Location: ' . $this->url);
        exit;
    }

    /**
     * @param $data
     */
    public function backWithErrors($data){
        Session::push('errors',$data);
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function backWith($key,$message){
        Session::put($key, $message);
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Attach a flash message and redirect
     * @param string $type
     * @param string $message
     * @return void
     */
    public function with($type, $message)
    {
        Session::put($type, $message);
        $this->go();
    }

    public function json($data){
        echo json_encode($data);
    }
}