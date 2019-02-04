<?php

namespace Core;

use Core\connection\database;

class Doctrine
{
    public $con;
    public $table;
    public $statement;
    public $where;
    public $fields;
    public $hide_fields;
    public $result;
    public $exception;

    public function __construct($table=null,$hidden_fields=null,$statement=null,$fields = null){
        $this->table  =   $table;
        $this->statement  =   $statement;
        $this->hide_fields = $hidden_fields;
        if(!is_null($fields)){
            $this->fields  =   str_replace($this->hide_fields,'',$fields);
        }else{
            $this->fields  =  $fields;
        }
        $db   =   new database();
        $this->con = $db->connection();
    }

    /**
     * @return mixed
     */
    public function first(){

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);
            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table." ".$array_statement['statement'];

            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table."  ".$array_statement['statement'];
            }

            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);

        }else{
            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table;
            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table;
            }
            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $this->result;
    }

    /**
     * @return array
     */
    public function get(){
        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);
            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table." ".$array_statement['statement'];
            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table." ".$array_statement['statement'];
            }
            $query = $this->con->query($sql) ;
            $this->result = $query->fetchAll(\PDO::FETCH_OBJ);
        }else{

            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table;
            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table;
            }
            $query = $this->con->query($sql) ;
            $this->result = $query->fetchAll(\PDO::FETCH_OBJ);
        }

        return $this->result;

    }

    /**
     * @param $column
     * @return Doctrine
     */
    public function latest($column){
        $query = " order by {$column} DESC";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @return Doctrine
     */
    public function oldest($column){
        $query = " order by {$column} ASC";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @param int $value
     * @return bool
     */
    public function increment($column,$value =1){

        $array_statement = getChildTableAndStatement($this->statement);
        $sql = "SELECT {$column} FROM " . $this->table." ".$array_statement['statement'];

        try {

            $query = $this->con->query($sql);
            $column_value = $query->fetch(\PDO::FETCH_OBJ);
            $increment = $column_value->$column + $value;
            $fields = array(
                "{$column}" => $increment
            );
            $result = $this->update($fields);
            return $result;
        }
        catch (Exception $e){
            $this->exception->errorException($e->getMessage());
        }
    }

    /**
     * @param $column
     * @param int $value
     * @return bool
     */
    public function decrement($column,$value=1){
        $array_statement = getChildTableAndStatement($this->statement);
        $sql = "SELECT {$column} FROM " . $this->table." ".$array_statement['statement'];

        try {
            $query = $this->con->query($sql);
            $column_value = $query->fetch(\PDO::FETCH_OBJ);
            $increment = $column_value->$column - $value;
            $fields = array(
                "{$column}" => $increment
            );
            $result = $this->update($fields);
            return $result;

        }
        catch (Exception $e){
            $this->exception->errorException($e->getMessage());
        }

    }

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column){

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);

            $sql = "SELECT SUM({$column}) as sum FROM " . $this->table." ".$array_statement['statement'];

            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);

        }else{
            $sql = "SELECT SUM({$column}) as sum FROM " . $this->table;
            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $this->result->sum;
    }

    /**
     * @return mixed
     */
    public function count(){

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);

            $sql = "SELECT COUNT(*) as count FROM " . $this->table." ".$array_statement['statement'];

            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);

        }else{
            $sql = "SELECT COUNT(*) as count FROM " . $this->table;
            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $this->result->count;
    }

    /**
     * @param $column
     * @return mixed
     */
    public function max($column){

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);

            $sql = "SELECT MAX({$column}) max FROM " . $this->table." ".$array_statement['statement'];

            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);

        }else{
            $sql = "SELECT MAX({$column}) max FROM " . $this->table;
            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $this->result->max;
    }

    /**
     * @param $column
     * @return mixed
     */
    public function min($column){

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);

            $sql = "SELECT MIN({$column}) min FROM " . $this->table." ".$array_statement['statement'];

            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);

        }else{
            $sql = "SELECT MIN({$column}) min FROM " . $this->table;
            $query = $this->con->query($sql) ;
            $this->result = $query->fetch(\PDO::FETCH_OBJ);
        }
        return $this->result->min;
    }

    /**
     * @param $data
     * @return bool
     */
    public function insert($data){
        $fields = '`' . implode('`, `', array_keys($data)) . '`';
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ({$placeholders})";

        try {
            $exec = $this->con->prepare($sql);
            $result = $exec->execute($data);
            return $result;
        }catch (Exception $e)
        {
            $this->exception->errorException($e->getMessage());
        }


    }

    /**
     * @param $data
     * @return string
     */
    public function insertGetId($data){
        $fields = '`' . implode('`, `', array_keys($data)) . '`';
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ({$placeholders})";

        try {
            $exec = $this->con->prepare($sql);
            $exec->execute($data);
            $last_id = $this->con->lastInsertId();
            return $last_id;
        }catch (Exception $e)
        {
            $this->exception->errorException($e->getMessage());
        }
    }

    /**
     * @param $fields
     * @return Doctrine
     */
    public function select($fields){
        $this->fields = $fields;
        return new Doctrine($this->table,$this->hide_fields,$this->statement,$this->fields);
    }

    /**
     * @param $fields
     * @return bool
     */
    public function update($fields){

        $query = "UPDATE {$this->table} SET ";
        foreach ($fields as $name => $value) {
            $query .= ' '.$name.' = :'.$name.',';
        }
        $query = substr($query, 0, -1);
        $query .= $this->statement;

        try {
            $exec = $this->con->prepare($query);
            $exec->execute($fields);
            $result = $exec->rowCount();
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }
        catch (Exception $e){
            $this->exception->errorException($e->getMessage());
        }

    }

    /**
     * @return bool
     */
    public function delete(){
        $query = "delete from {$this->table} ";
        $query .= $this->statement;

        try {
            $exec = $this->con->prepare($query);
            $result = $exec->execute();
            return $result;
        }
        catch (Exception $e){
            $this->exception->errorException($e->getMessage());
        }
    }

    /**
     * @param $field
     * @param $order
     * @return Doctrine
     */
    public function orderBy($field,$order){
        $query = " order by ".$field.' '.$order;
        $this->statement .= $query;

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $fields
     * @return Doctrine
     */
    public function groupBy($fields){
        $query = " group by ".$fields;
        $this->statement .= $query;

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function having($column,$condition,$value){
        $query = " having {$column} {$condition}  '".$value."' ";
        $this->statement .= $query;

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $limit
     * @return Doctrine
     */
    public function limit($limit){
        $query = " LIMIT {$limit} ";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $offset
     * @return Doctrine
     */
    public function offset($offset){
        $query = " OFFSET {$offset} ";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /*still working on it*/
    /*public function skip($skip){
        $query = " LIMIT {$skip} ";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }*/

    /**
     * @param $take
     * @return Doctrine
     */
    public function take($take){
        $query = " LIMIT {$take} ";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function where($column,$condition,$value){

        if(strpos($this->statement,'WHERE') === false) {
            $query = " WHERE {$column} {$condition} '".$value."' ";

        }else{
            $query = " AND {$column} {$condition} '".$value."' ";
        }

        $this->statement .= $query;

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function orWhere($column,$condition,$value){
        $query = " OR {$column} {$condition} '".$value."' ";
        $this->statement .= $query;

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $data
     * @return Doctrine
     */
    public function where_array($data){
        $count = 1;
        $query = '';
        foreach ($data as $column=> $value){
            if($count == 1){
                $query .= " WHERE ".$column." = '".$value."' ";
            }else{
                $query .= " AND ".$column." = '".$value."' ";
            }
            $count++;
        }

        if ($query != ''){
            $this->statement .= $query;
        }
        //debug($this->statement,'dump');

        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return Doctrine
     */
    public function join($table,$column,$equal,$second_column){
        $query = "INNER JOIN $table ON $column $equal $second_column ";
        $this->statement .= $query;
        $this->statement.="|$this->table|";
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return Doctrine
     */
    public function leftJoin($table,$column,$equal,$second_column){
        $query = "LEFT JOIN $table ON $column $equal $second_column ";
        $this->statement .= $query;
        $this->statement.="|$this->table|";
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $sql
     * @param bool $create
     * @return array|bool
     */
    public function rawQuery($sql,$create = false){

        try {
            $query = $this->con->query($sql);

            if($create){
                $result = true;
            }else{
                $result = $query->fetchAll(\PDO::FETCH_OBJ);
            }
            return $result;
        }
        catch (Exception $e){
            $this->exception->errorException($e->getMessage());
        }
    }

    /**
     * @param $limit
     * @return array
     */
    public function paginate($limit){

        $pagination = array();

        if(isset($_GET['page']) && $_GET['page'] > 2){
            $p_limit = $_GET['page']-1;
            $offset = $p_limit*($limit);
            $page = $_GET['page'];
        } elseif(isset($_GET['page']) && $_GET['page'] == 2){
            $offset = $limit;
            $page = $_GET['page'];
        } elseif(isset($_GET['page']) && $_GET['page'] == 1){
            $offset = 0;
            $page = 1;
        } else{
            $offset = 0;
            $page = 1;
        }

        if(!is_null($this->statement)){
            $array_statement = getChildTableAndStatement($this->statement);
            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table." ".$array_statement['statement']." LIMIT {$limit} OFFSET {$offset} ";
            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table." ".$array_statement['statement']." LIMIT {$limit} OFFSET {$offset} ";
            }
            $query = $this->con->query($sql) ;
            $result = $query->fetchAll(\PDO::FETCH_OBJ);
        }else{

            if(is_null($this->fields)) {
                $columns = $this->get_table_columns_except_some($this->table);
                $sql = "SELECT {$columns} FROM " . $this->table." LIMIT {$limit} OFFSET {$offset} ";
            }else{
                $sql = "SELECT ".$this->fields." FROM " . $this->table." LIMIT {$limit} OFFSET {$offset} ";
            }

            $query = $this->con->query($sql) ;
            $result = $query->fetchAll(\PDO::FETCH_OBJ);
        }

        if(!is_null($this->statement)) {
            $array_statement = getChildTableAndStatement($this->statement);
            $sql_statement = "SELECT count(*) as count FROM " . $this->table." ".$array_statement['statement'];
        }else{
            $sql_statement = "SELECT count(*) as count FROM " . $this->table;
        }
        $count = $this->con->query($sql_statement) ;
        $total = $count->fetch(\PDO::FETCH_OBJ);
        $current_count = count($result);

        if($current_count == 0){
            $from = null;
            $to = null;
            $next_page  = null;
            $current = 1;
            $prev_page = null;
            $last_page = 1;
            $last_page_url = full_path().'?page=1';
        }else{

            $dynamic_total = ceil(($total->count/$limit));
            if ($dynamic_total > $total->count){
                $dynamic_total = $total->count;
            }
            $next =  (int)$page+1;
            if ($next > $dynamic_total){
                $next = (int)$page;
            }
            $next_page  = full_path().'?page='.$next;

            $prev =  (int)$page-1;
            $prev_page  = full_path().'?page='.$prev;

            $current = (int)$page;

            $last_page = ceil(($total->count/$limit));

            $last_page_url = full_path().'?page='.$dynamic_total;

        }

        if($current_count > 0){
            $pagination['data'] = $result;
        }else{
            $pagination['data'] = array();
        }

        $pagination['current_page'] = $current;
        $pagination['first_page_url']= full_path().'?page=1';
        $pagination['last_page']= $last_page;
        $pagination['last_page_url']= $last_page_url;
        $pagination['next_page_url']= $next_page;
        $pagination['path']= full_path();
        $pagination['per_page']= $limit;

        $pagination['prev_page_url']= $prev_page;

        $pagination['total']= (int)$total->count;

        return $pagination;

    }

    public function simplePaginate($limit){
        $pagination['simple'] = $this->paginate($limit);
        return $pagination;

    }

    /**
     * @param $table
     * @return string
     */
    private function get_table_columns_except_some($table){
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".$table."' ";

        $fields = $this->rawQuery($query);

        $columns = '';
        foreach ($fields as $key=> $field) {
            if (!is_null($this->hide_fields)) {
                $hidden_fields = explode(',',$this->hide_fields);
                if(!in_array($field->COLUMN_NAME,$hidden_fields)){
                    $columns .= $field->COLUMN_NAME.',';
                }
            }else{
                $columns .= $field->COLUMN_NAME.',';
            }

        }

        $select_columns = rtrim($columns,',');

        return $select_columns;
    }


}