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

Route::prefix('v1')->group(function () {
    //auth
    Route::post('register',[App\Http\Controllers\Api\RegisterController::class,'register']);
    Route::post('login',[App\Http\Controllers\Api\LoginController::class,'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        //companyDetailRegißter
        Route::prefix('company')->group(function () {
            Route::post('/',[App\Http\Controllers\Api\RegisterController::class,'companyDetailRegister']);
        });
    });

    //Jobs
    Route::get('jobs',[App\Http\Controllers\Api\JobController::class,'index']);
    

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
