<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use App\Mail\testmail;
use Mail;
use URL;



class ResetPassword extends Controller
{
        public function resetpassword(Request $request){
            $validated = $request->validate([
                'email'=>'required|email',
            ]);
         
           $user = DB::table('users')->where('email',$request->email)->first();
           
           if($user){
            $token = Str::random(60);
            $date=Carbon::now()->format('Y-m-d H:i:s');
            $email=DB::table('password_resets')->where('email',$request->email)->first();
            if($email){
                DB::table('password_resets')->where('email',$request->email)->update([
                    'token'=>$token,
                    'created_at'=>$date
                ]);}else{
             DB::table('password_resets')->insert([
                'email'=>$request->email,
                'token'=>$token,
                'created_at'=>$date
            ]);}
            $domine=URL::to('/');
            $url=$domine.'/resetpassword?token='.$token;
            try{ Mail::to($request->email)->send(new testmail($url));
                return response()->json([
                    'message' => 'Check your email for reset password link'
                ],200);}catch(Exception $e){
                    return response()->json([
                        'message' => 'Something went wrong'
                    ]);
                }}
                
              else{
                return response()->json([
                    'message' => 'User not found'
                ],401);

              }}
              public function changepassword(Request $request){
                $validated = $request->validate([
                    'token'=>'required',
                    'password'=>'required|min:6',
                ]);
                $passwordreset = DB::table('password_resets')->where('token',$request->token)->first();
                
                    $created_at = DB::table('password_resets')->where('token',$request->token)->value('created_at');
                    $date=carbon::parse($created_at);
                    if($date->addMinutes(30)->isPast()){
                        return response()->json([
                            'message' => 'Token expired'
                        ]);
                    }
                    else{
                  
                    if($passwordreset){
                        
                    
                        $user = DB::table('users')->where('email',$passwordreset->email)->first();
                        if($user){
                            DB::table('users')->where('email',$passwordreset->email)->update([
                                'password'=>Hash::make($request->password)
                            ]);
                            DB::table('password_resets')->where('email',$passwordreset->email)->delete();
                            return response()->json([
                                'message' => 'Password reset successfully'
                            ]);
                        }else{
                            return response()->json([
                                'message' => 'User not found'
                            ]);
                        }
                    
            
                    }
                    else{
                        return "<h1>token not found</h1>";
                    }}}



}
                
                    
                
                   