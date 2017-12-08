<?php


use \CallDoc\Migration\Migration;

class Users extends Migration
{
   
    public function up()  {
        $this->schema->create('users', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id 
            $table->increments('id');
            //$table->integer('serial_number');
            $table->string('email');
            $table->string('password');
            $table->string('name');
            $table->integer('role_id');
            // Required for Eloquent's created_at and updated_at columns 
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('users');
    }
}
