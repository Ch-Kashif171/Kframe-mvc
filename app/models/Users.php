<?php
namespace App\Models;
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\Database\BaseModel;

class Users extends BaseModel
{
    public $table = 'users';

    public $hide_fields = 'password';
}
