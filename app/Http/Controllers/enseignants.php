<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Enseignant;
use App\Imports\enseignantsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithValidationException;

class enseignants extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
    }

    public function getenseignants(){
        $enseignant = DB::table('enseignants')->get();
        return response()->json($enseignant);
    }
    public function getenseignant($id){
        $enseignant =Enseignant::find($id);
        return response()->json($enseignant);
    }
    public function addenseignant(Request $request){
        $validated = $request->validate([
           'cin'=>'required|unique:enseignants',
            'nom'=>'required',
            'prenom'=>'required',
            'email'=>'required|email|unique:enseignants',
            
        ]);
       
        $enseignant = DB::table('enseignants')->insert([
            'cin'=>$request->cin,
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'email'=>$request->email,
        ]);
        return response()->json(['message'=>'enseignant added succ'],200);
    }
    public function updateenseignant(Request $request){
       
        $validated =$request->validate([
            'cin'=>'required',
            'nom'=>'required',
            'prenom'=>'required',
            'email'=>'required|email',
        ]);
       
        $enseignant = DB::table('enseignants')->where('id',$request->id)->update([
            'cin'=>$request->cin,
            'nom'=>$request->nom,
            'prenom'=>$request->prenom,
            'email'=>$request->email,
        ]);
        return response()->json(['message'=>'enseignant updated succ'],200);
    }
    public function deleteenseignant($id){
        $enseignant = DB::table('enseignants')->where('id',$id)->delete();
       
        return response()->json(['message'=>'enseignant deleted succ'],200);
    }
    public function import(Request $request){
        $validator = Validator::make($request->all(),[
            'file'=>'required|mimes:csv,xls,xlsx'
        ]);
       if($validator->fails()){
           return response()->json(['error'=>$validator->errors()],401);}
      try{
        Excel::import(new enseignantsImport, request()->file('file'));
  
        return response()->json(['message'=>'file imported succ'],200);}
        catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $failures = $e->failures();
                
            return response()->json(['error'=>$failures],401);
        }
           
      }
}
