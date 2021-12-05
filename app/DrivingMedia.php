<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrivingMedia extends Model
{
    protected $fillable = ['file_name','media_link', 'media_type'];
    public function mania(){
        $this->belongsTo(DrivingMania::class);
    }
}
