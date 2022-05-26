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
                $jobs = Job::with('category','jobType','company')->where('status','Open')->whereHas('category', function ($query) use ($categoryId){
                    $query->where('id',$categoryId);
                })->get();
            }
            elseif($request->has('jobTypeId'))
            {
                $jobTypeId = $request->jobTypeId;
                $jobs = Job::with('category','jobType','company')->where('status','Open')->whereHas('jobType', function ($query) use ($jobTypeId){
                    $query->where('id',$jobTypeId);
                })->get();

            }
            else{
                $jobs = Job::where('status','Open')->with('category','jobType','company')->get();
            }
        

            return ResponseBuilder::success(message:'Jobs Fetch Successfully',data:$jobs,statusCode:200);
            
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function show($id)
    {
        try {            
            $job = Job::with('category','jobType','company')->find($id);
            //check job is null or not
            if(!$job)
            {
                return ResponseBuilder::success(message:'Job Not Found',statusCode:404);
            }

            return ResponseBuilder::success(message:'Job Fetch Successfully',data:$job,statusCode:200);
            
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
}
