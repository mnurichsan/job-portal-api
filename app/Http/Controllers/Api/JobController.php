<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Category;
use App\Models\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        try {
            if($request->has('categoryId'))
            {
                $categoryId = $request->categoryId;
                $jobs = Job::with('category','jobType','company')->whereHas('category', function ($query) use ($categoryId){
                    $query->where('id',$categoryId);
                })->get();
            }
            elseif($request->has('jobTypeId'))
            {
                $jobTypeId = $request->jobTypeId;
                $jobs = Job::with('category','jobType','company')->whereHas('jobType', function ($query) use ($jobTypeId){
                    $query->where('id',$jobTypeId);
                })->get();

            }
            else{
                $jobs = Job::with('category','jobType','company')->get();
            }
           


            return ResponseBuilder::success(message:'Jobs Fetch Successfully',data:$jobs,statusCode:201);
            
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
}
