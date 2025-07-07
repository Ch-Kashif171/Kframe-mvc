<?php

namespace Core\Migrations;

class Blueprint
{
    public $statement = '';
    public $columns = [];
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
        $this->columns[] = new Blueprint($this->statement);
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
        $this->columns[] = new Blueprint($this->statement);
        return $this;
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
            $allow .= " '".$all."' ,";
        }
       $allow = rtrim($allow,',');
        $this->statement = " {$column} ENUM({$allow}) ";
        $this->columns[] = new Blueprint($this->statement);
        return $this;
    }

    /**
     * @param $column
     * @return Blueprint
     */
    public function text($column)
    {
        $this->statement = " {$column} text ";
        $this->columns[] = new Blueprint($this->statement);
        return $this;
    }

    /**
     * @param $column
     * @param int $length
     * @return Blueprint
     */
    public function integer($column,$length = 11)
    {
        $this->statement = " {$column} INT({$length}) ";
        $this->columns[] = new Blueprint($this->statement);
        return $this;
    }

    /**
     * @param $column
     * @return Blueprint
     */
    public function dateTime($column)
    {
        $this->statement = " {$column} DATETIME ";
        $this->columns[] = new Blueprint($this->statement);
        return $this;
    }

    /**
     * @return Blueprint
     */
    public function unique()
    {
        if (!empty($this->columns)) {
            $last = count($this->columns) - 1;
            $this->columns[$last]->statement .= " UNIQUE ";
        }
        return $this;
    }

    /**
     * @return Blueprint
     */
    public function nullable()
    {
        if (!empty($this->columns)) {
            $last = count($this->columns) - 1;
            $this->columns[$last]->statement .= " NULL ";
        }
        return $this;
    }

    /**
     * @return Blueprint
     */
    public function timestamps(){
        $this->statement =  ' created_at timestamp, updated_at timestamp';
        $this->columns[] = new Blueprint($this->statement);
        return $this;
    }

}

return new Blueprint();