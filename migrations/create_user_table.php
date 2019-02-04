<?php
namespace migrations;

use Core\database_migrations\Migrate;

class Migration extends Migrate
{
    public function up()
    {
        Migrate::create('users', function ($table) {
            $table->increments('id');
            $table->string('name',500);
            $table->string('email',500)->unique();
            $table->string('password');
            $table->enum('account',['free','premium'])->nullable();
            $table->timestamps();
        });
    }

}