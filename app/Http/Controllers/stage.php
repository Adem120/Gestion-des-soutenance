<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Date;
use App\Models\stages;
use App\Models\reclamation;
use Illuminate\Support\Facades\Auth;
#import user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\response;



class stage extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','consulter']]);
    }
    public function createstage(Request $request){
        $validated=$request->validate([
            'idetud'=>'required|unique:stage',
            'idens'=>'required',
            'stage'=>'required',
            'idsal'=>'required',
            'iddate'=>'required',
            'heuredebut'=>'required',
           
        ]);
        
      $ens1=DB::table('stage')->where('idens',$validated['idens'] )->where('iddate',$validated['iddate'])->where('heuredebut',$validated['heuredebut'])->get();
      $ens2=DB::table('stage')->where('idens2',$request->idens2 )->where('iddate',$validated['iddate'])->where('heuredebut',$validated['heuredebut'])->get();
      $sal=DB::table('stage')->where('idsal',$validated['idsal'] )->where('iddate',$validated['iddate'])->where('heuredebut',$validated['heuredebut'])->get();
    if($request->stage==1){
        if($ens1->isEmpty() && $sal->isEmpty()){
            DB::table('stage')->insert([
                'idetud'=>$validated['idetud'],
                'idens'=>$request->idens,
             'stage'=>$request->stage,
                'idsal'=>$validated['idsal'],
                'iddate'=>$validated['iddate'],
                'heuredebut'=>$validated['heuredebut'],
            ]);
            return response()->json(['message'=>'stage1 added succ'],200);
        }else{
            if(!$ens1->isEmpty() && $sal->isEmpty()){
                return response()->json(['message'=>'enseignant non disponible dans se date'],401);
            }
            elseif(!$sal->isEmpty() && $ens1->isEmpty()){
                return response()->json(['message'=>'salle non disponible dans se date'],401);}
                elseif(!$sal->isEmpty() && !$ens1->isEmpty()){
                    return response()->json(['message'=>'enseignant et salle non disponible dans se date'],401);
                }
    
    }}
        elseif($request->stage==2)
        {
            if($ens2->isEmpty()&&$ens1->isEmpty() && $sal->isEmpty()){
                DB::table('stage')->insert([
                    'idetud'=>$validated['idetud'],
                    'idens'=>$validated['idens'],
                    'idens2'=>$request->idens2,
                  'stage'=>$request->stage,
                    'idsal'=>$validated['idsal'],
                    'iddate'=>$validated['iddate'],
                    'heuredebut'=>$validated['heuredebut'],
                ]);
                return response()->json(['message'=>'stage2 added succ'],200);
            }else{
                if($ens2->isEmpty()  && !$ens1->isEmpty()){
                    return response()->json(['message'=>'Enseignant 1 non disponible dans se date '],401);
                }
                elseif($ens1->isEmpty()&& !$ens2->isEmpty()){
                    return response()->json(['message'=>'ens2 non disponible dans se date'],401);
                }
                elseif( !$ens2->isEmpty()&& !$ens1->isEmpty()){
                    return response()->json(['message'=>'ens1 et ens2 non disponible dans se date'],401);
                }
                elseif(!$sal->isEmpty()){
                    return response()->json(['message'=>'sal non disponible dans se date'],401);
                
                }
                
        }
    }

  
 
       }
        public function getstage($stag){
          $user=Auth::user();
         
          if($stag==2){
            if($user->role=='ADMIN'){
            $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
            ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
            ->select('stage.*','condidates.nom as nometud','condidates.parcours','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2','sessiondate.date')->where('stage.stage',$stag)->get();}
            else{
                $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
                ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
                ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
                ->select('stage.*','condidates.nom as nometud','condidates.parcours','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2','sessiondate.date')->where('stage.stage',$stag)->where('condidates.parcours',$user->role)->get();
                
                }
                
                  
                
          }elseif($stag==1){
            if($user->role=='ADMIN'){
            $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')
            ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
            ->select('stage.*','condidates.nom as nometud','condidates.parcours','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','sessiondate.date')->where('stage.stage',$stag)->get();}
            else{
             $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')
            ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
            ->select('stage.*','condidates.nom as nometud','condidates.parcours','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','sessiondate.date')->where('stage.stage',$stag)->where('condidates.parcours',$user->role)->get();
             
            }

          }

           
           
           return response()->json($stage);
         
               
      
        }
      
        public function getstagebyid($id){
            $ens2=(DB::table('stage')->where('id',$id)->value('idens2'));

            if($ens2!=null){
                $stage=DB::table('stage')

            ->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
            ->join('salles','stage.idsal','=','salles.id')
            ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2')->where('stage.id',$id)->get();
            return response()->json($stage);}
            else{
                $stage=DB::table('stage')

                ->join('condidates','stage.idetud','=','condidates.id')
                ->join('enseignants','stage.idens','=','enseignants.id')
                ->join('salles','stage.idsal','=','salles.id')
                ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block')->where('stage.id',$id)->get();
                return response()->json($stage);
            }
        }
