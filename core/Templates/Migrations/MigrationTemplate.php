<?php

use Core\database_migrations\Migrate;

class migrationname extends Migrate
{
    public function up()
    {
         Migrate::create('table_name', [
             $this->table->increments('id'),
             $this->table->timestamps(),
         ]);
    }

    public function down()
    {
         Migrate::drop('table_name');
    }
} 