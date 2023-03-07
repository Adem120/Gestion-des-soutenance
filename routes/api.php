<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\condidates;
use App\Http\Controllers\enseignants;
use App\Http\Controllers\salles;
use App\Http\Controllers\responsable;
use App\Http\Controllers\ResetPassword;
use App\http\controllers\Sessionstage;
use App\http\controllers\stage;
use App\http\controllers\recalamation;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'api','prefix'=>'auth'],function($router){
 Route::post('/register',[AuthController::class,'register']);
 Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);
Route::get('/condidates', [condidates::class,'getcondidates']);
Route::post('/addexcel', [condidates::class,'import']);
Route::get('/condidate/{id}', [condidates::class,'getcondidate']);
Route::post('/addcondidate', [condidates::class,'addcondidate']);
Route::put('/updatecondidate', [condidates::class,'updatecondidate']);
Route::delete('/deletecondidate/{id}', [condidates::class,'deletecondidate']);
Route::get('/enseignants', [enseignants::class,'getenseignants']);
Route::get('/enseignant/{id}', [enseignants::class,'getenseignant']);
Route::post('/addenseignant', [enseignants::class,'addenseignant']);
Route::put('/updateenseignant', [enseignants::class,'updateenseignant']);
Route::delete('/deleteenseignant/{id}', [enseignants::class,'deleteenseignant']);
Route::get('/salles', [salles::class,'getsalles']);
Route::get('/salles/{id}', [salles::class,'getsalle']);
Route::post('/salles', [salles::class,'addsalles']);
Route::put('/salles', [salles::class,'updatesalle']);
Route::post('/recherchesalvide', [salles::class,'recherchesalvide']);
Route::delete('/salles/{id}', [salles::class,'deletesalle']);
Route::get('/responsables', [responsable::class,'getusers']);
Route::get('/responsables/{id}', [responsable::class,'getuser']);
Route::post('/responsables', [responsable::class,'createuser']);
Route::delete('/responsables/{id}', [responsable::class,'deleteuser']);
Route::put('/responsables', [responsable::class,'updateuser']); 
Route::post('/resetpassword', [ResetPassword::class,'resetpassword']);
Route::post('/changepassword', [ResetPassword::class,'changepassword']);
Route::get('/session',[Sessionstage::class,'getdate']);
Route::post('/session',[Sessionstage::class,'createsession']);
Route::post('/sessionpossibe',[Sessionstage::class,'getdatepossibe']);
Route::get('/getstages/{stag}',[stage::class,'getstage']);
Route::post('/stages',[stage::class,'createstage']);
Route::get('/rec',[stage::class,'getreclamation']);
Route::post('/emailres',[stage::class,'repondreparmail']);
Route::delete('/deleterec/{id}',[stage::class,'deleterec']);
Route::post('/consulter',[recalamation::class,'getstagecin']);
Route::post('/addensexcel',[enseignants::class,'import']);
Route::get('/getstagebyid/{id}',[stage::class,'getstagebyid']);
Route::get('/get',[stage::class,'getstageall']);
Route::get('/getetud',[stage::class,'getetudientnonstage']);
Route::get('/nbr',[stage::class,'nombreder']);
Route::get('/nbrs1',[stage::class,'nombrestagein']);
Route::delete('/deletestag/{id}',[stage::class,'deletestage']);
Route::get('/nbrs2',[stage::class,'nombrestageex']);
Route::get('/dates',[Sessionstage::class,'date']);
Route::get('/user',[AuthController::class,'finduser']);
Route::post('/adrec',[recalamation::class,'ajoute']);
Route::get('/adrec',[recalamation::class,'getreclamation']);
Route::post('/recherche',[stage::class,'recherche']);



});