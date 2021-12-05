<?php


namespace App\Helpers;


class validationrules
{

    public static function rules($type=''){
         if ($type == "student"){
             $rules = [
                 'name' => 'required|string|max:255',
                 'surname'=>'required',
                 'email' => 'required|email|max:50|unique:users',
                 'password' => 'required|min:6|confirmed',
                 'postcode' => 'required|min:6',
                 'school_id'=> 'required'
             ];
             return $rules;
         }
         if($type == "parent"){
             $rules = [
             'first_name' => 'required|string|max:255',
             'last_name' => 'required',
             'email' => 'required|email|max:50|unique:users',
             'password' => 'required|min:6|confirmed',
             'postcode' => 'required|min:6',
              'school_id'=> 'required'
             ];
             return $rules;
         }
         if($type == "school"){
             $rules = [
                 'name'=> 'required',
                 'email' =>  'required|email|max:50|unique:users',
                 'password' => 'required|min:6|confirmed'
             ];
             return $rules;
         }
         else{
             return false;
         }

    }

}