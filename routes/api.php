<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditorialesController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\AutoresController;
use App\Http\Controllers\LibrosController;
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

Route::prefix('editorial')->group(function (){
    Route::get('', [EditorialesController::class, 'index']);
    Route::post('/save',[EditorialesController::class, 'store']);
    Route::get('/show/{id}',[EditorialesController::class, 'show']);
    Route::delete('/destroy/{id}',[EditorialesController::class, 'destroy']);
});

Route::prefix('categoria')->group(function (){
    Route::get('', [CategoriasController::class, 'index']);
    Route::post('/save',[CategoriasController::class, 'store']);
    Route::get('/show/{id}',[CategoriasController::class, 'show']);
    Route::delete('/destroy/{id}',[CategoriasController::class, 'destroy']);
});

Route::prefix('autor')->group(function (){
    Route::get('/show/{id}',[AutoresController::class, 'show']);
    Route::post('/save',[AutoresController::class, 'store']);
    Route::delete('/destroy/{id}',[AutoresController::class, 'destroy']);
});

Route::prefix('libro')->group(function (){
    Route::post('/save',[LibrosController::class, 'store']);
    Route::get('/show/{id}',[LibrosController::class, 'show']);
    Route::get('/show/year/{year}',[LibrosController::class, 'showByYear']);
    Route::delete('/destroy/{id}',[LibrosController::class, 'destroy']);
    Route::get('/showByIsbnOrTitle',[LibrosController::class, 'showByIsbnOrTitle']);


    Route::get('/modelKeys',[LibrosController::class, 'modelKeys']);
   
    
});
