<?php

use Core\Migrations\Blueprint;
use Core\Migrations\Migrate;

class CreateContactUsTable extends Migrate
{
    public function up()
    {
        Migrate::create('contact_us', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
         Migrate::drop('contact_us');
    }
} 