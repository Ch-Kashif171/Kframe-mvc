<?php

namespace Core\Support\Traits\Builder;

use Core\Database\QueryBuilder;
use Core\Support\Collection;

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
        $result = self::getResult($result);

        // Automatically load any defined relationships
        $result = self::hydrates($result);

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
        $result = self::getResult($result);

        // Automatically load any defined relationships
        $result = self::hydrate($result);

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
        foreach ($models as $model) {
            foreach ($with as $relation) {
                if (method_exists($model, $relation)) {
                    $result = $model->$relation();
                    if ($result instanceof QueryBuilder) {
                        $model->$relation = $result->get();
                    } else {
                        $model->$relation = $result;
                    }
                }
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