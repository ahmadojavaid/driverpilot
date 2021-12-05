<?php

namespace App\Http\Controllers\Student;

use App\DrivingMania;
use App\Events\ActivityWasNoted;
use App\Question;
use App\StudentResult;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;

class QuestionController extends Controller
{


    public function calculateTestResult(Request $request, $modid, $userid){

        $driving = DrivingMania::find($modid);
        $questions = $driving->questions;
        $data = [
             0 => 2,
             1 => '',
             2 => 1,
             3 => 3,
             4 => 2
        ];
        $res = 0;
        $options = 0;
        foreach ($questions as $key => $question){
            if (!empty($data[$key]) && $data[$key] == $question->correct_option){
                $options++;
                $data['status'][] = true;
            }
            if (empty($data[$key]) || $data[$key] == ''){
                $res++;
                $data['status'][] = false;
            }
            else{
                $data['status'][] = false;
            }
        }
        $result = new StudentResult();
        $result->user_id = $userid;
        $result->driving_manias_id = $modid;
        $result->attempted_questions = count($questions) - $res;
        $result->selected_options = json_encode( $data, JSON_FORCE_OBJECT );
        $result->correct_answers = $options;
        $result->practiced_time = '5 Minutes';
        $result->save();
        $notification = [];
        $notification['name'] = $driving->title;
        $notification['time'] = '5 minutes';
        $notification['mod_id'] = $driving->id;
        Event::dispatch(new ActivityWasNoted($notification));
        return $result;
    }
}
