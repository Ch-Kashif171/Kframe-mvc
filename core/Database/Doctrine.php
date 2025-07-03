<?php

namespace Core\Database;

use Core\Database\connection\database;
use Exception;
use Whoops\Exception\ErrorException;

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

    public function __construct($table = null, $hidden_fields = null, $statement = null, $fields = null){
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


    public function find($id){

        if(is_null($this->fields)) {
            $columns = $this->get_table_columns_except_some($this->table);
            $sql = "SELECT {$columns} FROM " . $this->table." WHERE id = ".$id;

        }else{
            $sql = "SELECT ".$this->fields." FROM " . $this->table." WHERE id = ".$id;
        }

        $query = $this->con->query($sql) ;
        $this->result = $query->fetch(\PDO::FETCH_OBJ);

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
    public function increment($column,$value = 1){

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
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * @param $column
     * @param int $value
     * @return bool
     */
    public function decrement($column, $value = 1)
    {
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
            throw new ErrorException($e->getMessage());
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
     * @throws ErrorException
     */
    public function insert($data) {
        $fields = '`' . implode('`, `', array_keys($data)) . '`';
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($fields) VALUES ({$placeholders})";

        try {
            return $this->con->prepare($sql)->execute($data);
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
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
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
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
            }
            return false;
        }
        catch (Exception $e) {
            throw new ErrorException($e->getMessage());
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
            $delete = $exec->rowCount();
            if ($delete) {
                return true;
            }
            return false;
        }
        catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * @param $field
     * @param $order
     * @return Doctrine
     */
    public function orderBy($field, $order): self
    {
        $query = " ORDER BY ".$field.' '.$order;
        $this->statement .= $query;

        return $this;
    }

    public function orderByDesc($field, $order = 'DESC'): self
    {
        $query = " ORDER BY ".$field.' '.$order;
        $this->statement .= $query;

        return $this;
    }

    /**
     * @param $fields
     * @return Doctrine
     */
    public function groupBy($fields): self {
        $query = " GROUP BY ".$fields;
        $this->statement .= $query;

        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function having($column,$condition,$value): self {
        $query = " HAVING {$column} {$condition}  '".$value."' ";
        $this->statement .= $query;

        return $this;
    }

    /**
     * @param $limit
     * @return Doctrine
     */
    public function limit($limit): self {
        $query = " LIMIT {$limit} ";
        $this->statement .= $query;
        return $this;
    }

    /**
     * @param $offset
     * @return Doctrine
     */
    public function offset($offset): self {
        $query = " OFFSET {$offset} ";
        $this->statement .= $query;
        return $this;
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
    public function take($take): self {
        $query = " LIMIT {$take} ";
        $this->statement .= $query;
        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function where($column,$condition,$value): self {

        if(!str_contains($this->statement, 'WHERE')) {
            $query = " WHERE {$column} {$condition} '".$value."' ";

        }else{
            $query = " AND {$column} {$condition} '".$value."' ";
        }

        $this->statement .= $query;

        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function orWhere($column,$condition,$value): self {
        $query = " OR {$column} {$condition} '".$value."' ";
        $this->statement .= $query;

        return $this;
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

        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return Doctrine
     */
    public function join($table,$column,$equal,$second_column): self {
        $query = "INNER JOIN $table ON $column $equal $second_column ";
        $this->statement .= $query;
        $this->statement.="|$this->table|";
        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return Doctrine
     */
    public function leftJoin($table,$column,$equal,$second_column): self {
        $query = "LEFT JOIN $table ON $column $equal $second_column ";
        $this->statement .= $query;
        $this->statement.="|$this->table|";
        return $this;
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
            throw new ErrorException($e->getMessage());
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

    /**
     * Pluck a single column's values from the result set.
     * @param string $column
     * @return array
     */
    public function pluck(string $column): array {
        $results = $this->get();
        return array_map(function($item) use ($column) {
            return $item->$column ?? null;
        }, $results);
    }

    /**
     * Check if any record exists for the current query.
     * @return bool
     */
    public function exists(): bool {
        return $this->count() > 0;
    }

    /**
     * Get the first result or throw an exception if not found.
     * @return mixed
     * @throws Exception
     */
    public function firstOrFail() {
        $result = $this->first();
        if (!$result) {
            throw new Exception("No record found.");
        }
        return $result;
    }

    /**
     * Create a new record and return it.
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        $id = $this->insertGetId($data);
        return $this->find($id);
    }

    /**
     * Update an existing record or create a new one.
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values) {
        $query = $this;
        foreach ($attributes as $key => $value) {
            $query = $query->where($key, '=', $value);
        }
        $record = $query->first();
        if ($record) {
            $this->update($values);
            return $this->find($record->id);
        } else {
            return $this->create(array_merge($attributes, $values));
        }
    }

    /**
     * Add a whereIn clause to the query.
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn($column, array $values): self {
        $in = implode(",", array_map(function($v) { return "'".addslashes($v)."'"; }, $values));
        $query = " WHERE {$column} IN ({$in})";
        $this->statement .= $query;
        return $this;
    }

    /**
     * Add a whereNull clause to the query.
     * @param string $column
     * @return $this
     */
    public function whereNull($column): self {
        $query = " WHERE {$column} IS NULL";
        $this->statement .= $query;
        return $this;
    }

    /**
     * Add a whereNotNull clause to the query.
     * @param string $column
     * @return $this
     */
    public function whereNotNull($column): self {
        $query = " WHERE {$column} IS NOT NULL";
        $this->statement .= $query;
        return $this;
    }

}