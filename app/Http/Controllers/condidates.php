<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Imports\condidatesImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Models\condidate;
use Maatwebsite\Excel\Concerns\WithValidationException;

class condidates extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
    }

    public function getcondidates(){
       $user=auth()->user();
        $condidate1=DB::table('condidates')->where('parcours',$user->role)->get();
        $condidate = DB::table('condidates')->get();
        if($user->role=='ADMIN'){
            return response()->json($condidate);
        }
        else{
            return response()->json($condidate1);
        }
    }
    public function getcondidate($id){
        //$condidate = DB::table('condidates')->where('id',$id)->get();
        $con=Condidate::find($id);
       #return condidate format json
        return response()->json($con);
    }
    public function addcondidate(Request $request){
        $validated = $request->validate([
            'cin'=>'required|unique:condidates',
            'nom'=>'required',
            'prenom'=>'required',
            'parcours'=>'required|in:DSI,SEM,RSI,MDW,TI',
            'groupe'=>'required',
            'stage'=>'required|in:1,2'
        ]);
      
        $condidate = DB::table('condidates')->insert([
            'cin'=>$request->cin,
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'parcours'=>$request->parcours,
            'groupe'=>$request->groupe,
            'stage'=>$request->stage
        ]);
       
        return response()->json(['message'=>'condidate added succ',],200);
    }
    public function updatecondidate(Request $request){
        $validated = $request->validate([
            'cin'=>'required',
            'nom'=>'required',
            'prenom'=>'required',
            'parcours'=>'required|in:DSI,SEM,RSI,MDW,TI',
            'groupe'=>'required',
            'stage'=>'required|in:1,2'
        ]);
        $condidate = DB::table('condidates')->where('id',$request->id)->update([
            'cin'=>$request->cin,
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'parcours'=>$request->parcours,
            'groupe'=>$request->groupe,
            'stage'=>$request->stage
        ]);
        return response()->json(['message'=>'condidate updated succ'],200);
    }
    public function deletecondidate($id){
        $condidate = DB::table('condidates')->where('id',$id)->delete();
        return response()->json(['message'=>'condidate deleted succ'],200);
    }
    public function import(Request $request){
        $validator = Validator::make($request->all(),[
            'file'=>'required|mimes:csv,xls,xlsx'
        ]);
       if($validator->fails()){
           return response()->json(['error'=>$validator->errors()],401);}
      try{
        Excel::import(new condidatesImport, request()->file('file'));
  
        return response()->json(['message'=>'file imported succ'],200);}
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $failures = $e->failures();
            return response()->json(['error'=>$failures],401);
        }
           
      }}


