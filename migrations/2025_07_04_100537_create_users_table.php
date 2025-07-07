<?php

use Core\database_migrations\Migrate;

class CreateUsersTable extends Migrate
{
    public function up()
    {
         Migrate::create('users', [
             $this->table->increments('id'),
             $this->table->string('name')->nullable(),
             $this->table->string('email')->unique(),
             $this->table->string('password'),
             $this->table->timestamps(),
         ]);
    }

    public function down()
    {
         Migrate::drop('users');
    }
} 