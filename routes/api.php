<?php

use App\Http\Controllers\MesageControler;
use App\Http\Controllers\PizzaControler;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('users',[UserController::class,'userGet']);
Route::post('insert-users',[UserController::class,'insertUsers']);




Route::prefix('messages')->group(function(){
    Route::get('/{id}',[MesageControler::class,'mesageGet']);
    Route::post('add',[MesageControler::class,'store']); 
    Route::delete('delete/{id}',[MesageControler::class,'delete']);
});

Route::post('login',[UserController::class,'loginPost']);
Route::post('register',[UserController::class,'registerPost']);

Route::get('get-hash' , function (Request $req){
    return response()->json(['hash' => Hash::make($req->password)]);
});

Route::get('search',[UserController::class,'search']);


Route::get('getLast',[UserController::class,'userLast']);