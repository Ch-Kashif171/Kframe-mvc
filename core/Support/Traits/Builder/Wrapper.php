<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;
use Core\Support\Collection\Collection;

trait Wrapper
{
    use Hydrate;
    /**
     * @param callable $callback
     * @return array
     */
    protected function wrapMultiple(callable $callback): array|Collection
    {
        // Execute the query and retrieve raw results from the database
        $result = $callback();

        // Filter out hidden attributes as defined in the model's $hidden property
        $result = $this->getResult($result);

        // Automatically load any defined relationships
        $result = $this->hydrates($result);

        // Eager load
        if (property_exists($this, 'with')) {
            $result = $this->eagerLoadRelations($result, $this->with);
        }

        return new Collection($result);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function wrapSingle(callable $callback): mixed
    {
        // Execute the query and retrieve raw results from the database
        $result = $callback();

        // Filter out hidden attributes as defined in the model's $hidden property
        $result = $this->getResult($result);

        // Automatically load any defined relationships
        $result = $this->hydrate($result);

        // Eager load
        if (property_exists($this, 'with')) {
            $result = $this->eagerLoadRelations($result, $this->with);
        }

        return $result;
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function wrapPaginate(callable $callback): mixed
    {
        // Get the pagination result from doctrine
        $pagination = $callback();

        // Process the data portion through the relation pipeline
        $processedData = $this->processPaginationData($pagination['data']);

        // Replace the data with processed data
        $pagination['data'] = $processedData;

        return $pagination;
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function wrapSimplePaginate(callable $callback): mixed
    {
        // Get the simple pagination result from doctrine
        $pagination = $callback();

        // Process the data portion through the relation pipeline
        $processedData = $this->processPaginationData($pagination['simple']['data']);

        // Replace the data with processed data
        $pagination['simple']['data'] = $processedData;

        return $pagination;
    }

    /**
     * @param $models
     * @param $with
     * @return array
     */
    protected function eagerLoadRelations($models, $with): mixed
    {
        if (empty($with) || !$models) {
            return $models;
        }

        // Handle array of models
        if (is_array($models)) {
            return $this->multiRelation($models, $with);
        }

        // Handle single model
        return $this->singleRelation($models, $with);
    }

    /**
     * @param $models
     * @param $with
     * @return mixed
     */
    private function singleRelation($models, $with): mixed
    {
        foreach ($with as $relation) {
            if (method_exists($models, $relation)) {
                $result = $models->$relation();
                if ($result instanceof QueryBuilder) {
                    $models->$relation = $result->get();
                } else {
                    $models->$relation = $result;
                }
            }
        }
        return $models;
    }

    /**
     * @param $models
     * @param $with
     * @return mixed
     */
    private function multiRelation($models, $with): mixed
    {
        foreach ($with as $relation) {
            if (empty($models)) continue;
            // Only support hasMany for now
            $firstModel = $models[0];
            if (!method_exists($firstModel, $relation)) continue;
            $relationQuery = $firstModel->$relation();
            if (!($relationQuery instanceof QueryBuilder)) {
                // fallback to old per-model logic for non-QueryBuilder relations
                foreach ($models as $model) {
                    $result = $model->$relation();
                    $model->$relation = $result;
                }
                continue;
            }
            // Get foreign key and related model class
            $relatedModel = new $relationQuery->modelClass();
            $relatedTable = $relatedModel->table();
            // Try to extract the foreign key from the where clause
            $foreignKey = null;
            if (property_exists($relationQuery->doctrine, 'wheres') && !empty($relationQuery->doctrine->wheres)) {
                if (preg_match('/(\w+)\s*=\s*[\'"]?([^\'"]*)[\'"]?/', $relationQuery->doctrine->wheres, $matches)) {
                    $foreignKey = $matches[1];
                }
            }
            if (!$foreignKey) {
                // fallback: guess from parent table
                $parentTable = method_exists($firstModel, 'table') ? $firstModel->table() : null;
                $foreignKey = $parentTable . '_id';
            }
            // Collect all parent keys
            $parentKey = 'id';
            $parentIds = array_map(fn($m) => $m->$parentKey, $models);
            // Fetch all related records in one query
            $relatedRows = (new QueryBuilder($relatedTable, $relatedModel->hidden, get_class($relatedModel)))
                ->whereIn($foreignKey, $parentIds)
                ->get();
            // Group related records by foreign key
            $grouped = [];
            foreach ($relatedRows as $row) {
                $fk = $row->$foreignKey;
                $grouped[$fk][] = $row;
            }
            // Assign related records to each parent
            foreach ($models as $model) {
                $model->$relation = $grouped[$model->$parentKey] ?? [];
            }
        }
        return $models;
    }

    /**
     * Process pagination data through the relation pipeline
     * @param array $data
     * @return array
     */
    private function processPaginationData($data): array
    {
        // Filter out hidden attributes as defined in the model's $hidden property
        $result = $this->getResult($data);

        // Automatically load any defined relationships
        $result = $this->hydrates($result);

        // Eager load
        if (property_exists($this, 'with')) {
            $result = $this->eagerLoadRelations($result, $this->with);
        }

        return $result;
    }

}