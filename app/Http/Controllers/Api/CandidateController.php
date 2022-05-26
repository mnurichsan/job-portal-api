<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function applyJob($jobId)
    {
        try {
            $myUserId = Auth::user()->id;
            $candidate = Candidate::where('user_id',$myUserId)->first();

            //check if candidate no complete the registration
            if(!$candidate){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            //check if job is not null
            $job = Job::find($jobId);

            if(!$job){
                return ResponseBuilder::error(message:'Job Not Found!!',statusCode: 404);
            }

            $applyJob = JobApplication::create([
                'job_id'        => $jobId,
                'candidate_id'  => $candidate->id,
                'status'        => 'Review',
            ]);

            return ResponseBuilder::success('Job Aplly Successfully',$applyJob,201);

        } catch (\Exception $e) {
             return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function myApplyJob()
    {
        try {

            $myUserId = Auth::user()->id;
            $candidate = Candidate::where('user_id',$myUserId)->first();

            if(!$candidate){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            $jobs = Job::with('category','jobType','company','jobapply')->whereHas('jobapply', function ($query) use ($candidate){
                $query->where('candidate_id',$candidate->id);
            })->get();
            
            return ResponseBuilder::success('Jobs Fetch Successfully',$jobs,200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function my()
    {
        try {
            $userId = Auth::user()->id;
            $candidate = User::with('candidate')->find($userId);
            
            return ResponseBuilder::success(message:'Candidate Fetch Successfully', data:$candidate, statusCode: 200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
    
}
