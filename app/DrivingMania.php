<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrivingMania extends Model
{

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function media(){
        return $this->hasMany(DrivingMedia::class, 'driving_manias_id');
    }
    public function questions(){
        return $this->hasMany(Question::class, 'driving_manias_id');
    }

}
