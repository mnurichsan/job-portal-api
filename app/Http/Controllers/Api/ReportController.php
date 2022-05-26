<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Helpers\ResponseBuilder;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function jobsReport(Request $request)
    {
        try {
            
            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            if($request->start_date)
            {
                $myJobsTotal = Job::whereDate('created_at',$request->start_date)->count();
                $myDraftJobsTotal = Job::where('status','Draft')->whereDate('created_at',$request->start_date)->count();
                $myOpenJobsTotal = Job::where('status','Open')->whereDate('created_at',$request->start_date)->count();
                $myCloseJobsTotal = Job::where('status','Close')->whereDate('created_at',$request->start_date)->count();

                if($request->end_date)
                {
                    $myJobsTotal = Job::whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myDraftJobsTotal = Job::where('status','Draft')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myOpenJobsTotal = Job::where('status','Open')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myCloseJobsTotal = Job::where('status','Close')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                }

            }else{

                $myJobsTotal = Job::count();
                $myDraftJobsTotal = Job::where('status','Draft')->count();
                $myOpenJobsTotal = Job::where('status','Open')->count();
                $myCloseJobsTotal = Job::where('status','Close')->count();
            }


            $myJobsReport = [
                'total' => $myJobsTotal,
                'draft' => $myDraftJobsTotal,
                'open'  => $myOpenJobsTotal,
                'close' => $myCloseJobsTotal
            ];

            return ResponseBuilder::success('My Company Jobs Report Fetch Successfully',$myJobsReport,200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }

    }

    public function candidatesReport(Request $request)
    {
        try {
            
            $myUserId = Auth::user()->id;
            $myCompany = Company::where('user_id',$myUserId)->first();

            if(!$myCompany){
                return ResponseBuilder::error(message:'Please Complete The Registration!!',statusCode: 400);
            }

            if($request->start_date)
            {
                $myCandidatesTotal = JobApplication::whereDate('created_at',$request->start_date)->count();
                $myReviewCandidatesTotal = JobApplication::where('status','Review')->whereDate('created_at',$request->start_date)->count();
                $myInterviewCandidatesTotal = JobApplication::where('status','Interview')->whereDate('created_at',$request->start_date)->count();
                $myAssessmentCandidatesTotal = JobApplication::where('status','Assessment')->whereDate('created_at',$request->start_date)->count();
                $myAcceptedCandidatesTotal = JobApplication::where('status','Accepted')->whereDate('created_at',$request->start_date)->count();
                $myRejectedCandidatesTotal = JobApplication::where('status','Rejected')->whereDate('created_at',$request->start_date)->count();

                if($request->end_date)
                {
                    $myCandidatesTotal = JobApplication::whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myReviewCandidatesTotal = JobApplication::where('status','Review')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myInterviewCandidatesTotal = JobApplication::where('status','Interview')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myAssessmentCandidatesTotal = JobApplication::where('status','Assessment')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myAcceptedCandidatesTotal = JobApplication::where('status','Accepted')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                    $myRejectedCandidatesTotal = JobApplication::where('status','Rejected')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
                }

            }else{

                $myCandidatesTotal = JobApplication::count();
                $myReviewCandidatesTotal = JobApplication::where('status','Review')->count();
                $myInterviewCandidatesTotal = JobApplication::where('status','Interview')->count();
                $myAssessmentCandidatesTotal = JobApplication::where('status','Assessment')->count();
                $myAcceptedCandidatesTotal = JobApplication::where('status','Accepted')->count();
                $myRejectedCandidatesTotal = JobApplication::where('status','Rejected')->count();
            }


            $myCandidatesReport = [
                'total'             =>$myCandidatesTotal,
                'review'            =>$myReviewCandidatesTotal,
                'interview'         =>$myInterviewCandidatesTotal,
                'assessment'        =>$myAssessmentCandidatesTotal,
                'accepted'          =>$myAcceptedCandidatesTotal,
                'rejected'          =>$myRejectedCandidatesTotal
            ];

            return ResponseBuilder::success('My Company Candidates Jobs Report Fetch Successfully',$myCandidatesReport,200);

        } catch (\Exception $e) {
            return ResponseBuilder::error('Something Errors!',$e->getMessage(),400);
        }
    }
}
