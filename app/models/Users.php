<?php
namespace App\Models;

use Core\Database\BaseModel;

class Users extends BaseModel
{
    protected $table = 'users';

    protected $hide_fields = 'password';
}
