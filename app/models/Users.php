<?php
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\Database\BaseModel;
use Core\Support\DB;

class Users extends BaseModel
{
    public $table = 'users';
    public $hide_fields = 'password';
}
