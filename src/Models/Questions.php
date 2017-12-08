<?php

namespace CallDoc\Models;

use Illuminate\Database\Eloquent\Model;


class Questions extends Model {

    protected $table = 'questions';



    public function answers()
    {
        return $this->hasMany('Answers');
    }
}