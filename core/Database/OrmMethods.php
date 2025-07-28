<?php
namespace Core\Database;

use Core\Support\DB;

/**
 * Trait OrmMethods
 * Provides model hydration helpers for ORM.
 */
trait OrmMethods
{
    /**
     * Hydrate a stdClass or array as a model instance
     */
    public static function hydrate($data)
    {
        if (is_null($data)) return null;
        $model = new static();
        foreach ((array)$data as $key => $value) {
            $model->$key = $value; // This will use __set and store in $attributes
        }
        return $model;
    }

    /**
     * Hydrate an array of stdClass/array as model instances
     */
    public static function hydrateMany($rows)
    {
        $models = [];
        foreach ($rows as $row) {
            $models[] = static::hydrate($row);
        }
        return $models;
    }

    /**
     * Define a hasOne relationship.
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on related model
     * @param string $localKey Local key on this model
     * @return QueryBuilder
     */
    public function hasOne($related, $foreignKey, $localKey = 'id')
    {
        $instance = new $related();
        return (new QueryBuilder($instance->table, $instance->hidden))
            ->where($foreignKey, '=', $this->$localKey);
    }

    /**
     * Define a hasMany relationship.
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on related model
     * @param string $localKey Local key on this model
     * @return QueryBuilder
     */
    public function hasMany($related, $foreignKey, $localKey = 'id')
    {
        $instance = new $related();
        return (new QueryBuilder($instance->table, $instance->hidden))
            ->where($foreignKey, '=', $this->$localKey);
    }

    /**
     * Define a belongsTo relationship.
     * @param string $related Related model class
     * @param string $foreignKey Foreign key on this model
     * @param string $ownerKey Key on related model
     * @return QueryBuilder
     */
    public function belongsTo($related, $foreignKey, $ownerKey = 'id')
    {
        $instance = new $related();
        return (new QueryBuilder($instance->table, $instance->hidden))
            ->where($ownerKey, '=', $this->$foreignKey);
    }

    /**
     * Define a belongsToMany relationship (pivot table).
     * @param string $related Related model class
     * @param string $pivot Pivot table name
     * @param string $foreignPivotKey Foreign key on pivot table for this model
     * @param string $relatedPivotKey Foreign key on pivot table for related model
     * @param string $localKey Local key on this model
     * @param string $relatedKey Local key on related model
     * @return array Array of related model instances
     */
    public function belongsToMany($related, $pivot, $foreignPivotKey, $relatedPivotKey, $localKey = 'id', $relatedKey = 'id')
    {
        $instance = new $related();
        $db = new DB();
        $pivotRows = $db::table($pivot)->where($foreignPivotKey, '=', $this->$localKey)->get();
        $relatedIds = array_map(function($row) use ($relatedPivotKey) { return $row->$relatedPivotKey; }, $pivotRows);
        if (empty($relatedIds)) return [];
        return (new QueryBuilder($instance->table, $instance->hidden))
            ->whereIn($relatedKey, $relatedIds)
            ->get();
    }
} 