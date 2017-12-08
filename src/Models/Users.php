<?php

namespace CallDoc\Models;

use Illuminate\Database\Eloquent\Model;


class Users extends Model {

    /**
     * set up the users table
     * 
     * @var $table
     */
    protected $table = 'users';


    /**
     * Questions Relationship
     * return every question that is created by users
     * 
     * @return object
     */
    public function questions()
    {
        return $this->hasMany('Questions');
    }


    public function answers()
    {
        return $this->hasMany('Answers');
    }


    public function consults()
    {
        return $this->hasMany('Consults');
    }


    
}