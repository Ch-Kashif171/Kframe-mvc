<?php
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\model\BaseModel;
use Core\Facades\DB;

class Users extends BaseModel
{
    public $table = 'users';
    public $hide_fields = 'password';
}
