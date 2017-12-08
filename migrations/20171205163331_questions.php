<?php


use \CallDoc\Migration\Migration;

class Questions extends Migration
{
    public function up()  {
        $this->schema->create('questions', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id 
            $table->increments('id');
            //$table->integer('serial_number');
            $table->integer('user_id');
            $table->text('question');
            // Required for Eloquent's created_at and updated_at columns 
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('questions');
    }
   
}
