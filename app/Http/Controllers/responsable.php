<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class responsable extends Controller
{    public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
  }
    public function createuser(Request $request){
      $validated=$request->validate([
        'name'=>'required',
        'firstname'=>'required',
        'email'=>'required|unique:users',
        'password'=>'required',
        'role'=>'required',
      ]);
        $user=User::create([
          'name'=>$validated['name'],
          'firstname'=>$validated['firstname'],
          'email'=>$validated['email'],
          'password'=>Hash::make($validated['password']),
          'role'=>$validated['role'],
        ]);
        return response()->json(['message'=>'user created successfully']);


    }
    public function updateuser(Request $request){
    
      $validated=$request->validate([
        'name'=>'required',
        'firstname'=>'required',
        'email'=>'required',
        'role'=>'required',
      ]);
        $user=User::where('id',$request->id)->update([
          'name'=>$validated['name'],
          'firstname'=>$validated['firstname'],
          'email'=>$validated['email'],
          'role'=>$validated['role'],
        ]);
        return response()->json($user);}
    public function deleteuser($id){
       
            $user=User::where('id',$id)->delete();
            return response()->json(['message'=>'user deleted successfully']);
        }
    public function getusers(Request $request ){
        $users=User::all();
        return response()->json($users);
    }
    public function getuser($id){
        
            $user=User::find($id);
            return response()->json($user);
        }
}