public function getstageall(){
    $stage=DB::table('stage')->get();
    return response()->json($stage);

}
public function getetudientnonstage( ){
   
    $user=Auth::user();

    if($user->role=="ADMIN"){
    $etud=DB::table('condidates')->whereNotIn('id',function($query){
          $query->select('idetud')->from('stage');
        })->get();
    }else{
    $etud=DB::table('condidates')->where("parcours",$user->role)->whereNotIn('id',function($query){
        $query->select('idetud')->from('stage');
    })->get();}
    return response()->json($etud);

       
}
public function nombreder(){
    $ens=DB::table('stage')->select('idens',DB::raw('count(*) as total'))->groupBy('idens')->get();
    $ens2=DB::table('stage')->select('idens2',DB::raw('count(*) as total'))->groupBy('idens2')->get();
    $nbens=DB::table('stage')->select('idens',DB::raw('count(*) as total'))->groupBy('idens')->count();
    $nbens2=DB::table('stage')->select('idens2',DB::raw('count(*) as total'))->groupBy('idens2')->count();
    return response()->json(['ens'=>$ens,'ens2'=>$ens2,'nbens'=>$nbens,'nbens2'=>$nbens2]);
   

}
public function nombrestagein(){
    $nb=DB::table('stage')->where('stage',1)->count();
    return response()->json(['nb'=>$nb]);}
public function nombrestageex(){
    $nb=DB::table('stage')->where('stage',2)->count();
    return response()->json(['nb'=>$nb]);}
  public function recherche(Request $request){
    $validated=$request->validate([
     "cin"=> 'required'
    ]);
    $cin=$validated['cin'];
    $etud=DB::table('condidates')->where('cin',$cin)->get();
    $ens=DB::table('enseignants')->where('cin',$cin)->get();
    if(!$etud->isEmpty()){
        $stage=DB::table('stage')->where('idetud',$etud[0]->id)
        ->join('condidates','stage.idetud','=','condidates.id')
        ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
        ->join('salles','stage.idsal','=','salles.id')
        ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2')->get();
    }else{
        $stage=DB::table('stage')->where('idens',$ens[0]->id)->Orwhere('idens2',$ens[0]->id)
        ->join('condidates','stage.idetud','=','condidates.id')
        ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
        ->join('salles','stage.idsal','=','salles.id')
        ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2')->get();
    }
    return response()->json(['stage'=>$stage]);
  } 
  
 public function getreclamation(){
    $user = Auth::user();
    if($user->role=="ADMIN"){
        $reclam=DB::table('reclamation')->join('condidates','reclamation.idetud','=','condidates.id')
            ->join('enseignants','reclamation.idens','=','enseignants.id')->select('reclamation.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens')->get();
           }
        else{
            $reclam=DB::table('reclamation')->join('condidates','reclamation.idetud','=','condidates.id')
            ->join('enseignants','reclamation.idens','=','enseignants.id')->select('reclamation.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens')->where('condidates.parcours',$user->role)->get();
           
        }
return response()->json($reclam);
  }

  public function deleterec($id){
    $reclamation = DB::table('reclamation')->where('id',$id)->delete();
    return response()->json(['message'=>'reclamation supprimé avec success'],200);
}
public function repondreparmail(Request $request){
    $validated=$request->validate([
        'id'=>'required',
        'idens'=>'required',
        'reponse'=>'required'
    ]);
    $id=$validated['idens'];
    $email=DB::table('enseignants')->where('id',$id)->value('email');
    
    $reponse=$validated['reponse'];
            try{ Mail::to($email)->send(new response($reponse));
                $rec=reclamation::find($request->id);
                DB::table('reclamation')->where('id',$request->id)->update([
                    'description'=>$rec->description,
                    'sujet'=>$rec->sujet,
                    'idetud'=>$rec->idetud,
                    'idens'=>$rec->idens,
                    'status'=>1]);
                
                
                return response()->json([
                    'message' => 'reponse envoyé avec success'
                ],200);}catch(Exception $e){
                    return response()->json([
                        'message' => 'Something went wrong'
                    ]);

                }
           
                

            }
        
        
        public function deletestage($id){
            $stage = DB::table('stage')->where('id',$id)->delete();
            return response()->json(['message'=>'stage supprimé avec success'],200);
        }
        }
