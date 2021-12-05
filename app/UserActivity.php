<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities';
    protected $fillable = ['name'];

    public $timestamps =false;

    public function user(){
        $this->belongsTo(User::class);
    }
}
