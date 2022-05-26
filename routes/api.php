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
    Route::post('/register',[App\Http\Controllers\Api\RegisterController::class,'register']);
    Route::post('/login',[App\Http\Controllers\Api\LoginController::class,'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        //companyDetailRegister
        Route::prefix('/my-company')->group(function () {
            Route::get('/',[App\Http\Controllers\Api\CompanyController::class,'myCompany']);
            Route::post('/register',[App\Http\Controllers\Api\RegisterController::class,'companyDetailRegister']);

            //company jobs 
            Route::get('jobs',[\App\Http\Controllers\Api\CompanyController::class,'companyJobs']);
            Route::prefix('/job')->group(function () {
                Route::post('/create',[\App\Http\Controllers\Api\CompanyController::class,'createJob']);
                Route::put('/edit/{id}',[App\Http\Controllers\Api\CompanyController::class,'editJob']);
                Route::delete('/delete/{id}',[\App\Http\Controllers\Api\CompanyController::class,'destroyJob']);
            });

            //review candidate
            Route::prefix('candidate-review')->group(function () {
                
            });

         });

        //candidate
        Route::prefix('candidate')->group(function () {
            Route::get('/my',[App\Http\Controllers\Api\CandidateController::class,'my']);
            Route::post('/register',[App\Http\Controllers\Api\RegisterController::class,'candidateDetailRegister']);
            Route::post('/apply-job/{jobid}',[App\Http\Controllers\Api\CandidateController::class,'applyJob']);
            Route::get('/my-apply',[App\Http\Controllers\Api\CandidateController::class,'myApplyJob']);
        });
        
    });

    //listing Jobs
    Route::get('jobs',[App\Http\Controllers\Api\JobController::class,'index']);
    

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
