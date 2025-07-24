<?php
namespace Core\Support;

class Response
{
    public function __construct()
    {

    }

    public function with($type,$message){
         Session::put($type,$message);
        return $message;
    }

    public function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

}