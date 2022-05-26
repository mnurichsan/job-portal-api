<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function showCandidate($jobId)
    {
        try {

            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

             //check if company no complete the registration
            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            $job = Job::find($jobId);

            //check job is null or not
            if(!$job){
                return ResponseBuilder::error(message:'Job Not Found !',statusCode: 404);
            }

            $myCandidate = JobApplication::with('candidate','candidate.user','job')->where('job_id',$jobId)->get();

            return ResponseBuilder::success('My Candidate Fetch Successfully',$myCandidate,200);
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function acceptedToInterview(Request $request,$candidateId)
    {
        try {

            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'  => 'Interview',
                'review'  => $request->review
            ]);

            return ResponseBuilder::success('Candidate Accepted To Interview Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function assessmentCandidate(Request $request,$candidateId)
    {
        try {
            
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Assessment',
                'assessment'  => $request->assessment
            ]);

            return ResponseBuilder::success('Candidate Accepted To Assessment Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function acceptedToCompany(Request $request,$candidateId)
    {
        try {
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Accepted To Company',
                'assessment'  => $request->assessment,
                'description' => $request->description
            ]);

            return ResponseBuilder::success('Candidate Accepted To Company Successfully',$applyCandidate,201);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }

    public function rejected(Request $request,$candidateId)
    {
        try {
            $candidate = Candidate::find($candidateId);

            //check candidate is null or not
            if(!$candidate){
                return ResponseBuilder::error(message:'Candidate Not Found !',statusCode: 404);
            }
            
            $applyCandidate = JobApplication::with('candidate','candidate.user','job')->where('candidate_id',$candidateId)->first();

            $applyCandidate->update([
                'status'      => 'Rejected',
                'assessment'  => $request->assessment,
                'description' => $request->description
            ]);

            return ResponseBuilder::success('Candidate Rejected Successfully',$applyCandidate,201);
        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
}
