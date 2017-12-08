<?php


use \CallDoc\Migration\Migration;

class Tokens extends Migration
{
    public function up()  {
        $this->schema->create('tokens', function(Illuminate\Database\Schema\Blueprint $table){
            // Auto-increment id 
            $table->increments('id');
            //$table->integer('serial_number');
            $table->integer('user_id');
            $table->string('value');
            $table->timestamp('expiry');
            // Required for Eloquent's created_at and updated_at columns 
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('tokens');
    }
    
}
