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

        //register
        Route::post('my-company/register',[App\Http\Controllers\Api\RegisterController::class,'companyDetailRegister']);
        Route::post('candidate/register',[App\Http\Controllers\Api\RegisterController::class,'candidateDetailRegister']);

        //company
        Route::middleware(['isCompany'])->group(function () {
            Route::prefix('/my-company')->group(function () {
                Route::get('/',[App\Http\Controllers\Api\CompanyController::class,'myCompany']);

                //company jobs 
                Route::get('jobs',[\App\Http\Controllers\Api\CompanyController::class,'companyJobs']);
                Route::prefix('/job')->group(function () {
                    //crud job
                    Route::post('/create',[\App\Http\Controllers\Api\CompanyController::class,'createJob']);
                    Route::get('/show/{id}',[App\Http\Controllers\Api\CompanyController::class,'showJob']);
                    Route::put('/edit/{id}',[App\Http\Controllers\Api\CompanyController::class,'editJob']);
                    Route::delete('/delete/{id}',[\App\Http\Controllers\Api\CompanyController::class,'destroyJob']);

                    //close job
                    Route::post('/close/{id}',[\App\Http\Controllers\Api\CompanyController::class,'closeJob']);
                });
    
                //review candidate
                Route::prefix('candidate-review')->group(function () {
                    Route::get('/show/{jobId}',[App\Http\Controllers\Api\ReviewController::class,'showCandidate']);
                    Route::post('/accepted/{candidateId}',[App\Http\Controllers\Api\ReviewController::class,'acceptedToInterview']);
                    Route::post('/assessment/{candidateId}',[App\Http\Controllers\Api\ReviewController::class,'assessmentCandidate']);
                    Route::post('/accepted-to-company/{candidateId}',[App\Http\Controllers\Api\ReviewController::class,'acceptedToCompany']);
                    Route::post('/rejected/{candidateId}',[App\Http\Controllers\Api\ReviewController::class,'rejected']);
                });
    
                //report 
                Route::prefix('report')->group(function () {
                    Route::get('/jobs',[App\Http\Controllers\Api\ReportController::class,'jobsReport']);
                    Route::get('/candidates',[\App\Http\Controllers\Api\ReportController::class,'candidatesReport']);
                });
    
            });
        });
       

        //candidate
        Route::middleware(['isCandidate'])->group(function () {
            Route::prefix('candidate')->group(function () {
                Route::get('/my',[App\Http\Controllers\Api\CandidateController::class,'my']);
                Route::post('/apply-job/{jobid}',[App\Http\Controllers\Api\CandidateController::class,'applyJob']);
                Route::get('/my-apply',[App\Http\Controllers\Api\CandidateController::class,'myApplyJob']);
            });
        });

        //store token push notification
        Route::post('/store-token',[App\Http\Controllers\Api\ReviewController::class,'storeToken']);
        
        
    });

    //listing Jobs
    Route::get('jobs',[App\Http\Controllers\Api\JobController::class,'index']);
    Route::get('job/show/{id}',[\App\Http\Controllers\Api\JobController::class,'show']);
    

});
