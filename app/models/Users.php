<?php
namespace App\Models;
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\Database\BaseModel;

class Users extends BaseModel
{
    protected $table = 'users';

    protected $hide_fields = 'password';
}
