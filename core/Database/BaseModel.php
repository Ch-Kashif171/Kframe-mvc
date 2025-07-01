<?php
namespace Core\Database;

use Core\Database\Doctrine;

class BaseModel
{
    public $table;
    public $hide_fields;
    public $db;

    public function __construct() {
        /*if table not define in model, then by default, model
         *name should be then table name*/
        if(is_null($this->table)) {
            $this->table = getTable(static::class);
        }
        $this->db = new Doctrine($this->table,$this->hide_fields);
    }

    public function hideFields(){
        return $this->hide_fields;
    }

    public function table(){
        return $this->table;
    }

    public static function all()
    {
        $instance = new static();
        return $instance->db->get();
    }

    public static function find($id)
    {
        $instance = new static();
        return $instance->db->find($id);
    }

    public static function paginate($limit)
    {
        $instance = new static();
        return $instance->db->paginate($limit);
    }

    public static function insert($data)
    {
        $instance = new static();
        return $instance->db->insert($data);
    }
}