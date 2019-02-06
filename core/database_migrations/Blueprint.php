<?php

namespace Core\database_migrations;

class Blueprint
{
    public $statement = '';
    public function __construct($statement = '')
    {
        $this->statement = $statement;

    }

    /**
     * @param $column
     * @return Blueprint
     */
    public function increments($column)
    {
        $this->statement = " {$column} INT NOT NULL AUTO_INCREMENT, primary key ({$column}) ";
        return $this;
    }

    /**
     * @param $column
     * @param int $length
     * @return Blueprint
     */
    public function string($column,$length = 255)
    {
        $this->statement = " {$column} VARCHAR({$length}) ";
        return new Blueprint($this->statement);
    }

    /**
     * @param $column
     * @param $allowed
     * @return Blueprint
     */
    public function enum($column,$allowed)
    {
        $allow = '';
        foreach ($allowed as $all){
            $allow .= " '".$all."' ".",";
        }
       $allow = rtrim($allow,',');
        $this->statement = " {$column} ENUM({$allow}) ";
        return new Blueprint($this->statement);
    }

    /**
     * @param $column
     * @return Blueprint
     */
    public function text($column)
    {
        $this->statement = " {$column} text ";
        return new Blueprint($this->statement);
    }

    /**
     * @param $column
     * @param int $length
     * @return Blueprint
     */
    public function integer($column,$length = 11)
    {
        $this->statement = " {$column} INT({$length}) ";
        return new Blueprint($this->statement);
    }

    /**
     * @param $column
     * @return Blueprint
     */
    public function dateTime($column)
    {
        $this->statement = " {$column} DATETIME ";
        return new Blueprint($this->statement);
    }

    /**
     * @return Blueprint
     */
    public function unique()
    {
        $this->statement .= " UNIQUE ";
        return new Blueprint($this->statement);
    }

    /**
     * @return Blueprint
     */
    public function nullable()
    {
        $this->statement .= " NULL ";
        return new Blueprint($this->statement);
    }

    /**
     * @return Blueprint
     */
    public function timestamps(){
        $this->statement =  ' created_at timestamp, updated_at timestamp';
        return new Blueprint($this->statement);
    }

}

return new Blueprint();