<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function companyJobs()
    {
        try {
            
            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            $myCompanyJobs = Job::with('company','category','jobType')->where('company_id',$myCompany->id)->get();

            return ResponseBuilder::success('My Company Jobs Fetch Successfully',$myCompanyJobs,200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function createJob(Request $request)
    {   
        try {

            $validator = Validator::make($request->all(),[
                'title'         => 'required',
                'description'   => 'required',
                'jobtype_id'    => 'required',
                'category_id'   => 'required',
                'status'        => 'required'
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }

            //check if company no complete the registration
            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

           $job =  Job::create([
                'title'         => $request->title,
                'slug'          => Str::slug($request->title." ". Str::random(6)),
                'description'   => $request->description,
                'company_id'    => $myCompany->id,
                'jobtype_id'    => $request->jobtype_id,
                'category_id'   => $request->category_id,
                'salary'        => $request->salary,
                'status'        => $request->status
            ]);

            return ResponseBuilder::success('Jobs Created Successfully',$job,201);



        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function showJob($id)
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

    public function editJob(Request $request,$id)
    {
        try {

            $validator = Validator::make($request->all(),[
                'title'         => 'required',
                'description'   => 'required',
                'jobtype_id'    => 'required',
                'category_id'   => 'required',
                'status'        => 'required'
            ]);

            if($validator->fails()){
                return ResponseBuilder::error('Validation Errors!',$validator->errors(),400);
            }

            //check if company no complete the registration
            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            //check job is null or not
            $job =  Job::find($id);

            if(!$job){
                return ResponseBuilder::error(message:'Job Not Found !',statusCode: 404);
            }

            $data = [
                'title'         => $request->title,
                'description'   => $request->description,
                'company_id'    => $myCompany->id,
                'jobtype_id'    => $request->jobtype_id,
                'category_id'   => $request->category_id,
                'salary'        => $request->salary,
                'status'        => $request->status
            ];

            //check if slug change, title is change to
            if($request->title !=  $job->title)
            {
                $data['slug'] =  Str::slug($request->title." ". Str::random(6));
            }


            $job->update($data);

            return ResponseBuilder::success('Jobs Updated Successfully',$job,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function destroyJob($id)
    {
        try {
            $job = Job::find($id);

            //check job is null or not
            if(!$job){
                return ResponseBuilder::error(message:'Job Not Found !',statusCode: 404);
            }
            
            //delete job
            $job->delete();
    
            return ResponseBuilder::success(message:'Jobs Deleted Successfully',statusCode: 201);
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
        
    }

    public function closeJob($id)
    {
        try {
           //check if company no complete the registration
           $myUserId = Auth::user()->id;
           $myCompany = Company::where('user_id',$myUserId)->first();

           if(!$myCompany){
               return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
           }

           //check job is null or not
           $job =  Job::find($id);

           if(!$job){
               return ResponseBuilder::error(message:'Job Not Found !',statusCode: 404);
           }

           $job->update([
                'status' => 'Close'
           ]);
            
            return ResponseBuilder::success(message:'Company Job Close Successfully', data:$job, statusCode: 201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }


    public function myCompany()
    {
        try {
            $userId = Auth::user()->id;
            $myCompany = User::with('company')->find($userId);
            
            return ResponseBuilder::success(message:'Company Fetch Successfully', data:$myCompany, statusCode: 201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }


}
