<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function driving_mania(){
        return $this->belongsTo(DrivingMania::class);
    }
}
