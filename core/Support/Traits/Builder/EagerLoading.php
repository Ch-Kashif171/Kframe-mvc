<?php

namespace Core\Support\Traits\Builder;

trait EagerLoading
{

    /**
     * @param $relations
     * @return $this
     */
    public function with($relations): static
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    /**
     * Filter by existence of a relation (has)
     * @throws \Exception
     */
    public function has($relation): static
    {
        [$relatedTable, $parentTable, $foreignKey, $relatedWhere] = $this->resolveRelationMeta($relation);
        $this->addExistsClause($relatedTable, $parentTable, $foreignKey);
        return $this;
    }

    /**
     * Filter by existence of a relation with additional constraints (whereHas)
     * @throws \Exception
     */
    public function whereHas($relation, $callback = null): static
    {
        [$relatedTable, $parentTable, $foreignKey, $relatedWhere] = $this->resolveRelationMeta($relation, $callback);
        $this->addExistsClause($relatedTable, $parentTable, $foreignKey, $relatedWhere);
        return $this;
    }

    /**
     * Eager load and filter by a relation in one call
     * @param $relation
     * @param $callback
     * @return $this
     * @throws \Exception
     */
    public function withWhereHas($relation, $callback = null): static
    {
        $this->whereHas($relation, $callback);
        if (!isset($this->with)) {
            $this->with = [];
        }
        if (!in_array($relation, $this->with, true)) {
            $this->with[] = $relation;
        }
        return $this;
    }

    /**
     * Resolve relation meta: table, parent table, foreign key, related where clause
     * @param string $relation
     * @param callable|null $callback
     * @return array [relatedTable, parentTable, foreignKey, relatedWhere]
     * @throws \Exception
     */
    private function resolveRelationMeta(string $relation, callable $callback = null): array
    {
        $parentModel = new $this->modelClass();
        if (!method_exists($parentModel, $relation)) {
            throw new \Exception("Relation $relation does not exist on " . $this->modelClass);
        }
        $relatedQuery = $parentModel->$relation();
        if (!($relatedQuery instanceof self)) {
            throw new \Exception("Relation $relation must return a QueryBuilder");
        }
        $relatedModel = new $relatedQuery->modelClass();
        $relatedTable = $relatedModel->table();
        $parentTable = $parentModel->table();
        $foreignKey = null;
        if (property_exists($relatedQuery->doctrine, 'wheres') && !empty($relatedQuery->doctrine->wheres)) {
            if (preg_match('/(\w+)\s*=\s*[\'"]?([^\'"]*)[\'"]?/', $relatedQuery->doctrine->wheres, $matches)) {
                $foreignKey = $matches[1];
            }
        }
        if (!$foreignKey) {
            $foreignKey = $parentTable . '_id';
        }
        // For whereHas/withWhereHas, allow callback to add more where clauses
        $relatedWhere = '';
        if ($callback) {
            $newRelatedQuery = new self($relatedTable, $relatedModel->hideFields(), $relatedQuery->modelClass);
            $callback($newRelatedQuery);
            $relatedWhere = property_exists($newRelatedQuery->doctrine, 'wheres') ? $newRelatedQuery->doctrine->wheres : '';
            if (!empty($relatedWhere)) {
                $relatedWhere = trim($relatedWhere);
                if (str_starts_with($relatedWhere, 'WHERE')) {
                    $relatedWhere = substr($relatedWhere, 5);
                    $relatedWhere = ' AND ' . trim($relatedWhere);
                } else {
                    $relatedWhere = ' AND ' . $relatedWhere;
                }
            }
        }
        return [$relatedTable, $parentTable, $foreignKey, $relatedWhere];
    }

    /**
     * Add EXISTS clause to the parent query
     */
    private function addExistsClause($relatedTable, $parentTable, $foreignKey, $relatedWhere = ''): void
    {
        $exists = "EXISTS (SELECT 1 FROM $relatedTable WHERE $relatedTable.$foreignKey = $parentTable.id$relatedWhere)";
        if (empty($this->doctrine->wheres)) {
            $this->doctrine->wheres = " WHERE $exists";
        } else {
            $this->doctrine->wheres .= " AND $exists";
        }
    }

}