<?php

use Illuminate\Http\Request;
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

//Route::middleware('auth:api')->get('/user', function (Request $filters) {
//    return $filters->user();
//});

Route::group(["namespace" => "Api"], function(){
    Route::get("/convenants/list", "CovenantsController@index");
    Route::get("/institutions/list", "InstitutionsController@index");
    Route::post("/institutions/fee-simulator", "InstitutionsController@calculateFee");
});
