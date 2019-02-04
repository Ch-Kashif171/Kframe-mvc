<?php

use Core\model\BaseModel;

class Users extends BaseModel
{
    public $table = 'users';
    public $hide_fields = 'password';

    public function create($data){
        $result = $this->db->insert($data);
        return $result;
    }

}