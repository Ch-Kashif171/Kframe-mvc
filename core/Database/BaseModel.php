<?php
namespace Core\Database;

use Core\Support\Traits\Builder\QueryBuilder;
use Core\Support\Traits\Builder\StaticForwarding;

class BaseModel
{
    use QueryBuilder, StaticForwarding;
}