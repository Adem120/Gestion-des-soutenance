<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateInterval;

use Illuminate\Support\Facades\Date;
use DateTime;
class Sessionstage extends Controller
{public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
  }
    public function createsession(Request $request)
    {
       $validated=$request->validate([
          'datedebut'=>'required|date',
            'heuredebut'=>'required',
            'heurefin'=>'required',
       ]);

       DB::table('sessiondate')->insert([
           'date'=>$validated['datedebut'],
           'heuredebut'=>$validated['heuredebut'],
           'heurefin'=>$validated['heurefin'],
       ]);
        return response()->json(['message'=>'session added succ'],200);
         
    }
    public function getdate(){
        $date=DB::table('sessiondate')->get();
        return response()->json($date);
    }
    public function getdatepossibe(Request $request){
      $validated=$request->validate([
          'id'=>'required',
          'stage'=>'required',
      ]);
        $n=0;
        $date=DB::table('sessiondate')->where('id',$request->id)->get();
        $datepossibe=[];
        $datedebut = (DB::table('sessiondate')->where('id',$request->id)->value('heuredebut'));
        $datefin = (DB::table('sessiondate')->where('id',$request->id)->value('heurefin'));
        $date1 = new DateTime($datedebut);
       $datepossibe[$n]=$date1->format('H:i:s');
       $n++;
        #converte datedebut to time 
        $date2 = Carbon::parse($datefin);
        $date2=new dateTime($date2);
        
       while($date1<$date2){
        if($request->stage==1){$date1 = $date1->add(new DateInterval('PT10M'));}
        elseif($request->stage==2){$date1 = $date1->add(new DateInterval('PT20M'));}
        
        $dta=$date1->format('H:i:s');
              if('12:00:00'>$dta || $dta>='13:00:00'){
            
          
            $datepossibe[$n]=$date1->format('H:i:s');
            $n++;}}
            return response()->json($datepossibe);
        
    

              }
              
 public function date (){
  $date=[];
  $date1='8:30';
  $date2='15:00';
  $date1 = new DateTime($date1);
  $date2 = new DateTime($date2);
  $date[]=$date1->format('H:i');
  while($date1<$date2){
   
    $date1 = $date1->add(new DateInterval('PT30M'));
    if('12:00'>$date1->format('H:i') || $date1->format('H:i')>='13:00'){
    $date[]=$date1->format('H:i');}
  }
  return response()->json($date);

 } 


          
    }

