<?php


use \CallDoc\Migration\Migration;

class BlackList extends Migration
{
    public function up()  {
        $this->schema->create('black_lists', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id 
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('doctors_id');
            $table->integer('black_listed');
            // Required for Eloquent's created_at and updated_at columns 
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('black_lists');
    }
    
}
