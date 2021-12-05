<?php

namespace App;

use App\Events\ActivityWasNoted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Rest omitted for brevity

    protected $fillable = ['name', 'email', 'password'];
    const ADMIN_TYPE = 'admin';
    const STUDENT_TYPE = 'student';
    const INSTRUCTOR_TYPE = 'instructor';

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     *
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin(){
        return $this->type === self::ADMIN_TYPE;
    }
    public function isStudent(){
        return $this->type === self::STUDENT_TYPE;
    }
    public function isInstructor(){
        return $this->type === self::INSTRUCTOR_TYPE;
    }

    public static function createRecord($type, $request){
        $user = new self();
        if($type=="student") {
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->postcode = $request->postcode;
            $user->school_id = $request->school_id;
            $user->type = $type;
            $user->verification_token = Str::random(20);
            $user->save();
        }
        if ($type == "parent") {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->postcode = $request->postcode;
            $user->type = $type;
            $user->verification_token = Str::random(20);
            $user->save();
        }
        if ($type == "school"){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->type = $type;
            $user->password = Hash::make($request->password);
            $user->school_creator_id = auth()->user()->id;
            $user->verification_token = Str::random(20);
            $user->save();
        }

        return $user;
    }
    public function topics(){
        return $this->hasMany(Topic::class);
    }
    public function driving_mania(){
        return $this->hasMany(DrivingMania::class);
    }
    public function user_activities(){
        return $this->hasMany(UserActivity::class);
    }
    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function getRatingAttribute()
    {
        return $this->ratings()->avg('rate');
    }


}