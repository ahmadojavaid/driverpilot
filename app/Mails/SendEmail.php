<?php


namespace App\Mails;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use Exception;

class SendEmail
{

    public static function sendVerificationEmail($user){

        Mail::send('emails.verify', ['user' => $user], function ($m) use ($user) {
            $m->from('bcs.12.433@gmail.com', 'Driver Inc');
            $m->to($user->email, $user->name)->subject('Verify Email');
        });
    }

    public static function sendPasswordVerificationEmail($mail){
            $data = array();
            $user = User::where('email', $mail)->first();
            $password = Str::random(5);
            $user->password = Hash::make($password);
            $user->save();
            $data['email'] = $mail;
            $data['password'] = $password;
            Mail::send('emails.password_verification', ['data'=> $data],function ($m) use ($data) {
                $m->from('bcs.12.43@gmail.com', 'Your Application');
                $m->to($data['email'])->subject('Your Reminder!');
            });
    }

}