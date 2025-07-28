<?php
namespace Core\Database;

use Core\Support\Traits\Builder\Builder;
use Core\Support\Traits\Builder\OrmMethods;
use Core\Support\Traits\Builder\Relational;
use Core\Support\Traits\Builder\StaticForwarding;

/**
 * Base ORM Model
 *
 * @property int $id
 */
class BaseModel
{
    use Builder, StaticForwarding, OrmMethods, Relational;
}