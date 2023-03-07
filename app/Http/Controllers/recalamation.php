<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Validator;
class recalamation extends Controller
{
    public function ajoute(Request $request){
        $validated = $request->validate([
            'cin'=>'required',
            'sujet'=>'required',
            'description'=>'required',
        ]);
        $cin=$request->cin;
        $idens=DB::table('enseignants')->where('cin',$cin)->value('id');
        $reclamation = DB::table('reclamation')->insert([
            'idetud'=>$request->idetud,
            'idens'=>$idens,
            'sujet'=>$request->sujet,
            'description'=>$request->description,
            'status'=>0
        ]);
        return response()->json(['message'=>'reclamation ajouteé avec success'],200);
    }
   
  
    public function getstagecin( Request $request){
        $validated=$request->validate([
            'cin'=>'required',
        ]);
        $id=DB::table('condidates')->where('cin',$validated['cin'])->value('id');
        $idens=DB::table('enseignants')->where('cin',$validated['cin'])->value('id');
        $sta=DB::table('stage')->where('idens2',$idens)->count();
        if($id!=null ){
           $stag=DB::table('stage')->where('stage.idetud',$id)->value('idens2');

            if($stag!=null){
            $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')->join('enseignants as ens2','stage.idens2','=','ens2.id')
            ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
            ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block','ens2.nom as nomens2','ens2.prenom as prens2')
            ->where('stage.idetud',$id)->get();}else{
                $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
                ->join('enseignants','stage.idens','=','enseignants.id')
                ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
                ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block')->where('stage.idetud',$id)->get();
            }
           
                return response()->json([$stage,$ens=false]);
            }
           
        else {
            $stage=DB::table('stage')->join('condidates','stage.idetud','=','condidates.id')
            ->join('enseignants','stage.idens','=','enseignants.id')
            ->join('salles','stage.idsal','=','salles.id')->join('sessiondate','stage.iddate','=','sessiondate.id')
            ->select('stage.*','condidates.nom as nometud','condidates.prenom as prenometud','enseignants.nom as nomens','enseignants.prenom as prenomens','salles.numero as numsal','salles.block as block')->where('stage.idens',$idens)->ORwhere('stage.idens2',$idens)->get();        
          return response()->json([$stage,$ens=true]);}
        }
}
