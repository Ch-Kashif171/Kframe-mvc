<?php

use Core\database_migrations\Migrate;

class Migration extends Migrate
{
    public function up()
    {
        Migrate::create('users',array(
            $this->table->increments('id'),
            $this->table->string('name',500)->nullable(),
            $this->table->string('email',500)->unique(),
            $this->table->string('password'),
            $this->table->timestamps(),
        ));

        // you can add more migration table structure same like above

    }

}