<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
class AuthController extends Controller
{
 
 public function __construct()
 {
  $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
}
    public function register(Request $request){
    
      $validated = $request->validate([
      'name'=>'required |string',
      'email'=>'required|string|email|unique:users',
      'password'=>'required|min:6',
      'role'=>'required'
     
    ]);
      
     
     $validated['password']=bcrypt($validated['password']); 
      
      $user =User::create($validated);
      
      
      return response()->json(['message'=>'User succ registered','user'=>$user],200);
    }
    public function login(Request $request){
        $validator= $request->validate([
              'email'=>'required|email',
            'password'=>'required|min:6'
            ]);
 
      if(!$token=auth()->attempt($validator)){
        return response()->json(['error'=>'Email or paasword not correct'],401);
      }
      return $this->createNewToken($token);
    }
    public function createNewToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL()*60,
            'user'=>auth()->user()
        ]);

    }
    public function profile(){
        return response()->json(auth()->user());
    }
   public function logout(){
    auth()->logout();
    return response()->json(['message'=>'user logout']);
   } 
public function finduser(){
   
    $user=Auth::user();
    return response()->json($user);
}
}