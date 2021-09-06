<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
   // public function calculator(){
   //   $collection=collect(['5','2','1','50'])->map(function($name){
   //    //   return $name;
   //    foreach($name as $n){
   //      return $n;
   //    }
   //   });
   //   return $collection;
   // }
   public function calculator(){
      $collection=collect(['5','2','1','50'])->map(function($pre)
      {
         $new=$pre+200;
         return $new;
      });
      foreach($collection as $name)
      {
         echo $name. " ";
      }
    }
    public function register(Request $request)
    {
      //   $this->validate($request, [
      //       'fullName'=>'required|string|between:3,15',
      //       'email'=>'required|email|unique:users',
      //       'password'=>'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
      //       'mobile'=>'required|digits:10',
      //       ]);
        $user = new User([
            'name'=> $request->input('name'),
            'email'=> $request->input('email'),
            'password'=> bcrypt($request->input('password')),
                
        ]);
        $userMailPresent=User::where('email',$request->input('email'))->value('email');
        if($userMailPresent){
            return response()->json(['Alert'=>"Email Already Taken"]);
        }
        $user->save();
        return response()->json(['message'=>'Successfully Created user'],201);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
        }catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'],500);
        }      
        return response()->json(['token' => $token], 200);
      
       
    }
 
}
