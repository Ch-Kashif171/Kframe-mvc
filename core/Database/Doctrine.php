<?php

namespace Core\Database;

use Core\Support\Traits\Internal\Queries;
use Exception;
use Whoops\Exception\ErrorException;

class Doctrine
{
    use Queries;
    // Add separate properties for each clause
    protected $joins = '';
    protected $wheres = '';
    protected $groupBy = '';
    protected $having = '';
    protected $orderBy = '';
    protected $limit = '';
    protected $offset = '';

    /**
     * @return mixed
     * @throws ErrorException
     */
    public function first()
    {
        if (is_null($this->fields)) {
            $columns = $this->get_table_columns_except_some($this->table);
        } else {
            $columns = $this->fields;
        }
        $sql = "SELECT {$columns} FROM {$this->table}"
            . $this->joins
            . $this->wheres
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit
            . $this->offset;
        $query = $this->con->query($sql);
        $this->result = $query->fetch(\PDO::FETCH_OBJ);
        return $this->result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws ErrorException
     */
    public function find($id)
    {
        // Support joins and custom select logic, like first()
        if (is_null($this->fields)) {
            $columns = $this->get_table_columns_except_some($this->table);
        } else {
            $columns = $this->fields;
        }
        $sql = "SELECT {$columns} FROM " . $this->table . $this->joins . " WHERE " . $this->table . ".id = " . $id;
        $query = $this->con->query($sql);
        $this->result = $query->fetch(\PDO::FETCH_OBJ);
        return $this->result;
    }

    /**
     * @return array|false
     * @throws ErrorException
     */
    public function get()
    {
        if (is_null($this->fields)) {
            $columns = $this->get_table_columns_except_some($this->table);
        } else {
            $columns = $this->fields;
        }
        $sql = "SELECT {$columns} FROM {$this->table}"
            . $this->joins
            . $this->wheres
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit
            . $this->offset;
        $query = $this->con->query($sql);
        $this->result = $query->fetchAll(\PDO::FETCH_OBJ);
        return $this->result;
    }

    /**
     * @param $column
     * @return Doctrine
     */
    public function latest($column)
    {
        $query = " order by {$column} DESC";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @return Doctrine
     */
    public function oldest($column)
    {
        $query = " order by {$column} ASC";
        $this->statement .= $query;
        return new Doctrine($this->table,$this->hide_fields,$this->statement);
    }

    /**
     * @param $column
     * @param int $value
     * @return bool
     */
    public function increment($column,$value = 1)
    {

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
    public function sum($column)
    {

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
    public function count()
    {

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
    public function max($column)
    {

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
    public function min($column)
    {

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
    public function insert($data)
    {
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
    public function insertGetId($data)
    {
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
    public function select()
    {
        $fields = func_get_args();
        // Convert 'table*' to 'table.*' for Laravel-like syntax
        foreach ($fields as &$field) {
            if (preg_match('/^([a-zA-Z0-9_]+)\*$/', $field, $matches)) {
                $field = $matches[1] . '.*';
            }
        }
        unset($field);
        $this->fields = implode(',', $fields);
        return new Doctrine($this->table,$this->hide_fields,$this->statement,$this->fields);
    }

    /**
     * @param $fields
     * @return bool
     */
    public function update($fields)
    {
        // Fetch the current record(s) using the current where clause
        $current = $this->first();
        if (!$current) {
            return false;
        }

        // Remove unchanged fields (dirty checking)
        foreach ($fields as $name => $value) {
            if (isset($current->$name) && $current->$name == $value) {
                unset($fields[$name]);
            }
        }

        // If nothing changed, skip update
        if (empty($fields)) {
            return true; // No error, nothing to update
        }

        $query = "UPDATE {$this->table} SET ";
        foreach ($fields as $name => $value) {
            $query .= ' '.$name.' = :'.$name.',';
        }
        $query = substr($query, 0, -1);
        // Use $this->wheres for the WHERE clause
        $query .= $this->wheres;

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
    public function delete()
    {
        $query = "DELETE FROM {$this->table}";
        $query .= $this->wheres;

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
    public function orderBy($field, $order = 'ASC'): self
    {
        $this->orderBy = " ORDER BY {$field} {$order}";
        return $this;
    }

    public function orderByDesc($field, $order = 'DESC'): self
    {
        $this->orderBy = " ORDER BY {$field} DESC";
        return $this;
    }

    /**
     * @param $fields
     * @return Doctrine
     */
    public function groupBy($fields): self
    {
        $this->groupBy = " GROUP BY {$fields}";
        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function having($column,$condition,$value): self
    {
        $this->having = " HAVING {$column} {$condition} '" . addslashes($value) . "' ";
        return $this;
    }

    /**
     * @param $limit
     * @return Doctrine
     */
    public function limit($limit): self
    {
        $this->limit = " LIMIT {$limit} ";
        return $this;
    }

    /**
     * @param $offset
     * @return Doctrine
     */
    public function offset($offset): self
    {
        $this->offset = " OFFSET {$offset} ";
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
    public function take($take): self
    {
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
    public function where($column, $condition, $value): self
    {
        if ($this->wheres === '') {
            $this->wheres = " WHERE {$column} {$condition} '" . addslashes($value) . "' ";
        } else {
            $this->wheres .= " AND {$column} {$condition} '" . addslashes($value) . "' ";
        }
        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @return Doctrine
     */
    public function orWhere($column, $condition, $value): self
    {
        // Only strip prefix if there are no joins
        if (empty($this->joins) && strpos($column, '.') !== false) {
            list(, $col) = explode('.', $column, 2);
            $column = $col;
        }
        if ($this->wheres === '') {
            $this->wheres = " WHERE {$column} {$condition} '" . addslashes($value) . "' ";
        } else {
            $this->wheres .= " OR {$column} {$condition} '" . addslashes($value) . "' ";
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
    public function join($table,$column,$equal,$second_column): self
    {
        $this->joins .= " INNER JOIN $table ON $column $equal $second_column ";
        return $this;
    }

    /**
     * @param $table
     * @param $column
     * @param $equal
     * @param $second_column
     * @return Doctrine
     */
    public function leftJoin($table,$column,$equal,$second_column): self
    {
        $this->joins .= " LEFT JOIN $table ON $column $equal $second_column ";
        return $this;
    }

    /**
     * @param $limit
     * @return array
     */
    public function paginate($limit)
    {
        $pagination = array();

        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        // Get total count first
        $sql_statement = "SELECT count(*) as count FROM {$this->table}"
            . $this->joins
            . $this->wheres
            . $this->groupBy
            . $this->having;
        $count = $this->con->query($sql_statement);
        $total = $count->fetch(\PDO::FETCH_OBJ);
        $totalCount = (int)$total->count;
        $lastPage = (int) ceil($totalCount / $limit);

        // If requested page is greater than last page, set to last page
        if ($page > $lastPage && $lastPage > 0) {
            $page = $lastPage;
        }
        $offset = ($page - 1) * $limit;

        // Get data for current page
        if (is_null($this->fields)) {
            $columns = $this->get_table_columns_except_some($this->table);
        } else {
            $columns = $this->fields;
        }
        $sql = "SELECT {$columns} FROM {$this->table}"
            . $this->joins
            . $this->wheres
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . " LIMIT {$limit} OFFSET {$offset} ";
        $query = $this->con->query($sql);
        $result = $query->fetchAll(\PDO::FETCH_OBJ);

        $from = $totalCount > 0 ? $offset + 1 : 0;
        $to = $totalCount > 0 ? min($offset + $limit, $totalCount) : 0;

        $baseUrl = full_path();
        $pagination['data'] = $result;
        $pagination['current_page'] = $page;
        $pagination['per_page'] = $limit;
        $pagination['total'] = $totalCount;
        $pagination['last_page'] = $lastPage;
        $pagination['from'] = $from;
        $pagination['to'] = $to;
        $pagination['first_page_url'] = $baseUrl . '?page=1';
        $pagination['last_page_url'] = $baseUrl . '?page=' . $lastPage;
        $pagination['next_page_url'] = $page < $lastPage ? $baseUrl . '?page=' . ($page + 1) : null;
        $pagination['prev_page_url'] = $page > 1 ? $baseUrl . '?page=' . ($page - 1) : null;
        $pagination['path'] = $baseUrl;

        return $pagination;
    }

    public function simplePaginate($limit)
    {
        $pagination['simple'] = $this->paginate($limit);
        return $pagination;

    }

    /**
     * Pluck a single column's values from the result set.
     * @param string $column
     * @return array
     */
    public function pluck(string $column): array
    {
        $results = $this->get();
        return array_map(function($item) use ($column) {
            return $item->$column ?? null;
        }, $results);
    }

    /**
     * Check if any record exists for the current query.
     * @return bool
     */
    public function exists(): bool
    {
        $result = $this->limit(1)->first();
        return $result !== false && $result !== null;
    }

    /**
     * Get the first result or throw an exception if not found.
     * @return mixed
     * @throws Exception
     */
    public function firstOrFail()
    {
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
    public function create(array $data)
    {
        $id = $this->insertGetId($data);
        return $this->find($id);
    }

    /**
     * Update an existing record or create a new one.
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values)
    {
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
    public function whereIn($column, array $values): self
    {
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
    public function whereNull($column): self
    {
        $query = " WHERE {$column} IS NULL";
        $this->statement .= $query;
        return $this;
    }

    /**
     * Add a whereNotNull clause to the query.
     * @param string $column
     * @return $this
     */
    public function whereNotNull($column): self
    {
        $query = " WHERE {$column} IS NOT NULL";
        $this->statement .= $query;
        return $this;
    }

}