<?php


use \CallDoc\Migration\Migration;

class Answers extends Migration
{
    public function up()  {
        $this->schema->create('answers', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id 
            $table->increments('id');
            $table->integer('question_id');
            $table->integer('doctors_id');
            $table->string('answer');
            // Required for Eloquent's created_at and updated_at columns 
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('answers');
    }
    
}
