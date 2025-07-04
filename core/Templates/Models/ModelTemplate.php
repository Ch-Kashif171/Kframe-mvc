<?php

namespace App\Models;

defined('root_path') OR exit("Sorry! No direct script access allowed ");

use Core\Database\BaseModel;

class modelname extends BaseModel {

 /**
  * Enter $table name: mandatory -> e.g protected table = 'tests';
  * Enter$hide_fields: Optional -> e.g protected $hide_fields = 'email,password';
  *
  */

  protected $table = '';
  protected $hide_fields = '';



}
