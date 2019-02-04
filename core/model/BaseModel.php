<?php
namespace Core\model;

use Core\Doctrine;

class BaseModel extends Doctrine
{

    public $table;
    public $hide_fields;
    public $db;

    public function __construct() {
        parent::__construct();
        /*if table not define in model, then by default, model
         *name should be then table name*/
        if(is_null($this->table) && $this->table == ''){
            $this->table = getTable(get_called_class());
        }

        /*-----------------------------------*/

        $this->db = new Doctrine($this->table,$this->hide_fields);
    }

    public function hideFields(){
        return $this->hide_fields;
    }

    public function table(){
        return $this->table;
    }

}