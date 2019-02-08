<?php
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\model\BaseModel;

class modelname extends BaseModel {

 /**
  * Enter $table name: mandatory -> e.g protected table = 'test';
  * Enter$hide_fields: Optional -> e.g protected $hide_fields = 'email,password';
  *
  */

  public $table;
    public $hide_fields;


    public function save($data){
        $result =   $this->db->insert($data);
        return $result;
    }


}
?>
