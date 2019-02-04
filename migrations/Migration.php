<?php
namespace migrations;

use Core\database_migrations\Migrate;

class Migration extends Migrate
{
    public function up()
    {
        Migrate::create_old('users',array(
            $this->table->increments('id'),
            $this->table->string('name',500),
            $this->table->string('email',500)->unique(),
            $this->table->string('password'),
            $this->table->enum('account',['free','premium'])->nullable(),
            $this->table->timestamps(),
        ));

        Migrate::create_old('comment',array(
            $this->table->increments('id'),
            $this->table->integer('user_id'),
            $this->table->text('comment'),
            $this->table->timestamps(),
        ));

        Migrate::create_old('user_comments',array(
            $this->table->increments('id'),
            $this->table->integer('user_id'),
            $this->table->integer('comment_id'),
            $this->table->timestamps(),
        ));

    }

}