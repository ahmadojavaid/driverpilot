<?php

namespace App\Http\Controllers\Student;

use App\Rating;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected  $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getProfile($id){
        $student = User::find($id);
        return $student;
    }

    public function updateProfile(Request $request, $id){

        try {
            $student = User::find($id);
            $res = Hash::check($request->old_password, $student->password);
            if ($res == false){
                return response()->json(['Message'=> 'Password not Matches with old Password'], 404);
            }
            if ($request->password != $request->confirm_password){
                return response()->json(['Message'=> 'Password Mis Matches'], 404);
            }
            $student->password = Hash::make($request->password);
            $student->phone = $request->phone;
            $student->dob = $request->dob;
            if ($request->has('file')) {
                $extension = $request->file('file')->getClientOriginalExtension();
                $photo = time().'-'.$request->file('file')->getClientOriginalName();
                $destination =  public_path('images/profile');
                $path = $request->file('file')->move($destination, $photo);
                $student->image = $path;
                $student->save();
                return response()->json(['status'=>'200','statusText'=>'Profile Successfully Updated','image url' => $path]);
            }
            $student->save();
        }catch (Exception $exception){
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage(),
                'data' => null,
            ], 403);
        }
    }

    public function getInstructors(){
        $users = User::where('type', 'instructor')->get();
        foreach ($users as $user){
            $user['ratings'] = $user->ratings()->avg('rate');
        }
        return $users;
    }

    public function viewInstructor($id){
        $user = User::where([
            'id' => $id,
            'type' => 'instructor'
        ])->first();
        $user['rating'] = round($user->ratings()->avg('rate'));
        return $user;
    }
    public function saveRating(Request $request, $ins_id){
        $user = User::where([
            ['id', $ins_id],
            ['type', 'instructor']])->first();
        //return $user->ratings()->avg('rate');
        $rating = new Rating();
        $rating->giver_id = auth()->user()->id;
        $rating->review =  $request->review;
        $rating->rate = $request->rate;
        $user->ratings()->save($rating);
        return response(['Message' => 'User Rating saved successfully'], 201);
    }
}
