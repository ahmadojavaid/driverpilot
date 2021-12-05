<?php

namespace App\Http\Controllers\Admin;

use App\DrivingMania;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function createQuestions(Request $request, $id){
        $driving =  DrivingMania::find($id);
        $question = new Question();
        $question->title = $request->title;
        $options = [];
        $i = 1;
        foreach ($request->options as $option){
            $options[$i] = $option;
            $i++;
        }
        $question->type = $request->type;
        $question->options = json_encode($options);
        $question->correct_option = $request->correct_option;
        if ($request->has('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $photo = time().'-'.$request->file('image')->getClientOriginalName();
            $destination =  public_path("Module/$id/Questions/Images");
            $path = $request->file('image')->move($destination, $photo);
            $question->image = $path;
        }
        $driving->questions()->save($question);
        return response()->json(['Message'=>'Question Created Successfully'], 201);
    }
}
