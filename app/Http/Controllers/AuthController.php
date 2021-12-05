<?php

namespace App\Http\Controllers;

use App\Mails\SendEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\validationrules as validations;
use Illuminate\Support\Str;
use Mail;
use Exception;



class AuthController extends Controller
{

    protected  $rules;
    protected  $model;
    public function __construct(validations $rules, User $user)
    {
        $this->model = $user;
        $this->rules = $rules;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = $this->user();
        return $this->respondWithToken($token, $user);
    }

    public function  register(Request $request)
    {
        $type = $request->type;
        if (empty($type)){
            return response()->json(['Please specify signup type'], 403);
        }
        $rules =  $this->rules::rules($type);
        if ($rules==false){
            return response()->json(['Message' => 'Something Zig Zagged'], 404);
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = $this->model::createRecord($type, $request);
        $x = SendEmail::sendVerificationEmail($user);
        $token = JWTAuth::fromUser($user);
        return response()->json(['message'=>"Check inbox to confirm your email $x"],201);
    }


    protected function respondWithToken($token, $user='')
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function logout()
    {
    try{
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
        }catch (JWTException $exception){
        return $exception->getCode();
     }
    }

    public function user()
    {
        return auth()->user();
    }

    public function checkConfirmation($id='', $token=''){

        try{
        $user = User::find($id);
        $time  = $user->created_at->format('H:i:s');
        $currentTime = Carbon::now();
        if($currentTime->diffInMinutes($time) >= 60) {
            $user->verification_token  = Str::random(20);
            $user->save();
            SendEmail::sendVerificationEmail($user);
            return response()->json(['Message' => 'Confirmation time exceed please re-confirm your email'], 200);
        }
        else{
            if ($user->verification_token == $token){
                $user->email_verified_at = Carbon::now();
                $user->verification_token = null;
                $user->save();
                return response()->json(['Message'=>'Your Account is verified'], 200);
            }
        }
        }catch (Exception $exception){
            return response()->json(['Message'=>'Something is not working'], 403);
        }
    }

    public function recorverPassword(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
             SendEmail::sendPasswordVerificationEmail($request->email);

        }catch (Exception $e){
            return response()->json(['message'=> $e->getMessage()], 403);
        }
        }

}
