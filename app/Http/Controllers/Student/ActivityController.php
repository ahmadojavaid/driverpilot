<?php

namespace App\Http\Controllers\Student;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function getActivity($userid){
        return User::find($userid)->user_activities;
    }
}
