<?php

use Core\model\BaseModel;

class Comment extends BaseModel
{
    public $table = 'comment';


    public function getAll(){
        $result =   $this->db->orderBy("id","DESC")->get();
        return $result;
    }

}