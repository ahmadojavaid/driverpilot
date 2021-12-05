<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function user(){
        $this->belongsTo(User::class);
    }
}
