<?php

namespace App\Http\Controllers;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use DateTime;
use DateInterval;   
class salles extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
    }
    public function getsalles(){
        $salle = DB::table('salles')->get();
        return response()->json($salle);
    }
    public function getsalle($id){
        $salle = Salle::find($id);
        return response()->json($salle);
    }
    public function addsalles(Request $request){
        $validated = $request->validate([
            'numero'=>'required|unique:salles',
            'block'=>'required',
        ]);
      
        $salle = DB::table('salles')->insert([
            'numero'=>$request->numero,
            'block'=>$request->block,
        ]);
        return response()->json(['message'=>'salle added succ'],200);
    }
    public function updatesalle(Request $request){
        $validated =$request->validate([
            'numero'=>'required',
            'block'=>'required',
        ]);
       
        $salle = DB::table('salles')->where('id',$request->id)->update([
            'numero'=>$request->numero,
            'block'=>$request->block,
        ]);
        return response()->json(['message'=>'salle updated succ'],200);
    }
    public function deletesalle($id){
        $salle = DB::table('salles')->where('id',$id)->delete();
        return response()->json(['message'=>'salle deleted succ'],200);
    }
    public function recherchesalvide(Request $request){
        $validated = $request->validate([
            'date'=>'required',
            'heure'=>'required',
            'typestage'=>'required',
        ]);

        $date=$request->date;
        $heure=$request->heure;
        $type=$request->typestage;

        if($type==2){
        $heure2=new DateTime($request->heure);
        #add 10 min to heure2
        $heure2->add(new DateInterval('PT10M'));
        $heure2=$heure2->format('H:i:s');
        $salle = DB::table('salles')->whereNotIn('id',function($query) use ($date,$heure,$heure2){
            $query->select('idsal')->from('stage')->where('iddate',$date)->where('heuredebut',$heure)->ORwhere('heuredebut',$heure2);
        })->get();}
        else{
            $salle = DB::table('salles')->whereNotIn('id',function($query) use ($date,$heure){
                $query->select('idsal')->from('stage')->where('iddate',$date)->where('heuredebut',$heure);
            })->get();
        }
        return response()->json($salle);
    }
}
